<template>
    <div class="saucers">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div>
                <div class="row">
                    <div class="col-sm-4 hidden-xs">
                        <p class="lead">Reservaciones</p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
                        <input type="text" class="form-control" placeholder="Buscar" v-model="search">
                    </div>
                </div>
            </div>
            <br>
            <!-- List Reservations -->
            <div class="row row-flex">
                <div class="col-xs-12 col-sm-4"
                v-for="(reservation, index) in reservations"
                v-if="reservations.length">
                    <div class="panel">
                        <div class="panel-heading">
                            <a :href="'/platillos/'+reservation.saucer.id">
                                <div class="ribbon">
                                    <div class="warning" v-if="reservation.approved == 0">
                                        <b>{{reservation.quantity}}</b> Por confirmar
                                    </div>
                                    <div class="primary" v-else>
                                        <b>{{reservation.quantity}}</b> Confirmado
                                    </div>
                                </div>
                                <img :src="reservation.saucer.images[0].url_thumbnail" v-if="reservation.saucer.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <h4 class="title">{{reservation.saucer.name | truncate(25)}}</h4>
                            <div class="date">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="reservation.saucer.start_time">
                                    <small>De</small> {{'2018-01-01 '+reservation.saucer.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="reservation.saucer.end_time">
                                    <small>a</small> {{'2018-01-01 '+reservation.saucer.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <a :href="'/profile/'+reservation.user.id" class="user-profile">
                                <img class="avatar-sm avatar-block" :src="reservation.user.avatar_url">
                                {{reservation.user.name}}
                            </a>
                            <div class="row">
                                <div class="col-xs-3">
                                    <h4 class="price">{{reservation.saucer.price * reservation.quantity | currency}}</h4>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <a href="#" class="btn btn-danger"
                                    @click.prevent="reject(reservation, index)"
                                    v-if="reservation.approved == 0">
                                        Rechazar
                                    </a>
                                    <a href="#" class="btn btn-primary"
                                    @click.prevent="approve(reservation, index)"
                                    v-if="reservation.approved == 0">
                                        Confirmar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Init Message -->
                <div class="init-message" v-else>
                    <i class="mdi mdi-local-dining" aria-hidden="true"></i>
                    <p class="lead">Don't exist Reservations...</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import Vue2Filters from 'vue2-filters'

    export default {
        data() {
            return {
                search: '',
                reservations: []
            }
        },
        mounted() {
            this.getMyReservations()
        },
        methods: {
            getMyReservations () {
                axios.get('/reservation/myReservations')
                .then(response => {
                    this.reservations = response.data.data
                })
            },
            approve(reservation, index){
                axios.get('/reservation/approve/' + reservation.id)
                .then(response => {
                    swal({
                        title: "Aprobación confirmada",
                        text:  "Tu Aprobación ha sido enviada a "+ reservation.user.name +", se enviaron los datos de la ubicación a su correo.",
                        type:  "success",
                    })
                    reservation.approved = 1
                })
            },
            reject(reservation, index){
                axios.get('/reservation/reject/' + reservation.id)
                .then(response => {
                    swal({
                        title: "Aprobación rechazada",
                        text:  "Se le notificara al comensal "+ reservation.user.name +" que su reservacion fue rechazada",
                        type:  "success",
                    })
                    this.reservations.splice(index, 1)
                })
            }
        }
    }
</script>
