<template>
    <div class="clients">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="material-button top right">
                <button href="#" class="btn-primary" @click.prevent="add">
                    <i class="mdi mdi-add mdi-lg"></i>
                </button>
            </div>
            <!-- No items found -->
            <div class="row" v-if="clients.length == 0">
                <div class="col-md-12">
                    <h3>No clients found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="clients.length != 0">
                <div class="col-md-2">
                    <div class="col-md-12 filters">
                        <h4 class="heading">Filters</h4>
                        <a href="#" class="pull-right" @click.prevent="clearFilters">Clear</a>
                        <div class="row">
                            <div class="card search col-xs-12">
                                <p class="title">
                                    <small>Search</small>
                                </p>
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
                                <p class="title">
                                    <small>Customers</small>
                                </p>
                                <multiselect v-model="sort.customers" track-by="name" label="name" :options="list.customers" placeholder="Select a customer" :multiple="true"></multiselect>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="col-md-12">
                        <div class="list-group">
                            <div class="list-item col-xs-12" v-for="(item, index) in filteredClients">
                                <div class="col-xs-11 details" @click="edit(item, index)">
                                    <p>{{item.name}}
                                        <small class="text-muted">{{item.customer.name}}</small>
                                    </p>
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
                                                <a href="#" @click.prevent="edit(item, index)">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
                                    <label class="col-sm-3 control-label required">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-lg" placeholder="Name" required v-model="client.name">
                                        <span class="help-block" v-if="error['client.name']">{{error['client.name'][0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.email}">
                                    <label class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-lg" placeholder="Email" v-model="client.email">
                                        <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_1}">
                                    <label class="col-sm-3 control-label">Mobile Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-lg" placeholder="Mobile Number" v-model="client.phone_number_1">
                                        <span class="help-block" v-if="error.phone_number_1">{{error.phone_number_1[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_2}">
                                    <label class="col-sm-3 control-label">Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-lg" placeholder="Number" v-model="client.phone_number_2">
                                        <span class="help-block" v-if="error.phone_number_2">{{error.phone_number_2[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.phone_number_3}">
                                    <label class="col-sm-3 control-label">Other</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-lg" placeholder="Other" v-model="client.phone_number_3">
                                        <span class="help-block" v-if="error.phone_number_3">{{error.phone_number_3[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.client}" v-if="list.customers.length !== 0">
                                    <label class="col-sm-3 control-label required">Customer</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" required v-model="client.customer">
                                            <option v-for="option in list.customers" :value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span class="help-block" v-if="error.customer">{{error.customer[0]}}</span>
                                        <span>Or <a href="#" @click.prevent="newCustomer()">create a new customer.</a></span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error['customer.name']}" v-if="addingCustomer || list.customers.length === 0">
                                    <label class="col-sm-3 control-label required">Add Customer</label>
                                    <div class="col-sm-9">
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
                                        <label class="col-sm-3 control-label required">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" :placeholder="client.name" required v-model="client.name">
                                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.email}">
                                        <label class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" placeholder="Email" v-model="client.email">
                                            <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_1}">
                                        <label class="col-sm-3 control-label">Mobile Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" placeholder="Mobile Number" v-model="client.phone_number_1">
                                            <span class="help-block" v-if="error.phone_number_1">{{error.phone_number_1[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_2}">
                                        <label class="col-sm-3 control-label">Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" placeholder="Number" required v-model="client.phone_number_2">
                                            <span class="help-block" v-if="error.phone_number_2">{{error.phone_number_2[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.phone_number_3}">
                                        <label class="col-sm-3 control-label">Other</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" placeholder="Other" v-model="client.phone_number_3">
                                            <span class="help-block" v-if="error.phone_number_3">{{error.phone_number_3[0]}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.client}" v-if="list.customers.length !== 0">
                                        <label class="col-sm-3 control-label">Customer</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" v-model="client.customer">
                                                <option v-for="option in list.customers" :value="option.id">
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
import Multiselect from 'vue-multiselect';
export default {
    data() {
        return {
            loading: false,
            addingCustomer: false,
            clients: [],
            client: {
                customer: {},
            },
            customer: {},
            error: {},
            list: {
                customers: [],
            },
            sort: {
                customers: [],
            },
            search: '',
        }
    },
    computed: {
        filteredClients: function() {
            var filteredArray = this.clients,
                sort_customers = this.sort.customers,
                search = this.search;

            if(sort_customers != '') {
                filteredArray = filteredArray.filter(item => {
                    return _.findKey(sort_customers, (o) => {
                        if(o.id === item.customer.id) {
                            return item;
                        }
                    })
                });
            }

            if(search) {
                search = search.trim().toLowerCase();
                filteredArray = filteredArray.filter((item) => {
                    return item.name.trim().toLowerCase().match(search);
                });
            }

            return filteredArray;
        }
    },
    components: {
        Spinner, Multiselect
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
                if(response.data.customer.name != this.list.customers.name) {
                    this.list.customers.push(response.data.customer);
                }
                this.client = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                this.addingCustomer = false;
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
                this.addingCustomer = false;
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
        newCustomer: function () {
            this.addingCustomer = true;
            this.client.customer = {};
        },
        clearFilters: function () {
            this.sort = {
                customers: [],
            };
            this.search = '';
        }
    }
}
</script>