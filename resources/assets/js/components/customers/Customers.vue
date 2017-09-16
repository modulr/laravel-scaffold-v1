<template>
    <div class="customers">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="#" class="btn btn-primary" @click.prevent="add">
                        <i class="mdi mdi-person-add mdi-lg"></i> Add Customer
                    </a>
                </div>
            </div>
            <br>
            <!-- No items found -->
            <div class="row" v-if="customers.length == 0">
                <div class="col-md-12">
                    <h3>No customers found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="customers.length != 0">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in customers">
                                <td>
                                    {{item.id}}
                                </td>
                                <td>
                                    {{item.name}}<br>
                                </td>
                                <td class="text-right" @click="edit(item, index)">
                                    <a href="#" class="btn btn-link" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Create customer -->
            <div class="modal fade" id="modalAdd">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add customer</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal">
                                <div class="form-group" :class="{'has-error': error.name}">
                                    <label class="col-sm-2 control-label">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Name" required v-model="customer.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
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
            <!-- Edit customer -->
            <div class="modal fade" id="modalEdit">
                <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Edit customer</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error': error.name}">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" :placeholder="customer.name" required v-model="customer.name">
                                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="list.contacts.length != 0">
                                        <label class="col-sm-2 control-tabel">Contacts</label>
                                        <div class="col-sm-10">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone number</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in list.contacts">
                                                        <td>
                                                            {{item.id}}
                                                        </td>
                                                        <td>
                                                            {{item.name}}
                                                        </td>
                                                        <td>
                                                            {{item.email}}
                                                        </td>
                                                        <td>
                                                            {{item.telephone}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
          customers: [],
          customer: {},
          error: {},
          list: {
              contacts: []
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
            axios.get('/customers/all')
                .then(response => {
                    this.customers = response.data;
                    this.loading = false;
                });
        },
        add: function() {
            this.customer = {
                name: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function(e) {
            var btn = $(e.target).button('loading')
            axios.post('/customers/store', this.customer)
                .then(response => {
                    this.customers.push(response.data);
                    this.customer = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
        },
        edit: function (customer, index) {
            this.customer = _.clone(customer);
            this.customer.index = index;
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/customers/update/'+this.customer.id, this.customer)
            .then(response => {
                this.customers[this.customer.index] = response.data;
                this.customer = {};
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
                text: "You will not be able to recover this customer!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/customers/destroy/' + self.customer.id)
                .then(response => {
                    self.customers.splice(self.customer.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The customer has been deleted.",
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
