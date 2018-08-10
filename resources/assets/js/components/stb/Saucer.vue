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
                            <a href="#" class="btn btn-success btn-lg"
                            @click.prevent="reserve(saucer)"
                            v-if="saucer.availables > 0">
                                Reservar
                            </a>
                            <div class="row">
                                <div class="col-xs-2 col-xs-offset-5">
                                    <select class="form-control"
                                    v-model="saucer.quantity"
                                    v-if="saucer.availables > 0">
                                        <option v-for="option in saucer.availables" :value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
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
                    images: []
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
                });
            },
            reserve(saucer) {
                axios.get('/reservation/reserve/' + saucer.id +'/'+ saucer.quantity)
                .then(response => {
                    saucer.availables = response.data.availables
                    saucer.quantity = 1
                    swal({
                        title: "Peticion enviada",
                        text:  "Se ha enviado una peticion para "+saucer.quantity+" lugares al cocinero, te notificaremos cuando acepte la peticion de reservacion para que finalices tu reservacion.",
                        type:  "success",
                    })
                })
                .catch(error => {
                    if (error.response.status == 401) {
                        location.href = '/login';
                    }
                })
            }
        }
    }
</script>
