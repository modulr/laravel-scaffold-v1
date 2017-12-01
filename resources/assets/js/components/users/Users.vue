<template>
    <div class="users">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-6 links">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="">Users</a></li>
                            <li><a href="/roles">Roles</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3 controls">
                        <input type="text" class="form-control" placeholder="Search" v-model="search">
                    </div>
                    <div class="col-sm-3 text-right controls">
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-users']"
                            @click.prevent="newUser">
                            <i class="mdi mdi-person-add mdi-lg"></i> New User
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Users -->
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
                                            <th>Role</th>
                                            <th class="text-center">Active</th>
                                            <th>created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in users.data">
                                            <td @click="editUser(item, index)">
                                                {{item.id}}
                                            </td>
                                            <td @click="editUser(item, index)">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <img class="avatar-xs" :src="item.avatar_url">
                                                    </div>
                                                    <div class="media-body">
                                                        <h4 class="media-heading">{{item.name}}</h4>
                                                        <a class="text-muted" :href="`mailto:${item.email}`">{{item.email}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td @click="editUser(item, index)">
                                                <span v-if="item.roles.length">{{item.roles[0].display_name}}</span>
                                                <span v-else>---</span>
                                            </td>
                                            <td class="text-center" @click="editUser(item, index)">
                                                <i class="mdi mdi-2x" :class="{'mdi-mood text-success': item.active, 'mdi-mood-bad': !item.active}"></i>
                                            </td>
                                            <td @click="editUser(item, index)">
                                                <small>{{item.created_at | moment("LLL")}}</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <paginate
                                v-if="users.last_page>1"
                                :page-count="users.last_page"
                                :click-handler="changePage"
                                :prev-text="'Prev'"
                                :next-text="'Next'"
                                :container-class="'pagination pull-right'">
                            </paginate>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal New User -->
        <div class="modal right sm" id="modalNewUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="John Doe" required
                                   v-model="userNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="switch pull-right">
                                        <input type="checkbox" v-model="userNew.active">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-sm-12 text-center">
                                    <img class="avatar-md" :src="userNew.avatar_url" v-if="userNew.avatar_url">
                                    <i class="mdi mdi-account-circle mdi-avatar-md" v-else></i>
                                    <br>
                                    <vue-core-image-upload
                                        class="btn btn-link"
                                        @imageuploaded="newAvatar"
                                        extensions="png,jpeg,jpg"
                                        :headers="{'X-CSRF-TOKEN': csrfToken}"
                                        :url="'users/upload/avatar/temp'">
                                    </vue-core-image-upload>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group" :class="{'has-error': error.role_id}">
                                <label>Role</label>
                                <select class="form-control text-capitalize"
                                    v-model="userNew.role_id">
                                    <option v-for="option in lists.roles" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.role_id">{{error.role_id[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="doe@modulr.io" required
                                       v-model="userNew.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.password}">
                                <label>Password *</label>
                                <input type="password" class="form-control" required
                                       v-model="userNew.password">
                                <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-link btn-sm" type="button" @click="generatePassword">
                                    Generate password
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-left"
                                v-if="user.hasPermission['create-users']"
                                @click="storeUser">Create</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit User -->
        <div class="modal right sm" id="modalEditUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Name" required
                                   v-model="userEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-xs-6">
                                    <a :href="`/profile/${userEdit.id}/edit`">
                                        <i class="fa fa-external-link" aria-hidden="true"></i> Edit Profile
                                    </a>
                                </div>
                                <div class="col-xs-6">
                                    <label class="switch pull-right">
                                        <input type="checkbox" v-model="userEdit.active">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <img class="avatar-md" :src="userEdit.avatar_url">
                                    <br>
                                    <vue-core-image-upload
                                        class="btn btn-link"
                                        @imageuploaded="editAvatar"
                                        extensions="png,jpeg,jpg"
                                        :headers="{'X-CSRF-TOKEN': csrfToken}"
                                        :url="'users/upload/avatar/'+userEdit.id">
                                    </vue-core-image-upload>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group" :class="{'has-error': error.role_id}">
                                <label>Role</label>
                                <select class="form-control text-capitalize"
                                    v-model="userEdit.role_id">
                                    <option v-for="option in lists.roles" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.role_id">{{error.role_id[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="Email" required
                                       v-model="userEdit.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.password}">
                                <label>Password</label>
                                <input type="password" class="form-control"
                                       v-model="userEdit.password">
                                <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-link btn-sm" type="button" @click="generatePassword">
                                    Generate password
                                </button>
                            </div>
                            <div class="form-group">
                                <label class="pull-right">Created: <em>{{userEdit.created_at | moment("LLL")}}</em></label>
                                <p class="form-control-static">ID: <strong>{{userEdit.id}}</strong></p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left"
                                v-if="user.hasPermission['update-users']"
                                @click="updateUser">Save</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default"
                                v-if="user.hasPermission['delete-users']"
                                @click="destroyUser">
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
    import Paginate from 'vuejs-paginate';

    export default {
        data() {
            return {
                user: Laravel.user,
                csrfToken: Laravel.csrfToken,
                error: {},
                search: '',
                users: {
                    last_page: 0
                },
                userNew: {},
                userEdit: {},
                lists: {
                    roles: [],
                },
            }
        },
        mounted() {
            this.getUsers();
            this.getLists();
        },
        components: {
            VueCoreImageUpload,
            Paginate,
        },
        watch: {
            search () {
                this.getUsers();
            }
        },
        methods: {
            getUsers (page) {
                if (!page) {
                    axios.get(`/users/all${this.search ? '?search=' + this.search: ''}`)
                    .then(response => {
                        this.users = response.data;
                    });
                } else {
                    axios.get(`users/all?page=${page}${this.search ? '&search=' + this.search: ''}`)
                    .then(response => {
                        this.users = response.data;
                    });
                }
            },
            getLists () {
                axios.get('/roles/all')
                .then(response => {
                    this.lists.roles = response.data;
                });
            },
            changePage (page) {
                this.getUsers(page);
            },
            newUser () {
                this.userNew = {
                    avatar_url: null,
                    password: '',
                    active: true,
                };
                this.error = {};
                $('#modalNewUser').modal('show');
            },
            storeUser (e) {
                var btn = $(e.target).button('loading')
                axios.post('/users/store', this.userNew)
                .then(response => {
                    this.users.data.push(response.data);
                    this.userNew = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNewUser').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editUser (item, index) {
                this.userEdit = _.clone(item);
                this.userEdit.index = index;
                this.$set(this.userEdit, 'password', '');
                if (this.userEdit.roles.length) {
                    this.userEdit.role_id = this.userEdit.roles[0].id;
                }
                this.error = {};
                $('#modalEditUser').modal('show');
            },
            updateUser (e) {
                var btn = $(e.target).button('loading')
                axios.put('/users/update/'+this.userEdit.id, this.userEdit)
                .then(response => {
                    this.users.data[this.userEdit.index] = response.data;
                    this.userEdit = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEditUser').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyUser () {
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
                    axios.delete('/users/destroy/' + self.userEdit.id)
                    .then(response => {
                        self.users.splice(self.userEdit.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The user has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.userEdit = {};
                        self.error = {};
                        $('#modalEditUser').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            newAvatar (response) {
                this.userNew.avatar = response.avatar;
                this.userNew.avatar_url = response.avatar_url;
            },
            editAvatar (response) {
                this.userEdit.avatar_url = response.avatar_url;
                this.users.data[this.userEdit.index].avatar_url = response.avatar_url;
                if (Laravel.user.id == this.userEdit.id) {
                    Laravel.user.avatar_url =response.avatar_url;
                }
            },
            generatePassword () {
                var length = 10;
                var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
                this.userNew.password = result;
                this.userEdit.password = result;
            }

        }
    }
</script>
