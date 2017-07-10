<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group" :class="{'has-error': error.title}">
                    <textarea rows="2" class="form-control" placeholder="What are you thinking?"
                        v-model="newsItem.title" autofocus></textarea>
                    <small class="help-block" v-if="error.title">{{error.title[0]}}</small>
                </div>
                <div class="form-group" :class="{'has-error': error.images}">
                    <dropzone id="myVueDropzone" ref="myVueDropzone" v-show="newsItem.type == 2"
                        url="/news/upload/temp" :use-font-awesome=true
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
                        <button type="button" class="btn btn-default" @click="store">Publish</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" v-for="(item, index) in news">
            <div class="panel-heading">
                <img :src="item.user.avatar" alt="">
                <small>{{item.user.name}}</small>

                <div class="dropdown pull-right" v-if="user.id == item.user.id">
                    <a href="#" class="btn btn-link" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v fa-lg" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" @click.prevent="destroy(item.id, index)"><i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <p :class="{'lead': item.title.length <= 50}" v-if="item.title">{{item.title}}</p>
                <swiper :options="swiperOption" v-if="item.type == 2">
                    <swiper-slide v-for="image in item.images" :key="image.id">
                        <img class="img-responsive" :src="image.url">
                    </swiper-slide>
                    <div class="swiper-pagination" slot="pagination"></div>
                </swiper>
                <div class="embed-responsive embed-responsive-4by3" v-if="item.type == 3">
                    <iframe class="embed-responsive-item" :src="item.video"></iframe>
                </div>
            </div>
            <div class="panel-footer">
                <a href="#" @click.prevent="like(item)">
                    <i class="fa fa-lg" :class="[item.like ? 'fa-heart text-danger' : 'fa-heart-o']" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-comment-o fa-lg" aria-hidden="true"></i>
                </a>
                <span v-show="item.likes_counter > 0">{{item.likes_counter}} Likes</span>
                <small class="pull-right">{{item.created_at | ago}}</small>
            </div>
        </div>

        <div class="panel panel-default" v-if="news.length == 0">
            <div class="panel-body text-center">
                <i class="fa fa-bullhorn fa-5x text-muted" aria-hidden="true"></i>
                <p class="lead text-muted">Publish the first news!!</p>
            </div>
        </div>

        <infinite-loading :on-infinite="loadMore" ref="infiniteLoading">
            <span slot="no-results"></span>
            <span slot="no-more"></span>
        </infinite-loading>
    </div>
</template>

<script>
    import moment from 'moment';
    import swal from 'sweetalert';
    import Dropzone from 'vue2-dropzone';
    import { swiper, swiperSlide } from 'vue-awesome-swiper';
    import InfiniteLoading from 'vue-infinite-loading';

    export default {
        data() {
            return {
                news: [],
                newsItem: {
                    type: 1
                },
                pagination: {
                    current_page: 0
                },
                error: {},
                dzOptions: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    acceptedFileTypes: '.jpg,.jpeg,.png'
                },
                swiperOption: {
                    pagination : '.swiper-pagination',
                    paginationClickable :true
                }
            }
        },
        props: [
            'user'
        ],
        components: {
            Dropzone,
            swiper,
            swiperSlide,
            InfiniteLoading
        },
        filters: {
            ago(date) {
                return moment(date).fromNow();
            }
        },
        methods: {
            store: function () {
                axios.post('/news/store', this.newsItem)
                .then(response => {
                    this.news.unshift(response.data);
                    this.newsItem = {
                        type: 1
                    };
                    this.error = {};
                    this.$refs.myVueDropzone.removeAllFiles();
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            destroy: function (id, index) {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this news!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/news/destroy/' + id)
                    .then(response => {
                        self.news.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "Your news has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        this.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            like: function (item) {
                axios.post('/news/like/'+item.id, {like: item.like})
                .then(response => {
                    item.likes = response.data.likes;
                    item.like = response.data.like;
                    item.likes_counter = response.data.likes_counter;
                    this.error = {};
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            loadMore: function () {
                var page = Number(this.pagination.current_page) + 1;

                axios.get('/news?page='+ page)
                .then(response => {
                    this.news = this.news.concat(response.data.data);
                    this.pagination = response.data
                    this.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
                    if (!this.pagination.next_page_url)
                        this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
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

<style lang="scss">
    .fa-cloud-upload {
        font-size: 3em
    }
    .panel-default {
        a {
            color: #636b6f;
        }
        .panel-heading {
            img {
                border-radius: 50%;
                height: 30px;
                width: 30px;
                margin-right: 8px;
            }
        }
        .panel-footer {
            > a {
                margin-right: 10px;
            }
        }
    }
</style>
