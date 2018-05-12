<template lang="html">
    <div class="modal-content autoparts-item">
        <!-- Modal header -->
        <div class="modal-header">
            <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="pull-right">
                <button type="button" class="btn btn-default"
                        v-if="action == 'edit'"
                        @click="printQR()">
                        <i class="fa fa-print fa-lg"></i>
                </button>
                <button type="button" class="btn btn-default"
                        v-if="user.hasPermission['delete-inventory'] && action == 'edit'"
                        @click="destroyAutopart">
                        <i class="fa fa-trash-o fa-lg"></i>
                </button>
                <button type="button" class="btn btn-success"
                        v-if="user.hasPermission['update-inventory'] && action == 'edit'"
                        @click="updateAutopart">Save
                </button>
                <button type="button" class="btn btn-success"
                        v-if="user.hasPermission['create-inventory'] && action == 'new'"
                        @click="storeAutopart">Save
                </button>
            </div>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <swiper :options="swiperOption" ref="mySwiper"
                v-if="action == 'edit'"
                v-show="autopart.images.length > 0">
                <swiper-slide v-for="image in autopart.images" :key="image.id">
                    <div>
                        <img :src="image.url">
                    </div>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
            <draggable class="images" v-model="autopart.images" @end="sortImage">
                <span class="image" v-for="(image, index) in autopart.images">
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
                    :class="{'vue-clip-block': autopart.images.length == 0}"
                    :options="vueClipOptions"
                    :on-sending="sendingImage"
                    :on-complete="completeImage"
                    v-if="user.hasPermission['create-inventory']">
                <template slot="clip-uploader-action">
                    <div>
                        <div class="dz-message btn btn-link">
                            <i class="fa fa-camera fa-lg" aria-hidden="true"></i>
                            <span v-show="autopart.images.length == 0"> ADD PHOTOS</span>
                        </div>
                    </div>
                </template>
            </vue-clip>
            <hr>
            <div class="form-group" v-if="action == 'edit'">
                <p class="form-control-static">ID: <strong>{{autopart.id}}</strong></p>
            </div>
            <div class="form-group" :class="{'has-error': errors.name}">
                <input type="text" class="form-control input-lg" placeholder="Autopart name"
                    v-model="autopart.name">
                <span class="help-block" v-if="errors.name">{{errors.name[0]}}</span>
            </div>
            <div class="form-group" :class="{'has-error': errors.description}">
                <textarea class="form-control" rows="2" placeholder="Description"
                    v-model="autopart.description"></textarea>
                <span class="help-block" v-if="errors.description">{{errors.description[0]}}</span>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" :class="{'has-error': errors.make_id}">
                        <multiselect v-model="autopart.make" :options="lists.makes" track-by="id" label="name"
                            selectLabel="" placeholder="Make" @remove="autopart.make={}"></multiselect>
                        <span class="help-block" v-if="errors.make_id">{{errors.make_id[0]}}</span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group" :class="{'has-error': errors.model_id}">
                        <multiselect v-model="autopart.model" :options="filteredModels" track-by="id" label="name"
                            selectLabel="" placeholder="Model" @remove="autopart.model={}"></multiselect>
                        <span class="help-block" v-if="errors.model_id">{{errors.model_id[0]}}</span>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group" :class="{'has-error': errors.years}">
                        <multiselect v-model="autopart.years" :options="lists.years" track-by="id" label="name"
                            selectLabel="" placeholder="Years" :multiple="true"></multiselect>
                        <span class="help-block" v-if="errors.years">{{errors.years[0]}}</span>
                    </div>
                </div>
                <div class="col-xs-12">
                    <fieldset class="separator">
                        <legend>Price</legend>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': errors.purchase_price}">
                                    <label>Purchase</label>
                                    <money class="form-control input-lg" v-model="autopart.purchase_price" v-bind="money"></money>
                                    <span class="help-block" v-if="errors.purchase_price">{{errors.purchase_price[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': errors.sale_price}">
                                    <label>Sale</label>
                                    <money class="form-control input-lg" v-model="autopart.sale_price" v-bind="money"></money>
                                    <span class="help-block" v-if="errors.sale_price">{{errors.sale_price[0]}}</span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-6">
                    <div class="form-group" :class="{'has-error': errors.origin_id}">
                        <label>Origin</label>
                        <select class="form-control text-capitalize"
                            v-model="autopart.origin_id">
                            <option v-for="option in lists.origins" :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="errors.origin_id">{{errors.origin_id[0]}}</span>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group" :class="{'has-error': errors.status_id}">
                        <label>Status</label>
                        <select class="form-control text-capitalize"
                            v-model="autopart.status_id">
                            <option v-for="option in lists.status" :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="errors.status_id">{{errors.status_id[0]}}</span>
                    </div>
                </div>
                <div class="col-xs-12" v-if="action == 'edit'">
                    <br>
                    <ul class="nav nav-tabs border-bottom" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#comments" data-toggle="tab">Comments</a>
                        </li>
                        <li role="presentation">
                            <a href="#activity" data-toggle="tab">Activity</a>
                        </li>
                    </ul>
                    <br>
                    <div class="tab-content">
                        <div class="tab-pane active" id="comments">
                            <comments
                                model="autopart"
                                :itemId="autopart.id"
                                :comments="autopart.comments">
                            </comments>
                        </div>
                        <div class="tab-pane" id="activity">
                            <activity :item="autopart"></activity>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import draggable from 'vuedraggable'
import VueClip from 'vue-clip'
import Multiselect from 'vue-multiselect'
import money from 'v-money'
import swal from 'sweetalert'

export default {
    data() {
        return {
            user: Laravel.user,
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
            lists: {
                makes: [],
                models: [],
                origins: [],
                status: [],
                years: []
            },
            autopart: {
                make: {},
                model: {},
                years: [],
                origin_id: 1,
                status_id: 1,
                images: [],
                creator: {}
            },
            action: 'new',
            errors: {}
        }
    },
    props: {
        item: {
            type: Object
        }
    },
    components: {
        swiper,
        swiperSlide,
        draggable,
        Multiselect
    },
    mounted () {
        this.getLists()
    },
    computed: {
        filteredModels () {
            var filteredArray = this.lists.models

            if (this.autopart.make) {
                if (this.autopart.make.id != this.autopart.model.make_id)
                    this.autopart.model = {}

                var makeId = this.autopart.make.id
                filteredArray = filteredArray.filter(function(item) {
                    return item.make_id == makeId
                })
            }

            return filteredArray
        }
    },
    watch: {
        item: function (newValue, oldValue) {
            if (newValue.id) {
                this.action = 'edit'
            } else {
                this.action = 'new'
            }
            this.autopart = newValue
        }
    },
    methods: {
        getLists () {
            axios.get('/api/autoparts/lists/makes')
            .then(response => {
                this.lists.makes = response.data
            })
            axios.get('/api/autoparts/lists/models')
            .then(response => {
                this.lists.models = response.data
            })
            axios.get('/api/autoparts/lists/origins')
            .then(response => {
                this.lists.origins = response.data
            })
            axios.get('/api/autoparts/lists/status')
            .then(response => {
                this.lists.status = response.data
            })
            axios.get('/api/autoparts/lists/years')
            .then(response => {
                this.lists.years = response.data
            })
        },
        storeAutopart (e) {
            var btn = $(e.target).button('loading')
            this.autopart.make_id = this.autopart.make ? this.autopart.make.id : null;
            this.autopart.model_id = this.autopart.model ? this.autopart.model.id : null;
            axios.post('/api/autoparts/store', this.autopart)
            .then(response => {
                this.$emit('created', response.data)
                this.errors = {}

                btn.button('reset')
                $('#modalAutopart').modal('hide')
            })
            .catch(error => {
                this.errors = error.response.data
                btn.button('reset')
            })
        },
        updateAutopart (e) {
            var btn = $(e.target).button('loading')

            this.autopart.make_id = this.autopart.make ? this.autopart.make.id : null
            this.autopart.model_id = this.autopart.model ? this.autopart.model.id : null

            axios.put(`/api/autoparts/update/${this.autopart.id}`, this.autopart)
            .then(response => {
                var autopart = response.data
                autopart.index = this.autopart.index
                this.$emit('updated', autopart)
                this.errors = {}

                btn.button('reset')
                $('#modalAutopart').modal('hide')
            })
            .catch(error => {
                this.errors = error.response.data
                btn.button('reset')
            })
        },
        destroyAutopart () {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Autopart!",
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete(`/api/autoparts/destroy/${this.autopart.id}`)
                    .then(response => {
                        this.$emit('deleted', this.autopart)
                        this.errors = {}
                        swal({
                            title: "Deleted!",
                            text: "The Autopart has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        })
                        $('#modalAutopart').modal('hide')
                    })
                    .catch(error => {
                        this.errors = error.response.data
                    })
                }
            })
        },
        setVueClipUrl () {
            if (this.action == 'new') {
                return '/api/autoparts/images/upload/temp'
            } else {
                return '/api/autoparts/images/upload'
            }
        },
        sendingImage (file, xhr, formData) {
            this.autopart.images.push(file)
            if (this.action == 'edit') {
                formData.append('id', this.autopart.id)
            }
        },
        completeImage (file, status, xhr) {
            if (status == 'success') {
                if (this.action == 'new') {
                    var index = this.autopart.images.indexOf(file)
                    Object.assign(this.autopart.images[index], JSON.parse(xhr.response))
                } else {
                    var index = this.autopart.images.indexOf(file)
                    this.autopart.images.splice(index, 1)
                    this.autopart.images.push(JSON.parse(xhr.response))
                    this.$emit('updated', this.autopart)

                }
            } else {
                SnotifyService.error(JSON.parse(xhr.response).file[0])
            }
        },
        destroyImage (image, index) {
            if (this.action == 'new') {
                this.autopart.images.splice(index, 1)
            } else {
                axios.delete(`/api/autoparts/images/destroy/${image.id}`)
                .then(response => {
                    this.autopart.images.splice(index, 1)
                    this.errors = {}

                    if (this.action == 'edit')
                        this.$emit('updated', this.autopart)
                })
                .catch(error => {
                    this.errors = error.response.data
                })
            }
        },
        sortImage () {
            if (this.action == 'edit') {
                axios.post(`/api/autoparts/images/sort/${this.autopart.id}`, {images:this.autopart.images})
                .then(response => {
                    if (this.action == 'edit')
                        this.$emit('updated', this.autopart)
                })
                .catch(error => {
                    this.errors = error.response.data
                })
            }
        },
        printQR () {
            //window.open(`/api/autoparts/export/qr/${this.autopart.id}`, '_blank');

            var content =
            `<html>
                <head>
                    <style>
                        @page {
                            size: 2.4in 4in landscape;
                            margin: 0;
                        }
                        @page :first {
                            margin: 0;
                        }
                        html, body {
                            font-family: Helvetica, Arial, sans-serif;
                            font-size: 12pt;
                            margin: 0;
                            padding: 0;
                            border: 0;
                        }
                        table {
                            border: none;
                            border-collapse: collapse;
                            border-spacing: 0;
                        }
                        .title {
                            color: #333;
                            text-transform: uppercase;
                            font-size: 4pt;
                            display: block;
                        }
                        .qr img {
                            height: 100px;
                            width: 100px;
                        }
                        .code {
                            text-align: center;
                            font-size: 80pt;
                            line-height: 1;
                        }
                    </style>
                </head>
                <body onload="window.focus(); window.print(); window.close();">
                    <div class="code">
                        ${this.autopart.id}
                    </div>
                    <table>
                        <tr>
                            <td class="qr">
                                <img src="${this.autopart.qr}">
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td class="make">
                                            <span class="title">Marca</span>
                                            ${this.autopart.make.name}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="model">
                                            <span class="title">Modelo</span>
                                            ${this.autopart.model.name}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="years">
                                            <span class="title">Años</span>
                                            ${this.autopart.years.map(year =>
                                                `<span>${year.name}</span>`
                                            ).join(',')}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>`

            var myWindow = window.open()
            myWindow.document.write(content)
            myWindow.document.close()
        }
    }
}
</script>
