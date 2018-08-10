<template>
    <div class="saucers">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div>
                <div class="row">
                    <div class="col-sm-4 hidden-xs">
                        <p class="lead">Cerca de ti</p>
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
                            <a :href="'/profile/'+reservation.saucer.owner.id" class="owner-profile">
                                <img class="avatar-sm" :src="reservation.saucer.owner.avatar_url">
                            </a>
                            <h4 class="title">{{reservation.saucer.name | truncate(25)}}</h4>
                            <p class="description">{{reservation.saucer.description | truncate(200)}}</p>
                            <div class="date">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="reservation.saucer.start_time">
                                    <small>De</small> {{'2018-01-01 '+reservation.saucer.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="reservation.saucer.end_time">
                                    <small>a</small> {{'2018-01-01 '+reservation.saucer.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-7">
                                  <h4 class="price">{{reservation.saucer.price * reservation.quantity | currency}}</h4>
                                </div>
                                <div class="col-xs-5 text-right">
                                    <a href="#" class="btn btn-block btn-danger"
                                    @click.prevent="cancel(reservation, index)"
                                     v-if="reservation.approved == 0">
                                        Cancelar
                                    </a>
                                    <a href="#" class="btn btn-block btn-success"
                                    @click.prevent="pay(reservation.saucer)"
                                     v-else>
                                        Pagar
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
            this.getReservations()
        },
        methods: {
            getReservations () {
                axios.get('/reservation/reservations')
                .then(response => {
                    this.reservations = response.data.data
                })
            },
            cancel(reservation, index) {
                axios.get('/reservation/cancel/' + reservation.id)
                .then(response => {
                    swal({
                        title: "Reservacion cancelada",
                        text:  "Tu reservacion fue cancelada y hemos notificado al cocinero.",
                        type:  "success",
                    })
                    this.reservations.splice(index, 1)
                })
                .catch(error => {
                    swal({
                        title: "Reservacion fallida",
                        text:  "Algo salio mal al intentar cancelar la reservacion, por favor intentalo de nuevo.",
                        type:  "warning",
                    })
                })
            },
            pay(saucer) {
                axios.post('/paypal/checkout', saucer)
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
                })
            }
        }
    }
</script>
