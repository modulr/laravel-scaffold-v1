<template>
  <div class="modal right fade" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Quote</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="index.html" method="post">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Project</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{opportunity.name}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute" v-if="project.client">{{project.client.customer.name}}</small>
                                    <small class="text-mute" v-else>Data not available.</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label class="col-sm-3 control-label required">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="quote.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.designer}">
                            <label class="col-sm-3 control-label required">Designer</label>
                            <div class="col-sm-9">
                                <multiselect v-model="quote.designer" label="name" :options="list.designers" placeholder="Designer"></multiselect>
                                <span class="help-block" v-if="error.designer">{{error.designer[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.salesman}">
                            <label class="col-sm-3 control-label required">Salesman</label>
                            <div class="col-sm-9">
                                <multiselect v-model="quote.salesman" label="name" :options="list.sellers" placeholder="Salesman"></multiselect>
                                <span class="help-block" v-if="error.salesman">{{error.salesman[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.service}">
                            <label class="col-sm-3 control-label required">Service</label>
                            <div class="col-sm-9">
                                <multiselect v-model="quote.service" label="title" :options="list.services" placeholder="Service"></multiselect>
                                <span class="help-block" v-if="error.service">{{error.service[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Amount </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="quote.amount">
                            </div>
                            <label class="col-sm-3 control-label">Currency </label>
                            <div class="col-sm-9">
                            <select type="text" class="form-control input-lg" placeholder="Currency" required v-model="quote.currency">
                                <option v-for="currency in list.currencies" :value="currency.id"> {{ currency.title }} </option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.request_date}">
                            <label class="col-sm-3 control-label required">Date</label>
                            <div class='col-sm-9 date'>
                                <input type='date' class="form-control" v-model="quote.request_date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <span class="help-block" v-if="error.request_date">{{error.request_date[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Delivery Date</label>
                            <div class='col-sm-9 date'>
                                <input type='date' class="form-control" v-model="quote.delivery_date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="store">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect'
export default {
    props: ['list', 'quotes', 'quote', 'opportunity'],
    components: {
        Multiselect
    },
    data() {
        return {
            error: {}            
        }
    },
    methods : {
        setQuoteDate (project) {
            console.log(project)
            if (project) {
                this.quote.project = project.id
                this.quote.request_date = project.registered_date                
            }
        },
        store (e) {
            var btn = $(e.target).button('loading')
            this.quote.customer = this.opportunity.client.customer.id
            this.quote.project = this.opportunity.id
            this.quote.designer = this.quote.designer.id
            this.quote.salesman = this.quote.salesman.id
            this.quote.service = this.quote.service.id
            axios.post('/quote/store', this.quote)
                .then(response => {
                    var btn = $(e.target).button('reset')
                    this.$parent.quotes.push(response.data)
                    this.$parent.quote = {}
                    this.$parent.list.projects = []
                    $('#modalAdd').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
        },
        searchProjects (customer) {
            if (customer) {
                this.quote.customer = customer
                axios.get('/opportunities/customers/' + customer.id)
                .then(response => {
                    this.$parent.list.projects = response.data
                });
            } else {
                this.$parent.list.projects = []
            }
        },
    }
}
</script>
