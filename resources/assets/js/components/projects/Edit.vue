<template>
    <div class="modal right fade" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit Project</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label class="col-sm-3 control-label required">Name</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.name}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="project.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>
                            <div class="form-group" :class="{'has-error': error.start_date}">
                            <label class="col-sm-3 control-label required">Start Date</label>
                            <div class="col-sm-9">
                                <div class="col-sm-9">
                                <input type="date" class="form-control" v-bind:placeholder="project.start_date" required v-model="project.start_date">
                                <span class="help-block" v-if="error.start_date">{{error.start_date[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.end_date}">
                            <label class="col-sm-3 control-label required">Deadline</label>
                            <div class="col-sm-9">
                                <div class="col-sm-9">
                                <input type="date" class="form-control" v-bind:placeholder="project.end_date" required v-model="project.end_date">
                                <span class="help-block" v-if="error.end_date">{{error.end_date[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Registered</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.registered_date | date}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.priority}">
                            <label class="col-sm-3 control-label required">Priority</label>
                            <div class="col-sm-9" v-if="list.priorities && project.priority">
                                <multiselect v-model="project.priority" label="name" :options="list.priorities" :placeholder="project.priority.name" :multiple="false"></multiselect>
                                <span class="help-block" v-if="error.priority">{{error.priority[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.user}">
                            <label class="col-sm-3 control-label required">Project Leader</label>
                            <div class="col-sm-9" v-if="list.employees">
                                <multiselect v-model="project.leader" label="name" :options="list.employees" :placeholder="project.leader ? project.leader.name:'Select a leader'" :multiple="false"></multiselect>
                                <span class="help-block" v-if="error.leader">{{error.leader[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.supervisor}">
                            <label class="col-sm-3 control-label required">Project Supervisor</label>
                            <div class="col-sm-9" v-if="list.employees">
                                <multiselect v-model="project.supervisor" label="name" :options="list.employees" :placeholder="project.supervisor ? project.supervisor.name:'Select a supervisor'" :multiple="false"></multiselect>
                                <span class="help-block" v-if="error.supervisor">{{error.supervisor[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.status}">
                            <label class="col-sm-3 control-label required">Project Status</label>
                            <div class="col-sm-9" v-if="list.statuses">
                                <multiselect v-model="project.project_status" label="name" :options="list.statuses" :placeholder="project.status ? project.status.name:'Select a status'" :multiple="false"></multiselect>
                                <span class="help-block" v-if="error.status">{{error.status[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.area}">
                            <label class="col-sm-3 control-label required">Region</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute">{{project.area.title}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.completed_percentage}">
                            <label class="col-sm-3 control-label required">% Completed</label>
                            <div class="col-sm-9">
                                <div class="col-sm-9">
                                <input type="number" class="form-control" :placeholder="project.completed_percentage" required v-model="project.completed_percentage" min="0" max="100">
                                <span class="help-block" v-if="error.completed_percentage">{{error.completed_percentage[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Owner</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <small class="text-mute" v-if="project.owner">{{project.owner.name}}</small>
                                </p>
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
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
import Multiselect from 'vue-multiselect';
export default {
    props: ['project', 'projects'],
    components: {
        Multiselect, Spinner
    },
    data() {
        return {
            list: {
                priorities: [],
                clients: [],
                customers: [],
                areas: [],
                employees: [],
                statuses: []
            },
            error: {}
        }
    },
    mounted() {
        axios.get('/projects/list/priorities')
            .then(response => {
                this.list.priorities = response.data;
            });
        axios.get('/projects/list/clients')
            .then(response => {
                this.list.clients = response.data;
            });
        axios.get('/projects/list/customers')
            .then(response => {
                this.list.customers = response.data;
            });
        axios.get('/projects/list/areas')
            .then(response => {
                this.list.areas = response.data;
            });
        axios.get('/projects/list/statuses')
            .then(response => {
                this.list.statuses = response.data;
            });
        axios.get('/employees/all')
            .then(response => {
                this.list.employees = response.data.data;
            });
    },
    methods: {
        update: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/projects/update/' + this.project.id, this.project)
                .then(response => {
                    this.$parent.projects[this.project.index] = response.data;
                    this.$parent.project = {};
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
                    text: "You will not be able to recover this project!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function () {
                    axios.delete('/projects/destroy/' + self.project.id)
                        .then(response => {
                            self.projects.splice(self.project.index, 1);
                            swal({
                                title: "Deleted!",
                                text: "The project has been deleted.",
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
    },
    filters: {
        date(date) {
            return moment(date).format('LL');
        }
    }
}
</script>