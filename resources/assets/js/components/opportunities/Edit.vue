<template>
    <div class="modal right fade" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit opportunity</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label class="col-sm-3 control-label required">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="opportunity.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.priority}">
                            <label class="col-sm-3 control-label required">Priority</label>
                            <div class="col-sm-9" v-if="list.priorities && opportunity.priority">
                                <select class="form-control" required v-model="opportunity.priority.id">
                                    <option v-for="option in list.priorities" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.priority">{{error.priority[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.area}">
                            <label class="col-sm-3 control-label required">Area</label>
                            <div class="col-sm-9" v-if="list.areas && opportunity.area">
                                <select class="form-control" required v-model="opportunity.area.id">
                                    <option v-for="option in list.areas" :value="option.id">
                                        {{ option.title }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.area">{{error.area[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Owner</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute" v-if="opportunity.owner">{{opportunity.owner.name}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Registered</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{opportunity.registered_date | date}}</small>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" @click="destroy()">
                        <i class="mdi mdi-delete mdi-lg"></i> Delete
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
export default {
    props: ['opportunity'],
    data() {
        return {
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: []
            },
            error: {}
        }
    },
    mounted() {
        axios.get('/opportunities/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
            });
        axios.get('/opportunities/list/clients')
            .then(response => {
                this.list.clients = response.data;
            });
        axios.get('/opportunities/list/customers')
            .then(response => {
                this.list.customers = response.data;
            });
        axios.get('/opportunities/list/areas')
            .then(response => {
                this.list.areas = response.data;
            });
    },
    methods: {
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/opportunities/update/' + this.opportunity.id, this.opportunity)
                .then(response => {
                    this.opportunities[this.opportunity.index] = response.data;
                    this.opportunity = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEdit').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
        },
        destroy: function () {
            var self = this;
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this opportunity!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {
                    axios.delete('/opportunities/destroy/' + self.opportunity.id)
                        .then(response => {
                            self.opportunities.splice(self.opportunity.index, 1);
                            swal({
                                title: "Deleted!",
                                text: "The opportunity has been deleted.",
                                type: "success",
                                timer: 1000,
                                showConfirmButton: false
                            });
                            self.error = {};
                            $('#modalEdit').modal('hide');
                        })
                        .catch(error => {
                            self.error = error.response.data;
                        });
                });
        },
    },
    filters: {
        date(date) {
            return moment(date).format('LL');
        }
    }
}
</script>