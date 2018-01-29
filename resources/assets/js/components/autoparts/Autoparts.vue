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
                        <a href="#" class="btn btn-link"
                            @click.prevent="showFilters">
                            <i class="fa fa-sliders fa-lg" aria-hidden="true"></i>
                        </a>
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
                    <div class="media" v-for="(item, index) in autoparts.data" @click="editAutopart(item, index)">
                        <div class="media-left">
                            <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                            <i class="fa fa-camera-retro fa-4x" v-else></i>
                        </div>
                        <div class="media-body">
                            <small class="text-muted pull-right hidden-xs hidden-sm">{{item.created_at | moment("LLL")}}</small>
                            <h5 class="media-heading">{{item.name}}</h5>
                            <span v-show="item.description">{{item.description}}<br></span>
                            <small>{{item.make.name}} - {{item.model.name}} - {{item.year.name}}<br></small>
                            <span class="lead">${{item.purchase_price}} / <strong>${{item.sale_price}}</strong></span>
                            <span class="label label-default pull-right" :class="{
                                'label-primary': item.status_id == 1,
                                'label-warning': item.status_id == 4,
                                'label-info': item.status_id == 3,
                                'label-success': item.status_id == 5,
                                }">{{item.status.name}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Init Message -->
            <div class="init-message" v-else>
                <i class="mdi mdi-directions-car" aria-hidden="true"></i>
                <p class="lead">Don't exist Autoparts!!</p>
            </div>
        </div>
        <!-- Modal Autopart -->
        <div class="modal right md" id="modalAutopart">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-link"
                                    v-if="user.hasPermission['delete-autoparts'] && autopart.action == 'edit'"
                                    @click="destroyAutopart"><i class="fa fa-trash fa-lg"></i></button>
                            <button type="button" class="btn btn-success"
                                    v-if="user.hasPermission['update-autoparts'] && autopart.action == 'edit'"
                                    @click="updateAutopart">Save</button>
                            <button type="button" class="btn btn-success"
                                    v-if="user.hasPermission['create-autoparts'] && autopart.action == 'new'"
                                    @click="storeAutopart">Save</button>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <swiper :options="swiperOption" ref="mySwiper" v-if="autopart.action == 'edit'">
                            <swiper-slide v-for="image in autopart.data.images" :key="image.id">
                                <div>
                                    <img :src="image.url">
                                </div>
                            </swiper-slide>
                            <div class="swiper-pagination" slot="pagination"></div>
                        </swiper>
                        <draggable class="images" v-model="autopart.data.images" @end="sortImage">
                            <span class="image" v-for="(image, index) in autopart.data.images">
                                <img class="img-rounded" :src="image.dataUrl" v-if="image.dataUrl">
                                <img class="img-rounded" :src="image.url_thumbnail" v-else>
                                <div class="progress" v-if="image.status">
                                    <div class="progress-bar"
                                     :class="{'progress-bar-danger': image.status == 'error'}"
                                      :style="{width: image.progress+'%'}">
                                    </div>
                                </div>
                                <a href="#" class="btn-delete" @click.prevent="destroyImage(image, index)">
                                    <i class="fa fa-times-circle"></i>
                                </a>
                            </span>
                            <vue-clip class="vue-clip-photo"
                                      :options="vueClipOptions"
                                      :on-sending="sendingImage"
                                      :on-complete="completeImage"
                                      v-if="user.hasPermission['create-autoparts']">
                                <template slot="clip-uploader-action">
                                    <div class="">
                                        <div class="dz-message btn btn-link btn-upload">
                                            <i class="fa fa-photo fa-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </template>
                            </vue-clip>
                        </draggable>
                        <hr>
                        <div class="form-group" :class="{'has-error': autopart.error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Write name Autopart"
                                v-model="autopart.data.name">
                            <span class="help-block" v-if="autopart.error.name">{{autopart.error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': autopart.error.description}">
                            <textarea class="form-control" rows="3" placeholder="Description"
                                v-model="autopart.data.description"></textarea>
                            <span class="help-block" v-if="autopart.error.description">{{autopart.error.description[0]}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': autopart.error.make_id}">
                                    <label>Make</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopart.data.make_id">
                                        <option v-for="option in lists.makes" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="autopart.error.make_id">{{autopart.error.make_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': autopart.error.model_id}">
                                    <label>Model</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopart.data.model_id">
                                        <option v-for="option in lists.models" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="autopart.error.model_id">{{autopart.error.model_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group" :class="{'has-error': autopart.error.year_id}">
                                    <label>Year</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopart.data.year_id">
                                        <option v-for="option in lists.years" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="autopart.error.year_id">{{autopart.error.year_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group" :class="{'has-error': autopart.error.status_id}">
                                    <label>Status</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopart.data.status_id">
                                        <option v-for="option in lists.status" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="autopart.error.status_id">{{autopart.error.status_id[0]}}</span>
                                </div>
                            </div>
                        </div>
                        <fieldset>
                            <legend>Price</legend>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group" :class="{'has-error': autopart.error.purchase_price}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd fa-lg" aria-hidden="true"></i></span>
                                            <input type="number" class="form-control" placeholder="Purchase price"
                                                v-model="autopart.data.purchase_price">
                                            <span class="help-block" v-if="autopart.error.purchase_price">{{autopart.error.purchase_price[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group" :class="{'has-error': autopart.error.sale_price}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd fa-lg" aria-hidden="true"></i></span>
                                            <input type="number" class="form-control" placeholder="Sale price"
                                                v-model="autopart.data.sale_price">
                                            <span class="help-block" v-if="autopart.error.sale_price">{{autopart.error.sale_price[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset v-if="autopart.action == 'edit'">
                            <legend>Meta</legend>
                            <p class="text-muted"><small>Created at </small>{{autopart.data.created_at | moment('lll')}}, <small>by </small>{{autopart.data.creator.name}}</p>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Autopart -->
        <div class="modal left sm" id="modalFilters">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-link"
                                    v-if="user.hasPermission['read-autoparts']"
                                    @click="applyFilters">Apply</button>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="lead">Filters</p>
                        Comming soon...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import { swiper, swiperSlide } from 'vue-awesome-swiper';
    import VueClip from 'vue-clip';
    import draggable from 'vuedraggable'
    import {SnotifyService} from 'vue-snotify';

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: null,
                swiperOption: {
                    loop: true,
                    pagination: '.swiper-pagination'
                },
                vueClipOptions: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: this.getVueClipUrl.bind(),
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
                lists: {
                    status: [],
                    makes: [],
                    models: [],
                    years: [],
                },
                autoparts: {
                    data: []
                    // paginate info
                    // ...
                },
                autopart: {
                    data: {
                        images: [],
                        creator: {}
                    },
                    action: 'new',
                    error: {}
                },
            }
        },
        mounted() {
            this.getLists();
            this.getAutoparts();
        },
        components: {
            swiper,
            swiperSlide,
            draggable,
        },
        methods: {
            getAutoparts () {
                axios.get('/api/autoparts/all')
                .then(response => {
                    this.autoparts = response.data
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
                this.autopart = {
                    data: {
                        status_id: 1,
                        images: [],
                        creator: {}
                    },
                    action: 'new',
                    error: {}
                };
                this.getVueClipUrl();
                $('#modalAutopart').modal('show');
            },
            storeAutopart (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/autoparts/store', this.autopart.data)
                .then(response => {
                    this.autoparts.data.unshift(response.data);
                    this.autopart = {
                        data: {
                            images: [],
                            creator: {}
                        },
                        action: 'new',
                        error: {}
                    };
                    var btn = $(e.target).button('reset')
                    $('#modalAutopart').modal('hide')
                })
                .catch(error => {
                    this.autopart.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editAutopart (item, index) {
                axios.get('/api/autoparts/show/'+item.id)
                .then(response => {
                    this.autopart.data = response.data;
                    this.autopart.data.index = index;
                    this.autopart.action = 'edit';
                    this.autopart.error = {};
                    this.getVueClipUrl();
                    $('#modalAutopart').modal('show')
                })
                .catch(error => {
                    this.autopart.error = error.response.data;
                });
            },
            updateAutopart (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/autoparts/update/'+this.autopart.data.id, this.autopart.data)
                .then(response => {
                    this.autoparts.data[this.autopart.data.index] = response.data;
                    this.autopart = {
                        data: {
                            images: [],
                            creator: {}
                        },
                        action: 'new',
                        error: {}
                    };
                    var btn = $(e.target).button('reset')
                    $('#modalAutopart').modal('hide')
                })
                .catch(error => {
                    this.autopart.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyAutopart () {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Autopart!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/api/autoparts/destroy/' + self.autopart.data.id)
                    .then(response => {
                        self.autoparts.data.splice(self.autopart.data.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The Autopart has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        $('#modalAutopart').modal('hide');
                    })
                    .catch(error => {
                        self.autopart.error = error.response.data;
                    });
                });
            },

            getVueClipUrl: function() {
                if (this.autopart.action == 'new') {
                    return '/api/autoparts/images/upload/temp';
                } else {
                    return '/api/autoparts/images/upload';
                }
            },
            sendingImage (file, xhr, formData) {
                this.autopart.data.images.push(file);
                if (this.autopart.action == 'edit') {
                    formData.append('id', this.autopart.data.id);
                }
            },
            completeImage (file, status, xhr) {
                if (status == 'success') {
                    var index = this.autopart.data.images.indexOf(file);
                    if (this.autopart.action == 'new') {
                        Object.assign(this.autopart.data.images[index], JSON.parse(xhr.response))
                    } else {
                        this.autopart.data.images[index] = JSON.parse(xhr.response);
                        this.autoparts.data[this.autopart.data.index].images.push(JSON.parse(xhr.response));
                    }
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            destroyImage (image, index) {
                if (this.autopart.action == 'new') {
                    this.autopart.data.images.splice(index, 1);
                } else {
                    axios.delete('/api/autoparts/images/destroy/' + image.id)
                    .then(response => {
                        if (this.autopart.action == 'edit') {
                            var key;
                            this.autoparts.data[this.autopart.data.index].images.forEach(function(v, k){
                                if (v.id == image.id) {
                                    key = k;
                                }
                            });
                            this.autoparts.data[this.autopart.data.index].images.splice(key, 1);
                        }

                        this.autopart.data.images.splice(index, 1);
                        this.autopart.error = {};
                    })
                    .catch(error => {
                        this.autopart.error = error.response.data;
                    });
                }
            },
            sortImage () {
                axios.post('/api/autoparts/images/sort/' + this.autopart.data.id, {images:this.autopart.data.images})
                .then(response => {
                    this.autoparts.data[this.autopart.data.index].images = response.data;
                })
                .catch(error => {
                    this.autopart.error = error.response.data;
                });
            },

            showFilters () {
                $('#modalFilters').modal('show');
            },
            applyFilters () {

            }

        }
    }
</script>
