<template lang="html">
    <div class="projects">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="wrapper" v-if="!loading">
          <!-- No items found -->
          <div class="row" v-if="projects.length == 0">
              <div class="col-md-12">
                  <h3>No projects found.</h3>
              </div>
          </div>
          <!-- List -->
          <div class="row" v-if="projects.length != 0">
              <div class="col-md-12">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Leader</th>
                              <th>Supervisor</th>
                              <th>Client</th>
                              <th>Customer</th>
                              <th>Progress</th>
                              <th>Status</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(item, index) in projects">
                              <td>
                                  {{item.id}}
                              </td>
                              <td>
                                  {{item.name}}<br>
                              </td>
                              <td>
                                  {{item.start_date | date}}<br>
                              </td>
                              <td>
                                  {{item.end_date | date}}<br>
                              </td>
                              <td>
                                  Leader<br>
                              </td>
                              <td>
                                  Supervisor<br>
                              </td>
                              <td>
                                  {{item.client.name}}
                              </td>
                              <td>
                                  {{item.client.customer.name}}
                              </td>
                              <td>
                                  50%
                              </td>
                              <td>
                                  {{item.project_status.name}}
                              </td>
                              <td class="text-right col-sm-2">
                                  <div class="btn-group">
                                      <a href="#" class="btn btn-default btn-sm" @click.prevent="edit(item, index)" v-tooltip:top="'Edit'">
                                          <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                      </a>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- Create project -->
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
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="project.name">
                                      <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.start_date}">
                                  <label class="col-sm-2 control-label">Start Date *</label>
                                  <div class="col-sm-10">
                                      <input type="datetime-local" class="form-control" v-bind:placeholder="project.start_date" required v-model="project.start_date">
                                      <span class="help-block" v-if="error.start_date">{{error.start_date[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-2 control-label">Description</label>
                                  <div class="col-sm-10">
                                      <textarea type="text" class="form-control" placeholder="Description" required v-model="project.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-2 control-label">Priority</label>
                                  <div class="col-sm-10">
                                      <select class="form-control" required v-model="project.priority">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                      <span class="help-block" v-if="error.start_date">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.client}">
                                  <label class="col-sm-2 control-label">Contact</label>
                                  <div class="col-sm-10">
                                      <select class="form-control" required v-model="project.client">
                                          <option v-for="option in list.clients" :value="option.id">
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
          <!-- Edit project -->
          <div class="modal fade" id="modalEdit">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Edit project</h4>
                      </div>
                      <div class="modal-body">
                          <form class="form-horizontal">
                              <div class="form-group" :class="{'has-error': error.name}">
                                  <label class="col-sm-2 control-label">Name</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control input-lg" placeholder="Name" required v-model="project.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.description}">
                                  <label class="col-sm-2 control-label">Description</label>
                                  <div class="col-sm-10">
                                      <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="project.description">
                                      </textarea>
                                      <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                                  </div>
                              </div>
                              <div class="form-group" :class="{'has-error': error.priority}">
                                  <label class="col-sm-2 control-label">Priority</label>
                                  <div class="col-sm-10" v-if="list.priorities && project.priority">
                                      <select class="form-control" required v-model="project.priority.id">
                                          <option v-for="option in list.priorities" :value="option.id">
                                              {{ option.name }}
                                          </option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-2 control-label">Owner</label>
                                  <div class="col-sm-10">
                                      <p class="form-control-static">
                                          <small class="text-mute" v-if="project.owner">{{project.owner.name}}</small>
                                      </p>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <label class="col-sm-2 control-label">Registered</label>
                                  <div class="col-sm-10">
                                      <p class="form-control-static">
                                          <small class="text-mute">{{project.start_date | date}}</small>
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
            projects: [],
            project: {},
            search: '',
            error: {},
            list: {
                priorities: [],
                clients: [],
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
            axios.get('/projects/all')
                .then(response => {
                    this.projects = response.data;
                    this.loading = false;
                });
        },
        add: function() {
            axios.get('/projects/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
            });
            axios.get('/projects/list/clients')
            .then(response => {
                this.list.clients = response.data;
            });
            this.project = {
                name: '',
                start_date: '',
                description: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function(e) {
            var btn = $(e.target).button('loading')
            axios.post('/projects/store', this.project)
                .then(response => {
                    this.projects.push(response.data);
                    this.project = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                  });
        },
        edit: function (project, index) {
            axios.get('/projects/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
                this.project = _.clone(project);
                this.project.index = index;
            });
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/projects/update/'+this.project.id, this.project)
            .then(response => {
                this.projects[this.project.index] = response.data;
                this.project = {};
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
                text: "You will not be able to recover this project!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/projects/destroy/' + self.project.id)
                .then(response => {
                    self.projects.splice(self.project.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The project has been deleted.",
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
        makeProject: function (id, index) {
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
            function(){
                axios.put('/projects/update/' + id + '/make_project')
                .then(response => {
                    self.projects.splice(index, 1);
                    swal({
                        title: "Updated!",
                        text: "The project has been turned into a project.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });When
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
