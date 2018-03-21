<template>
    <div class="quotes">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">                
                <div class="col-sm-12">
                    <a href="#" class="btn btn-default pull-right" @click.prevent="toggleLayout">
                        <i class="fa fa-lg" aria-hidden="true"
                            :class="{'fa-list': layout == 'list', 'fa-th-large': layout == 'grid'}">
                        </i>
                    </a>
                    <a href="#" class="btn btn-primary pull-right" @click.prevent="add" v-if="layout == 'list'">
                        <i class="mdi mdi-person-add mdi-lg"></i> New Invoice
                    </a>
                </div>                
            </div>            
            <br>
            <!-- List -->
            <div class="row">
                <!-- No items found -->
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loadingQuotes"></vue-simple-spinner>
                <h3>Quotes</h3>
                <div v-if="!loadingQuotes">
                    <div class="col-md-12" v-if="quotes.length == 0">
                    <h3>No quotes found.</h3>
                </div>
                <div class="col-md-12" v-if="quotes.length != 0">
                    <div class="col-md-12">
                        <table class="table table-hover" v-if="layout == 'list'">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Project</th>
                                    <th>Designer</th>
                                    <th>Salesman</th>
                                    <th>Amount</th>
                                    <th>Invoiced</th>
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
                                    <td> {{ totalInvoices(quote) | currency }} </td>
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
                        <div v-else>
                            <div class="panel panel-default"  v-for="(quote, index) in quotes">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{quote.name}}</h3>           
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <dl>
                                                <dd>Delivery Date</dd>
                                                <dt>{{quote.delivery_date | date}}</dt>
                                            </dl>
                                            <dl>
                                                <dd>Request Date</dd>
                                                <dt>{{quote.request_date | date}}</dt>
                                            </dl>
                                            <dl>                                                
                                                <dd>Designer</dd>
                                                <dt>
                                                    {{quote.designer.name}}
                                                </dt>
                                            </dl>
                                            <dl>                                                
                                                <dd>Salesman</dd>
                                                <dt>
                                                    {{quote.salesman.name}}
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="col-xs-4">
                                            <dl>
                                                <dd>Status</dd>
                                                <dt>{{quote.status.title}}</dt>
                                            </dl>
                                            <dl>
                                                <dd>Amount</dd>
                                                <dt>
                                                    {{ quote.amount | currency }}
                                                </dt>
                                            </dl>
                                            <dl>                                                
                                                <dd>Designer’s {{ quote.request_date | dateMonth }} volume</dd>
                                                <dt v-if="designers[quote.designer_id]">
                                                    {{ designers[quote.designer_id].commission_title }} ({{ designers[quote.designer_id].commission_percentage}}%)
                                                </dt>
                                            </dl>
                                            <dl>                                                
                                                <dd>Salesman’s {{ quote.request_date | dateMonth }} volume</dd>
                                                <dt v-if="salesmans[quote.salesman_id]">
                                                    {{ salesmans[quote.salesman_id].commission_title }} ({{ salesmans[quote.salesman_id].commission_percentage}}%)
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="col-xs-4">
                                            <dl>
                                                <dd>Invoices</dd>
                                                <dt>{{quote.invoices.length}}</dt>
                                            </dl>
                                            <dl>
                                                <dd>Invoiced</dd>
                                                <dt>{{ totalInvoices(quote) | currency }}</dt>
                                            </dl>
                                            <dl v-if="designers[quote.designer_id]">
                                                <dd>Total designed</dd>
                                                <dt>{{ designers[quote.designer_id].total | currency}}</dt>
                                            </dl>
                                            <dl v-if="salesmans[quote.salesman_id]">
                                                <dd>Total Quoted</dd>
                                                <dt>{{ salesmans[quote.salesman_id].total | currency}}</dt>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Invoices</h6>
                                            <table class="table table-hover">
                                                <thead>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Amount</th> 
                                                    <th>Percentage Of Quote</th>
                                                    <th>Status</th>
                                                    <th>{{quote.designer.name}}</th>
                                                    <th>{{quote.salesman.name}}</th>                                                    
                                                </thead>
                                                <tbody>
                                                <tr v-for="(invoice,index) in quote.invoices">
                                                    <td>{{ index + 1}}</td>
                                                    <td>
                                                        {{invoice.description}}
                                                    </td>
                                                    <td>{{invoice.pivot.amount | currency}}</td>
                                                    <td>
                                                        {{ percentageOfInvoicePerQuote(quote.amount, invoice.pivot.amount) }} %
                                                    </td>
                                                    <td>
                                                        {{invoice.invoicie_status.name}}
                                                    </td>
                                                    <td v-if="designers[quote.designer_id]"> 
                                                        {{ totalOfCommissionForEmployeePerInvoice(invoice.pivot.amount, designers[quote.designer_id].commission_percentage) | currency}} 
                                                    </td>
                                                    <td v-if="salesmans[quote.salesman_id]">
                                                        {{ totalOfCommissionForEmployeePerInvoice(invoice.pivot.amount, salesmans[quote.salesman_id].commission_percentage) | currency}} 
                                                    </td>                                                    
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <quotes-attachment :type="type" :quotes="quotes" :quote="quote" class="modal fade" id="myModalFile"></quotes-attachment>
        <create-invoice :project="project" :invoices="invoices" :invoice="invoice" :quotes="quotes" class="modal right fade" id="modalAdd"></create-invoice>        
        <quote-invoice-list :invoices="invoices" class="modal fade" id="myModalInvoices"></quote-invoice-list>
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
            employees: [],
            invoices: [],
            invoice: {},
            designers: {},
            salesmans: {},
            error: {},
            type: '2',
            layout: 'list',
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
        },
        dateMonth (date) {
            return date ? moment(date).format('MMMM') : '';
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
        },
        add() {                          
            $('#modalAdd').modal('show');
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
        totalInvoices (quote) {
            let total = 0.00
            quote.invoices.forEach(item => {
                total += parseFloat(item.pivot.amount)
            })
            quote.invoiced = total
            quote.remainder = quote.amount - quote.invoiced
            return total
        },
        percentageOfInvoicePerQuote (quoteAmount, invoiceAmount) {
            let percentage = (invoiceAmount * 100) / quoteAmount
            return percentage.toFixed(2)
        },
        totalOfCommissionForEmployeePerInvoice (invoiceAmount,commissionPercentage) {
            let total = (invoiceAmount * commissionPercentage) / 100
            return total.toFixed(2);

        },
        viewInvoices (quote) {
            this.invoices = quote.invoices
            $('#myModalInvoices').modal('show');
        },
        toggleLayout () {
            this.layout == 'list' ? this.layout = 'grid' : this.layout = 'list';
            this.quotes.forEach(item => {
                axios.get(`/quote/month?month=${item.request_date.split('-')[1]}&designer=${item.designer_id}`)
                    .then(response => {            
                        let key = item.designer_id
                        if (this.designers.hasOwnProperty(key)) {
                            this.designers[key].quotes = this.designers[key].quotes.concat(response.data.quotes)
                            this.designers[key].total = response.data.total
                            this.designers[key].commission_title = response.data.commission_title
                            this.designers[key].commission_percentage = response.data.commission_percentage
                        } else {
                            this.$set(this.designers, key, 
                                { 
                                    designer: item.designer.name, 
                                    quotes: response.data.quotes, 
                                    total:response.data.total,
                                    commission_title: response.data.commission_title,
                                    commission_percentage: response.data.commission_percentage 
                                }
                            )
                        }                        
                    });
                axios.get(`/quote/month?month=${item.request_date.split('-')[1]}&salesman=${item.salesman_id}`)
                    .then(response => {            
                        let key = item.salesman_id
                        if (this.salesmans.hasOwnProperty(key)) {
                            this.salesmans[key].quotes = this.salesmans[key].quotes.concat(response.data.quotes)
                            this.salesmans[key].total = response.data.total
                            this.salesmans[key].commission_title = response.data.commission_title
                            this.salesmans[key].commission_percentage = response.data.commission_percentage
                        } else {
                            this.$set(this.salesmans, key, 
                                { 
                                    salesman: item.salesman.name, 
                                    quotes: response.data.quotes, 
                                    total:response.data.total,
                                    commission_title: response.data.commission_title,
                                    commission_percentage: response.data.commission_percentage
                                }
                            )
                        }                        
                    });
            })            
        }
    }
}
</script>