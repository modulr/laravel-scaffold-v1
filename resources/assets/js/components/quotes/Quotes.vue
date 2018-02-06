<template>
  <div class="quotes">
    <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
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
              <a href="#" class="pull-right" @click.prevent="searchQuotes">Search</a>
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
                    <p class="title"><small>Customer</small></p>
                      <multiselect v-model="sort.customer" @input="searchProjects" track-by="name" label="name" :options="list.customers" placeholder="Customer"></multiselect>
                  </div>
                </div>
                <div class="row">
                  <div class="card col-xs-12">
                    <p class="title"><small>Project</small></p>
                      <multiselect v-if="list.projects && list.projects.length > 0" v-model="sort.project" track-by="name" label="name" :options="list.projects" placeholder="Project"></multiselect>
                  </div>
                </div>
                <div class="row">
                  <div class="card col-xs-12">
                    <p class="title"><small>Status</small></p>
                      <multiselect v-model="sort.status" track-by="name" label="title" :options="list.status" placeholder="Status"></multiselect>
                  </div>
                </div>
                <div class="row">
                  <div class="card col-xs-12">
                    <p class="title"><small>Service</small></p>
                      <multiselect v-model="sort.service" track-by="name" label="title" :options="list.services" placeholder="Service"></multiselect>
                  </div>
                </div>
          </div>
        </div>
        <!-- No items found -->
        <div class="col-md-10" v-if="quotes.length == 0">
            <h3>No quotes found.</h3>
          </div>
        <div class="col-md-10" v-if="quotes.length != 0">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Amount MXN</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.amountMXN | currency }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Amount USD</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.amountUSD | currency }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Total Pending</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.pending }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Total Sent</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.sent }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Total Approve</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.approve }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Total Refuse</h3>
                  </div>
                  <div class="panel-body">
                    {{ insights.refuse }}
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
                                    <a href="#" @click.prevent="edit(quote, index)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                      Edit
                                    </a>
                                    <a href="#" @click.prevent="uploadFile(quote)"><i class="fa fa-file-pdf-o"></i>
                                      Upload PDF
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li>
                                  <a href="#" @click.prevent="changeStatus(quote, index, 2)"><i class="fa fa-envelope-o"></i> Sent</a>
                                  <a href="#" @click.prevent="changeStatus(quote, index, 3)"><i class="fa fa-thumbs-o-up"></i> Approve</a>
                                  <a href="#" @click.prevent="changeStatus(quote, index, 4)"><i class="fa fa-thumbs-o-down"></i> Refuse</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="pull-left">
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
        </div>
      </div>
    </div>
    <!-- Add quote -->
    <quotes-create :list="list" :quotes="quotes" :quote="quote" class="modal right fade" id="modalAdd"></quotes-create>
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
  data () {
    return {
      user: Laravel.user,
      loading: false,
      quotes: [],
      quote: {},
      list: {
        designers: [],
        sellers: [],
        customers: [],
        projects: [],
        services: [],
        currencies: [
          {
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
      search: '',
      sort: {
        customer: "",
        status: "",
        project: "",
        service: ""
      },
      pagination : {
        current_page: 1,
        total: '',
        from: '',
        to: '',
        // last_page: 1
      },
      insights : {}
    }
  },
  components: {
    Spinner,
    Paginate,
    Multiselect
  },
  filters: {
      date (date) {
          return date ? moment(date).format('LL'): '';
      },
      datetime (date) {
          return date ? moment(date).format('LLL'): '';
      }
  },
  mounted () {
    this.getAll()
    this.getQuotes()
  },
  methods: {
    searchProjects (customer) {
      if (customer) {
        this.quote.customer = customer
        axios.get('/opportunities/customers/' + customer.id)
          .then(response => {
            this.list.projects = response.data
          });
      } else {
        this.list.projects = []
      }
    },
    clickCallback (page) {
      this.pagination.current_page = page
      this.getQuotes()
    },
    getAll () {
      this.loading = true;
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
    getQuotes () {
      var page = Number(this.pagination.current_page);
      this.loading = true
      axios.get(`/quote/all?page=${page}${this.search ? '&name=' + this.search: ''}${this.sort.customer ? '&customer=' + this.sort.customer.id : ''}${this.sort.project ? '&project=' + this.sort.project.id : ''}${this.sort.status ? '&status=' + this.sort.status.id : ''}${this.sort.service ? '&service=' + this.sort.service.id : ''}`)
        .then(response => {
          this.pagination.last_page = response.data.quotes.last_page
          this.pagination.total = response.data.quotes.total
          this.pagination.from = response.data.quotes.from
          this.pagination.to = response.data.quotes.to
          this.quotes = response.data.quotes.data
          this.insights.total = response.data.quotes.total
          this.insights.amountMXN = response.data.amountMXN
          this.insights.amountUSD = response.data.amountUSD
          this.insights.pending = response.data.pending
          this.insights.sent = response.data.sent
          this.insights.approve = response.data.approve
          this.insights.refuse = response.data.refuse
          this.loading = false
        });
    },
    add () {
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
    edit (quote, index) {
      this.quote = _.clone(quote)
      this.quote.index = index
      $('#modalEdit').modal('show')
    },
    uploadFile (quote) {
      this.type = 2
      this.quote = _.clone(quote)
      let self = this
      this.quote.attachment.forEach(function (item, index) {
        if(item.type === '1'){
          self.$set(self.quote, 'oc', item)
          self.quote.attachment.splice(index,1)
        }
      })
      $('#myModalFile').modal('show')
    },
    changeStatus (quote, index, status) {
      this.quote = _.clone(quote)
      this.quote.status_id = status
      this.quote.index = index
      if(status !== 3) {
        axios.put('/quote/update/'+this.quote.id, this.quote)
          .then(response => {
            this.quotes[this.quote.index] = response.data
            this.quote = {}
            this.error = {}
          })
          .catch(error => {
            this.error = error.response.data;
          });
      }else {
        $('#myModalFile').modal('show')
        this.type = 1
      }
    },
    clearFilters: function() {
        this.sort = {
          customer: "",
          status: "",
          project: "",
          service: ""
        },
        this.search = '';
        this.getQuotes()
    },
    searchQuotes () {
      this.current_page = 1
      this.getQuotes()
    }
  }
}
</script>
