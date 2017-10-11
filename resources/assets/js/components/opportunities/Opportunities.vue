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
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Registered Date</th>
                              <th>Description</th>
                              <th>Owner</th>
                              <th>Contact</th>
                              <th>Customer</th>
                              <th>Area</th>
                              <th>Priority</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(item, index) in opportunities">
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
                                  <a v-bind:href="'/profile/'+item.owner.id">{{item.owner.name}}</a>
                              </td>
                              <td>
                                  {{item.contact.name}}
                              </td>
                              <td>
                                  {{item.contact.customer.name}}
                              </td>
                              <td>
                                  {{item.area.title}}
                              </td>
                              <td>
                                  <span class="chip" :class="'priority-'+item.priority.name">
                                      {{item.priority.name}}
                                  </span>
                              </td>
                              <td class="text-right col-sm-2">
                                  <div class="btn-group">
                                      <a href="#" class="btn btn-default btn-sm" @click.prevent="edit(item, index)" v-tooltip:top="'Edit'">
                                          <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                      </a>
                                      <a href="#" class="btn btn-default btn-sm" v-tooltip:top="'Make project'">
                                          <i class="fa fa-check-square-o fa-lg" aria-hidden="true"></i>
                                      </a>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- Create opportunity -->
          <div class="modal fade" id="modalAdd">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Add Opportunity</h4>
                      </div>
                      <div class="modal-body">
                          <form class="form-horizontal">
                              <div class="form-group" :class="{'has-error': error.name}">
                                  <label class="col-sm-2 control-label">Name *</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                      <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.start_date}">
                                  <label class="col-sm-2 control-label">Start Date *</label>
                                  <div class="col-sm-10">
                                      <input type="datetime-local" class="form-control" v-bind:placeholder="opportunity.start_date" required v-model="opportunity.start_date">
                                      <span class="help-block" v-if="error.start_date">{{error.start_date[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-2 control-label">Description</label>
                                  <div class="col-sm-10">
                                      <textarea type="text" class="form-control" placeholder="Description" required v-model="opportunity.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-2 control-label">Priority</label>
                                  <div class="col-sm-10">
                                      <select class="form-control" required v-model="opportunity.priority">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.area}">
                                  <label class="col-sm-2 control-label">Area</label>
                                  <div class="col-sm-10">
                                      <select class="form-control" required v-model="opportunity.area">
                                          <option v-for="option in list.areas" :value="option.id">
                                              {{ option.title }}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.contact}">
                                  <label class="col-sm-2 control-label">Contact</label>
                                  <div class="col-sm-10">
                                      <select class="form-control" required v-model="opportunity.contact">
                                          <option v-for="option in list.contacts" :value="option.id">
                                              {{option.name}}, {{option.customer.name}}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
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
          <!-- Edit opportunity -->
          <div class="modal fade" id="modalEdit">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Edit opportunity</h4>
                      </div>
                      <div class="modal-body">
                          <form class="form-horizontal">
                              <div class="form-group" :class="{'has-error': error.name}">
                                  <label class="col-sm-2 control-label">Name</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-2 control-label">Description</label>
                                  <div class="col-sm-10">
                                      <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="opportunity.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-2 control-label">Priority</label>
                                  <div class="col-sm-10" v-if="list.priorities && opportunity.priority">
                                      <select class="form-control" required v-model="opportunity.priority.id">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.area}">
                                  <label class="col-sm-2 control-label">Area</label>
                                  <div class="col-sm-10" v-if="list.priorities && opportunity.area">
                                      <select class="form-control" required v-model="opportunity.area.id">
                                          <option v-for="option in list.areas" :value="option.id">
                                              {{ option.title }}
                                          </option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-2 control-label">Owner</label>
                                  <div class="col-sm-10">
                                      <p class="form-control-static">
                                          <small class="text-mute" v-if="opportunity.owner">{{opportunity.owner.name}}</small>
                                      </p>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-2 control-label">Registered</label>
                                  <div class="col-sm-10">
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
export default {
    data() {
        return {
            loading: false,
            opportunities: [],
            opportunity: {},
            search: '',
            error: {},
            list: {
                priorities: [],
                contacts: [],
                areas: []
            }
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
            return moment(date).format('LLL');
        }
    },
    methods: {
        getAll: function() {
            this.loading = true;
            axios.get('/opportunities/all')
                .then(response => {
                    this.opportunities = response.data;
                    this.loading = false;
                });
        },
        add: function() {
            axios.get('/opportunities/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
            });
            axios.get('/opportunities/list/contacts')
            .then(response => {
                this.list.contacts = response.data;
            });
            axios.get('/opportunities/list/areas')
            .then(response => {
                this.list.areas = response.data;
            });
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
                    var btn = $(e.target).button('reset')
                });
        },
        edit: function (opportunity, index) {
            axios.get('/opportunities/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
                this.opportunity = _.clone(opportunity);
                this.opportunity.index = index;
            });
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
    }
}
</script>
