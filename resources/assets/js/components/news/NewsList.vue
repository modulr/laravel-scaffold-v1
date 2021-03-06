<template>
    <div class="news">
        <!-- List News -->
        <div class="panel panel-default" v-for="(item, index) in news">
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
                            <a href="#" @click.prevent="destroyNews(item.id, index)">
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
        <!-- Init Message  -->
        <div class="init-message" v-if="news.length == 0">
            <i class="mdi mdi-whatshot" aria-hidden="true"></i>
            <p class="lead">Don't exist News!!</p>
        </div>
        <!-- Loading -->
        <infinite-loading @infinite="loadNews" ref="infiniteLoading">
            <span slot="no-results"></span>
            <span slot="no-more"></span>
        </infinite-loading>
    </div>
</template>

<script>
import swal from 'sweetalert'
import { swiper, swiperSlide } from 'vue-awesome-swiper';
import InfiniteLoading from 'vue-infinite-loading';
import EventBus from './event-bus';

export default {
    data() {
        return {
            user: Laravel.user,
            news: [],
            pagination: {
                current_page: 0
            },
            swiperOption: {
                pagination : '.swiper-pagination',
                paginationClickable :true,
            },
        }
    },
    mounted() {
        EventBus.$on('add-news', this.addNews);
    },
    components: {
        swiper,
        swiperSlide,
        InfiniteLoading,
    },
    methods: {
        addNews (item) {
            this.news.unshift(item)
        },
        loadNews ($state) {
            var page = Number(this.pagination.current_page) + 1;

            axios.get('/api/news/all?page='+ page)
            .then(response => {
                this.news = this.news.concat(response.data.data);
                this.pagination = response.data
                $state.loaded();
                if (!this.pagination.next_page_url)
                    $state.complete();
            });
        },
        destroyNews (id, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this news!",
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/news/destroy/' + id)
                    .then(response => {
                        self.news.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "Your news has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        });
                        self.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                }
            });
        },
        likeNews (item) {
            if (!this.user.hasPermission['update-news']) {
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
