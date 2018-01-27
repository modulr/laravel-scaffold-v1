<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-6 col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar" v-model="search">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-8 text-right">
                        <!-- <a href="#" class="btn btn-link">
                            <i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i>
                        </a> -->
                        <a href="#" class="btn btn-success"
                           v-if="user.hasPermission['create-events']"
                           @click.prevent="newEvent">
                            <i class="fa fa-eercast"></i> Crear platillo
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Events -->
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3" v-if="events.length" v-for="(item, index) in events">
                    <div class="panel card">
                        <div class="panel-heading">
                            <a :href="'/platillos/'+item.id">
                                <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <img class="avatar-sm" :src="item.owner.avatar_url">
                            <h3>{{item.name}}</h3>
                            <p v-show="item.description">{{item.description}}</p>
                            <div v-show="item.date || item.start_time || item.end_time">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="item.date">{{item.date | moment('LL')}}.</span>
                                <span v-if="item.start_time">
                                    <small class="text-muted">De </small>{{'2018-01-01 '+item.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="item.end_time">
                                    <small class="text-muted">a </small>{{'2018-01-01 '+item.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <div v-show="item.attending_limit > 0">Platos ({{item.attendings.length}}/{{item.attending_limit}})</div>
                        </div>
                        <a href="#" class="btn-edit" @click.prevent="editEvent(item, index)">
                            <span class="fa-stack">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
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
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Nombre del platillo"
                                v-model="eventNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Descripción"
                                v-model="eventNew.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.content}">
                            <textarea class="form-control" rows="3" placeholder="¿Que contiene?"
                                v-model="eventNew.content"></textarea>
                            <span class="help-block" v-if="error.content">{{error.content[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.attending_limit}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eercast fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Numero de platillos"
                                    v-model="eventNew.attending_limit">
                                <span class="help-block" v-if="error.attending_limit">{{error.attending_limit[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.price}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Precio"
                                    v-model="eventNew.price">
                                <span class="help-block" v-if="error.price">{{error.price[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Lugar"
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
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> De</span>
                                        <input type="time" class="form-control" v-model="eventNew.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> a</span>
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
                                <span class="dz-message btn btn-link btn-upload">
                                    <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Sube unas fotos
                                </span>
                            </template>
                        </vue-clip>
                        - Arrastra las fotos para cambiar su orden
                        <draggable class="row images" v-model="eventNew.images">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in eventNew.images">
                                <div class="image">
                                    <img :src="image.dataUrl" v-if="image.dataUrl">
                                    <img :src="image.url_thumbnail" v-else>
                                    <div class="progress" v-if="image.status">
                                        <div class="progress-bar"
                                             :class="{'progress-bar-danger': image.status == 'error'}"
                                             :style="{width: image.progress+'%'}"></div>
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
                                @click="storeEvent">publicar</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Event -->
        <div class="modal right md" data-backdrop="false" id="modalEditEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Nombre del platillo"
                                v-model="eventEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Descripcion"
                                v-model="eventEdit.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.content}">
                            <textarea class="form-control" rows="3" placeholder="¿Que contiene?"
                                v-model="eventEdit.content"></textarea>
                            <span class="help-block" v-if="error.content">{{error.content[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.attending_limit}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eercast fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Numero de platillos"
                                    v-model="eventEdit.attending_limit">
                                <span class="help-block" v-if="error.attending_limit">{{error.attending_limit[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.price}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Precio"
                                    v-model="eventEdit.price">
                                <span class="help-block" v-if="error.price">{{error.price[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Lugar"
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
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> De</span>
                                        <input type="time" class="form-control" v-model="eventEdit.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> a</span>
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
                                <span class="dz-message btn btn-link btn-upload">
                                    <i class="fa fa-plus fa-lg" aria-hidden="true"></i> Sube unas fotos
                                </span>
                            </template>
                        </vue-clip>
                        - Arrastra las fotos para cambiar su orden
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
                                @click="updateEvent">Guardar</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancelar</button>
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
                search: '',
                optionsImageTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/events/images/upload/temp',
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
                    url: '/events/images/upload',
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
                axios.get('/events/byOwner')
                .then(response => {
                    this.events = response.data.data
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
                this.eventEdit.images = [];

                $('#modalEditEvent').modal('show')

                axios.get('/events/'+item.id)
                .then(response => {
                    this.eventEdit.images = response.data.images;
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
                axios.post('/events/images/sort/' + this.eventEdit.id, {images:this.eventEdit.images})
                .then(response => {
                    this.events[this.eventEdit.index].images = response.data;
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
            destroyImage (image, index) {
                axios.delete('/events/images/destroy/' + image.id)
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
