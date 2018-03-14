<template lang="html">
    <div>
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Inventory</h5>
                    </div>
                    <div class="col-xs-6 text-right controls">
                        <!-- <input type="text" class="form-control" placeholder="Search" v-model="search"> -->
                        <a href="#" class="btn btn-link"
                            @click.prevent="showFilters">
                            <i class="fa fa-sliders fa-lg" aria-hidden="true"></i> <span class="hidden-xs">Filters</span>
                        </a>
                        <a href="#" class="btn btn-link"
                            @click.prevent="showSearchQR">
                            <i class="fa fa-qrcode fa-lg" aria-hidden="true"></i> <span class="hidden-xs">Search</span>
                        </a>
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-autoparts']"
                            @click.prevent="newAutopart">
                            <i class="fa fa-plus"></i> New
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Autoparts -->
            <div class="row autoparts-list" v-if="autoparts.data.length">
                <div class="col-md-12">
                    <div class="media" v-for="(item, index) in autoparts.data" @click="editAutopart(item.id, index)">
                        <div class="media-left">
                            <span class="label" :class="{
                                'label-primary': item.status_id == 1,
                                'label-default': item.status_id == 2,
                                'label-info': item.status_id == 3,
                                'label-success': item.status_id == 4
                                }">
                                {{item.status.name}}
                            </span>
                            <img class="img-rounded" :src="item.images[0].url_thumbnail"
                                v-if="item.images.length">
                            <div class="img-rounded" v-else>
                                <i class="fa fa-picture-o fa-5x"></i>
                            </div>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading">{{item.id}} {{item.name}}</h5>
                            <p class="text-muted">
                                {{item.make.name}} - {{item.model.name}} -
                                <span v-for="(year, index) in item.years">
                                    {{year.name}}<span v-if="index+1 < item.years.length">, </span>
                                </span>
                            </p>
                            <h5>
                                {{item.sale_price | currency}}
                                <small class="pull-right" :class="{
                                    'text-success': item.origin_id == 1,
                                    'text-info': item.origin_id == 2,
                                    'text-primary': item.origin_id == 3
                                    }">
                                    {{item.origin.name}}
                                </small>
                            </h5>
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
        <div class="modal right md autoparts-item" id="modalAutopart">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="pull-right">
                            <button type="button" class="btn btn-link"
                                    v-if="user.hasPermission['read-autoparts'] && autopart.action == 'edit'"
                                    @click="printQR(autopart.data)">
                                    <i class="fa fa-print fa-lg"></i>
                            </button>
                            <button type="button" class="btn btn-link"
                                    v-if="user.hasPermission['delete-autoparts'] && autopart.action == 'edit'"
                                    @click="destroyAutopart"><i class="fa fa-trash-o fa-lg"></i></button>
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
                        <swiper :options="swiperOption" ref="mySwiper"
                            v-if="autopart.action == 'edit'"
                            v-show="autopart.data.images.length > 0">
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
                                    <span class="fa-stack">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-trash fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </span>
                        </draggable>
                        <vue-clip class="vue-clip-photo"
                                  :options="vueClipOptions"
                                  :on-sending="sendingImage"
                                  :on-complete="completeImage"
                                  v-if="user.hasPermission['create-autoparts']">
                            <template slot="clip-uploader-action">
                                <div>
                                    <div class="dz-message btn btn-link btn-upload">
                                        <i class="fa fa-photo fa-lg" aria-hidden="true"></i>
                                        <span v-show="autopart.data.images.length == 0"> ADD PHOTOS</span>
                                    </div>
                                </div>
                            </template>
                        </vue-clip>
                        <hr>
                        <div class="form-group" :class="{'has-error': autopart.error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Name autopart"
                                v-model="autopart.data.name">
                            <span class="help-block" v-if="autopart.error.name">{{autopart.error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': autopart.error.description}">
                            <textarea class="form-control" rows="2" placeholder="Description"
                                v-model="autopart.data.description"></textarea>
                            <span class="help-block" v-if="autopart.error.description">{{autopart.error.description[0]}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': autopart.error.make_id}">
                                    <multiselect v-model="autopart.data.make" :options="lists.makes" track-by="id" label="name"
                                        selectLabel="" placeholder="Make" @remove="autopart.data.make={}"></multiselect>
                                    <span class="help-block" v-if="autopart.error.make_id">{{autopart.error.make_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': autopart.error.model_id}">
                                    <multiselect v-model="autopart.data.model" :options="filteredModels" track-by="id" label="name"
                                        selectLabel="" placeholder="Model" @remove="autopart.data.model={}"></multiselect>
                                    <span class="help-block" v-if="autopart.error.model_id">{{autopart.error.model_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group" :class="{'has-error': autopart.error.years}">
                                    <multiselect v-model="autopart.data.years" :options="lists.years" track-by="id" label="name"
                                        selectLabel="" placeholder="Years" :multiple="true" :close-on-select="false"></multiselect>
                                    <span class="help-block" v-if="autopart.error.years">{{autopart.error.years[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <fieldset class="separator">
                                    <legend>Price</legend>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group" :class="{'has-error': autopart.error.purchase_price}">
                                                <label>Purchase</label>
                                                <money class="form-control input-lg" v-model="autopart.data.purchase_price" v-bind="money"></money>
                                                <span class="help-block" v-if="autopart.error.purchase_price">{{autopart.error.purchase_price[0]}}</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group" :class="{'has-error': autopart.error.sale_price}">
                                                <label>Sale</label>
                                                <money class="form-control input-lg" v-model="autopart.data.sale_price" v-bind="money"></money>
                                                <span class="help-block" v-if="autopart.error.sale_price">{{autopart.error.sale_price[0]}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': autopart.error.origin_id}">
                                    <label>Origin</label>
                                    <select class="form-control text-capitalize"
                                        v-model="autopart.data.origin_id">
                                        <option v-for="option in lists.origins" :value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="autopart.error.origin_id">{{autopart.error.origin_id[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
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
                            <div class="col-xs-12" v-if="autopart.action == 'edit'">
                                <fieldset class="separator">
                                    <legend>Activity</legend>
                                    <p class="text-muted"><small>Created at </small>{{autopart.data.created_at | moment('lll')}}, <small>by </small>{{autopart.data.creator.name}}</p>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Filters -->
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
                                    @click="clearFilters">Clear</button>
                            <button type="button" class="btn btn-link"
                                    v-if="user.hasPermission['read-autoparts']"
                                    @click="applyFilters">Apply</button>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <fieldset class="separator">
                            <legend>Filters</legend>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <multiselect v-model="filters.make" :options="lists.makes" track-by="id" label="name"
                                            selectLabel="" placeholder="Make" @remove="filters.make={}"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <multiselect v-model="filters.model" :options="filteredFiltersModels" track-by="id" label="name"
                                            selectLabel="" placeholder="Model" @remove="filters.model={}"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <multiselect v-model="filters.years" :options="lists.years" track-by="id" label="name"
                                            selectLabel="" placeholder="Years" :multiple="true" :close-on-select="false"></multiselect>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="separator">
                            <legend>Status</legend>
                            <div class="switch switch-block">
                                <label class="switch-label">
                                    <input type="checkbox" v-model="filters.available">
                                    <span class="slider round"></span>
                                </label>
                                <span class="switch-text">Available</span>
                            </div>
                            <div class="switch switch-block">
                                <label class="switch-label">
                                    <input type="checkbox" v-model="filters.notAvailable">
                                    <span class="slider round"></span>
                                </label>
                                <span class="switch-text">Not Available</span>
                            </div>
                            <div class="switch switch-block">
                                <label class="switch-label">
                                    <input type="checkbox" v-model="filters.separated">
                                    <span class="slider round"></span>
                                </label>
                                <span class="switch-text">Separated</span>
                            </div>
                            <div class="switch switch-block">
                                <label class="switch-label">
                                    <input type="checkbox" v-model="filters.sold">
                                    <span class="slider round"></span>
                                </label>
                                <span class="switch-text">Sold</span>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal QR Search -->
        <div class="modal fade autoparts-qr" id="modalSearchQR">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p class="modal-title">Scan the QR code</p>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <qrcode-reader @decode="searchQR" v-if="showQRSearch">
                            <div class="qr-scanner-back"></div>
                            <div class="qr-scanner"><em></em><span></span></div>
                            <p class="qr-error text-danger">{{errorQRSearch}}</p>
                        </qrcode-reader>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    import VueClip from 'vue-clip'
    import draggable from 'vuedraggable'
    import {SnotifyService} from 'vue-snotify'
    import Multiselect from 'vue-multiselect'
    import { QrcodeReader } from 'vue-qrcode-reader'
    import money from 'v-money'
    import Vue2Filters from 'vue2-filters'

    export default {
        data() {
            return {
                swiperOption: {
                    loop: true,
                    pagination: '.swiper-pagination'
                },
                vueClipOptions: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: this.setVueClipUrl.bind(),
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
                money: {
                    decimal: '.',
                    thousands: ',',
                    prefix: '$ ',
                    precision: 2,
                    masked: false
                },
                showQRSearch: false,
                errorQRSearch: '',
                user: Laravel.user,
                filters: {
                    make: {},
                    model: {},
                    years: [],
                    available: true,
                    notAvailable: true,
                    separated: true,
                    sold: true
                },
                lists: {
                    makes: [],
                    models: [],
                    origins: [],
                    status: [],
                    years: []
                },
                autoparts: {
                    data: []
                    // paginate info
                    // ...
                },
                autopart: {
                    data: {
                        make: {},
                        model: {},
                        years: [],
                        images: [],
                        creator: {}
                    },
                    action: 'new',
                    error: {}
                },
            }
        },
        components: {
            swiper,
            swiperSlide,
            draggable,
            Multiselect,
            QrcodeReader
        },
        mounted () {
            this.getLists()
            this.getAutoparts()
            $('#modalSearchQR').on('hidden.bs.modal', this.hideSearchQR)
        },
        computed: {
            filteredModels () {
                var filteredArray = this.lists.models

                if (this.autopart.data.make.id != this.autopart.data.model.make_id)
                    this.autopart.data.model = {}

                var makeId = this.autopart.data.make.id
                filteredArray = filteredArray.filter(function(item) {
                    return String(item.make_id).indexOf(makeId) !== -1
                })

                return filteredArray;;
            },
            filteredFiltersModels () {
                var filteredArray = this.lists.models

                if (this.filters.make.id != this.filters.model.make_id)
                    this.filters.model = {}

                var makeId = this.filters.make.id
                filteredArray = filteredArray.filter(function(item)  {
                    return String(item.make_id).indexOf(makeId) !== -1
                })

                return filteredArray
            }
        },
        methods: {
            getAutoparts () {
                axios.get('/api/autoparts/all')
                .then(response => {
                    this.autoparts = response.data
                });
            },
            getLists () {
                axios.get('/api/autoparts/list/makes')
                .then(response => {
                    this.lists.makes = response.data
                })
                axios.get('/api/autoparts/list/models')
                .then(response => {
                    this.lists.models = response.data
                })
                axios.get('/api/autoparts/list/origins')
                .then(response => {
                    this.lists.origins = response.data
                })
                axios.get('/api/autoparts/list/status')
                .then(response => {
                    this.lists.status = response.data
                })
                axios.get('/api/autoparts/list/years')
                .then(response => {
                    this.lists.years = response.data
                })
            },

            newAutopart () {
                this.autopart = {
                    data: {
                        make: {},
                        model: {},
                        origin_id: 1,
                        status_id: 1,
                        years: [],
                        images: [],
                        creator: {},
                    },
                    action: 'new',
                    error: {}
                };
                this.setVueClipUrl();
                $('#modalAutopart').modal('show');
            },
            storeAutopart (e) {
                var btn = $(e.target).button('loading')
                this.autopart.data.make_id = this.autopart.data.make ? this.autopart.data.make.id : null;
                this.autopart.data.model_id = this.autopart.data.model ? this.autopart.data.model.id : null;
                axios.post('/api/autoparts/store', this.autopart.data)
                .then(response => {
                    this.autoparts.data.unshift(response.data);
                    this.autopart = {
                        data: {
                            make: {},
                            model: {},
                            years: [],
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
            editAutopart (id, index) {
                axios.get('/api/autoparts/show/'+id)
                .then(response => {
                    this.autopart.data = response.data
                    this.autopart.data.index = index
                    this.autopart.action = 'edit'
                    this.autopart.error = {}
                    this.setVueClipUrl()
                    $('#modalAutopart').modal('show')
                })
                .catch(error => {
                    this.autopart.error = error.response.data;
                });
            },
            updateAutopart (e) {
                var btn = $(e.target).button('loading')
                this.autopart.data.make_id = this.autopart.data.make ? this.autopart.data.make.id : null
                this.autopart.data.model_id = this.autopart.data.model ? this.autopart.data.model.id : null

                axios.put('/api/autoparts/update/'+this.autopart.data.id, this.autopart.data)
                .then(response => {
                    this.autoparts.data[this.autopart.data.index] = response.data
                    this.autopart = {
                        data: {
                            make: {},
                            model: {},
                            years: [],
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
                        this.autopart = {
                            data: {
                                make: {},
                                model: {},
                                years: [],
                                images: [],
                                creator: {}
                            },
                            action: 'new',
                            error: {}
                        };
                        $('#modalAutopart').modal('hide');
                    })
                    .catch(error => {
                        self.autopart.error = error.response.data;
                    });
                });
            },

            printQR (autopart) {
                var content =
                `<html>
                    <head>
                        <style>
                            @page { margin: 0cm }
                            html, body {
                                width: 86mm;
                                height: 60mm;
                                font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif;
                                margin: .10em;
                                padding: 0;
                            }
                            table {
                                width: 100%;
                            }
                            span {
                                font-size:.7em;
                            }
                            img {
                                heigth:3em;
                                width:3em;
                            }
                            .origin {
                                color: #fff;
                                background-color: #f00;
                                font-size:.5em;
                                padding: .10em .5em;
                            }
                            .number {
                                font-size:7.5em;
                                line-height:.65em;
                            }

                        </style>
                    </head>
                    <body onload="window.focus(); window.print(); window.close();">
                        <table>
                            <tr>
                                <td><img src="${autopart.qr}"></td>
                                <td>
                                    <span class="origin">${autopart.origin.name}</span>
                                    <br>
                                    <span>${autopart.make.name}</span>
                                    <span>${autopart.model.name}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="number">${autopart.id}</span>
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>`

                var myWindow = window.open()
                myWindow.document.write(content)
                myWindow.document.close();
            },
            showSearchQR () {
                this.showQRSearch = true
                $('#modalSearchQR').modal('show')
            },
            hideSearchQR () {
                this.errorQRSearch = ''
                this.showQRSearch = false
            },
            searchQR (id) {
                axios.get('/api/autoparts/show/'+id)
                .then(response => {
                    if (response.data) {
                        this.autopart.data = response.data
                        this.autoparts.data.forEach(function(val, index){
                            if (val.id == this.autopart.data.id) {
                                this.autopart.data.index = index
                            }
                        }, this)
                        this.autopart.action = 'edit'
                        this.autopart.error = {}
                        this.setVueClipUrl()
                        $('#modalSearchQR').modal('hide')
                        $('#modalAutopart').modal('show')
                    } else {
                        this.errorQRSearch = 'The Autopart do not exist!'
                    }

                })
                .catch(error => {
                    this.autopart.error = error.response.data
                });
            },

            setVueClipUrl () {
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
                $('#modalFilters').modal('show')
            },
            applyFilters (e) {
                var years = []
                if (this.filters.years.length) {
                    this.filters.years.forEach(function(val){
                        years.push(val.id)
                    })
                }
                var btn = $(e.target).button('loading')
                axios.post('/api/autoparts/filter', {
                    make: this.filters.make,
                    model: this.filters.model,
                    years: years,
                    available: this.filters.available,
                    notAvailable: this.filters.notAvailable,
                    separated: this.filters.separated,
                    sold: this.filters.sold
                })
                .then(response => {
                    this.autoparts = response.data
                    var btn = $(e.target).button('reset')
                    $('#modalFilters').modal('hide')
                });
            },
            clearFilters (e) {
                var btn = $(e.target).button('loading')
                this.filters = {
                    make: {},
                    model: {},
                    years: [],
                    available: true,
                    notAvailable: true,
                    separated: true,
                    sold: true
                },
                this.getAutoparts()
                var btn = $(e.target).button('reset')
                $('#modalFilters').modal('hide')
            }
        }
    }
</script>
