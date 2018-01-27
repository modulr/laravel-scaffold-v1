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
                                <div class="media-left text-center">
                                    <img class="avatar-md" :src="event.owner.avatar_url">
                                    <h4><i class="fa fa-eercast" aria-hidden="true"></i> {{event.attending_limit-event.attendings.length}}/{{event.attending_limit}}</h4>
                                    <span class="text-muted" v-show="event.attending_limit > 0">
                                        Disponibles
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{event.name}}</h4>
                                    <p class="lead">{{event.description}}</p>
                                    <p class="lead" v-if="showPaidMsg || showAttendingsList"><i class="fa fa-fw fa-map-marker fa-lg" aria-hidden="true"></i> {{event.place}}</p>
                                    <p if="event.date || event.start_time || event.end_time">
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
                            <h3 class="panel-title">Reservaciones</h3>
                        </div>
                        <div class="panel-body" v-if="event.attendings.length && showAttendingsList">
                            <div class="media" v-for="(attending, index) in event.attendings">
                                <div class="media-left">
                                    <img class="avatar-xs" :src="attending.avatar_url">
                                </div>
                                <div class="media-body">
                                    <div class="pull-right" v-if="event.owner.id == user.id">
                                        <a href="#" class="btn btn-xs btn-link"
                                            @click.prevent="reject(attending.id, index)"
                                            v-if="!attending.pivot.paid">
                                            Rechazar
                                        </a>
                                        <a href="#" class="btn btn-xs btn-primary"
                                            @click.prevent="approve(attending.id, index)"
                                            v-if="!attending.pivot.approved">
                                            Aprobar
                                        </a>
                                        <small class="text-primary" v-if="attending.pivot.paid">Pagada</small>
                                        <small v-if="attending.pivot.approved && attending.pivot.paid"> / </small>
                                        <small class="text-success" v-if="attending.pivot.approved">Aprovada</small>
                                    </div>
                                    <p>
                                        {{attending.name}}
                                    </p>
                                    <a class="text-muted" :href="`mailto:${attending.email}`">{{attending.email}}</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="panel-body">
                            <h1 class="text-center">${{event.price}}</h1>
                        </div>
                        <div class="panel-footer text-center">
                            <a href="#" class="btn btn-success" @click.prevent="attend" v-if="showAttendButton">
                                Reserva y Paga con Paypal
                            </a>
                            <a href="#" class="btn btn-link" @click.prevent="attend" v-if="showCancelButton">
                                Cancelar reservacion
                            </a>
                            <a href="#" class="btn btn-primary" @click.prevent="pay" v-if="showPayButton">
                                Pagar con Paypal
                            </a>
                            <p class="alert alert-success" v-if="showPaidMsg">Tu reservacion ya esta lista!!</p>
                            <p v-if="showPaidMsg">Aquí es la direccion en donde disfrutaras de tu platillo.</p>
                            <i class="fa fa-map-marker fa-3x" aria-hidden="true"></i>
                            <p class="lead" v-if="showPaidMsg">{{event.place}}</p>
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
                showAttendButton: false,
                showCancelButton: false,
                showPayButton: false,
                showPaidMsg: false,
                showAttendingsList: false,
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
                    this.showHideButton()
                });
            },
            attend() {
                axios.get('/events/attend/'+this.id)
                .then(response => {
                    this.event.attendings = response.data.attendings;
                    this.showHideButton();
                    if (response.data.attend) {
                        swal({
                            title: "Peticion enviada",
                            text:  "Tu Reservación ha sido enviada al cocinero "+this.event.owner.name+", en caso de ser aceptada podras terminar tu reservacion pagando el platillo por medio de Paypal.",
                            type:  "success",
                        })
                    } else {
                        swal({
                            title: "Reservacion cancelada",
                            text:  "Tu Reservación ha sido enviada al cocinero "+this.event.owner.name+", en caso de ser aceptada podras terminar tu reservacion pagando el platillo por medio de Paypal.",
                            type:  "success",
                        })
                    }
                });
            },
            pay() {
                axios.post('/paypal/checkout', this.event)
                .then(response => {
                    if (response.data.success) {
                        location.href = response.data.approvalUrl;
                    }
                })
                .catch(error => {
                    swal({
                        title: "Pago fallido",
                        text:  "Algo salio mal al intentar hacer tu pago, por favor intentalo de nuevo",
                        type:  "warning",
                    })
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
                        text:  "Se le notificara al comensal "+this.event.attendings[index].name+" que su peticion fue rechazada",
                        type:  "warning",
                    })
                });
            },
            showHideButton(){
                if (this.user.id != this.event.owner.id) {
                    this.showAttendButton = true;
                    if (this.event.attending_limit > this.event.attendings.length) {
                        this.showAttendButton = true;
                    }
                    if (this.event.attendings.length) {
                        var self = this;
                        this.event.attendings.forEach(function(v){
                            if (self.user.id == v.id) {
                                self.showAttendButton = false;
                                self.showCancelButton = true
                                if (v.pivot.approved) {
                                    self.showPayButton = true
                                    self.showCancelButton = false
                                }
                                if (v.pivot.paid) {
                                    self.showPayButton = false
                                    self.showCancelButton = false
                                    self.showPaidMsg= true
                                }
                            }
                        });
                    } else {
                        this.showCancelButton = false;
                    }

                } else {
                    this.showAttendingsList = true;
                }
            }
        }
    }
</script>
