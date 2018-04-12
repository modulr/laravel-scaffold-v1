<template>
    <div class="desk">
        <!-- Container -->
        <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>Reservaciones</h3>
                    </div>
                </div>

                <!-- Attends user -->
                <div class="row row-events">
                        <div class="col-xs-12 col-sm-4 col-md-3" v-if="attends.length" v-for="(item, index) in attends">
                            <div class="panel card">
                                <div class="panel-heading">
                                    <div id="ribbon">
                                        <div v-bind:class="[item.pivot.approved ? confirm : attend,'']">
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

                <!--Own Attends-->
                <div class="row row-events">
                        <div class="col-xs-12 col-sm-4 col-md-3" v-if="events.length" v-for="(item, index) in events">
                            <div class="panel card">
                                <div class="panel-heading">
                                    <div id="ribbon">
                                        <div v-bind:class="[item.pivot.approved ? 'confirm' : 'attend','']">
                                            <block v-if="!item.pivot.approved">
                                                1</br>
                                                <span>Reservacion</span>
                                            </block>
                                            <block v-if="item.pivot.approved">
                                                </br>
                                                <span>Confirmado</span>
                                            </block>
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
                                            <h6> {{item.attending_limit-item.attendings}} Restantes</h6>
                                        </div>
                                    </div>

                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <img class="avatar-sm" :src="item.avatar_url">
                                        </li>
                                        </li>
                                        <li class="list-inline-item">
                                            <span>{{item.email}}</span>
                                        </li>
                                    </ul>

                                    <div class="row pull-right">
                                        <div class="col-lg-6">
                                            <button v-if="!item.pivot.approved" type="button" class="btn btn-secondary btn-dark" @click="rejectEvent(item.event_id, item.id)">Rechazar</button>
                                        </div>
                                        <div class="col-lg-6">
                                                <button type="button" :disabled="item.pivot.approved == 1" class="btn btn-success" @click="approveEvent(item.event_id, item.id)">Confirmar</button>
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
                                            <button type="button" class="btn btn-danger" v-if="user.hasPermission['delete-events']"
                                            @click="destroyEvent(item.id, index)">Eliminar</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <button type="button" class="btn btn-warning" @click.prevent="editEvent(item, index)">Editar</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                </div>
        </div>

        <!-- Modal Edit Event -->
        <div class="modal right md" data-backdrop="false" id="modalEditEvent">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="form-group" :class="{'has-error': error.name}">
                                <input type="text" class="form-control input-lg" placeholder="Nombre del platillo"
                                    v-model="eventEdit.name">
                                <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.description}">
                                <textarea class="form-control" rows="3" placeholder="Descripcion"
                                    v-model="eventEdit.description"></textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.content}">
                                <textarea class="form-control" rows="3" placeholder="¿Que contiene?"
                                    v-model="eventEdit.content"></textarea>
                                <span class="help-block" v-if="error.content">{{error.content[0]}}</span>
                            </div>
                            <div class="form-group" :class="{'has-error': error.attending_limit}">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-eercast fa-lg" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Numero de platillos"
                                        v-model="eventEdit.attending_limit">
                                    <span class="help-block" v-if="error.attending_limit">{{error.attending_limit[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.price}">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar fa-lg" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Precio"
                                        v-model="eventEdit.price">
                                    <span class="help-block" v-if="error.price">{{error.price[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.place}">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker fa-lg" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Lugar"
                                        v-model="eventEdit.place">
                                    <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.date}">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></span>
                                    <input type="date" class="form-control" v-model="eventEdit.date">
                                    <span class="help-block" v-if="error.date">{{error.date[0]}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group" :class="{'has-error': error.start_time}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> De</span>
                                            <input type="time" class="form-control" v-model="eventEdit.start_time">
                                            <span class="help-block" v-if="error.start_time">{{error.start_time[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group" :class="{'has-error': error.end_time}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i> a</span>
                                            <input type="time" class="form-control" v-model="eventEdit.end_time">
                                            <span class="help-block" v-if="error.end_time">{{error.end_time[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary pull-left"
                                    v-if="user.hasPermission['update-events']"
                                    @click="updateEvent">Guardar</button>
                            <button type="button" class="btn btn-link pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-default"
                                    v-if="user.hasPermission['delete-events']"
                                    @click="destroyEvent">
                                <i class="fa fa-trash-o fa-lg"></i>
                            </button>
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
                user: Laravel.user,
                error: {},
                search: '',
                events: [],
                myevents: [],
                attends:[],
                checked:true,
                eventEdit: {},
            }
        },
        mounted() {
            
            this.getOwnEvents();
            this.getAttends();
            this.getAttendsByOwner();
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
            getAttendsByOwner (){
                axios.get('/events/attendingsByOwner')
                .then(response => {
                    this.events = response.data
                });
            }, 
            destroyEvent (id, index) {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Event!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/events/destroy/' + id)
                    .then(response => {
                        self.myevents.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The Event has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        $('#modalEditEvent').modal('hide');
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            },
            editEvent (item, index) {
                this.eventEdit = _.clone(item);
                this.eventEdit.index = index;
                this.eventEdit.images = [];

                $('#modalEditEvent').modal('show')

                axios.get('/events/'+item.id)
                .then(response => {
                    this.eventEdit.images = response.data.images;
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            updateEvent (e) {
                var btn = $(e.target).button('loading')
                axios.put('/events/update/'+this.eventEdit.id, this.eventEdit)
                .then(response => {
                    this.events[this.eventEdit.index] = response.data;
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalEditEvent').modal('hide')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            approveEvent (id_event, id_user) {
                axios.get('/events/approve/' + id_event + '/'+ id_user)
                .then(response => {
                    //this.eventEdit.images = response.data.images;
                    console.log(response);
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            },
            rejectEvent (id_event, id_user) {
                axios.get('/events/reject/' + id_event + '/'+ id_user)
                .then(response => {
                    //this.eventEdit.images = response.data.images;
                    console.log(response);
                })
                .catch(error => {
                    this.error = error.response.data;
                });
            }

        }
    }
</script>
