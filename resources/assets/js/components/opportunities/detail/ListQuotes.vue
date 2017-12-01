<template>
    <div class="quotes">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">
                <div class="col-sm-12">
                    <a href="#" class="btn btn-primary pull-right" @click.prevent="add">
                        <i class="mdi mdi-person-add mdi-lg"></i> New Quote
                    </a>
                </div>
            </div>
            <br>
            <!-- List -->
            <div class="row">
                <!-- No items found -->
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loadingQuotes"></vue-simple-spinner>
                <div v-if="!loadingQuotes">
                    <div class="col-md-12" v-if="quotes.length == 0">
                    <h3>No quotes found.</h3>
                </div>
                <div class="col-md-12" v-if="quotes.length != 0">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Project</th>
                                    <th>Designer</th>
                                    <th>Salesman</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Request Date</th>
                                    <th>Delivery Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(quote, index) in quotes">
                                    <td> {{ quote.id }} </td>
                                    <td> {{ quote.name }} </td>
                                    <td> {{ quote.project.name }} </td>
                                    <td> {{ quote.designer.name }} </td>
                                    <td> {{ quote.salesman.name }} </td>
                                    <td> {{ quote.amount | currency }} </td>
                                    <td> {{ quote.currency.title }} </td>
                                    <td> {{ quote.request_date | date }} </td>
                                    <td> {{ quote.delivery_date | date }} </td>
                                    <td>
                                        <span class="chip" :class="'status-'+quote.status.title">
                                            {{quote.status.title}}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="#" @click.prevent="edit(quote, index)">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        Edit
                                                    </a>
                                                    <a href="#" @click.prevent="uploadFile(quote)">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                        Upload PDF
                                                    </a>
                                                </li>
                                                <li role="separator" class="divider"></li>
                                                <li>
                                                    <a href="#" @click.prevent="changeStatus(quote, index, 2)">
                                                        <i class="fa fa-envelope-o"></i> Sent</a>
                                                    <a href="#" @click.prevent="changeStatus(quote, index, 3)">
                                                        <i class="fa fa-thumbs-o-up"></i> Approve</a>
                                                    <a href="#" @click.prevent="changeStatus(quote, index, 4)">
                                                        <i class="fa fa-thumbs-o-down"></i> Refuse</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- Add quote -->
        <create-quote :opportunity="opportunity" :list="list" :quotes="quotes" :quote="quote" class="modal right fade" id="modalAdd"></create-quote>
        <!-- Edit quote -->
        <quotes-edit :list="list" :quotes="quotes" :quote="quote" class="modal right fade" id="modalEdit"></quotes-edit>
        <!-- Modal File -->
        <quotes-attachment :type="type" :quotes="quotes" :quote="quote" class="modal fade" id="myModalFile"></quotes-attachment>
    </div>
</template>

<script>
import moment from 'moment';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
import Paginate from 'vuejs-paginate';
import Multiselect from 'vue-multiselect'
export default {
    props: ['opportunity'],
    data() {
        return {
            user: Laravel.user,
            loading: false,
            loadingQuotes: false,
            quotes: [],
            quote: {},
            list: {
                designers: [],
                sellers: [],
                customers: [],
                projects: [],
                services: [],
                currencies: [{
                        id: 1,
                        title: 'MXN'
                    },
                    {
                        id: 2,
                        title: 'USD'
                    }
                ],
                status: []
            },
            error: {},
            type: '2',
        }
    },
    components: {
        Spinner,
        Paginate,
        Multiselect
    },
    filters: {
        date(date) {
            return date ? moment(date).format('LL') : '';
        },
        datetime(date) {
            return date ? moment(date).format('LLL') : '';
        }
    },
    mounted() {
        this.getQuote()
    },
    computed: {},
    methods: {
        getQuote() {
            this.loadingQuotes = true
            axios.get(`/quote/all?project=${this.opportunity.id}`)
                .then(response => {
                    this.quotes = response.data.data
                    this.loadingQuotes = false
                });
            axios.get('/services/all')
                .then(response => {
                    this.list.services = response.data
            });
            axios.get('/employees/quotes')
                .then(response => {
                    this.list.sellers = response.data
                    this.list.designers = response.data
            });
            axios.get('/customers/all')
                .then(response => {
                    this.list.customers = response.data
                });
            axios.get('/quote/status/all')
                .then(response => {
                    this.list.status = response.data
                });
        },
        add() {
            this.quote = {
                project: '',
                designer: '',
                salesman: '',
                customer: '',
                service: '',
                currency: '1'
            }
            $('#modalAdd').modal('show');
        },
        edit(quote, index) {
            this.quote = _.clone(quote)
            this.quote.index = index
            $('#modalEdit').modal('show')
        },
        uploadFile(quote) {
            this.type = 2
            this.quote = _.clone(quote)
            let self = this
            this.quote.attachment.forEach(function (item, index) {
                if (item.type === '1') {
                    self.$set(self.quote, 'oc', item)
                    self.quote.attachment.splice(index, 1)
                }
            })
            $('#myModalFile').modal('show')
        },
        changeStatus(quote, index, status) {
            this.quote = _.clone(quote)
            this.quote.status_id = status
            this.quote.index = index
            if (status !== 3) {
                axios.put('/quote/update/' + this.quote.id, this.quote)
                    .then(response => {
                        this.quotes[this.quote.index] = response.data
                        this.quote = {}
                        this.error = {}
                    })
                    .catch(error => {
                        this.error = error.response.data;
                    });
            } else {
                $('#myModalFile').modal('show')
                this.type = 1
            }
        },
        clearFilters: function () {
            this.sort = {
                    customer: "",
                    status: "",
                    project: "",
                    service: ""
                },
                this.search = '';
            this.getQuotes()
        }
    }
}
</script>