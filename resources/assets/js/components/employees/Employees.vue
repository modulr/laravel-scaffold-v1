<template>
    <div class="employees">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="material-button bottom right">
                <button href="#" class="btn-primary" @click.prevent="add">
                    <i class="mdi mdi-add mdi-lg"></i>
                </button>
            </div>
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
                                <th>Email</th>
                                <th>Phone</th>
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
                                    {{ item.email }}
                                </td>
                                <td>
                                    {{ item.phone }}
                                </td>
                                <td class="text-right" @click="edit(item, index)">
                                    <a href="#" class="btn btn-link" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <paginate
                      :page-count="pagination.last_page"
                      :margin-pages="2"
                      :page-range="2"
                      :initial-page="pagination.current_page"
                      :container-class="'ui pagination menu'"
                      :page-link-class="'item'"
                      :prev-link-class="'item'"
                      :next-link-class="'item'"
                      :click-handler="clickCallback">
                    </paginate>
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
                                <div class="form-group" :class="{'has-error': error.email}">
                                    <label class="col-sm-2 control-label">Email *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Email" required v-model="employee.email">
                                        <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone}">
                                    <label class="col-sm-2 control-label">Phone *</label>
                                    <div class="col-sm-10">
                                        <!-- <input type="text" class="form-control input-lg" placeholder="Phone" required v-model="employee.phone"> -->
                                        <masked-input mask="\+\52 (111) 111-11-11" placeholder="Phone" class="form-control input-lg"
                                          @input="employee.phone = arguments[1]" />
                                        <span class="help-block" v-if="error.phone">{{error.phone[0]}}</span>
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
                                    <div class="form-group" :class="{'has-error': error.email}">
                                        <label class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" :placeholder="employee.email" required v-model="employee.email">
                                            <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone}">
                                        <label class="col-sm-2 control-label">Phone</label>
                                        <div class="col-sm-10">
                                            <!-- <input type="text" class="form-control input-lg" :placeholder="employee.phone" required v-model="employee.phone"> -->
                                            <masked-input mask="\+\52 (111) 111-11-11" placeholder="Phone" class="form-control input-lg" v-model="employee.phone"
                                              @input="employee.phone = arguments[1]" />
                                            <span class="help-block" v-if="error.phone">{{error.phone[0]}}</span>
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
import Paginate from 'vuejs-paginate';
import MaskedInput from 'vue-masked-input'
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
          },
          pagination : {
            current_page: 0,
            last_page: 1
          }
      }
    },
    components: {
      Spinner,
      Paginate,
      MaskedInput
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
        clickCallback (page) {
          this.pagination.current_page = page
          this.getAll()
        },
        getAll: function() {
          var page = Number(this.pagination.current_page);
          this.loading = true;
          axios.get('/employees/all?page=' + page)
            .then(response => {
                this.employees = response.data.data;
                this.pagination.last_page = response.data.last_page
                this.loading = false
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
          console.log(this.employee)
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
                text: "You will not be able to recover this employee!",
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
                        text: "The employee has been deleted.",
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
