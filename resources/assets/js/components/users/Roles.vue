<template>
    <div class="roles">
        <!-- Header -->
        <div class="container-fluid header">
            <div class="row">
                <div class="col-xs-6 header-title">
                    <h1>Roles</h1>
                </div>
                <div class="col-xs-6 header-buttons">
                    <a href="#" class="btn btn-success"
                       v-if="user.hasPermission['create-roles']"
                       @click.prevent="newItem">
                        <i class="mdi mdi-group-add mdi-lg"></i> New Role
                    </a>
                </div>
                <div class="col-xs-12">
                    <ul class="nav nav-tabs header-tabs">
                        <li><a href="/users">Users</a></li>
                        <li class="active"><a href="">Roles</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <!-- Actionbar -->
                    <div class="actionbar">
                        <div class="row">
                            <div class="col-xs-6 col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- List Items -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in filteredListItems">
                                                    <td @click="editItem(item, index)">
                                                        {{item.id}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        {{item.display_name}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        {{item.description}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        {{item.users.length}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal New Item -->
        <div class="modal right fade" tabindex="-1" id="modalNewItem">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">New Role</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="currentItem.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="currentItem.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                            <br>
                            <ul class="nav nav-tabs border-bottom">
                                <li class="active"><a>Permissions</a></li>
                            </ul>
                            <br>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th class="text-right">Allow</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in currentItem.permissions">
                                        <td>{{item.display_name}}</td>
                                        <td class="text-right">
                                            <input type="checkbox" v-model="item.allow">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-left"
                                @click="storeItem"
                                v-if="user.hasPermission['create-roles']">Create</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Item -->
        <div class="modal right fade" tabindex="-1" data-backdrop="false" id="modalEditItem">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Edit Role</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="currentItem.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="currentItem.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                            <br>
                            <ul class="nav nav-tabs border-bottom" role="tablist">
                                <li role="presentation" class="active"><a href="#permissions" aria-controls="permissions" role="tab" data-toggle="tab">Permissions</a></li>
                                <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">Users</a></li>
                            </ul>
                            <br>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="permissions">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Permission</th>
                                                <th class="text-right">Allow</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in currentItem.checkPermissions">
                                                <td>{{item.display_name}}</td>
                                                <td class="text-right">
                                                    <input type="checkbox" v-model="item.allow">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="users">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in currentItem.users">
                                                <td>{{item.id}}</td>
                                                <td><img class="avatar-sm" :src="item.avatar_url"></td>
                                                <td>{{item.name}}</td>
                                                <td>{{item.email}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left"
                                @click="updateItem"
                                v-if="user.hasPermission['update-roles']">Save</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default"
                                @click="destroyItem"
                                v-if="user.hasPermission['delete-roles']">
                            <i class="fa fa-trash-o fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert';

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                currentItem: {},
                listPermissions: [],
                listItems: [],
            }
        },
        mounted() {
            this.getListItems();
            this.getAllPermissions();
        },
        computed: {
            filteredListItems: function () {
                var filteredArray = this.listItems,
                    search = this.search;

                if(!search){
                    return filteredArray;
                }

                search = search.trim().toLowerCase();

                filteredArray = filteredArray.filter(function(item){
                    return Object.keys(item).some(function (key) {
                        return String(item[key]).toLowerCase().indexOf(search) !== -1
                    })
                })

                return filteredArray;;
            }
        },
        methods: {
            getListItems: function () {
                axios.get('/roles/all')
                .then(response => {
                    this.listItems = response.data;
                });
            },
            getAllPermissions: function () {
                axios.get('/permissions/all')
                .then(response => {
                    this.listPermissions = response.data;
                });
            },
            newItem: function () {
                this.currentItem = {};
                this.currentItem.permissions = _.clone(this.listPermissions);
                this.currentItem.permissions.forEach(function(v){
                    if (v.allow)
                        v.allow = false;
                });
                this.error = {};
                $('#modalNewItem').modal('show');
            },
            storeItem: function (e) {
                var btn = $(e.target).button('loading')
                axios.post('/roles/store', this.currentItem)
                .then(response => {
                    this.listItems.push(response.data);
                    this.currentItem = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNewItem').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editItem: function (item, index) {
                axios.get('/roles/'+item.id)
                .then(response => {
                    this.currentItem = response.data;
                    this.currentItem.index = index;
                    $('#modalEditItem').modal('show');
                });
            },
            updateItem: function (e) {
                var btn = $(e.target).button('loading')
                axios.put('/roles/update/'+this.currentItem.id, this.currentItem)
                .then(response => {
                    this.listItems[this.currentItem.index] = response.data;
                    this.currentItem = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEditItem').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyItem: function () {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this role and will be remove all users use it!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/roles/destroy/' + self.currentItem.id)
                    .then(response => {
                        self.listItems.splice(self.currentItem.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The role has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        $('#modalEditItem').modal('hide');
                    })
                    .catch(error => {
                        swal({
                            title: "Cannot deleted!",
                            text: error.response.data,
                            type: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        self.error = error.response.data;
                    });
                });
            }

        }
    }
</script>
