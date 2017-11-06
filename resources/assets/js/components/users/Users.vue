<template>
    <div class="users">
        <!-- Header -->
        <div class="container-fluid header">
            <div class="row">
                <div class="col-xs-6 header-title">
                    <h1>Users</h1>
                </div>
                <div class="col-xs-6 header-buttons">
                    <a href="#" class="btn btn-success"
                       v-if="user.hasPermission['create-users']"
                       @click.prevent="newItem">
                        <i class="mdi mdi-person-add mdi-lg"></i> New User
                    </a>
                </div>
                <div class="col-xs-12">
                    <ul class="nav nav-tabs header-tabs">
                        <li class="active"><a href="">Users</a></li>
                        <li><a href="/roles">Roles</a></li>
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
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th class="text-center">Active</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in filteredListItems">
                                                    <td @click="editItem(item, index)">
                                                        {{item.id}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        <img class="avatar-sm" :src="item.avatar_url">
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        {{item.name}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        {{item.email}}
                                                    </td>
                                                    <td @click="editItem(item, index)">
                                                        <span v-if="item.roles.length">{{item.roles[0].display_name}}</span>
                                                        <span v-else>---</span>
                                                    </td>
                                                    <td class="text-center" @click="editItem(item, index)">
                                                        <i class="mdi mdi-2x" :class="{'mdi-mood text-success': item.active, 'mdi-mood-bad': !item.active}"></i>
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
                        <h4 class="modal-title">New User</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="checkbox" v-model="currentItem.active"> Active
                                </div>
                                <label class="col-sm-12 text-center">
                                    <img class="avatar-md" :src="currentItem.avatar_url" v-if="currentItem.avatar_url">
                                    <i class="mdi mdi-account-circle mdi-10x" v-else></i>
                                    <br>
                                    <vue-core-image-upload
                                        class="btn btn-link"
                                        @imageuploaded="newAvatar"
                                        extensions="png,jpeg,jpg"
                                        :headers="{'X-CSRF-TOKEN': csrfToken}"
                                        :url="'users/upload/avatar/temp'">
                                    </vue-core-image-upload>
                                </label>
                            </div>
                            <hr>
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" placeholder="John Doe" required
                                       v-model="currentItem.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="doe@modulr.io" required
                                       v-model="currentItem.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.password}">
                                <label>Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" required
                                           v-model="currentItem.password">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" @click="generatePassword">
                                            Generate password
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.role_id}">
                                <label>Role</label>
                                <select class="form-control text-capitalize"
                                    v-model="currentItem.role_id">
                                    <option v-for="option in lists.roles" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.role_id">{{error.role_id[0]}}</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-left"
                                v-if="user.hasPermission['create-users']"
                                @click="storeItem">Create</button>
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
                        <h4 class="modal-title">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="checkbox" v-model="currentItem.active"> Active
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a :href="'/profile/'+currentItem.id">
                                        View Profile
                                    </a>
                                </div>
                                <label class="col-xs-12 text-center">
                                    <img class="avatar-md" :src="currentItem.avatar_url">
                                    <br>
                                    <vue-core-image-upload
                                        class="btn btn-link"
                                        @imageuploaded="editAvatar"
                                        extensions="png,jpeg,jpg"
                                        :headers="{'X-CSRF-TOKEN': csrfToken}"
                                        :url="'users/upload/avatar/'+currentItem.id">
                                    </vue-core-image-upload>
                                </label>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-1">
                                    ID:
                                </div>
                                <div class="col-xs-3">
                                    <strong>{{currentItem.id}}</strong>
                                </div>
                                <div class="col-xs-8 text-right">
                                    <small class="text-mute">Created: </small>
                                    <em>{{currentItem.created_at | moment("LLL")}}</em>
                                </div>
                            </div>
                            <br>
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" placeholder="Name" required
                                       v-model="currentItem.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="Email" required
                                       v-model="currentItem.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.password}">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           v-model="currentItem.password">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" @click="generatePassword">
                                            Generate password
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.role_id}">
                                <label>Role</label>
                                <select class="form-control text-capitalize"
                                    v-model="currentItem.role_id">
                                    <option v-for="option in lists.roles" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.role_id">{{error.role_id[0]}}</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left"
                                v-if="user.hasPermission['update-users']"
                                @click="updateItem">Save</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default"
                                v-if="user.hasPermission['delete-users']"
                                @click="destroyItem">
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
    import VueCoreImageUpload from 'vue-core-image-upload';

    export default {
        data() {
            return {
                user: Laravel.user,
                csrfToken: Laravel.csrfToken,
                error: {},
                search: '',
                listItems: [],
                currentItem: {},
                lists: {
                    roles: []
                },
            }
        },
        mounted() {
            this.getListItems();
            this.getLists();
        },
        components: {
            'vue-core-image-upload': VueCoreImageUpload
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
                axios.get('/users/all')
                .then(response => {
                    this.listItems = response.data;
                });
            },
            getLists: function () {
                axios.get('/roles/all')
                .then(response => {
                    this.lists.roles = response.data;
                });
            },
            newItem: function () {
                this.currentItem = {
                    avatar_url: null,
                    password: '',
                    active: true,
                };
                this.error = {};
                $('#modalNewItem').modal('show');
            },
            storeItem: function (e) {
                var btn = $(e.target).button('loading')
                axios.post('/users/store', this.currentItem)
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
                this.currentItem = _.clone(item);
                this.currentItem.index = index;
                this.$set(this.currentItem, 'password', '');
                if (this.currentItem.roles.length) {
                    this.currentItem.role_id = this.currentItem.roles[0].id;
                }
                this.error = {};
                $('#modalEditItem').modal('show');
            },
            updateItem: function (e) {
                var btn = $(e.target).button('loading')
                axios.put('/users/update/'+this.currentItem.id, this.currentItem)
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
                    text: "You will not be able to recover this user!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/users/destroy/' + self.currentItem.id)
                    .then(response => {
                        self.listItems.splice(self.currentItem.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The user has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.currentItem = {};
                        this.error = {};
                        $('#modalEditItem').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            newAvatar(response) {
                this.currentItem.avatar = response.avatar;
                this.currentItem.avatar_url = response.avatar_url;
            },
            editAvatar(response) {
                this.currentItem.avatar_url = response.avatar_url;
                this.listItems[this.currentItem.index].avatar_url = response.avatar_url;
                if (Laravel.user.id == this.currentItem.id) {
                    Laravel.user.avatar_url =response.avatar_url;
                }
            },
            generatePassword: function () {
                var length = 10;
                var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
                this.currentItem.password = result;
            }

        }
    }
</script>
