<template lang="html">
    <div>
        <!-- Header -->
        <div class="container-fluid header">
            <div class="row">
                <div class="col-xs-6 header-title">
                    <h1>Users / Groups</h1>
                </div>
                <div class="col-xs-6 header-buttons">
                    <a href="#" class="btn btn-success" @click.prevent="add">
                        <i class="mdi mdi-group-add mdi-lg"></i> New Group
                    </a>
                </div>
                <div class="col-xs-12">
                    <ul class="nav nav-tabs header-tabs">
                        <li><a href="/users">Users</a></li>
                        <li class="active"><a href="">Groups</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Container -->
        <div class="container-fluid groups">
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
                    <!-- List -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Group</th>
                                                    <th>Description</th>
                                                    <th>Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in filteredGroups">
                                                    <td @click="edit(item, index)">
                                                        {{item.id}}
                                                    </td>
                                                    <td @click="edit(item, index)">
                                                        {{item.title}}
                                                    </td>
                                                    <td @click="edit(item, index)">
                                                        {{item.description}}
                                                    </td>
                                                    <td @click="edit(item, index)">
                                                        list users used this group
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
        <!-- New group -->
        <div class="modal right fade" id="modalAdd">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">New Group</h4>
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
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" @click="store">Create</button>
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
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-link" @click="destroy()">
                            <i class="fa fa-trash-o fa-lg"></i>
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
