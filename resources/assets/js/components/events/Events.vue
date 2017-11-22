<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-success"
                           v-if="user.hasPermission['create-events']"
                           @click.prevent="newEvent">
                            <i class="fa fa-calendar-plus-o"></i> New Event
                        </a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Search" v-model="search">
                        </div>
                    </div>
                    <div class="col-sm-4 text-right">
                        <span class="separator"></span>
                        <a href="#" class="btn btn-link">
                            <i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Events -->
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-3" v-if="events.length" v-for="(item, index) in events">
                    <div class="thumbnail">
                        <a href="#" class="heading"
                            :style="{'background-image': 'url('+item.attachments[0].url_thumbnail+')'}"
                            v-if="item.attachments.length > 0 && item.attachments[0].type.match('image/*')"
                            @click.prevent="editEvent(item, index)">
                        </a>
                        <a href="#" class="heading" v-else
                            @click.prevent="editEvent(item, index)"
                            style="background-image: url('img/bg/bg-banner.png')">
                        </a>
                        <div class="caption">
                            <img :src="item.user.avatar_url" class="avatar-sm">
                            <h3>{{item.name}}</h3>
                            <p v-show="item.description">{{item.description}}</p>
                            <div v-show="item.date || item.start_time || item.end_time">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="item.date">{{item.date | moment('LL')}}.</span>
                                <span v-if="item.start_time">
                                    <small class="text-muted">From </small>{{'2018-01-01 '+item.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="item.end_time">
                                    <small class="text-muted">to </small>{{'2018-01-01 '+item.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Init Message -->
                <div class="init-message" v-else>
                    <i class="mdi mdi-event" aria-hidden="true"></i>
                    <p class="lead">Don't exist Events... create one!!</p>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalNewEvent"
                       v-if="user.hasPermission['create-events']">
                        <i class="fa fa-calendar-plus-o"></i> New Event
                    </a>
                </div>
            </div>
        </div>
        <!-- Modal New Event -->
        <div class="modal right md" id="modalNewEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body">
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
                        <div class="form-group">
                            <div class="input-group" :class="{'has-error': error.place}">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Place"
                                    v-model="eventNew.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group" :class="{'has-error': error.date}">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="eventNew.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="input-group" :class="{'has-error': error.start_time}">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> From</span>
                                        <input type="time" class="form-control" v-model="eventNew.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="input-group" :class="{'has-error': error.end_time}">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> To</span>
                                        <input type="time" class="form-control" v-model="eventNew.end_time">
                                        <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <vue-clip class="vue-clip-btn"
                                  :options="optionsFileTemp"
                                  :on-sending="sendingFileTemp"
                                  :on-complete="completeFileTemp"
                                  v-if="user.hasPermission['create-events']">
                            <template slot="clip-uploader-action">
                                <span class="dz-message btn btn-link btn-attachments">
                                    <i class="fa fa-paperclip fa-lg" aria-hidden="true"></i> Add Attachment
                                </span>
                            </template>
                        </vue-clip>
                        <div class="media vue-clip-queue" v-for="(file, index) in eventNew.attachments">
                            <div class="media-left">
                                <img class="media-object" :src="file.dataUrl" v-if="file.type.match('image/*')">
                                <i class="fa fa-file-o fa-4x" v-else></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{file.name}}</h4>
                                <small class="text-muted">{{file.status}}</small>
                                <span class="text-muted" v-if="file.status == 'success'">
                                     -
                                    <a href="#" @click.prevent="destroyFileTemp(index)">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                    <a href="#" v-if="file.type.match('image/*')"
                                        @click.prevent="makeCoverFileTemp(file, index)">
                                        <i class="fa fa-window-maximize"></i>
                                        <span v-if="!file.cover">Make Cover</span>
                                        <span v-else>Remove Cover</span>
                                    </a>
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" aria-valuemin="0" aria-valuemax="100"
                                        :aria-valuenow="file.progress"
                                        :style="{width: file.progress+'%'}">
                                    </div>
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
        <div class="modal right md" data-backdrop="false" id="modalEditEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body">
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
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> To</span>
                                        <input type="time" class="form-control" v-model="eventEdit.end_time">
                                        <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <vue-clip class="vue-clip-btn"
                                  :options="optionsFile"
                                  :on-sending="sendingFile"
                                  :on-complete="completeFile"
                                  v-if="user.hasPermission['update-events']">
                            <template slot="clip-uploader-action">
                                <span class="dz-message btn btn-link btn-attachments">
                                    <i class="fa fa-paperclip fa-lg" aria-hidden="true"></i> Add Attachment
                                </span>
                            </template>
                        </vue-clip>
                        <div class="media vue-clip-queue" v-for="(file, index) in eventEdit.attachments">
                            <div class="media-left" v-if="file.url_thumbnail">
                                <img class="media-object" :src="file.url_thumbnail" v-if="file.type.match('image/*')">
                                <i class="fa fa-file-o fa-4x" v-else></i>
                            </div>
                            <div class="media-left" v-else>
                                <img class="media-object" :src="file.dataUrl" v-if="file.type.match('image/*')">
                                <i class="fa fa-file-o fa-4x" v-else></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{file.name}}</h4>
                                <div v-if="file.status">
                                    <small class="text-muted">{{file.status}}</small>
                                    <div class="progress">
                                        <div class="progress-bar" aria-valuemin="0" aria-valuemax="100"
                                             :aria-valuenow="file.progress"
                                             :style="{width: file.progress+'%'}">
                                            <span class="sr-only">{{file.progress}}% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted"v-if="file.created_at">
                                    <small>Added {{file.created_at | moment('from')}}</small>
                                    <br>
                                    <small>
                                        <a href="#" @click.prevent="destroyFile(file, index)">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                        <a :href="file.url" download>
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                        <a href="#"
                                            v-if="file.type.match('image/*')"
                                            @click.prevent="makeCoverFile(file, index)">
                                            <i class="fa fa-window-maximize"></i>
                                            <span v-if="!file.cover">Make Cover</span>
                                            <span v-else>Remove Cover</span>
                                        </a><br>
                                    </small>
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
    import VueClip from 'vue-clip'

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                optionsFileTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/events/attachments/upload/temp',
                    paramName: 'file',
                    maxFilesize: {
                        limit: 10,
                        message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                    },
                    maxFiles: {
                        limit: 10,
                        message: 'You can only upload a max of 10 files'
                    },
                    acceptedFiles: {
                        extensions: ['image/*', 'application/pdf'],
                        message: 'You are uploading an invalid file'
                    },
                },
                optionsFile: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/events/attachments/upload',
                    paramName: 'file',
                    maxFilesize: {
                        limit: 10,
                        message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                    },
                    maxFiles: {
                        limit: 10,
                        message: 'You can only upload a max of 10 files'
                    },
                    acceptedFiles: {
                        extensions: ['image/*', 'application/pdf'],
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
        methods: {
            getEvents () {
                axios.get('/events/all')
                .then(response => {
                    this.events = response.data.data
                });
            },
            newEvent () {
                this.eventNew = {
                    attachments: [],
                };
                $('#modalEditEvent').modal('hide');
                $('#modalNewEvent').modal('show');
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
                this.eventEdit.attachments = [];

                $('#modalEditEvent').modal('show')

                axios.get('/events/'+item.id)
                .then(response => {
                    this.eventEdit.attachments = response.data.attachments;
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            updateEvent (e) {
                var btn = $(e.target).button('loading')
                axios.put('/events/update/'+this.eventEdit.id, this.eventEdit)
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
            destroyFileTemp (index) {
                this.eventNew.attachments.splice(index, 1);
            },
            destroyFile (file, index) {
                axios.delete('/events/attachments/destroy/' + file.id)
                .then(response => {
                    this.eventEdit.attachments.splice(index, 1);
                    if (file.cover) {
                        this.events[this.eventEdit.index].attachments = [];
                    }
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
            makeCoverFileTemp (file, index) {
                this.eventNew.attachments.forEach(function(v,k){
                    if (k == index) {
                        v.cover = true;
                    } else {
                        v.cover = false;
                    }
                });
            },
            makeCoverFile (file, index) {
                var data = {
                    eventId: file.event_id,
                    cover: !file.cover
                };
                axios.post('/events/attachments/makeCover/'+file.id, data)
                .then(response => {
                    this.eventEdit.attachments.forEach(function(v){
                        v.cover = false;
                    });
                    this.eventEdit.attachments[index].cover = response.data.cover;
                    if (response.data.cover) {
                        this.events[this.eventEdit.index].attachments[0] = response.data;
                    } else {
                        this.events[this.eventEdit.index].attachments = [];
                    }
                    this.error = {};
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            sendingFileTemp (file, xhr, formData) {
                this.eventNew.attachments.push(file);
            },
            completeFileTemp (file, status, xhr) {
                if (status == 'success') {
                    var index = this.eventNew.attachments.indexOf(file);
                    Object.assign(this.eventNew.attachments[index], JSON.parse(xhr.response))
                    this.$set(this.eventNew.attachments[index], 'cover', false);
                }
            },
            sendingFile (file, xhr, formData) {
                this.eventEdit.attachments.push(file);
                formData.append('id', this.eventEdit.id);
            },
            completeFile (file, status, xhr) {
                if (status == 'success') {
                    var index = this.eventEdit.attachments.indexOf(file);
                    this.eventEdit.attachments[index] = JSON.parse(xhr.response);
                }
            },
        }
    }
</script>
