<template>
    <div class="event">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel">
                        <swiper :options="swiperOption" ref="mySwiper">
                            <swiper-slide v-for="file in event.attachments" :key="file.id"
                                          v-if="file.type.match('image/*')">
                                <div>
                                    <img class="center-block" :src="file.url">
                                </div>
                            </swiper-slide>
                            <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
                            <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
                        </swiper>
                        <div class="panel-body">
                            <div class="media">
                                <div class="media-left">
                                    <img class="avatar-md" :src="event.user.avatar_url">
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{event.name}}</h4>
                                    <p>{{event.description}}</p>
                                    <p class="lead date" v-show="event.date || event.start_time || event.end_time">
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
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Información</h3>
                        </div>
                        <div class="panel-body">
                            Contiene
                            <hr>
                            2.5 tazas de agua <br>
                            4 zanahorias bebé <br>
                            3 calabacitas <br>
                            2 chayotesbr  <br>
                            5 ramas de apiobr <br>
                            1 cebolla <br>
                            1 cilantro fresco <br>
                            1 manojo de hierba de olorbr  <br>
                            al gusto de salbr <br>
                            8 bolitas de pimienta enterab <br><br>
                            <p>Platos (0/8)</p>
                            <hr>
                            <h1 class="text-center">$46.00</h1>
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
                    user: {},
                    attachments: [],
                },
                swiperOption: {
                    autoplay: 5000,
                    loop: true,
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
                });
            },
        }
    }
</script>
