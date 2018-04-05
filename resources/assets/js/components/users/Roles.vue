<template>
    <div class="roles">
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
                        <h5>Roles</h5>
                    </div>
                    <div class="col-xs-6 text-right controls">
                        <a href="#" class="btn btn-link"
                            @click="toggleSearchBtn(true)">
                            <i class="mdi mdi-search mdi-lg"></i>
                        </a>
                        <a href="#" class="btn btn-success"
                           v-if="user.hasPermission['create-roles']"
                           @click.prevent="newRole">
                            <i class="mdi mdi-group-add mdi-lg"></i>
                            <span class="hidden-xs">New Role</span>
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
                    <div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="hidden-xs">ID</th>
                                    <th>Role</th>
                                    <th>Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(role, index) in roles.data">
                                    <td class="hidden-xs" @click="editRole(role, index)">
                                        {{role.id}}
                                    </td>
                                    <td @click="editRole(role, index)">
                                        <h4 class="media-heading">{{role.display_name}}</h4>
                                        <small class="text-muted">{{role.description}}</small>
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
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success"
                                    @click="storeRole"
                                    v-if="user.hasPermission['create-roles']">Create</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="roleNew.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="2" v-model="roleNew.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                            <br>
                            <ul class="nav nav-tabs border-bottom">
                                <li class="active"><a>Permissions</a></li>
                            </ul>
                            <br>
                            <div class="media roles-permissions-list" v-for="role in roleNew.modules">
                                <div class="media-body">
                                    <p class="roles-permissions-module">{{role.display_name}}</p>
                                    <div class="text-right">
                                        <small class="text-muted roles-permissions-permission" v-for="permission in role.permissions">
                                            <input type="checkbox" v-model="permission.allow">{{permission.display_name}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Role -->
        <div class="modal right md" id="modalEditRole">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-default"
                                    @click="destroyRole"
                                    v-if="user.hasPermission['delete-roles']">
                                <i class="fa fa-trash-o fa-lg"></i>
                            </button>
                            <button type="button" class="btn btn-primary"
                                    @click="updateRole"
                                    v-if="user.hasPermission['update-roles']">Save</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.display_name}">
                                <label>Name *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="roleEdit.display_name">
                                <span class="help-block" v-if="error.display_name">{{error.display_name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="2" v-model="roleEdit.description"></textarea>
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
                                    <div class="media roles-permissions-list" v-for="role in roleEdit.modules">
                                        <div class="media-body">
                                            <p class="roles-permissions-module">{{role.display_name}}</p>
                                            <div class="text-right">
                                                <small class="text-muted roles-permissions-permission" v-for="permission in role.permissions">
                                                    <input type="checkbox" v-model="permission.allow">{{permission.display_name}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="users">
                                    <div class="media roles-users-list" v-for="item in roleEdit.users">
                                        <div class="media-left">
                                            <a :href="`/profile/${item.id}`">
                                                <img class="avatar-xs" :src="item.avatar_url">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <a class="roles-users-name" :href="`/profile/${item.id}`">
                                                {{item.name}}
                                            </a>
                                            <small class="text-muted roles-users-email">
                                                {{item.email}}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginate from 'vuejs-paginate'
    import swal from 'sweetalert'

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                active: false,
                roles: {
                    last_page: 0
                },
                roleNew: {},
                roleEdit: {},
                lists: {
                    modules: []
                }
            }
        },
        mounted() {
            this.getRoles()
            this.getLists()
        },
        components: {
            Paginate
        },
        watch: {
            search () {
                this.getRoles()
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
            getRoles (page) {
                if (!page) {
                    axios.get(`/api/roles/filter${this.search ? '?search=' + this.search: ''}`)
                    .then(response => {
                        this.roles = response.data
                    })
                } else {
                    axios.get(`/api/roles/filter?page=${page}${this.search ? '&search=' + this.search: ''}`)
                    .then(response => {
                        this.roles = response.data
                    })
                }
            },
            getLists () {
                axios.get('/api/modules/permissions')
                .then(response => {
                    this.lists.modules = response.data
                });
            },
            changePage (page) {
                this.getRoles(page)
            },
            newRole () {
                this.roleNew = {}
                this.roleNew.modules = _.clone(this.lists.modules);
                this.roleNew.modules.forEach(function(val) {
                    val.permissions.forEach(function(v) {
                        v.allow = false
                    })
                })
                this.error = {}
                $('#modalNewRole').modal('show')
            },
            storeRole (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/roles/store', this.roleNew)
                .then(response => {
                    this.roles.data.push(response.data);
                    this.roleNew = {}
                    this.error = {}
                    btn.button('reset')
                    $('#modalNewRole').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data
                    btn.button('reset')
                })
            },
            editRole (role, index) {
                axios.get('/api/roles/'+role.id)
                .then(response => {
                    this.roleEdit = response.data
                    this.roleEdit.index = index
                    $('#modalEditRole').modal('show')
                })
            },
            updateRole (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/roles/update/'+this.roleEdit.id, this.roleEdit)
                .then(response => {
                    this.roles.data[this.roleEdit.index] = response.data
                    this.roleEdit = {}
                    this.error = {}
                    btn.button('reset')
                    $('#modalEditRole').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data
                    btn.button('reset')
                })
            },
            destroyRole () {
                var self = this
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this role and will be remove all users use it!",
                    icon: "warning",
                    buttons: true
                })
                .then((value) => {
                    if (value) {
                        axios.delete('/api/roles/destroy/' + self.roleEdit.id)
                        .then(response => {
                            self.roles.data.splice(self.roleEdit.index, 1)
                            swal({
                                title: "Deleted!",
                                text: "The role has been deleted.",
                                icon: "success",
                                buttons: false,
                                timer: 1000
                            })
                            self.error = {}
                            $('#modalEditRole').modal('hide')
                        })
                        .catch(error => {
                            self.error = error.response.data
                        })
                    }
                })
            }

        }
    }
</script>
