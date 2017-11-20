<template>
    <!-- Create project -->
    <div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Add Opportunity</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label class="col-sm-3 control-label required">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-lg" placeholder="Name" required v-model="project.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.registered_date}">
                            <label class="col-sm-3 control-label required">Registered Date</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" v-bind:placeholder="project.registered_date" required v-model="project.registered_date">
                                <span class="help-block" v-if="error.registered_date">{{error.registered_date[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" placeholder="Description" v-model="project.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.priority}">
                            <label class="col-sm-3 control-label required">Priority</label>
                            <div class="col-sm-9">
                                <select class="form-control" required v-model="project.priority">
                                    <option v-for="option in list.priorities" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.priority">{{error.priority[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.area}">
                            <label class="col-sm-3 control-label required">Area</label>
                            <div class="col-sm-9">
                                <select class="form-control" required v-model="project.area">
                                    <option v-for="option in list.areas" :value="option.id">
                                        {{ option.title }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.area">{{error.area[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.client}">
                            <label class="col-sm-3 control-label required">Client</label>
                            <div class="col-sm-9">
                                <select class="form-control" required v-model="project.client">
                                    <option v-for="option in list.clients" :value="option.id">
                                        {{option.name}}, {{option.customer.name}}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.client">{{error.client[0]}}</span>
                                <span>Or
                                    <a :href="'/clients'">create a new client.</a>
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
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
export default {
    props: ['projects'], 
    data() {
        return {
            error: {},
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: []
            },
            project: {}
        }
    },
    mounted() {
        axios.get('/projects/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
            });
        axios.get('/projects/list/clients')
            .then(response => {
                this.list.clients = response.data;
            });
        axios.get('/projects/list/customers')
            .then(response => {
                this.list.customers = response.data;
            });
        axios.get('/projects/list/areas')
            .then(response => {
                this.list.areas = response.data;
            });
    },
    methods: {
        store: function (e) {
            var btn = $(e.target).button('loading')
            axios.post('/projects/store', this.project)
                .then(response => {
                    this.$parent.projects.push(response.data);
                    this.project = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    console.log(error.response.data);
                    var btn = $(e.target).button('reset')
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