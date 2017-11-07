<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-9">
                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalNewEvent"
                           v-if="user.hasPermission['create-events']">
                            <i class="fa fa-calendar-plus-o"></i> New Event
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" v-model="search">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List Events -->
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="panel panel-default" v-if="events.length">
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tbody>
                                    <tr v-for="(item, index) in events" >
                                        <td @click="editEvent(item, index)">
                                            <div class="media">
                                                <div class="media-left">
                                                    <i class="fa fa-calendar fa-4x"></i>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">{{item.name}}</h4>
                                                    <span class="text-muted">{{item.description}}</span>
                                                    <div class="event-info">
                                                        <span v-show="item.place">
                                                            <small class="text-muted">Place. </small>{{item.place}}
                                                        </span>
                                                        <br>
                                                        <span v-if="item.date">{{item.date | moment('LL')}}.</span>
                                                        <span v-if="item.start_time">
                                                            <small class="text-muted">From </small>{{item.date+' '+item.start_time | moment('h:mm a')}}
                                                        </span>
                                                        <span v-if="item.end_time">
                                                            <small class="text-muted">to </small>{{item.date+' '+item.end_time | moment('h:mm a')}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Init Message -->
                    <div class="init-message" v-else>
                        <i class="mdi mdi-event" aria-hidden="true"></i>
                        <p class="lead">Don't exist Events... create one!!</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal New Event -->
        <div class="modal right fade" id="modalNewEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">New Event</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label>Name *</label>
                            <input type="text" class="form-control input-lg" v-model="eventNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" v-model="eventNew.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <label>Place</label>
                            <input type="text" class="form-control" v-model="eventNew.place">
                            <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <label>Date</label>
                            <input type="date" class="form-control" v-model="eventNew.date">
                            <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Start Time</label>
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <input type="time" class="form-control" v-model="eventNew.start_time">
                                    <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <label>End Time</label>
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <input type="time" class="form-control" v-model="eventNew.end_time">
                                    <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-left"
                                v-if="user.hasPermission['create-events']"
                                @click="storeEvent">Create</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Event -->
        <div class="modal right fade" data-backdrop="false" id="modalEditEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Edit Event</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label>Name *</label>
                            <input type="text" class="form-control input-lg" v-model="eventEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" v-model="eventEdit.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <label>Place</label>
                            <input type="text" class="form-control" v-model="eventEdit.place">
                            <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <label>Date</label>
                            <input type="date" class="form-control" v-model="eventEdit.date">
                            <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <label>Start Time</label>
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <input type="time" class="form-control" v-model="eventEdit.start_time">
                                    <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <label>End Time</label>
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <input type="time" class="form-control" v-model="eventEdit.end_time">
                                    <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left"
                                v-if="user.hasPermission['update-events']"
                                @click="updateEvent">Save</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-default"
                                v-if="user.hasPermission['delete-events']"
                                @click="destroyEvent">
                            <i class="fa fa-trash-o fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                events: [],
                eventNew: {},
                eventEdit: {},
            }
        },
        mounted() {
            this.getEvents();
        },
        methods: {
            getEvents () {
                axios.get('/events/all')
                .then(response => {
                    this.events = response.data.data
                });
            },
            storeEvent (e) {
                var btn = $(e.target).button('loading')
                axios.post('/events/store', this.eventNew)
                .then(response => {
                    this.events.unshift(response.data);
                    this.eventNew = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNewEvent').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editEvent (item, index) {
                this.eventEdit = _.clone(item);
                this.eventEdit.index = index;
                $('#modalEditEvent').modal('show')
            },
            updateEvent (e) {
                var btn = $(e.target).button('loading')
                axios.put('/events/update/'+this.eventEdit.id, this.eventEdit)
                .then(response => {
                    this.events[this.eventEdit.index] = response.data;
                    this.eventEdit = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEditEvent').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyEvent () {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Event!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/events/destroy/' + self.eventEdit.id)
                    .then(response => {
                        self.events.splice(self.eventEdit.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The Event has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        $('#modalEditEvent').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
        }
    }
</script>
