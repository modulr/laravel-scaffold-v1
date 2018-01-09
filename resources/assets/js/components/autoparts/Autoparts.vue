<template>
    <div class="autoparts">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-4 controls">
                        <!-- <input type="text" class="form-control" placeholder="Search" v-model="search"> -->
                    </div>
                    <div class="col-sm-8 text-right controls">
                        <!-- <a href="#" class="btn btn-default">
                            <i class="fa fa-lg fa-list" aria-hidden="true"></i>
                        </a>
                        <span class="separator"></span> -->
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-autoparts']"
                            @click.prevent="newAutopart">
                            <i class="fa fa-car"></i> New Autopart
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Autoparts -->
            <div class="row" v-if="autoparts.data.length">
                <div class="col-md-12">
                    <div class="table-responsive table-elevation">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Autopart</th>
                                    <th>Model</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in autoparts.data">
                                    <td @click="editAutopart(item, index)">
                                        {{item.id}}
                                    </td>
                                    <td @click="editAutopart(item, index)">
                                        <div class="media">
                                            <div class="media-left">
                                                <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                                                <i class="fa fa-camera-retro fa-4x" v-else></i>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{item.name}}</h4>
                                                <small class="text-muted">{{item.description}}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td @click="editAutopart(item, index)">
                                        <small>{{item.make.name}} - {{item.model.name}} - {{item.year.name}}</small>
                                    </td>
                                    <td @click="editAutopart(item, index)">
                                        ${{item.purchase_price}}<br>
                                        <strong>${{item.sale_price}}</strong>
                                    </td>
                                    <td @click="editAutopart(item, index)">
                                        {{item.status.name}}
                                    </td>
                                    <td @click="editAutopart(item, index)">
                                        <small>{{item.created_at | moment("LLL")}}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Init Message -->
            <div class="init-message" v-else>
                <i class="mdi mdi-directions-car" aria-hidden="true"></i>
                <p class="lead">Don't exist Autoparts!!</p>
            </div>
        </div>
        <!-- Modal New Autopart -->
        <div class="modal right md" id="modalNewAutopart">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Write name Autopart"
                                v-model="autopartNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Description"
                                v-model="autopartNew.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': error.make_id}">
                                    <label>Make</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopartNew.make_id">
                                        <option v-for="option in lists.makes" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.make_id">{{error.make_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': error.model_id}">
                                    <label>Model</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopartNew.model_id">
                                        <option v-for="option in lists.models" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.model_id">{{error.model_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': error.year_id}">
                                    <label>Year</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopartNew.year_id">
                                        <option v-for="option in lists.years" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.year_id">{{error.year_id[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group" :class="{'has-error': error.observations}">
                            <textarea class="form-control" rows="3" placeholder="Observations"
                                v-model="autopartNew.observations"></textarea>
                            <span class="help-block" v-if="error.observations">{{error.observations[0]}}</span>
                        </div> -->
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.purchase_price}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-usd fa-lg" aria-hidden="true"></i></span>
                                        <input type="number" class="form-control" placeholder="Purchase price"
                                            v-model="autopartNew.purchase_price">
                                        <span class="help-block" v-if="error.purchase_price">{{error.purchase_price[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.sale_price}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-usd fa-lg" aria-hidden="true"></i></span>
                                        <input type="number" class="form-control" placeholder="Sale price"
                                            v-model="autopartNew.sale_price">
                                        <span class="help-block" v-if="error.sale_price">{{error.sale_price[0]}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.status_id}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tag fa-lg" aria-hidden="true"></i></span>
                                <select class="form-control text-capitalize"
                                    v-model="autopartNew.status_id">
                                    <option v-for="option in lists.status" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.status_id">{{error.status_id[0]}}</span>
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
                        <draggable class="row images" v-model="autopartNew.images">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in autopartNew.images">
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
                                v-if="user.hasPermission['create-autoparts']"
                                @click="storeAutopart">Create</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Event -->
        <div class="modal right md" id="modalEditEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Write name event"
                                v-model="autopartEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Description"
                                v-model="autopartEdit.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Place"
                                    v-model="autopartEdit.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="autopartEdit.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> From</span>
                                        <input type="time" class="form-control" v-model="autopartEdit.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> to</span>
                                        <input type="time" class="form-control" v-model="autopartEdit.end_time">
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
                        <draggable class="row images" v-model="autopartEdit.images" @end="sortImage">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in autopartEdit.images">
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
                search: null,
                optionsImageTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/autoparts/images/upload/temp',
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
                    url: '/api/autoparts/images/upload',
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
                autoparts: {
                    data: []
                },
                autopartNew: {},
                autopartEdit: {},
                lists: {
                    status: [],
                    makes: [],
                    models: [],
                    years: [],
                },
            }
        },
        mounted() {
            this.getAutoparts();
            this.getLists();
        },
        components: {
            draggable,
        },
        methods: {
            getAutoparts () {
                axios.get('/api/autoparts/all')
                .then(response => {
                    this.autoparts = response.data
                    console.log(this.autoparts.data);
                });
            },
            getLists () {
                axios.get('/api/autoparts/list/status')
                .then(response => {
                    this.lists.status = response.data;
                });
                axios.get('/api/autoparts/list/makes')
                .then(response => {
                    this.lists.makes = response.data;
                });
                axios.get('/api/autoparts/list/models')
                .then(response => {
                    this.lists.models = response.data;
                });
                axios.get('/api/autoparts/list/years')
                .then(response => {
                    this.lists.years = response.data;
                });
            },
            newAutopart () {
                this.autopartNew = {
                    status_id: 1,
                    images: [],
                };
                $('#modalEditEvent').modal('hide');
                $('#modalNewAutopart').modal('show');
            },
            storeAutopart (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/autoparts/store', this.autopartNew)
                .then(response => {
                    this.autoparts.data.unshift(response.data);
                    this.autopartNew = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNewAutopart').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editEvent (event, index) {
                this.autopartEdit = _.clone(event);
                this.autopartEdit.index = index;
                this.autopartEdit.images = [];

                $('#modalEditEvent').modal('show')

                axios.get('/api/events/show/'+event.id)
                .then(response => {
                    this.autopartEdit.images = response.data.images;
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            updateEvent (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/events/update/'+this.autopartEdit.id, this.autopartEdit)
                .then(response => {
                    this.events[this.autopartEdit.index] = response.data;
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
                    axios.delete('/api/events/destroy/' + self.autopartEdit.id)
                    .then(response => {
                        self.events.splice(self.autopartEdit.index, 1);
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
                this.autopartNew.images.push(file);
            },
            completeImageTemp (file, status, xhr) {
                if (status == 'success') {
                    var index = this.autopartNew.images.indexOf(file);
                    Object.assign(this.autopartNew.images[index], JSON.parse(xhr.response))
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            destroyImageTemp (index) {
                this.autopartNew.images.splice(index, 1);
            },
            sendingImage (file, xhr, formData) {
                this.autopartEdit.images.push(file);
                formData.append('id', this.autopartEdit.id);
            },
            completeImage (file, status, xhr) {
                if (status == 'success') {
                    var index = this.autopartEdit.images.indexOf(file);
                    this.autopartEdit.images[index] = JSON.parse(xhr.response);
                    this.events[this.autopartEdit.index].images.push(JSON.parse(xhr.response));
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            sortImage () {
                axios.post('/api/events/images/sort/' + this.autopartEdit.id, {images:this.autopartEdit.images})
                .then(response => {
                    this.events[this.autopartEdit.index].images = response.data;
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
                    this.events[this.autopartEdit.index].images.forEach(function(v, k){
                        if (v.id == image.id) {
                            key = k;
                        }
                    });
                    this.events[this.autopartEdit.index].images.splice(key, 1);
                    this.autopartEdit.images.splice(index, 1);
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
        }
    }
</script>
