<template>
    <div class="invoices">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>

        <div class="wrapper" v-else>
            <div class="col-md-2">
                <div class="col-md-12 filters">
                    <h4 class="heading">Filters</h4>
                    <a href="#" class="pull-right" @click.prevent="doSearch">Search</a>
                    <br>
                    <a href="#" class="pull-right" @click.prevent="clearFilters">Clear</a>
                    <div class="row">
                        <div class="card col-xs-12">
                            <p class="title"><small>Owner</small></p>
                            <multiselect v-model="sort.owner" track-by="name" label="name" :options="list.owners" placeholder="Select a owner" :multiple="true"></multiselect>
                        </div>
                        <div class="card col-xs-12">
                            <p class="title"><small>Status</small></p>
                            <multiselect v-model="sort.status" track-by="name" label="name" :options="list.status" placeholder="Select a status" :multiple="true"></multiselect>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <!-- No items found -->
                <div class="col-md-12" v-if="invoices.length == 0">
                    <h3>No invoices found.</h3>
                </div>
                <div class="col-md-12" v-else>
                    <div class="row">
                        <div class="pull-right">
                            <p>Displaying
                                <b>{{pagination.from}}</b> -
                                <b>{{pagination.to}}</b> out of
                                <b>{{pagination.total}}</b> records</p>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Owner</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in invoices" :key="item.id">
                                    <td>
                                        {{item.id}}
                                    </td>
                                    <td>
                                        <a class="heading" :href="'/invoices/'+item.id">
                                            <strong>{{item.name}}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{item.amount | currency}}
                                        <br>
                                    </td>
                                    <td>
                                        {{item.description }}
                                        <br>
                                    </td>
                                    <td>
                                        {{item.created_at | date }}
                                        <br>
                                    </td>
                                    <td>
                                        <span v-if="item.owner">
                                            <img class="avatar-sm" :src="item.owner.avatar_url" v-tooltip="item.owner.name">
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
                            <paginate :page-count="pagination.last_page" :margin-pages="2" :page-range="2" :initial-page="pagination.current_page - 1"
                                :container-class="'ui pagination menu'" :page-link-class="'item'" :prev-link-class="'item'" :next-link-class="'item'"
                                :click-handler="clickCallback">
                            </paginate>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice quote -->
        <invoice-edit :list="list" :invoice="invoice" class="modal right fade" id="modalEdit"></invoice-edit>
    </div>
</template>

<script>
import InvoiceEdit from './Edit.vue';

import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
import Paginate from 'vuejs-paginate';
import Multiselect from 'vue-multiselect';
export default {
    data() {
        return {
            error: '',
            loading: false,
            newCustomer: false,
            invoices: [],
            invoice: {
                area: {},
                user: {},
                status: {}
            },
            sort: {
                owner: [],
                status: []
            },
            search: '',
            list: {
                owners: [],
                status: []
            },
            pagination: {
                current_page: 1,
                total: '',
                from: '',
                to: '',
                // last_page: 1
            }
        }
    },
    components: {
        Spinner,
        Paginate,
        Multiselect
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
        clickCallback(page) {
            this.pagination.current_page = page
            this.getAll()
        },
        getAll: function () {
            var page = Number(this.pagination.current_page);
            this.loading = true;
            axios.get(
                    `/invoices/all?page=${page}
                    ${this.search ? '&name=' + this.search: ''}
                    ${(this.sort.owner && this.sort.owner.length > 0) ? '&owner=' + this.sort.owner.map(item => item.id) : ''}
                    ${(this.sort.status && this.sort.status.length > 0) ? 'status=' + this.sort.status.map(item => item.id) : ''}`
                )
                .then(response => {
                    axios.get('/invoices/list/owners')
                    .then(response => {
                        this.list.owners = response.data;
                    });
                    axios.get('/invoices/list/status')
                    .then(response => {
                        this.list.status = response.data;
                    });
                    this.pagination.last_page = response.data.invoices.last_page
                    this.pagination.total = response.data.invoices.total
                    this.pagination.from = response.data.invoices.from
                    this.pagination.to = response.data.invoices.to
                    this.invoices = response.data.invoices.data;
                    this.loading = false;
                });
        },
        add: function () {
            this.invoice = {
                name: '',
                registered: '',
                description: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        edit: function (invoice, index) {
            this.invoice = _.clone(invoice);
            this.invoice.index = index;
            $('#modalEdit').modal('show');
        },
        totalQuotes: function (quotes) {
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
                owner: []
            }
            this.getAll()
        },
        doSearch() {
            this.current_page = 1
            this.getAll()
        }
    }
}
</script>