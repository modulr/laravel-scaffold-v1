<template lang="html">
    <div class="projects">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="wrapper" v-if="!loading">
            <!-- No items found -->
            <div class="row" v-if="projects.length == 0">
                <div class="col-md-12">
                    <h3>No projects found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="projects.length != 0">
                <div class="col-md-2">
                    <div class="col-md-12 filters">
                        <h4 class="heading">Filters</h4>
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
                                <select class="form-control" required v-model="sort.customers">
                                    <option value="" disabled selected>Customer</option>
                                    <option v-for="option in list.customers" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card col-xs-12">
                                <p class="title"><small>Clients</small></p>
                                <select class="form-control" required v-model="sort.client">
                                    <option value="" disabled selected>Client</option>
                                    <option v-for="option in list.clients" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                    <option value="">None</option>
                                </select>
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
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>Deadline</th>
                                    <th>Priority</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredOpportunities">
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
                                        <span class="chip" :class="'priority-'+item.priority.name">
                                            {{item.priority.name}}
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
                        <paginate :page-count="pagination.last_page" :margin-pages="2" :page-range="2" :initial-page="pagination.current_page" :container-class="'ui pagination menu'"
                            :page-link-class="'item'" :prev-link-class="'item'" :next-link-class="'item'" :click-handler="clickCallback">
                        </paginate>
                    </div>
                </div>
            </div>
            <!-- Create project -->
            <projects-create :projects="projects" class="modal right fade" id="modalAdd"></projects-create>
            <!-- Edit project -->
            <projects-edit :project="project" class="modal right fade" id="modalEdit"></projects-edit>
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
export default {
    data() {
        return {
            loading: false,
            newCustomer: false,
            projects: [],
            project: {
                area: {},
            },
            sort: {
                customers: '',
                client: '',
                area: '',
                total: '',
            },
            search: '',
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: []
            },
            pagination : {
                current_page: 0,
                last_page: 1
            }
        }
    },
    components: {
        Spinner, Paginate
    },
    mounted() {
        this.getAll();
    },
    computed: {
    filteredOpportunities: function () {
        var filteredArray = this.projects,
            sort_customers = this.sort.customers,
            sort_client = this.sort.client,
            sort_area = this.sort.area,
            search = this.search;

        if (sort_customers) {
            filteredArray = filteredArray.filter(function (item) {
                if (item.client.customer_id === sort_customers) {
                    return item;
                }
            });
        }

        if (sort_client) {
            filteredArray = filteredArray.filter(function (item) {
                if (item.client.id === sort_client) {
                    return item;
                }
            });
        }

        if (sort_area) {
            filteredArray = filteredArray.filter(function (item) {
                if (item.area.id === sort_area) {
                    return item;
                }
            });
        }

        if (search) {
            search = search.trim().toLowerCase();

            filteredArray = filteredArray.filter(function (item) {
                console.log(item);
                return Object.keys(item).some(function (key) {
                    return String(item[key]).toLowerCase().indexOf(search) !== -1
                })
            })
        }
        
        return filteredArray;
        }
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
            axios.get('/projects/all?page=' + page)
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
            this.pagination.last_page = response.data.last_page
            this.projects = response.data.data;
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
        clearFilters: function () {
            this.sort = {
                customers: '',
                client: '',
                area: [],
                total: '',
            }
        }
    }
}
</script>