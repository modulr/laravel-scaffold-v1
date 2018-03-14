<template>
    <div class="roles">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-6 links">
                        <h5>Roles</h5>
                    </div>
                    <div class="col-xs-6 col-sm-3 controls">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="mdi mdi-search mdi-lg"></i></span>
                            <input type="text" class="form-control" placeholder="Search" v-model="search">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3 text-right controls">
                        <a href="#" class="btn btn-success"
                           v-if="user.hasPermission['create-roles']"
                           @click.prevent="newRole">
                            <i class="mdi mdi-group-add mdi-lg"></i> New Role
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Roles -->
            <div class="row">
                <div class="col-md-12">
                    <paginate
                        v-if="roles.last_page>1"
                        :page-count="roles.last_page"
                        :click-handler="changePage"
                        :prev-text="'Prev'"
                        :next-text="'Next'"
                        :container-class="'pagination pull-right'">
                    </paginate>
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
                                <tr v-for="(role, index) in roles.data">
                                    <td @click="editRole(role, index)">
                                        {{role.id}}
                                    </td>
                                    <td @click="editRole(role, index)">
                                        <h4 class="media-heading">{{role.display_name}}</h4>
                                    </td>
                                    <td @click="editRole(role, index)">
                                        {{role.description}}
                                    </td>
                                    <td @click="editRole(role, index)">
                                        {{role.users.length}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal New Role -->
        <div class="modal right md" id="modalNewRole">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="roleNew.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="roleNew.description"></textarea>
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
                                    <tr v-for="permission in roleNew.permissions">
                                        <td>{{permission.display_name}}</td>
                                        <td class="text-right">
                                            <input type="checkbox" v-model="permission.allow">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-left"
                                @click="storeRole"
                                v-if="user.hasPermission['create-roles']">Create</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Role -->
        <div class="modal right md" id="modalEditRole">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="roleEdit.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="roleEdit.description"></textarea>
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
                                            <tr v-for="permission in roleEdit.checkPermissions">
                                                <td>{{permission.display_name}}</td>
                                                <td class="text-right">
                                                    <input type="checkbox" v-model="permission.allow">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="users">
                                    <div class="media" v-for="item in roleEdit.users">
                                        <div class="media-left">
                                            <a :href="`/profile/${item.id}`">
                                                <img class="avatar-xs" :src="item.avatar_url">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <a :href="`/profile/${item.id}`">
                                                {{item.name}}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{item.email}}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left"
                                @click="updateRole"
                                v-if="user.hasPermission['update-roles']">Save</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default"
                                @click="destroyRole"
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
    import Paginate from 'vuejs-paginate';

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                roles: {
                    last_page: 0
                },
                roleNew: {},
                roleEdit: {},
                lists: {
                    permissions: [],
                }
            }
        },
        mounted() {
            this.getRoles();
            this.getLists();
        },
        components: {
            Paginate,
        },
        watch: {
            search () {
                this.getRoles();
            }
        },
        methods: {
            getRoles (page) {
                if (!page) {
                    axios.get(`/api/roles/filter${this.search ? '?search=' + this.search: ''}`)
                    .then(response => {
                        this.roles = response.data;
                    });
                } else {
                    axios.get(`/api/roles/filter?page=${page}${this.search ? '&search=' + this.search: ''}`)
                    .then(response => {
                        this.roles = response.data;
                    });
                }
            },
            getLists () {
                axios.get('/api/permissions/all')
                .then(response => {
                    this.lists.permissions = response.data;
                });
            },
            changePage (page) {
                this.getRoles(page);
            },
            newRole () {
                this.roleNew = {};
                this.roleNew.permissions = _.clone(this.lists.permissions);
                this.roleNew.permissions.forEach(function(v){
                    if (v.allow)
                        v.allow = false;
                });
                this.error = {};
                $('#modalNewRole').modal('show');
            },
            storeRole (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/roles/store', this.roleNew)
                .then(response => {
                    this.roles.data.push(response.data);
                    this.roleNew = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNewRole').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editRole (role, index) {
                axios.get('/api/roles/'+role.id)
                .then(response => {
                    this.roleEdit = response.data;
                    this.roleEdit.index = index;
                    $('#modalEditRole').modal('show');
                });
            },
            updateRole (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/roles/update/'+this.roleEdit.id, this.roleEdit)
                .then(response => {
                    this.roles.data[this.roleEdit.index] = response.data;
                    this.roleEdit = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEditRole').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyRole () {
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
                    axios.delete('/api/roles/destroy/' + self.roleEdit.id)
                    .then(response => {
                        self.roles.data.splice(self.roleEdit.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The role has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        $('#modalEditRole').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            }

        }
    }
</script>
