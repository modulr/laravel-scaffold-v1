<template>
    <div class="news">
        <!-- New News -->
        <div class="panel panel-default news-publish"
             v-if="user.hasPermission['create-news']">
            <div class="panel-body">
                <div class="form-group" :class="{'has-error': error.name}">
                    <textarea rows="2" class="form-control" placeholder="What are you thinking?"
                        v-model="newItem.name" autofocus></textarea>
                    <small class="help-block" v-if="error.name">{{error.name[0]}}</small>
                </div>
                <div class="form-group" :class="{'has-error': error.images}">
                    <div class="media vue-clip-queue" v-for="image in newItem.images">
                        <div class="media-left">
                            <img class="media-object" :src="image.dataUrl">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">{{image.name}}</h4>
                            <small class="text-muted" :class="{'text-danger': image.errorMessage.file}">{{image.status}}</small>
                            <small class="text-danger" v-if="image.errorMessage.file"> - {{image.errorMessage.file[0]}}</small>
                            <div class="progress" v-if="image.status">
                                <div class="progress-bar"
                                    :class="{'progress-bar-danger': image.status == 'error'}"
                                    :style="{width: image.progress+'%'}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <small class="help-block" v-if="error.images">{{error.images[0]}}</small>
                </div>
                <div class="form-group" :class="{'has-error': error.video}">
                    <input type="url" class="form-control" placeholder="Insert Youtube video url"
                        v-model="newItem.video" v-show="newItem.type == 3">
                    <small class="help-block" v-if="error.video">{{error.video[0]}}</small>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <a href="#" class="btn btn-default btn-sm" @click.prevent="toogleType(1)">
                            <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
                        </a>
                        <vue-clip class="vue-clip-btn"
                                  :options="optionsImageTemp"
                                  :on-sending="sendingImageTemp"
                                  :on-complete="completeImageTemp"
                                  v-if="user.hasPermission['create-events']">
                            <template slot="clip-uploader-action">
                                <div>
                                    <div class="dz-message btn btn-default btn-sm">
                                        <i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </template>
                        </vue-clip>
                        <a href="#" class="btn btn-default btn-sm" @click.prevent="toogleType(3)">
                            <span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span>
                        </a>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-primary" @click="storeNews">Publish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueClip from 'vue-clip';
    import EventBus from './event-bus';

    export default {
        data () {
            return {
                user: Laravel.user,
                error: {},
                newItem: {
                    type: 1,
                    images: []
                },
                optionsImageTemp: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/news/upload/temp',
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
                    }
                }
            }
        },
        methods: {
            storeNews (e) {
                var btn = $(e.target).button('loading')
                axios.post('/api/news/store', this.newItem)
                .then(response => {
                    EventBus.$emit('add-news', response.data)
                    this.newItem = {
                        type: 1,
                        images: []
                    };
                    this.error = {};
                    var btn = $(e.target).button('reset')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            toogleType (type) {
                if (this.newItem.type != type)
                    this.newItem.type = type;
            },
            sendingImageTemp (file, xhr, formData) {
                this.toogleType(2);
                this.newItem.images.push(file);
            },
            completeImageTemp (file, status, xhr) {
                if (status == 'success') {
                    var index = this.newItem.images.indexOf(file);
                    Object.assign(this.newItem.images[index], JSON.parse(xhr.response))
                }
            },
        }
    }
</script>
