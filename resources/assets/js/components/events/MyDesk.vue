<template>
    <div class="desk">
        <!-- Container -->
        <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Reservaciones</h3>
                    </div>
                </div>

                <div class="row row-events">
                        <div class="col-xs-12 col-sm-4 col-md-3" v-if="attends.length" v-for="(item, index) in attends">
                            <div class="panel card">
                                <div class="panel-heading">
                                    <div id="ribbon">
                                        <div class="attend">
                                            1</br>
                                            <span>Reservacion</span>
                                        </div>
                                    </div>
                                    <a :href="'/platillos/'+item.id">
                                        <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                                    </a>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <h5>{{item.name}}</h5>
                                        </div>
                                        <div class="col-lg-3">
                                            <h6> {{item.attending_limit-item.attendings.length}} Restantes</h6>
                                        </div>
                                    </div>

                                    <ul class="list-inline">
                                        <li class="list-inline-item"></li>
                                            <img class="avatar-sm" :src="item.owner.avatar_url">
                                        </li>
                                        <li class="list-inline-item">
                                            <span>{{item.owner.name}}</span>
                                        </li>
                                    </ul>

                                    <div class="row pull-right">
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-secondary btn-dark ">Cancelar</button>
                                        </div>
                                        <div class="col-lg-6">
                                                <button type="button" class="btn btn-success">Pagar</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                </div>
    
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Publicaciones</h3>
                    </div>
                </div>

                <div class="row row-events">
                        <div class="col-xs-12 col-sm-4 col-md-3" v-if="myevents.length" v-for="(item, index) in myevents">
                            <div class="panel card">
                                <div class="panel-heading">
                                    <a :href="'/platillos/'+item.id">
                                        <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                                    </a>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <h5>{{item.name}}</h5>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="pull-right">
                                                <bootstrap-toggle v-model="checked" :options="{ on: 'Activo', off: 'Inactivo' }"/>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row pull-right">
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-danger ">Eliminar</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-warning">Editar</button>
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
    import moment from 'moment';
    import BootstrapToggle from 'vue-bootstrap-toggle'

    export default {
        components: { BootstrapToggle },
        data() {
            return {
                //user: Laravel.user,
                search: '',
                events: [],
                myevents: [],
                attends:[],
                checked:true
            }
        },
        mounted() {
            this.getEvents();
            this.getOwnEvents();
            this.getAttends();
            console.log('Setup');
        },
        methods: {
            getEvents () {
                axios.get('/events/all')
                .then(response => {
                    this.events = response.data.data
                });
            },
            getOwnEvents () {
                axios.get('/events/byOwner')
                .then(response => {
                    this.myevents = response.data.data
                });
            },
            getAttends () {
                axios.get('/events/attendings')
                .then(response => {
                    this.attends = response.data
                    console.log(response);
                    console.log('attends', this);
                });
            },
        }
    }
</script>
