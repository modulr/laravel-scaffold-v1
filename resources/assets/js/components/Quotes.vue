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
                <th>Seller</th>
                <th>Ammount</th>
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
                <td> {{ quote.seller.name }} </td>
                <td> {{ quote.ammount }} </td>
                <td> {{ quote.request_date | date }} </td>
                <td> {{ quote.delivery_date | date }} </td>
                <td> {{ quote.status }} </td>
                <td class="text-right">
                    <a href="#" class="btn btn-link" @click.prevent="edit(quote, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
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
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="quote.description">
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Project *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Customer" required v-model="quote.project_id">
                              <option v-for="project in list.projects" :value="project.id"> {{ project.name }} </option>
                            </select>
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
                        <label class="col-sm-2 control-label">Seller *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Seller" required v-model="quote.seller_id">
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
                        <label class="col-sm-2 control-label">Ammout *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Ammout" required v-model="quote.ammount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Request Date *</label>
                        <div class='input-group date'>
                            <input type='datetime-local' class="form-control" v-model="quote.request_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date</label>
                        <div class='input-group date'>
                            <input type='datetime-local' class="form-control" v-model="quote.delivery_date"/>
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
                        <label class="col-sm-2 control-label">Seller *</label>
                        <div class="col-sm-10">
                            <select type="text" class="form-control input-lg" placeholder="Seller" required v-model="quote.seller_id">
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
                        <label class="col-sm-2 control-label">Ammout *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control input-lg" placeholder="Ammout" required v-model="quote.ammount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Request Date </label>
                        <div class="col-sm-10">
                          <p class="form-control-static">
                            <small class="text-mute">{{quote.request_date}}</small>
                          </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Delivery Date </label>
                        <div class='input-group date'>
                            <input type='datetime-local' class="form-control" v-model="quote.delivery_date"/>
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
  </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
export default {
  data () {
    return {
      loading: false,
      quotes: [],
      quote: {
        close_date: "2017-08-05"
      },
      list: {
        designers: [],
        sellers: [],
        customers: [ { name: 'First customer', id: '1' }, { name: 'Second customer', id: '2' } ],
        projects: [],
      }
    }
  },
  components: {
    Spinner
  },
  filters: {
      date (date) {
          return moment(date).format('LLL');
      }
  },
  mounted () {
    this.getAll()
  },
  methods: {
    getAll () {
      this.loading = true;
      axios.get('/quote/all')
        .then(response => {
          this.quotes = response.data;
          this.loading = false;
        });
      axios.get('/employees/sellers')
        .then(response => {
          this.list.sellers = response.data;
        });
      axios.get('/employees/designers')
        .then(response => {
          this.list.designers = response.data;
        });
      axios.get('/opportunities/all')
        .then(response => {
          this.list.projects = response.data;
        });
    },
    add () {
      this.quote = {}
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
          this.quotes[this.quote.index] = response.data;
          this.quote = {};
          this.error = {};
          var btn = $(e.target).button('reset')
          $('#modalEdit').modal('hide');
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
          $('#modalEdit').modal('hide');
        })
        .catch(error => {
            self.error = error.response.data;
        });
      });
    }
  }
}
</script>
