<template lang="html">
    <div class="opportunities">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="wrapper" v-if="!loading">
            <div class="material-button bottom right">
                <button href="#" class="btn-primary" @click.prevent="add">
                    <i class="mdi mdi-add mdi-lg"></i>
                </button>
            </div>
            <!-- List -->
            <div class="row">
                <div class="col-md-2">
                    <div class="col-md-12 filters">
                        <h4 class="heading">Filters</h4>
                        <a href="#" class="pull-right" @click.prevent="getAll">Search</a>
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
                    <div class="col-md-12" v-if="opportunities.length == 0">
                        <h3>No opportunities found.</h3>
                    </div>
                    <div class="col-md-12" v-if="opportunities.length != 0">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Registered Date</th>
                                <th>Total Amount</th>
                                <th>Owner</th>
                                <th>Client</th>
                                <th>Customer</th>
                                <th>Region</th>
                                <th>Priority</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in filteredOpportunities">
                                <td>
                                    {{item.id}}
                                </td>
                                <td>
                                    <a :href="'/opportunities/'+item.id+'/get'">
                                        <strong>{{item.name}}</strong>
                                    </a>
                                </td>
                                <td>
                                    {{item.registered_date | date}}
                                    <br>
                                </td>
                                <td>
                                    <span v-if="item.quote">
                                        {{ totalQuotes(item.quote) | currency }}
                                    </span>
                                </td>
                                <td>
                                    <a v-bind:href="'/profile/'+item.owner.id">{{item.owner.name}}</a>
                                </td>
                                <td>
                                    <span v-if="item.client != null">{{item.client.name}}</span>
                                </td>
                                <td>
                                    <span v-if="item.client != null">
                                        {{item.client.customer.name}}
                                    </span>
                                </td>
                                <td>
                                    <span v-if="item.area">
                                        {{item.area.title}}
                                    </span>
                                </td>
                                <td>
                                    <span class="chip" :class="'priority-'+item.priority.name">
                                        {{item.priority.name}}
                                    </span>
                                </td>
                                <td>
                                    {{item.description}}
                                    <br>
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
                                                <a href="#" @click.prevent="makeProject(item, index)">
                                                    <i class="fa fa-check-circle-o"></i>
                                                    Make Project
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <paginate
                        :page-count="pagination.last_page"
                        :margin-pages="2"
                        :page-range="2"
                        :initial-page="pagination.current_page - 1"
                        :container-class="'ui pagination menu'"
                        :page-link-class="'item'"
                        :prev-link-class="'item'"
                        :next-link-class="'item'"
                        :click-handler="clickCallback">
                    </paginate>
                    </div>
                </div>
            </div>
            <!-- Create opportunity -->
            <opportunities-create :opportunities="opportunities" class="modal right fade" id="modalAdd"></opportunities-create>
            <!-- Edit opportunity -->
            <opportunities-edit :opportunities="opportunities" :opportunity="opportunity" class="modal right fade" id="modalEdit"></opportunities-edit>
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
            opportunities: [],
            opportunity: {
                area: {},
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
                areas: []
            },
            pagination : {
                current_page: 0,
                last_page: 1
            }
        }
    },
    components: {
        Spinner, Paginate, Multiselect
    },
    mounted() {
        this.getAll();
    },
    computed: {
    filteredOpportunities: function () {
        var filteredArray = this.opportunities,
            sort_customers = this.sort.customers,
            sort_client = this.sort.client,
            sort_area = this.sort.area,
            search = this.search;

        if(sort_customers != '') {
            filteredArray = filteredArray.filter(item => {
                return _.findKey(sort_customers, (o) => {
                    if(o.id === item.client.customer.id) {
                        return item;
                    }
                })
            });
        }

        if(sort_client != '') {
            filteredArray = filteredArray.filter(item => {
                return _.findKey(sort_client, (o) => {
                    if(o.id === item.client.id) {
                        return item;
                    }
                })
            });
        }

        if (sort_area.length) {
            return filteredArray = filteredArray.filter(item => sort_area.includes(item.area_id));
        }

        if (search) {
            search = search.trim().toLowerCase();

            filteredArray = filteredArray.filter(function (item) {
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
            axios.get(`/opportunities/all?page=${page}${this.search ? '&name=' + this.search: ''}${(this.sort.client && this.sort.client.length > 0) ? '&client=' + this.sort.client.map(item => item.id) : ''}${(this.sort.customers && this.sort.customers.length > 0) ? '&customer=' + this.sort.customers.map(item => item.id) : ''}${(this.sort.area && this.sort.area.length > 0) ? '&area=' + this.sort.area : ''}`)
            .then(response => {
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
            this.pagination.last_page = response.data.last_page
            this.opportunities = response.data.data;
            this.loading = false;
            });
        },
        add: function () {
            this.opportunity = {
                name: '',
                registered: '',
                description: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        edit: function (opportunity, index) {
            this.opportunity = _.clone(opportunity);
            this.opportunity.index = index;
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
        makeProject: function (item, index) {
            var self = this;
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this opportunity!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, turn it into a new project!",
                    closeOnConfirm: false
                },
                function () {
                    axios.put('/opportunities/update/' + item.id + '/make_project')
                        .then(response => {
                            self.opportunities.splice(index, 1);
                            swal({
                                title: "Updated!",
                                text: "The opportunity has been turned into a project.",
                                type: "success",
                                timer: 1000,
                                showConfirmButton: false
                            });
                            When
                            self.error = {};
                            $('#modalEdit').modal('hide');
                        })
                        .catch(error => {
                            self.error = error.response.data;
                        });
                });
        },
        clearFilters: function() {
            this.sort = {
                customers: [],
                client: [],
                area: [],
                total: '',
            }
        }
    }
}
</script>
