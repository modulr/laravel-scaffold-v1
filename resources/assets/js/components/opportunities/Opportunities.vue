<template lang="html">
    <div class="opportunities">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="wrapper" v-if="!loading">
          <div class="row">
              <div class="col-xs-12 text-right">
                  <a href="#" class="btn btn-primary" @click.prevent="add">
                      <i class="mdi mdi-person-add mdi-lg"></i> Add Opportunity
                  </a>
              </div>
          </div>
          <br>
          <!-- No items found -->
          <div class="row" v-if="opportunities.length == 0">
              <div class="col-md-12">
                  <h3>No opportunities found.</h3>
              </div>
          </div>
          <!-- List -->
          <div class="row" v-if="opportunities.length != 0">
              <div class="col-md-12">
                  <div class="filters">
                      <div class="sort col-xs-2">
                          <select class="form-control" required v-model="sort.customers">
                              <option value="" disabled selected>Customer</option>
                              <option v-for="option in list.customers" :value="option.id">
                                  {{ option.name }}
                              </option>
                              <option value="">None</option>
                          </select>
                      </div>
                      <div class="sort col-xs-2">
                          <select class="form-control" required v-model="sort.client">
                              <option value="" disabled selected>Client</option>
                              <option v-for="option in list.clients" :value="option.id">
                                  {{ option.name }}
                              </option>
                              <option value="">None</option>
                          </select>
                      </div>
                      <div class="sort col-xs-2">
                          <select class="form-control" required v-model="sort.area">
                              <option value="" disabled selected>Region</option>
                              <option v-for="option in list.areas" :value="option.id">
                                  {{ option.title }}
                              </option>
                              <option value="">None</option>
                          </select>
                      </div>
                      <div class="search col-xs-6">
                          <div class="col-xs-1">
                              <i class="mdi mdi-search mdi-2x"></i>
                          </div>
                          <div class="col-xs-8">
                              <input type="text" class="form-control" placeholder="Search" v-model="search">
                          </div>
                      </div>
                  </div>
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Registered Date</th>
                              <th>Description</th>
                              <th>Total Amount</th>
                              <th>Owner</th>
                              <th>Client</th>
                              <th>Customer</th>
                              <th>Area</th>
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
                                  <a :href="'/opportunities/'+item.id+'/get'">
                                      <strong>{{item.name}}</strong>
                                  </a>
                              </td>
                              <td>
                                  {{item.start_date | date}}<br>
                              </td>
                              <td>
                                  {{item.description}}<br>
                              </td>
                              <td>
                                {{ totalQuotes(item.quote) | currency }}
                              </td>
                              <td>
                                  <a v-bind:href="'/profile/'+item.owner.id">{{item.owner.name}}</a>
                              </td>
                              <td>
                                  {{item.client.name}}
                              </td>
                              <td>
                                  {{item.client.customer.name}}
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
                              <td class="text-right">
                                  <div class="dropdown">
                                      <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                          <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                      </a>
                                      <ul class="dropdown-menu dropdown-menu-right">
                                          <li>
                                              <a href="#" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                Edit
                                              </a>
                                              <!-- <a href="#"><i class="fa fa-file-pdf-o"></i>
                                                Create Quote
                                              </a> -->
                                          </li>
                                      </ul>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- Create opportunity -->
          <div class="modal right fade" id="modalAdd">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Add Opportunity</h4>
                      </div>
                      <div class="modal-body">
                          <form class="form-horizontal">
                              <div class="form-group" :class="{'has-error': error.name}">
                                  <label class="col-sm-3 control-label required">Name</label>
                                  <div class="col-sm-9">
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                      <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.start_date}">
                                  <label class="col-sm-3 control-label required">Start Date</label>
                                  <div class="col-sm-9">
                                      <input type="date" class="form-control" v-bind:placeholder="opportunity.start_date" required v-model="opportunity.start_date">
                                      <span class="help-block" v-if="error.start_date">{{error.start_date[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-3 control-label">Description</label>
                                  <div class="col-sm-9">
                                      <textarea type="text" class="form-control" placeholder="Description" v-model="opportunity.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-3 control-label required">Priority</label>
                                  <div class="col-sm-9">
                                      <select class="form-control" required v-model="opportunity.priority">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.priority">{{error.priority[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.area}">
                                  <label class="col-sm-3 control-label required">Area</label>
                                  <div class="col-sm-9">
                                      <select class="form-control" required v-model="opportunity.area">
                                          <option v-for="option in list.areas" :value="option.id">
                                              {{ option.title }}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.area">{{error.area[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.client}">
                                  <label class="col-sm-3 control-label required">Client</label>
                                  <div class="col-sm-9">
                                      <select class="form-control" required v-model="opportunity.client">
                                          <option v-for="option in list.clients" :value="option.id">
                                              {{option.name}}, {{option.customer.name}}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.client">{{error.client[0]}}</span>
                                      <span>Or <a :href="'/clients'">create a new client.</a></span>
                                  </div>
                              </div>
                          </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                          <button type="button" class="btn btn-primary" @click="store">Add</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Edit opportunity -->
          <div class="modal right fade" id="modalEdit">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Edit opportunity</h4>
                      </div>
                      <div class="modal-body">
                          <form class="form-horizontal">
                              <div class="form-group" :class="{'has-error': error.name}">
                                  <label class="col-sm-3 control-label required">Name</label>
                                  <div class="col-sm-9">
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-3 control-label">Description</label>
                                  <div class="col-sm-9">
                                      <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="opportunity.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-3 control-label required">Priority</label>
                                  <div class="col-sm-9" v-if="list.priorities && opportunity.priority">
                                      <select class="form-control" required v-model="opportunity.priority.id">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                       <span class="help-block" v-if="error.priority">{{error.priority[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.area}">
                                  <label class="col-sm-3 control-label required">Area</label>
                                  <div class="col-sm-9" v-if="list.areas && opportunity.area">
                                      <select class="form-control" required v-model="opportunity.area.id">
                                          <option v-for="option in list.areas" :value="option.id">
                                              {{ option.title }}
                                          </option>
                                      </select>
                                       <span class="help-block" v-if="error.area">{{error.area[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-3 control-label required">Owner</label>
                                  <div class="col-sm-9">
                                      <p class="form-control-static">
                                          <small class="text-mute" v-if="opportunity.owner">{{opportunity.owner.name}}</small>
                                      </p>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-3 control-label required">Registered</label>
                                  <div class="col-sm-9">
                                      <p class="form-control-static">
                                          <small class="text-mute">{{opportunity.start_date | date}}</small>
                                      </p>
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
    </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
export default {
    data() {
        return {
            loading: false,
            newCustomer: false,
            opportunities: [],
            opportunity: {
                area: {},
            },
            search: '',
            sort: {
                customers: '',
                client: '',
                area: '',
                total: '',
            },
            error: {},
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: []
            }
        }
    },
    computed: {
        filteredOpportunities: function() {
            var filteredArray = this.opportunities,
                sort_customers = this.sort.customers,
                sort_client = this.sort.client,
                sort_area = this.sort.area,
                search = this.search;

            if(sort_customers) {
                filteredArray = filteredArray.filter(function (item) {
                    if (item.client.customer_id === sort_customers) {
                        return item;
                    }
                });
            }

            if(sort_client) {
                filteredArray = filteredArray.filter(function (item) {
                    if (item.client.id === sort_client) {
                        return item;
                    }
                });
            }

            if(sort_area) {
                filteredArray = filteredArray.filter(function (item) {
                    if (item.area.id === sort_area) {
                        return item;
                    }
                });
            }

            if(search) {
                search = search.trim().toLowerCase();

                filteredArray = filteredArray.filter(function(item){
                    console.log(item);
                    return Object.keys(item).some(function (key) {
                        return String(item[key]).toLowerCase().indexOf(search) !== -1
                    })
                })
            }


            return filteredArray;

        }
    },
    components: {
      Spinner
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
        getAll: function() {
            this.loading = true;
            axios.get('/opportunities/all')
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
                    this.opportunities = response.data;
                    this.loading = false;
                });
        },
        add: function() {
            this.opportunity = {
                name: '',
                start_date: '',
                description: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function(e) {
            var btn = $(e.target).button('loading')
            axios.post('/opportunities/store', this.opportunity)
                .then(response => {
                    this.opportunities.push(response.data);
                    this.opportunity = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    console.log(error.response.data);
                    var btn = $(e.target).button('reset')
                });
        },
        edit: function (opportunity, index) {
            this.opportunity = _.clone(opportunity);
            this.opportunity.index = index;
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/opportunities/update/'+this.opportunity.id, this.opportunity)
            .then(response => {
                this.opportunities[this.opportunity.index] = response.data;
                this.opportunity = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalEdit').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroy: function () {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this opportunity!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/opportunities/destroy/' + self.opportunity.id)
                .then(response => {
                    self.opportunities.splice(self.opportunity.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The opportunity has been deleted.",
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
        },
        totalQuotes (quotes) {
          let total = 0.00
          if(quotes.length > 0)
            quotes.forEach(function (item) {
              total += item.amount
            })
          return total
        }
    }
}
</script>
