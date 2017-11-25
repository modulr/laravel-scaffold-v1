<template>
    <div class="event">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel card">
                        <div class="panel-heading">
                            <swiper :options="swiperOption" ref="mySwiper" v-if="event.images.length">
                                <swiper-slide v-for="image in event.images" :key="image.id">
                                    <div>
                                        <img :src="image.url">
                                    </div>
                                </swiper-slide>
                                <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
                                <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
                            </swiper>
                        </div>
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <img class="avatar-md" :src="event.owner.avatar_url">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{event.name}}</h4>
                                    <p>{{event.description}}</p>
                                    <p class="lead" v-show="event.date || event.start_time || event.end_time">
                                        <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                        <span v-if="event.date">{{event.date | moment('LL')}}.</span>
                                        <span v-if="event.start_time">
                                            <small class="text-muted">De </small>{{'2018-01-01 '+event.start_time | moment('h:mm a')}}
                                        </span>
                                        <span v-if="event.end_time">
                                            <small class="text-muted">a </small>{{'2018-01-01 '+event.end_time | moment('h:mm a')}}
                                        </span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel information">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información</h3>
                        </div>
                        <div class="panel-body">
                            Contiene
                            <hr>
                            <p class="text-nl2br">{{event.content}}</p>
                            <p v-show="event.attending_limit > 0">Platos (0/{{event.attending_limit}})</p>
                            <hr>
                            <h1 class="text-center">${{event.price}}</h1>
                        </div>
                        <div class="panel-footer text-center">
                            <a href="#" class="btn btn-primary">Reservar</a>
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
                event: {
                    owner: {},
                    images: [],
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
            this.getEvent();
        },
        methods: {
            getEvent() {
                axios.get('/events/'+this.id)
                .then(response => {
                    this.event = response.data
                    console.log(this.event);
                });
            },
        }
    }
</script>
