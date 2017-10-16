<template>
  <div class="quotes">
    <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
    <div class="wrapper" v-if="!loading">
      <div class="row">
        <div class="col-xs-12 text-right">
          <a href="#" class="btn btn-primary" @click.prevent="add">
            <i class="mdi mdi-person-add mdi-lg"></i> Add Quote
          </a>
        </div>
      </div>
      <br>
      <!-- No items found -->
      <div class="row" v-if="quotes.length == 0">
        <div class="col-md-12">
          <h3>No quotes found.</h3>
        </div>
      </div>
      <!-- List -->
      <div class="row" v-if="quotes.length != 0">
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
                    <!-- <a href="#" class="btn btn-link" @click.prevent="edit(quote, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                    <a href="#" class="btn btn-link" @click.prevent="uploadFile(quote)"><i class="mdi mdi-note-add mdi-lg"></i></a> -->
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
        </div>
      </div>
    </div>
    <!-- Add quote -->
    <div class="modal fade" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Quote</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" action="index.html" method="post">
                    <div class="form-group" :class="{'has-error': error.name}">
                        <label class="col-sm-2 control-label">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.description}">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="quote.description">
                            </textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.project}">
                        <label class="col-sm-2 control-label">Project *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Customer" required v-model="quote.project">
                              <option v-for="project in list.projects" :value="project.id"> {{ project.name }} </option>
                            </select>
                            <span class="help-block" v-if="error.project">{{error.project[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.designer}">
                        <label class="col-sm-2 control-label">Designer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Designer" required required v-model="quote.designer">
                              <option v-for="designer in list.designers" :value="designer.id"> {{ designer.name }} </option>
                            </select>
                            <span class="help-block" v-if="error.designer">{{error.designer[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.salesman}">
                        <label class="col-sm-2 control-label">Salesman *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Salesman" required v-model="quote.salesman">
                              <option v-for="seller in list.sellers" :value="seller.id"> {{ seller.name }} </option>
                            </select>
                            <span class="help-block" v-if="error.salesman">{{error.salesman[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.customer}">
                        <label class="col-sm-2 control-label">Customer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Customer" required v-model="quote.customer">
                              <option v-for="customer in list.customers" :value="customer.id"> {{ customer.name }} </option>
                            </select>
                            <span class="help-block" v-if="error.customer">{{error.customer[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.service}">
                        <label class="col-sm-2 control-label">Service *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Service" required v-model="quote.service">
                              <option v-for="service in list.services" :value="service.id"> {{ service.title }} </option>
                            </select>
                            <span class="help-block" v-if="error.service">{{error.service[0]}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Amount </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="quote.amount">
                        </div>
                        <label class="col-sm-2 control-label">Currency </label>
                        <div class="col-sm-2">
                          <select type="text" class="form-control input-lg" placeholder="Currency" required v-model="quote.currency">
                            <option v-for="currency in list.currencies" :value="currency.id"> {{ currency.title }} </option>
                          </select>
                        </div>
                    </div>
                    <div class="form-group" :class="{'has-error': error.request_date}">
                        <label class="col-sm-2 control-label">Request Date *</label>
                        <div class='input-group date'>
                            <input type='date' class="form-control" v-model="quote.request_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <span class="help-block" v-if="error.request_date">{{error.request_date[0]}}</span>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date</label>
                        <div class='input-group date'>
                            <input type='date' class="form-control" v-model="quote.delivery_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" @click="store">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit quote -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit quote</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" action="index.html" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                        </div>
                    </div>
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="quote.description">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Project *</label>
                        <div class="col-sm-10">
                          <p class="form-control-static" v-if="quote.project">
                            <small class="text-mute">{{quote.project.name}}</small>
                          </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Designer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Designer" required required v-model="quote.designer_id">
                              <option v-for="designer in list.designers" :value="designer.id"> {{ designer.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Salesman *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Salesman" required v-model="quote.salesman_id">
                              <option v-for="seller in list.sellers" :value="seller.id"> {{ seller.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Customer *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Customer" required v-model="quote.customer_id">
                              <option v-for="customer in list.customers" :value="customer.id"> {{ customer.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Service *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Service" required v-model="quote.service_id">
                              <option v-for="service in list.services" :value="service.id"> {{ service.title }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Amount </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="quote.amount">
                        </div>
                        <label class="col-sm-2 control-label">Currency </label>
                        <div class="col-sm-2">
                          <select type="text" class="form-control input-lg" placeholder="Currency" required v-model="quote.currency_id">
                            <option v-for="currency in list.currencies" :value="currency.id"> {{ currency.title }} </option>
                          </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Request Date </label>
                        <div class="col-sm-10">
                          <p class="form-control-static">
                            <small class="text-mute">{{quote.request_date | date}}</small>
                          </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date </label>
                        <div class='input-group date'>
                            <input type='date' class="form-control" v-model="quote.delivery_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
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
    <!-- Modal File -->
    <div class="modal fade" id="myModalFile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Upload Files</h4>
                </div>
                <div class="modal-body">
                    <dropzone id="myVueDropzone" ref="myVueDropzone"
                        url="/attachments/store" :use-font-awesome=true
                        :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                        v-on:vdropzone-success="uploadSuccess">
                        <input type="hidden" name="quote_id" v-model="quote.id">
                    </dropzone>
                    <div class="row" v-if="!quote.attachment">
                      <div class="col-md-12">
                        <h3>No attachments found.</h3>
                      </div>
                    </div>
                    <div class="col-md-12" v-if="quote.attachment">
                      <table class="table table-hover">
                        <thead>
                          <th>Id</th>
                          <th>Name</th>
                          <th></th>
                        </thead>
                        <tbody>
                          <tr v-for="(a,index) in quote.attachment">
                            <td>{{ index + 1}}</td>
                            <td>
                              <a :href="a.url" target="_blank">
                                  <i class="fa fa-fw fa-file-o fa-2x"></i>
                                  {{a.name}}
                              </a>
                            </td>
                            <td>
                              <a href="#">
                                <i class="fa fa-trash-o" aria-hidden="true" @click.prevent="deleteAttachment(quote, a, index)"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Dropzone from 'vue2-dropzone';
import Vue2Filters from 'vue2-filters';
export default {
  data () {
    return {
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
        ]
      },
      error: {},
      dzOptions: {
          acceptedFileTypes: '.jpg,.jpeg,.png,.pdf',
          headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
      }
    }
  },
  components: {
    Spinner,
    Dropzone
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
  },
  methods: {
    getAll () {
      this.loading = true;
      axios.get('/services/all')
        .then(response => {
          this.list.services = response.data
        });
      axios.get('/quote/all')
        .then(response => {
          this.quotes = response.data
          this.loading = false
        });
      axios.get('/employees/all')
        .then(response => {
          this.list.sellers = response.data
          this.list.designers = response.data
        });
      axios.get('/opportunities/all')
        .then(response => {
          this.list.projects = response.data;
        });
      axios.get('/customers/all')
        .then(response => {
            this.list.customers = response.data;
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
    store (e) {
      var btn = $(e.target).button('loading')
      axios.post('/quote/store', this.quote)
        .then(response => {
            var btn = $(e.target).button('reset')
            this.quotes.push(response.data)
            this.quote = {}
            $('#modalAdd').modal('hide')
        })
        .catch(error => {
            this.error = error.response.data;
            var btn = $(e.target).button('reset')
        });
    },
    edit (quote, index) {
      this.quote = _.clone(quote)
      this.quote.index = index
      $('#modalEdit').modal('show')
    },
    update (e) {
      var btn = $(e.target).button('loading')
      axios.put('/quote/update/'+this.quote.id, this.quote)
        .then(response => {
          this.quotes[this.quote.index] = response.data
          this.quote = {}
          this.error = {}
          var btn = $(e.target).button('reset')
          $('#modalEdit').modal('hide')
        })
        .catch(error => {
          this.error = error.response.data;
          var btn = $(e.target).button('reset')
        });
    },
    destroy () {
      var self = this;
      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this quote!",
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function(){
        axios.delete('/quote/destroy/' + self.quote.id)
        .then(response => {
          self.quotes.splice(self.quote.index, 1);
          swal({
              title: "Deleted!",
              text: "The quote has been deleted.",
              type: "success",
              timer: 1000,
              showConfirmButton: false
          });
          self.error = {};
          $('#modalEdit').modal('hide')
        })
        .catch(error => {
            self.error = error.response.data
        });
      });
    },
    uploadFile (quote) {
      this.quote = _.clone(quote)
      $('#myModalFile').modal('show')
    },
    uploadSuccess: function (file, response) {
        if(this.quote.attachment){
          this.quote.attachment.push(response)
        }
        else{
          this.$set(this.quote, 'attachment', [response])
        }
        // $('#myModalFile').modal('hide');
    },
    changeStatus (quote, index, status) {
      this.quote = _.clone(quote)
      this.quote.status_id = status
      this.quote.index = index
      axios.put('/quote/update/'+this.quote.id, this.quote)
        .then(response => {
          this.quotes[this.quote.index] = response.data
          this.quote = {}
          this.error = {}
        })
        .catch(error => {
          this.error = error.response.data;
        });
    },
    deleteAttachment (quote, attachment, index) {
      console.log(quote)
      var self = this;
      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this Attachment!",
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function(){
        axios.delete('/attachments/destroy/' + attachment.id)
        .then(response => {
          quote.attachment.splice(index, 1);
          swal({
              title: "Deleted!",
              text: "The Attachment has been deleted.",
              type: "success",
              timer: 1000,
              showConfirmButton: false
          });
          self.error = {};
          $('#modalEdit').modal('hide')
        })
        .catch(error => {
            self.error = error.response.data
        });
      });
    }
  }
}
</script>
