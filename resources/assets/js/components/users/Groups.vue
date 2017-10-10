<template lang="html">
    <div>
        <div class="row toolbar">
            <!-- Tabs -->
            <div class="col-xs-6">
                <ul class="nav nav-tabs">
                    <li><a href="/users">Users</a></li>
                    <li class="active"><a href="">Groups</a></li>
                </ul>
            </div>
            <!-- Actions Buttons -->
            <div class="col-xs-6 text-right">
                <form class="form-inline">
                    <input type="text" class="form-control" placeholder="Search"
                        v-model="search">
                    <a href="#" class="btn btn-success" @click.prevent="add">
                        Add Group
                    </a>
                </form>
            </div>
        </div>
        <!-- List -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Group</th>
                            <th>Users</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filteredGroups">
                            <td @click="edit(item, index)">
                                {{item.id}}
                            </td>
                            <td @click="edit(item, index)">
                                <p>{{item.title}}</p>
                                <small class="text-muted">{{item.description}}</small>
                            </td>
                            <td @click="edit(item, index)">
                                list users used this group
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
        <!-- Add group -->
        <div class="modal right fade" id="modalAdd">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Group</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.title}">
                                <label>Group *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="group.title">
                                <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="group.description"></textarea>
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
        <!-- Edit group -->
        <div class="modal right fade" data-backdrop="false" id="modalEdit">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Edit Group</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" :class="{'has-error': error.title}">
                                <label>Group *</label>
                                <input type="text" class="form-control input-lg" required
                                    v-model="group.title">
                                <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="group.description"></textarea>
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
            groups: [],
            group: {},
            search: '',
            error: {}
        }
    },
    mounted() {
        this.getAll();
    },
    computed: {
        filteredGroups: function () {
            var filteredArray = this.groups,
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
            axios.get('/group/all')
            .then(response => {
                this.groups = response.data;
            });
        },
        add: function () {
            this.group = {
                title: '',
                description: ''
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function (e) {
            var btn = $(e.target).button('loading')
            axios.post('/group/store', this.group)
            .then(response => {
                this.groups.push(response.data);
                this.group = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalAdd').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        edit: function (group, index) {
            this.group = _.clone(group);
            this.group.index = index;
            $('#modalEdit').modal('show');
        },
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/group/update/'+this.group.id, this.group)
            .then(response => {
                this.groups[this.group.index] = response.data;
                this.group = {};
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
                text: "You will not be able to recover this group!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/group/destroy/' + self.group.id)
                .then(response => {
                    self.groups.splice(self.group.index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The group has been deleted.",
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
