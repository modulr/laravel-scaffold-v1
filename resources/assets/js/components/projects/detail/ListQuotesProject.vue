<template>
    <div class="quotes">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">                
                <div class="col-sm-12">
                    <a href="#" class="btn btn-primary pull-right" @click.prevent="add">
                        <i class="mdi mdi-person-add mdi-lg"></i> New Invoice
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
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="#" @click.prevent="uploadFile(quote)">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                        Attachments
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" @click.prevent="viewInvoices(quote)">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                        Invoices
                                                    </a>
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

        <quotes-attachment :type="type" :quotes="quotes" :quote="quote" class="modal fade" id="myModalFile"></quotes-attachment>
        <create-invoice :project="project" :list="list" :invoices="invoices" :invoice="invoice" :quotes="quotes" class="modal right fade" id="modalAdd"></create-invoice>        
        <!-- <quotes-invoice-list :invoices="invoices"></quotes-invoice-list> -->
    </div>
</template>

<script>
import moment from 'moment';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
import Paginate from 'vuejs-paginate';
import Multiselect from 'vue-multiselect'
export default {
    props: ['project'],
    data() {
        return {
            user: Laravel.user,
            loading: false,
            loadingQuotes: false,
            quotes: [],
            quote: {},
            invoices: [],
            invoice: {},
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
            axios.get(`/quote/all?project=${this.project.id}`)
                .then(response => {
                    this.quotes = response.data.quotes.data
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
        makeProject: function (item, index) {
            var self = this;
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this project!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, turn it into a new project!",
                    closeOnConfirm: false
                },
                function () {
                    axios.put('/opportunities/update/' + self.project.id + '/make_project')
                        .then(response => {
                            swal({
                                title: "Updated!",
                                text: "The project has been turned into a project.",
                                type: "success",
                                timer: 1000,
                                showConfirmButton: false
                            });
                            self.error = {};
                            $('#modalEdit').modal('hide');
                            window.location.replace("/projects");
                        })
                        .catch(error => {
                            self.error = error.response.data;
                        });
                });
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
        },
        viewInvoices (quote) {
            this.invoices = quote.invoices
        }
    }
}
</script>