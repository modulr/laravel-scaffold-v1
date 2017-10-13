<template>
    <div class="clients">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="#" class="btn btn-primary" @click.prevent="add()">
                        <i class="mdi mdi-person-add mdi-lg"></i> Add Client
                    </a>
                </div>
            </div>
            <br>
            <!-- No items found -->
            <div class="row" v-if="clients.length == 0">
                <div class="col-md-12">
                    <h3>No clients found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="clients.length != 0">
                <div class="col-md-12">
                    <div class="list-group">
                        <div class="filters">
                            <div class="sort col-xs-2">
                                <select class="form-control" required v-model="sort">
                                    <option value="" disabled selected>Sort By</option>
                                    <option v-for="option in list.customers" :value="option.id">
                                        {{ option.name }}
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
                        <div class="list-item col-xs-12" v-for="(item, index) in filteredClients">
                            <div class="col-xs-11 clickable" @click="edit(item, index)">
                                <p>{{item.name}} <small class="text-muted">{{item.customer.name}}</small></p>
                                <small class="text-muted">
                                    <span v-if="item.email">{{item.email}}</span>
                                    <span v-if="item.phone_number_1">- {{item.phone_number_1}}</span>
                                    <span v-if="item.phone_number_2">- {{item.phone_number_2}}</span>
                                    <span v-if="item.phone_number_3">- {{item.phone_number_3}}</span>
                                </small>
                            </div>
                            <div class="actions col-xs-1">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="mdi mdi-more-vert mdi-2x"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="#" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                              Edit
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create client -->
            <div class="modal right fade" id="modalAdd">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add client</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal">
                                <div class="form-group" :class="{'has-error': error['client.name']}">
                                    <label class="col-sm-2 control-label">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Name" required v-model="client.name">
                                        <span class="help-block" v-if="error['client.name']">{{error['client.name'][0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.email}">
                                    <label class="col-sm-2 control-label">Email *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Email" required v-model="client.email">
                                        <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_1}">
                                    <label class="col-sm-2 control-label">Phone Number 1 *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Phone Number 1" required v-model="client.phone_number_1">
                                        <span class="help-block" v-if="error.phone_number_1">{{error.phone_number_1[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_2}">
                                    <label class="col-sm-2 control-label">Phone Number 2</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Phone Number 2" required v-model="client.phone_number_2">
                                        <span class="help-block" v-if="error.phone_number_2">{{error.phone_number_2[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_3}">
                                    <label class="col-sm-2 control-label">Phone Number 3</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Phone Number 3" required v-model="client.phone_number_3">
                                        <span class="help-block" v-if="error.phone_number_3">{{error.phone_number_3[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.client}" v-if="list.customers.length !== 0">
                                    <label class="col-sm-2 control-label">Customer *</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" required v-model="client.customer">
                                            <option v-for="option in list.customers" :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span class="help-block" v-if="error.customer">{{error.customer[0]}}</span>
                                        <span>Or <a href="#" @click.prevent="newCustomer = true">create a new customer.</a></span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error['customer.name']}" v-if="newCustomer || list.customers.length === 0">
                                    <label class="col-sm-2 control-label">Add Customer *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-lg" placeholder="Customer Name" v-model="customer.name">
                                        <span class="help-block" v-if="error['customer.name']">{{error['customer.name'][0]}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" @click="store($event)">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit client -->
            <div class="modal right fade" id="modalEdit">
                <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Edit client</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error': error.name}">
                                        <label class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" :placeholder="client.name" required v-model="client.name">
                                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.email}">
                                        <label class="col-sm-2 control-label">Email *</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" placeholder="Email" required v-model="client.email">
                                            <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_1}">
                                        <label class="col-sm-2 control-label">Phone Number 1 *</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" placeholder="Phone Number 1" required v-model="client.phone_number_1">
                                            <span class="help-block" v-if="error.phone_number_1">{{error.phone_number_1[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_2}">
                                        <label class="col-sm-2 control-label">Phone Number 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" placeholder="Phone Number 2" required v-model="client.phone_number_2">
                                            <span class="help-block" v-if="error.phone_number_2">{{error.phone_number_2[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_3}">
                                        <label class="col-sm-2 control-label">Phone Number 3</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control input-lg" placeholder="Phone Number 3" required v-model="client.phone_number_3">
                                            <span class="help-block" v-if="error.phone_number_3">{{error.phone_number_3[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.client}" v-if="list.customers.length !== 0">
                                        <label class="col-sm-2 control-label">Customer *</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" required v-model="client.customer.name">
                                                <option v-for="option in list.customers" :value="client.customer.name">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                            <span class="help-block" v-if="error.customer">{{error.customer[0]}}</span>
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
          newCustomer: false,
          clients: [],
          client: {
              customer: {},
          },
          customer: {},
          error: {},
          list: {
              customers: [],
          },
          search: '',
          sort: '',
      }
    },
    computed: {
        filteredClients: function() {
            var filteredArray = this.clients,
                sort = this.sort,
                search = this.search;

            if(sort) {
                filteredArray = filteredArray.filter(function (item) {
                    if (item.customer_id === sort) {
                        return item;
                    }
                });
            }

            if(search) {
                search = search.trim().toLowerCase();

                filteredArray = filteredArray.filter(function(item){
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
            return moment(date).format('LLL');
        }
    },
    methods: {
        getAll: function() {
            this.loading = true;
            axios.get('/clients/all')
                .then(response => {
                    this.clients = response.data;
                    this.loading = false;
                });
            axios.get('/customers/all')
                .then(response => {
                    this.list.customers = response.data;
            });
        },
        add: function() {
            this.client = {
                name: '',
                email: '',
                phone_number_1: '',
                phone_number_2: '',
                phone_number_3: '',
                customer: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function(e) {
        var btn = $(e.target).button('loading')
        axios.post('/clients/store', {'client': this.client, 'customer': this.customer})
            .then(response => {
                this.clients.push(response.data);
                this.client = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalAdd').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        edit: function (client, index) {
            this.client = _.clone(client);
            this.client.index = index;
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/clients/update/'+this.client.id, this.client)
            .then(response => {
                this.clients[this.client.index] = response.data;
                this.client = {};
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
                text: "You will not be able to recover this client!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/clients/destroy/' + self.client.id)
                .then(response => {
                    self.clients.splice(self.client.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The client has been deleted.",
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
