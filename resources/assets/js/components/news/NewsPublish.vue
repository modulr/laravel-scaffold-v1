<template>
    <div class="news">
        <div class="panel panel-default news-publish">
            <div class="panel-body">
                <div class="form-group" :class="{'has-error': error.title}">
                    <textarea rows="2" class="form-control" placeholder="What are you thinking?"
                        v-model="newsItem.title" autofocus></textarea>
                    <small class="help-block" v-if="error.title">{{error.title[0]}}</small>
                </div>
                <div class="form-group" :class="{'has-error': error.images}">
                    <dropzone id="myVueDropzone" ref="myVueDropzone" v-show="newsItem.type == 2"
                        url="/news/upload/temp"
                        :use-custom-dropzone-options=true :dropzoneOptions="dzOptions"
                        v-on:vdropzone-success="uploadSuccess">
                    </dropzone>
                    <small class="help-block" v-if="error.images">{{error.images[0]}}</small>
                </div>
                <div class="form-group" :class="{'has-error': error.video}">
                    <input type="url" class="form-control" placeholder="Insert Youtube video url"
                        v-model="newsItem.video" v-show="newsItem.type == 3">
                    <small class="help-block" v-if="error.video">{{error.video[0]}}</small>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <a href="#" class="btn btn-default btn-sm btn-type" @click.prevent="toogleType(1)">
                            <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
                        </a>
                        <a href="#" class="btn btn-default btn-sm btn-type" @click.prevent="toogleType(2)">
                            <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                        </a>
                        <a href="#" class="btn btn-default btn-sm btn-type" @click.prevent="toogleType(3)">
                            <span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span>
                        </a>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-primary" @click="store">Publish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Dropzone from 'vue2-dropzone';

    import EventBus from './event-bus';

    export default {
        data () {
            return {
                newsItem: {
                    type: 1
                },
                error: {},
                dzOptions: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    acceptedFileTypes: '.jpg,.jpeg,.png',
                },
            }
        },
        components: {
            Dropzone,
        },
        methods: {
            store: function (e) {
                var btn = $(e.target).button('loading')
                axios.post('/news/store', this.newsItem)
                .then(response => {
                    EventBus.$emit('add-news', response.data)
                    //this.news.unshift(response.data);
                    this.newsItem = {
                        type: 1
                    };
                    this.error = {};
                    this.$refs.myVueDropzone.removeAllFiles();
                    var btn = $(e.target).button('reset')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            uploadSuccess: function(file, response){
                if (!this.newsItem.images)
                    this.newsItem.images = [];

                this.newsItem.images.push(response);
            },
            toogleType: function (type) {
                if (this.newsItem.type != type) {
                    this.newsItem.type = type;
                }
            },
        }
    }
</script>
