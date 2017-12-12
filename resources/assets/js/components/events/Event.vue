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
                            <p v-show="event.attending_limit > 0">Platos ({{event.attendings.length}}/{{event.attending_limit}})</p>
                            <hr>
                            <h1 class="text-center">${{event.price}}</h1>
                        </div>
                        <div class="panel-footer text-center">
                            <a href="#" class="btn btn-primary" @click.prevent="attend" v-if="!attending" v-show="event.attending_limit > event.attendings.length">
                                Reservar
                            </a>
                            <a href="#" class="btn btn-danger" @click.prevent="attend" v-else>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="panel" v-if="event.attendings.length">
                        <div class="panel-heading">
                            <h3 class="panel-title">Reservaciones</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(attending, index) in event.attendings">
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="avatar-sm" :src="attending.avatar_url">
                                        </div>
                                        <div class="media-body">
                                            <h5 class="media-heading">{{attending.name}} <i class="mdi mdi-check-circle text-success" v-if="attending.pivot.approved"></i></h5>
                                            <a class="text-muted" :href="`mailto:${attending.email}`">{{attending.email}}</a>
                                            <div class="pull-right" v-if="event.owner.id == user.id">
                                                <a href="#" class="btn btn-sm btn-primary"
                                                    @click.prevent="approve(attending.id, index)"
                                                    v-if="!attending.pivot.approved">
                                                    Aprobar
                                                </a>
                                                <a href="#" class="btn btn-sm btn-danger"
                                                    @click.prevent="reject(attending.id, index)">
                                                    Rechazar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
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
                event: {
                    owner: {},
                    images: [],
                    attendings: []
                },
                attending: false,
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
                    this.isAttending();
                });
            },
            attend() {
                axios.get('/events/attend/'+this.id)
                .then(response => {
                    this.event.attendings = response.data.attendings;
                    this.isAttending();
                    if (response.data.attend) {
                        swal({
                            title: "Reservación exitosa",
                            text:  "Tu Reservación ha sido enviada a "+this.event.owner.name+", en cuanto sea confirmada recibiras la notificación en tu correo.",
                            type:  "success",
                        })
                    }
                });
            },
            approve(userId, index){
                axios.get('/events/approve/'+this.id+'/'+userId)
                .then(response => {
                    this.event.attendings[index].pivot.approved = 1;
                    swal({
                        title: "Aprobación exitosa",
                        text:  "Tu Aprobación ha sido enviada a "+this.event.attendings[index].name+", se enviaron los datos de la ubicación a su correo.",
                        type:  "success",
                    })
                });
            },
            reject(userId, index){
                axios.get('/events/reject/'+this.id+'/'+userId)
                .then(response => {
                    this.event.attendings.splice(index, 1);
                    swal({
                        title: "Aprobación rechazada",
                        text:  "Tu Rechazo ha sido enviado a "+this.event.attendings[index].name+" por correo.",
                        type:  "warning",
                    })
                });
            },
            isAttending(){
                var self = this;
                self.attending = false;
                this.event.attendings.forEach(function(v){
                    if (v.id == self.user.id) {
                        self.attending = true;
                    }
                });
            }
        }
    }
</script>
