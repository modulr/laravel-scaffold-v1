<template>
    <div>
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="form-group input-lg search" :class="{'active': active}">
                    <input type="text" class="form-control" ref="search" placeholder="Search"
                        v-model="search">
                    <button type="button" class="close" aria-label="Close"
                        @click="toggleSearchBtn(false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-xs-6 links">
                        <h5>Users</h5>
                    </div>
                    <div class="col-xs-6 text-right controls">
                        <a href="#" class="btn btn-link"
                            @click="toggleSearchBtn(true)">
                            <i class="mdi mdi-search mdi-lg"></i>
                        </a>
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-users']"
                            @click.prevent="newUser">
                            <i class="mdi mdi-person-add mdi-lg"></i>
                            <span class="hidden-xs">New User</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Users -->
            <div class="row users-list">
                <div class="col-md-12">
                    <div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="hidden-xs">ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th class="hidden-xs text-center">Active</th>
                                    <th class="hidden-xs">created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in users.data">
                                    <td class="hidden-xs" @click="editUser(item, index)">
                                        {{item.id}}
                                    </td>
                                    <td @click="editUser(item, index)">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="avatar-xs" :src="item.avatar_url">
                                                <span class="label label-active" :class="{'label-primary': item.active, 'label-default': !item.active}"></span>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{item.name}}</h4>
                                                <small class="text-muted">{{item.email}}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td @click="editUser(item, index)">
                                        <span v-if="item.roles.length">{{item.roles[0].display_name}}</span>
                                        <span v-else>---</span>
                                    </td>
                                    <td class="hidden-xs text-center" @click="editUser(item, index)">
                                        <i class="mdi mdi-2x" :class="{'mdi-mood text-primary': item.active, 'mdi-mood-bad': !item.active}"></i>
                                    </td>
                                    <td class="hidden-xs" @click="editUser(item, index)">
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
        <!-- Modal New User -->
        <div class="modal right sm" id="modalNewUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    v-if="user.hasPermission['create-users']"
                                    @click="storeUser">Create</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="switch">
                            <label class="switch-label">
                                <input type="checkbox" v-model="userNew.active">
                                <span class="slider round"></span>
                            </label>
                            <span class="switch-text">Active</span>
                        </div>
                        <div class="modal-avatar">
                            <vue-clip class="vue-clip-btn"
                                      :options="optionsAvatarTemp"
                                      :on-complete="completeAvatarTemp"
                                      v-if="user.hasPermission['create-users']">
                                <template slot="clip-uploader-action">
                                    <div class="">
                                        <div class="dz-message btn-avatar">
                                            <img class="avatar-md" :src="userNew.avatar_url" v-if="userNew.avatar_url">
                                            <i class="mdi mdi-account-circle mdi-avatar-md" v-else></i>
                                            <span class="fa-stack fa-lg text-primary">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-camera fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </vue-clip>
                        </div>
                        <form>
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" placeholder="John Doe" required
                                       v-model="userNew.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="doe@modulr.io" required
                                       v-model="userNew.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
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
                </div>
            </div>
        </div>
        <!-- Modal Edit User -->
        <div class="modal right sm" id="modalEditUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-default"
                                    v-if="user.hasPermission['delete-users']"
                                    @click="destroyUser">
                                <i class="fa fa-trash-o fa-lg"></i>
                            </button>
                            <button type="button" class="btn btn-primary"
                                    v-if="user.hasPermission['update-users']"
                                    @click="updateUser">Save</button>

                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="switch">
                            <label class="switch-label">
                                <input type="checkbox" v-model="userEdit.active">
                                <span class="slider round"></span>
                            </label>
                            <span class="switch-text">Active</span>
                            <a class="pull-right" :href="`/profile/${userEdit.id}/edit`">
                                <i class="fa fa-pencil" aria-hidden="true"></i> Edit Profile
                            </a>
                        </div>
                        <div class="modal-avatar">
                            <vue-clip class="vue-clip-btn"
                                      :options="optionsAvatar"
                                      :on-sending="sendingAvatar"
                                      :on-complete="completeAvatar"
                                      v-if="user.hasPermission['update-users']">
                                <template slot="clip-uploader-action">
                                    <div class="">
                                        <div class="dz-message btn-avatar">
                                            <img class="avatar-md" :src="userEdit.avatar_url">
                                            <span class="fa-stack fa-lg text-primary">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-camera fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </vue-clip>
                        </div>
                        <form>
                            <div class="form-group">
                                <p class="form-control-static">ID: <strong>{{userEdit.id}}</strong></p>
                            </div>
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" placeholder="Name" required
                                       v-model="userEdit.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.email}">
                                <label>Email *</label>
                                <input type="email" class="form-control" placeholder="Email" required
                                       v-model="userEdit.email">
                                <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                            </div>
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
                            <div class="form-group" :class="{'has-error': error.password}">
                                <label>Change Password</label>
                                <input type="password" class="form-control"
                                       v-model="userEdit.password">
                                <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-link btn-sm" type="button" @click="generatePassword">
                                    Generate password
                                </button>
                            </div>
                            <fieldset class="separator">
                                <legend>Activity</legend>
                                <p class="text-muted"><small>Created at </small>{{userEdit.created_at | moment('lll')}}</p>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueClip from 'vue-clip';
    import swal from 'sweetalert';
    import Paginate from 'vuejs-paginate';

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                active: false,
                optionsAvatarTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/users/upload/avatar/temp',
                    paramName: 'file',
                    parallelUploads: 1,
                    maxFilesize: {
                        limit: 10,
                        message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                    },
                    maxFiles: {
                        limit: 10,
                        message: 'You can only upload a max of 10 files'
                    },
                    acceptedFiles: {
                        extensions: ['image/*'],
                        message: 'You are uploading an invalid file'
                    },
                },
                optionsAvatar: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/users/upload/avatar',
                    paramName: 'file',
                    parallelUploads: 1,
                    maxFilesize: {
                        limit: 10,
                        message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                    },
                    maxFiles: {
                        limit: 10,
                        message: 'You can only upload a max of 10 files'
                    },
                    acceptedFiles: {
                        extensions: ['image/*'],
                        message: 'You are uploading an invalid file'
                    },
                },
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
            Paginate,
        },
        watch: {
            search () {
                this.getUsers();
            }
        },
        methods: {
            toggleSearchBtn (active) {
                this.search = null
                if (active) {
                    this.active = active
                    var self = this
                    setTimeout(function() {
                        self.$refs.search.focus()
                    }, 100);
                } else {
                    this.active = active
                }
            },
            getUsers (page) {
                if (!page) {
                    axios.get(`/api/users/filter${this.search ? '?search=' + this.search: ''}`)
                    .then(response => {
                        this.users = response.data;
                    });
                } else {
                    axios.get(`/api/users/filter?page=${page}${this.search ? '&search=' + this.search: ''}`)
                    .then(response => {
                        this.users = response.data;
                    });
                }
            },
            getLists () {
                axios.get('/api/roles/all')
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
                    role_id: 1,
                    password: '',
                    active: true,
                };
                this.error = {};
                $('#modalNewUser').modal('show');
            },
            storeUser (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/users/store', this.userNew)
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
                axios.put('/api/users/update/'+this.userEdit.id, this.userEdit)
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
                    axios.delete('/api/users/destroy/' + self.userEdit.id)
                    .then(response => {
                        self.users.data.splice(self.userEdit.index, 1);
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
            completeAvatarTemp (file, status, xhr) {
                if (status == 'success') {
                    this.userNew.avatar_url = file.dataUrl;
                    this.userNew.avatar_path = JSON.parse(xhr.response).path;
                }
            },
            sendingAvatar (file, xhr, formData) {
                formData.append('id', this.userEdit.id);
            },
            completeAvatar (file, status, xhr) {
                if (status == 'success') {
                    this.userEdit.avatar_url = JSON.parse(xhr.response).avatar_url;
                    this.users.data[this.userEdit.index].avatar_url = JSON.parse(xhr.response).avatar_url;
                    if (Laravel.user.id == this.userEdit.id) {
                        Laravel.user.avatar_url = JSON.parse(xhr.response).avatar_url;
                    }
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
