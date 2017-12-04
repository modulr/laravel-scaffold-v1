<template>
    <div class="news">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a :href="`/profile/${item.creator.id}`">
                    <img class="avatar-xs" :src="item.creator.avatar_url" alt="">
                </a>
                {{item.creator.name}}
                <div class="dropdown pull-right" v-if="user.id == item.creator.id && user.hasPermission['delete-news']">
                    <a href="#" class="btn btn-link" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v fa-lg" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" @click.prevent="destroyNews(item.id)">
                            <i class="fa fa-fw fa-trash" aria-hidden="true"></i> Delete</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <p :class="{'lead': item.name.length <= 100}" v-if="item.name">{{item.name}}</p>
                <swiper :options="swiperOption" v-if="item.type == 2">
                    <swiper-slide v-for="image in item.images" :key="image.id">
                        <img class="img-responsive center-block" :src="image.url">
                    </swiper-slide>
                    <div class="swiper-pagination" slot="pagination"></div>
                </swiper>
                <div class="embed-responsive embed-responsive-4by3" v-if="item.type == 3">
                    <iframe class="embed-responsive-item" :src="item.video"></iframe>
                </div>
            </div>
            <div class="panel-footer">
                <a href="#" @click.prevent="likeNews(item)">
                    <i class="fa fa-lg" :class="[item.like ? 'fa-heart text-danger' : 'fa-heart-o']" aria-hidden="true"></i>
                </a>
                <a href="#">
                    <i class="fa fa-comment-o fa-lg" aria-hidden="true"></i>
                </a>
                <span class="text-muted" v-show="item.likes_counter > 0">{{item.likes_counter}} Likes</span>
                <small class="text-muted pull-right">{{item.created_at | moment('from')}}</small>
            </div>
        </div>
    </div>
</template>

<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper';

    export default {
        data() {
            return {
                user: Laravel.user,
                item: {
                    creator: {},
                    images: [],
                },
                swiperOption: {
                    pagination : '.swiper-pagination',
                    paginationClickable :true,
                },
            }
        },
        props: ['id'],
        components: {
            swiper,
            swiperSlide,
        },
        mounted() {
            this.getNews();
        },
        methods: {
            getNews() {
                axios.get('/api/news/show/'+this.id)
                .then(response => {
                    this.item = response.data
                });
            },
            destroyNews (id) {
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
                    axios.delete('/api/news/destroy/' + id)
                    .then(response => {
                        self.news = {};
                        swal({
                            title: "Deleted!",
                            text: "Your news has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        window.location.replace('/news');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            likeNews (item) {
                if (!this.user.hasPermission['update-tasks']) {
                    swal("Oops", "Don't have permissions to give like!",  "error");
                    return false;
                }
                axios.post('/api/news/like/'+item.id, {like: item.like})
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
        }
    }
</script>
