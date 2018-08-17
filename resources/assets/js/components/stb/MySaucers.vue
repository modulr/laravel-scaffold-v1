<template>
    <div class="saucers">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-12 col-sm-7">
                        <input type="text" class="form-control" placeholder="Buscar" v-model="search">
                    </div>
                    <div class="col-xs-12 col-sm-5">
                        <a href="#" class="btn btn-success btn-block"
                           v-if="user.hasPermission['create-events']"
                           @click.prevent="newSaucer">
                            <i class="fa fa-eercast"></i> Crear platillo
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Saucers -->
            <div class="row row-flex">
                <div class="col-xs-12 col-sm-4" v-if="saucers.length" v-for="(saucer, index) in saucers">
                    <div class="panel">
                        <div class="panel-heading">
                            <a :href="'/mis_platillos/'+saucer.id">
                                <div class="ribbon">
                                    <div class="primary" v-if="saucer.availables > 0">
                                        Queda {{saucer.availables}} platos
                                    </div>
                                    <div class="danger" v-else>
                                        Agotado
                                    </div>
                                </div>
                                <img :src="saucer.images[0].url_thumbnail" v-if="saucer.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <h4 class="title">{{saucer.name | truncate(25)}}</h4>
                            <p class="description">{{saucer.description | truncate(100)}}</p>
                            <div class="date">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="saucer.start_time">
                                    <small>De</small> {{'2018-01-01 '+saucer.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="saucer.end_time">
                                    <small>a</small> {{'2018-01-01 '+saucer.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-7">
                                    <h4 class="price">{{saucer.price | currency}}</h4>
                                </div>
                                <div class="col-xs-5 text-right">
                                    <div class="activate">
                                        <span class="switch-text">Activar</span>
                                        <label class="switch-label">
                                            <input type="checkbox" v-model="saucer.enabled" @click="activateSaucer(saucer)">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn-edit" @click.prevent="editSaucer(saucer, index)">
                            <i class="fa fa-pencil fa-lg"></i>
                        </a>
                    </div>
                </div>
                <!-- Init Message -->
                <div class="init-message" v-else>
                    <i class="mdi mdi-local-dining" aria-hidden="true"></i>
                    <p class="lead">Don't exist saucers... create one!!</p>
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalNew"
                       v-if="user.hasPermission['create-events']">
                        <i class="mdi mdi-local-dining"></i> New Saucer
                    </a>
                </div>
            </div>
        </div>
        <!-- Modal New Saucer -->
        <div class="modal right md" id="modalNew">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Crear platillo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Nombre del platillo"
                                v-model="saucerNew.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Descripción"
                                v-model="saucerNew.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.content}">
                            <textarea class="form-control" rows="3" placeholder="¿Que contiene?"
                                v-model="saucerNew.content"></textarea>
                            <span class="help-block" v-if="error.content">{{error.content[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.limit}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eercast fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Numero de platillos"
                                    v-model="saucerNew.limit">
                                <span class="help-block" v-if="error.limit">{{error.limit[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.price}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Precio"
                                    v-model="saucerNew.price">
                                <span class="help-block" v-if="error.price">{{error.price[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Lugar"
                                    v-model="saucerNew.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="saucerNew.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> De</span>
                                        <input type="time" class="form-control" v-model="saucerNew.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> a</span>
                                        <input type="time" class="form-control" v-model="saucerNew.end_time">
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
                        <draggable class="row images" v-model="saucerNew.images">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in saucerNew.images">
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
        <!-- Modal Edit Saucer -->
        <div class="modal right md" data-backdrop="false" id="modalEdit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Editar platillo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <input type="text" class="form-control input-lg" placeholder="Nombre del platillo"
                                v-model="saucerEdit.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.description}">
                            <textarea class="form-control" rows="3" placeholder="Descripcion"
                                v-model="saucerEdit.description"></textarea>
                            <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.content}">
                            <textarea class="form-control" rows="3" placeholder="¿Que contiene?"
                                v-model="saucerEdit.content"></textarea>
                            <span class="help-block" v-if="error.content">{{error.content[0]}}</span>
                        </div>
                        <div class="form-group" :class="{'has-error': error.limit}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eercast fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Numero de platillos"
                                    v-model="saucerEdit.limit">
                                <span class="help-block" v-if="error.limit">{{error.limit[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.price}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-dollar fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Precio"
                                    v-model="saucerEdit.price">
                                <span class="help-block" v-if="error.price">{{error.price[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.place}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" placeholder="Lugar"
                                    v-model="saucerEdit.place">
                                <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.date}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                <input type="date" class="form-control" v-model="saucerEdit.date">
                                <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.start_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> De</span>
                                        <input type="time" class="form-control" v-model="saucerEdit.start_time">
                                        <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group" :class="{'has-error': error.end_time}">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> a</span>
                                        <input type="time" class="form-control" v-model="saucerEdit.end_time">
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
                        <draggable class="row images" v-model="saucerEdit.images" @end="sortImage">
                            <div class="col-xs-6 col-md-4" v-for="(image, index) in saucerEdit.images">
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
                                @click="updateSaucer">Guardar</button>
                        <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-default"
                                v-if="user.hasPermission['delete-events']"
                                @click="destroySaucer">
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
    import Vue2Filters from 'vue2-filters'

    export default {
        data() {
            return {
                user: Laravel.user,
                error: {},
                search: '',
                optionsImageTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/saucer/images/upload/temp',
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
                    url: '/saucer/images/upload',
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
                saucers: [],
                saucerNew: {},
                saucerEdit: {},
            }
        },
        mounted() {
            this.getSaucers();
        },
        components: {
            draggable,
        },
        methods: {
            getSaucers () {
                axios.get('/saucer/byOwner')
                .then(response => {
                    this.saucers = response.data.data
                });
            },
            newSaucer () {
                this.saucerNew = {
                    date: moment().format('YYYY-MM-DD'),
                    start_time: moment().add(1,'h').startOf('hour').format('HH:mm'),
                    images: [],
                };
                $('#modalEdit').modal('hide');
                $('#modalNew').modal('show');
            },
            storeEvent (e) {
                var btn = $(e.target).button('loading')
                axios.post('/saucer/store', this.saucerNew)
                .then(response => {
                    this.saucers.unshift(response.data);
                    this.saucerNew = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalNew').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            editSaucer (saucer, index) {
                this.saucerEdit = _.clone(saucer);
                this.saucerEdit.index = index;
                this.saucerEdit.images = [];

                $('#modalEdit').modal('show')

                axios.get('/saucer/'+saucer.id)
                .then(response => {
                    this.saucerEdit.images = response.data.images;
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            updateSaucer (e) {
                var btn = $(e.target).button('loading')
                axios.put('/saucer/update/'+this.saucerEdit.id, this.saucerEdit)
                .then(response => {
                    this.saucers[this.saucerEdit.index] = response.data;
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEdit').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroySaucer () {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Saucer!",
                    icon: "warning"
                },
                function(){
                    axios.delete('/saucer/destroy/' + self.saucerEdit.id)
                    .then(response => {
                        self.saucers.splice(self.saucerEdit.index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The Saucer has been deleted.",
                            icon: "success",
                            timer: 1000
                        });
                        self.error = {};
                        $('#modalEdit').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            activateSaucer (saucer, index) {
                axios.get('/saucer/enable/' + saucer.id)
                .then(response => {
                    saucer = response.data
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
            sendingImageTemp (file, xhr, formData) {
                this.saucerNew.images.push(file);
            },
            completeImageTemp (file, status, xhr) {
                if (status == 'success') {
                    var index = this.saucerNew.images.indexOf(file);
                    Object.assign(this.saucerNew.images[index], JSON.parse(xhr.response))
                    this.$set(this.saucerNew.images[index], 'cover', false);
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            destroyImageTemp (index) {
                this.saucerNew.images.splice(index, 1);
            },
            sendingImage (file, xhr, formData) {
                this.saucerEdit.images.push(file);
                formData.append('id', this.saucerEdit.id);
            },
            completeImage (file, status, xhr) {
                if (status == 'success') {
                    var index = this.saucerEdit.images.indexOf(file);
                    this.saucerEdit.images[index] = JSON.parse(xhr.response);
                    this.saucers[this.saucerEdit.index].images.push(JSON.parse(xhr.response));
                } else {
                    SnotifyService.error(JSON.parse(xhr.response).file[0]);
                }
            },
            sortImage () {
                axios.post('/saucer/images/sort/' + this.saucerEdit.id, {images:this.saucerEdit.images})
                .then(response => {
                    this.saucers[this.saucerEdit.index].images = response.data;
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
            destroyImage (image, index) {
                axios.delete('/saucer/images/destroy/' + image.id)
                .then(response => {
                    var key;
                    this.saucers[this.saucerEdit.index].images.forEach(function(v, k){
                        if (v.id == image.id) {
                            key = k;
                        }
                    });
                    this.saucers[this.saucerEdit.index].images.splice(key, 1);
                    this.saucerEdit.images.splice(index, 1);
                    this.error = {};
                })
                .catch(error => {
                    self.error = error.response.data;
                });
            },
        }
    }
</script>
