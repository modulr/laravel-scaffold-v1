<template lang="html">
    <div>
        <!-- Tabs -->
        <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-tabs">
                    <li><a href="/users">Users</a></li>
                    <li class="active"><a href="#">Roles</a></li>
                </ul>
            </div>
        </div>
        <br>
        <!-- Actions Buttons -->
        <div class="row">
            <div class="col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search"
                        v-model="search">
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <a href="#" class="btn btn-success" @click.prevent="add">
                    <i class="mdi mdi-person-add mdi-lg"></i> Add Role
                </a>
            </div>
        </div>
        <br>
        <!-- List -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role</th>
                            <th>Users</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filteredRoles">
                            <td @click="edit(item, index)">
                                {{item.id}}
                            </td>
                            <td @click="edit(item, index)">
                                <p>{{item.title}}</p>
                                <small class="text-muted">{{item.description}}</small>
                            </td>
                            <td @click="edit(item, index)">
                                list users used this role
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-sm" v-tooltip:top="'Edit'"
                                        @click.prevent="edit(item, index)">
                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Add role -->
        <div class="modal right fade" id="modalAdd">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Role</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.title}">
                                <label>Role *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="role.title">
                                <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="role.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
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
        <!-- Edit role -->
        <div class="modal right fade" data-backdrop="false" id="modalEdit">
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
                            <div class="form-group" :class="{'has-error': error.title}">
                                <label>Role *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="role.title">
                                <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="role.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="destroy()">
                            <i class="mdi mdi-delete mdi-lg"></i> Delete
                        </button>
                        <button type="button" class="btn btn-primary" @click="update">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert';
Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
})
export default {
    data() {
        return {
            roles: [],
            role: {},
            search: '',
            error: {}
        }
    },
    mounted() {
        this.getAll();
    },
    computed: {
        filteredRoles: function () {
            var filteredArray = this.roles,
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
        getAll: function () {
            axios.get('/roles/all')
            .then(response => {
                this.roles = response.data;
            });
        },
        add: function () {
            this.role = {
                title: '',
                description: ''
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function (e) {
            var btn = $(e.target).button('loading')
            axios.post('/roles/store', this.role)
            .then(response => {
                this.roles.push(response.data);
                this.role = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalAdd').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        edit: function (role, index) {
            this.role = _.clone(role);
            this.role.index = index;
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/roles/update/'+this.role.id, this.role)
            .then(response => {
                this.roles[this.role.index] = response.data;
                this.role = {};
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
                text: "You will not be able to recover this role!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/roles/destroy/' + self.role.id)
                .then(response => {
                    self.roles.splice(self.role.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The role has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                    self.error = {};
                    $('#modalEdit').modal('hide');
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
