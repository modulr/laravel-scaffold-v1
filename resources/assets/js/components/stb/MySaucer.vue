<template>
    <div class="saucer">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="ribbon">
                                <div class="primary" v-if="saucer.availables > 0">
                                    Queda {{saucer.availables}} platos
                                </div>
                                <div class="danger" v-else>
                                    Agotado
                                </div>
                            </div>
                            <swiper :options="swiperOption" ref="mySwiper" v-if="saucer.images.length">
                                <swiper-slide v-for="image in saucer.images" :key="image.id">
                                    <div>
                                        <img :src="image.url">
                                    </div>
                                </swiper-slide>
                                <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
                                <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
                            </swiper>
                        </div>
                        <div class="panel-body text-center">
                            <a :href="'/profile/'+saucer.owner.id">
                                <img class="avatar-md" :src="saucer.owner.avatar_url">
                            </a>
                            <h4 class="title">{{saucer.name}}</h4>
                            <div class="date">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="saucer.start_time">
                                    <small>De </small>{{'2018-01-01 '+saucer.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="saucer.end_time">
                                    <small>a </small>{{'2018-01-01 '+saucer.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <p class="description lead">{{saucer.description | truncate(200)}}</p>
                            <h3 class="price">{{saucer.price | currency}}</h3>
                        </div>
                    </div>
                    <div class="panel information">
                        <div class="panel-body" v-if="saucer.reservations.length">
                            <h3 class="panel-title">Reservaciones</h3>
                            <div class="media" v-for="(reservation, index) in saucer.reservations">
                                <div class="media-left">
                                    <img class="avatar-xs" :src="reservation.user.avatar_url">
                                </div>
                                <div class="media-body">
                                    <p>{{reservation.user.name}}</p>
                                    <a class="text-muted" :href="`mailto:${reservation.user.email}`">{{reservation.user.email}}</a>
                                    <span class="pull-right label" :class="{'label-warning': reservation.approved == 0, 'label-primary': reservation.approved == 1}">
                                        <span v-if="reservation.approved == 0">Por confirmar </span>
                                        <span v-else>Confirmado </span>
                                        {{reservation.quantity}}
                                    </span>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
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
                saucer: {
                    owner: {},
                    images: [],
                    reservations: []
                },
                swiperOption: {
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                },
            }
        },
        props: ['id'],
        components: {
            swiper,
            swiperSlide,
        },
        mounted() {
            this.getSaucer();
        },
        methods: {
            getSaucer() {
                axios.get('/saucer/' + this.id)
                .then(response => {
                    this.saucer = response.data
                    console.log(this.saucer);
                });
            }
        }
    }
</script>
