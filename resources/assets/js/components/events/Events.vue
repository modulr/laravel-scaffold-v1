<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-6 links">
                        <ul class="nav nav-tabs">
                            <li :class="{active: tabActive == 'events'}"><a href="#" @click.prevent="getEvents">Events</a></li>
                            <li :class="{active: tabActive == 'myEvents'}"><a href="#" @click.prevent="getMyEvents">My Events</a></li>
                        </ul>
                    </div>
                    <!-- <div class="col-sm-4 controls">
                        <input type="text" class="form-control" placeholder="Search" v-model="search">
                    </div> -->
                    <div class="col-sm-6 text-right controls">
                        <!-- <a href="#" class="btn btn-default">
                            <i class="fa fa-lg fa-list" aria-hidden="true"></i>
                        </a>
                        <span class="separator"></span> -->
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-events']"
                            @click.prevent="newEvent">
                            <i class="fa fa-calendar-plus-o"></i> New Event
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Events -->
            <div class="row" v-if="events.length">
                <div class="col-xs-12 col-sm-4 col-md-3" v-for="(event, index) in events">
                    <div class="panel card">
                        <div class="panel-heading">
                            <a :href="'/events/'+event.id">
                                <img :src="event.images[0].url_thumbnail" v-if="event.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <a :href="`/profile/${event.owner.id}`">
                                <img class="avatar-sm" :src="event.owner.avatar_url">
                            </a>
                            <h4>{{event.name}}</h4>
                            <p><i class="fa fa-fw fa-map-marker fa-lg" aria-hidden="true"></i> {{event.place}}</p>
                            <div v-show="event.date || event.start_time || event.end_time">
                                <i class="fa fa-fw fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="event.date">{{event.date | moment('LL')}}.</span>
                                <span v-if="event.start_time && !event.end_time">
                                    <small class="text-muted">At </small>{{'2018-01-01 '+event.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="event.start_time && event.end_time">
                                    <small class="text-muted">From </small>{{'2018-01-01 '+event.start_time | moment('h:mm a')}}
                                    <small class="text-muted">to </small>{{'2018-01-01 '+event.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                        </div>
                        <a href="#" class="btn-edit"
                            v-if="event.owner_id == user.id"
                            @click.prevent="editEvent(event, index)">
                            <span class="fa-stack">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Init Message -->
            <div class="init-message" v-else>
                <i class="mdi mdi-event" aria-hidden="true"></i>
                <p class="lead">Don't exist Events... create one!!</p>
                <a href="#" class="btn btn-success"
                    v-if="user.hasPermission['create-events']"
                    @click.prevent="newEvent">
                    <i class="fa fa-calendar-plus-o"></i> New Event
                </a>
            </div>
        </div>
        <!-- Modal New Event -->
        <div class="modal right md" id="modalNewEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Write name event"
                                v-model="eventNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Description"
                                v-model="eventNew.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Place"
                                    v-model="eventNew.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="eventNew.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> From</span>
                                        <input type="time" class="form-control" v-model="eventNew.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> to</span>
                                        <input type="time" class="form-control" v-model="eventNew.end_time">
                                        <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <vue-clip class="vue-clip-btn"
                                  :options="optionsImageTemp"
                                  :on-sending="sendingImageTemp"
                                  :on-complete="completeImageTemp"
                                  v-if="user.hasPermission['create-events']">
                            <template slot="clip-uploader-action">
                                <div class="">
                                    <div class="dz-message btn btn-link btn-upload">
                                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Add Images
                                    </div>
                                </div>
                            </template>
                        </vue-clip>
                        <draggable class="row images" v-model="eventNew.images">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in eventNew.images">
                                <div class="image">
                                    <img :src="image.dataUrl" v-if="image.dataUrl">
                                    <img :src="image.url_thumbnail" v-else>
                                    <div class="progress" v-if="image.status">
                                        <div class="progress-bar"
                                            :class="{'progress-bar-danger': image.status == 'error'}"
                                            :style="{width: image.progress+'%'}">
                                        </div>
                                    </div>
                                    <a href="#" class="btn-delete" @click.prevent="destroyImageTemp(index)">
                                        <span class="fa-stack">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </draggable>
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
        <div class="modal right md" id="modalEditEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Write name event"
                                v-model="eventEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Description"
                                v-model="eventEdit.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Place"
                                    v-model="eventEdit.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="eventEdit.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> From</span>
                                        <input type="time" class="form-control" v-model="eventEdit.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> to</span>
                                        <input type="time" class="form-control" v-model="eventEdit.end_time">
                                        <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <vue-clip class="vue-clip-btn"
                                  :options="optionsImage"
                                  :on-sending="sendingImage"
                                  :on-complete="completeImage"
                                  v-if="user.hasPermission['update-events']">
                            <template slot="clip-uploader-action">
                                <div>
                                    <div class="dz-message btn btn-link btn-upload">
                                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Add Images
                                    </div>
                                </div>
                            </template>
                        </vue-clip>
                        <draggable class="row images" v-model="eventEdit.images" @end="sortImage">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in eventEdit.images">
                                <div class="image">
                                    <img :src="image.dataUrl" v-if="image.dataUrl">
                                    <img :src="image.url_thumbnail" v-else>
                                    <div class="progress" v-if="image.status">
                                        <div class="progress-bar"
                                             :class="{'progress-bar-danger': image.status == 'error'}"
                                             :style="{width: image.progress+'%'}"></div>
                                    </div>
                                    <a href="#" class="btn-delete" @click.prevent="destroyImage(image, index)">
                                        <span class="fa-stack">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </draggable>
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
    import moment from 'moment';
    import VueClip from 'vue-clip';
    import draggable from 'vuedraggable'
    import {SnotifyService} from 'vue-snotify';

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                tabActive: 'events',
                optionsImageTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/events/images/upload/temp',
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
                optionsImage: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/events/images/upload',
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
                events: [],
                eventNew: {},
                eventEdit: {},
            }
        },
        mounted() {
            this.getEvents();
        },
        components: {
            draggable,
        },
        methods: {
            getEvents () {
                axios.get('/api/events/all')
                .then(response => {
                    this.events = response.data.data
                    this.tabActive = 'events'
                });
            },
            getMyEvents () {
                axios.get('/api/events/byOwner')
                .then(response => {
                    this.events = response.data.data
                    this.tabActive = 'myEvents'
                });
            },
            newEvent () {
                this.eventNew = {
                    date: moment().format('YYYY-MM-DD'),
                    start_time: moment().add(1,'h').startOf('hour').format('HH:mm'),
                    images: [],
                };
                $('#modalEditEvent').modal('hide');
                $('#modalNewEvent').modal('show');
            },
            storeEvent (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/events/store', this.eventNew)
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
            editEvent (event, index) {
                this.eventEdit = _.clone(event);
                this.eventEdit.index = index;
                this.eventEdit.images = [];

                $('#modalEditEvent').modal('show')

                axios.get('/api/events/show/'+event.id)
                .then(response => {
                    this.eventEdit.images = response.data.images;
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            updateEvent (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/events/update/'+this.eventEdit.id, this.eventEdit)
                .then(response => {
                    this.events[this.eventEdit.index] = response.data;
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
                    axios.delete('/api/events/destroy/' + self.eventEdit.id)
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
            sendingImageTemp (file, xhr, formData) {
                this.eventNew.images.push(file);
            },
            completeImageTemp (file, status, xhr) {
                if (status == 'success') {
                    var index = this.eventNew.images.indexOf(file);
                    Object.assign(this.eventNew.images[index], JSON.parse(xhr.response))
                    this.$set(this.eventNew.images[index], 'cover', false);
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            destroyImageTemp (index) {
                this.eventNew.images.splice(index, 1);
            },
            sendingImage (file, xhr, formData) {
                this.eventEdit.images.push(file);
                formData.append('id', this.eventEdit.id);
            },
            completeImage (file, status, xhr) {
                if (status == 'success') {
                    var index = this.eventEdit.images.indexOf(file);
                    this.eventEdit.images[index] = JSON.parse(xhr.response);
                    this.events[this.eventEdit.index].images.push(JSON.parse(xhr.response));
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            sortImage () {
                axios.post('/api/events/images/sort/' + this.eventEdit.id, {images:this.eventEdit.images})
                .then(response => {
                    this.events[this.eventEdit.index].images = response.data;
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
            destroyImage (image, index) {
                axios.delete('/api/events/images/destroy/' + image.id)
                .then(response => {
                    var key;
                    this.events[this.eventEdit.index].images.forEach(function(v, k){
                        if (v.id == image.id) {
                            key = k;
                        }
                    });
                    this.events[this.eventEdit.index].images.splice(key, 1);
                    this.eventEdit.images.splice(index, 1);
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
        }
    }
</script>
