<template>
    <div class="employees">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="#" class="btn btn-primary" @click.prevent="add()">
                        <i class="mdi mdi-person-add mdi-lg"></i> Add Employee
                    </a>
                </div>
            </div>
            <br>
            <!-- No items found -->
            <div class="row" v-if="employees.length == 0">
                <div class="col-md-12">
                    <h3>No employees found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="employees.length != 0">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in employees">
                                <td>
                                    {{item.id}}
                                </td>
                                <td>
                                    {{item.name}}<br>
                                </td>
                                <td>
                                    {{ item.role.title }}
                                </td>
                                <td class="text-right" @click="edit(item, index)">
                                    <a href="#" class="btn btn-link" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Create employees -->
            <div class="modal fade" id="modalAddEmployee">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Employee</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal">
                                <div class="form-group" :class="{'has-error': error.name}">
                                    <label class="col-sm-2 control-label">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Name" required v-model="employee.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.name}">
                                    <label class="col-sm-2 control-label">Type *</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="role" v-model="employee.role">
                                          <option value="1">Customer</option>
                                          <option value="2">Seller</option>
                                        </select>
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" @click="store($event)">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit employees -->
            <div class="modal fade" id="modalEditEmployee">
                <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Edit Employee</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error': error.name}">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" :placeholder="employee.name" required v-model="employee.name">
                                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.name}">
                                        <label class="col-sm-2 control-label">Type </label>
                                        <div class="col-sm-10">
                                          <span v-if="employee.role">{{ employee.role.title }}</span>
                                          <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
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
          employees: [],
          employee: {},
          error: {},
          contact: {},
          list: {
              employees: [],
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
            axios.get('/employees/all')
                .then(response => {
                    this.employees = response.data;
                    this.loading = false;
                });
        },
        add: function() {
            this.employee = {
                name: '',
                role: ''
            };
            this.error = {};
            $('#modalAddEmployee').modal('show');
        },
        store: function(e) {
          var btn = $(e.target).button('loading')
          axios.post('/employees/store', this.employee)
              .then(response => {
                  this.employees.push(response.data);
                  this.employee = {};
                  this.error = {};
                  var btn = $(e.target).button('reset')
                  $('#modalAddEmployee').modal('hide');
              })
              .catch(error => {
                  this.error = error.response.data;
                  var btn = $(e.target).button('reset')
              });
        },
        edit: function (employee, index) {
            this.employee = _.clone(employee);
            this.employee.index = index;
            $('#modalEditEmployee').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/employees/update/'+this.employee.id, this.employee)
            .then(response => {
                this.employees[this.employee.index] = response.data;
                this.employee = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalEditEmployee').modal('hide');
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
                text: "You will not be able to recover this customer!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/employees/destroy/' + self.employee.id)
                .then(response => {
                    self.employees.splice(self.employee.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The customer has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    self.error = {};
                    $('#modalEditEmployee').modal('hide');
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            });
        },
    }
}
</script>
