<template lang="html">
    <div class="projects">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="wrapper" v-if="!loading">
            <!-- List -->
            <div class="row">
                <div class="col-md-2">
                    <div class="col-md-12 filters">
                        <h4 class="heading">Filters</h4>
                        <a href="#" class="pull-right" @click.prevent="doSearch">Search</a>
                        <br>
                        <a href="#" class="pull-right" @click.prevent="clearFilters">Clear</a>
                        <div class="row">
                            <div class="card search col-xs-12">
                                <p class="title"><small>Search</small></p>
                                <div class="col-xs-1">
                                    <i class="mdi mdi-search mdi-2x"></i>
                                </div>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" placeholder="Search" v-model="search">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card col-xs-12">
                                <p class="title"><small>Customers</small></p>
                                <multiselect v-model="sort.customers" track-by="name" label="name" :options="list.customers" placeholder="Select a customer" :multiple="true"></multiselect>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card col-xs-12">
                                <p class="title"><small>Clients</small></p>
                                <multiselect v-model="sort.client" track-by="name" label="name" :options="list.clients" placeholder="Select a client" :multiple="true"></multiselect>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card col-xs-12">
                                <p class="title"><small>Region</small></p>
                                <div v-for="option in list.areas">
                                    <input type="checkbox" v-model="sort.area" :value="option.id">
                                    <label>{{option.title}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <!-- No items found -->
                    <div class="col-md-12" v-if="projects.length == 0">
                        <h3>No projects found.</h3>
                    </div>
                    <div class="col-md-12" v-if="projects.length != 0">
                        <div class="row">
                            <div class="col-md-2" v-for="(value, key) in insights">
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ key }}</h3>
                                </div>
                                <div class="panel-body">                             
                                    <ul class="list">
                                        <li class="list-item" v-for="(v, key) in value"> {{key}} : {{v}} </li class="list">
                                    </ul>       
                                    <!-- <p v-for="(v, key) in value"> {{key}} : {{v}} </p> -->
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="pull-right">
                                <p>Displaying <b>{{pagination.from}}</b> - <b>{{pagination.to}}</b> out of <b>{{pagination.total}}</b> records</p>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>Deadline</th>
                                        <th>Leader</th>
                                        <th>Supervisor</th>
                                        <th>Priority</th>
                                        <th>% Completed</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in projects">
                                        <td>
                                            {{item.id}}
                                        </td>
                                        <td>
                                            <a class="heading" :href="'/projects/'+item.id+'/get'">
                                                <strong>{{item.name}}</strong>
                                            </a>
                                            <span class="text-muted">{{item.client.customer.name}}</span>
                                        </td>
                                        <td>
                                            {{item.start_date | date}}
                                            <br>
                                        </td>
                                        <td>
                                            {{item.end_date | date}}
                                            <br>
                                        </td>
                                        <td>
                                            <span v-if="item.leader">
                                                {{item.leader.name}}
                                                <!-- <img class="avatar-sm" :src="item.leader.avatar_url" v-tooltip="item.leader.name"> -->
                                            </span>

                                        </td>
                                        <td>
                                            <span v-if="item.supervisor">
                                                {{item.supervisor.name}}
                                                <!-- <img class="avatar-sm" :src="item.supervisor.avatar_url" v-tooltip="item.supervisor.name"> -->
                                            </span>
                                        </td>
                                        <td>
                                            <span class="chip" :class="'priority-'+item.priority.name">
                                                {{item.priority.name}}
                                            </span>
                                        </td>
                                        <td><span v-if="item.completed_percentage">{{item.completed_percentage}}%</span>
                                        <span v-else>0%</span></td>
                                        <td>
                                            <span :class="[item.project_status.id == 4 ? 'chip warning' : '']">
                                                {{item.project_status.name}}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a href="#" @click.prevent="edit(item, index)">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="pull-left">
                                <paginate :page-count="pagination.last_page" :margin-pages="2" :page-range="2" :initial-page="pagination.current_page - 1" :container-class="'ui pagination menu'"
                                    :page-link-class="'item'" :prev-link-class="'item'" :next-link-class="'item'" :click-handler="clickCallback">
                                </paginate>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create project -->
            <projects-create :projects="projects" class="modal right fade" id="modalAdd"></projects-create>
            <!-- Edit project -->
            <projects-edit :projects="projects" :project="project" class="modal right fade" id="modalEdit"></projects-edit>
        </div>
    </div>
</template>

<script>
import OpportunitiesCreate from './Create.vue';
import OpportunitiesEdit from './Edit.vue';

import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
import Paginate from 'vuejs-paginate';
import Multiselect from 'vue-multiselect';
export default {
    data() {
        return {
            loading: false,
            newCustomer: false,
            projects: [],
            project: {
                area: {},
                user: {}
            },
            sort: {
                customers: [],
                client: [],
                area: [],
                total: '',
            },
            search: '',
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: [],
                users: [],
            },
            pagination : {
                current_page: 1,
                total: '',
                from: '',
                to: '',
                // last_page: 1
            },
            insights : {
                Region : {},
                Priority : {},
                Status : {}
            }
        }
    },
    components: {
        Spinner, Paginate, Multiselect
    },
    mounted() {
        this.getAll();
    },
    filters: {
        date(date) {
            return moment(date).format('LL');
        }
    },
    methods: {
        clickCallback (page) {
            this.pagination.current_page = page
            this.getAll()
        },
        getAll: function () {
            var page = Number(this.pagination.current_page);
            this.loading = true;
            axios.get(`/projects/all?page=${page}${this.search ? '&name=' + this.search: ''}${(this.sort.client && this.sort.client.length > 0) ? '&client=' + this.sort.client.map(item => item.id) : ''}${(this.sort.customers && this.sort.customers.length > 0) ? '&customer=' + this.sort.customers.map(item => item.id) : ''}${(this.sort.area && this.sort.area.length > 0) ? '&area=' + this.sort.area : ''}`)
            .then(response => {
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
            this.pagination.last_page = response.data.projects.last_page
            this.pagination.total = response.data.projects.total
            this.pagination.from = response.data.projects.from
            this.pagination.to = response.data.projects.to
            this.projects = response.data.projects.data;
            this.insights.Region.North = response.data.north
            this.insights.Region.South = response.data.south
            this.insights.Region.Center = response.data.center
            this.insights.Priority.High = response.data.high
            this.insights.Priority.Medium = response.data.medium
            this.insights.Priority.Low = response.data.low
            this.insights.Status.Done = response.data.done
            this.insights.Status['In Progress'] = response.data.inProgress
            this.insights.Status.Canceled = response.data.canceled
            this.insights.Status.New = response.data.new
            this.loading = false;
            });
        },
        add: function () {
            this.project = {
                name: '',
                registered: '',
                description: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        edit: function (project, index) {
            this.project = _.clone(project);
            this.project.index = index;
            $('#modalEdit').modal('show');
        },
        totalQuotes: function(quotes) {
            let total = 0.0;
                if (quotes) {
                    if (quotes.length > 0)
                        quotes.forEach(function (item, index) {
                            if (item.currency.id == 2) {
                                total += item.amount * item.currency.exchange_rate;
                            } else {
                                total += item.amount
                            }
                        })
                }
            return total;
        },
        clearFilters: function() {
            this.sort = {
                customers: [],
                client: [],
                area: [],
                total: '',
            }
            this.getAll()
        },
        doSearch () {
            this.current_page = 1
            this.getAll()
        }
    }
}
</script>
