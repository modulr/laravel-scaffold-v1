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
            <!-- List Saucers -->
            <div class="row row-flex">
                <div class="col-xs-12 col-sm-4" v-if="saucers.length" v-for="(saucer, index) in saucers">
                    <div class="panel">
                        <div class="panel-heading">
                            <a :href="'/platillos/'+saucer.id">
                                <div class="ribbon">
                                    <div class="primary" v-if="saucer.availables > 0">
                                        Queda {{saucer.availables}} platos
                                    </div>
                                    <div class="danger" v-else>
                                        Agotado
                                    </div>
                                </div>
                                <img :src="saucer.images[0].url_thumbnail" v-if="saucer.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <a :href="'/profile/'+saucer.owner.id" class="owner-profile">
                                <img class="avatar-sm" :src="saucer.owner.avatar_url">
                            </a>
                            <h4 class="title">{{saucer.name | truncate(25)}}</h4>
                            <p class="description">{{saucer.description | truncate(200)}}</p>
                            <div class="date">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="saucer.start_time">
                                    <small>De</small> {{'2018-01-01 '+saucer.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="saucer.end_time">
                                    <small>a</small> {{'2018-01-01 '+saucer.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-7">
                                  <h4 class="price">{{saucer.price | currency}}</h4>
                                </div>
                                <div class="col-xs-5">
                                    <a href="#" class="btn btn-block btn-success"
                                    @click.prevent="reserve(saucer)"
                                    v-if="saucer.availables > 0">
                                        Reservar
                                    </a>
                                    <select class="form-control quantity"
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
                <!-- Init Message -->
                <div class="init-message" v-else>
                    <i class="mdi mdi-local-dining" aria-hidden="true"></i>
                    <p class="lead">Don't exist Saucers...</p>
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
                saucers: []
            }
        },
        mounted() {
            this.getSaucers()
        },
        methods: {
            getSaucers () {
                axios.get('/saucer/active')
                .then(response => {
                    this.saucers = response.data.data
                })
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
