<template>
    <div>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header pull-left">
                    <a class="navbar-brand" href="/dashboard">
                        <img src="/img/logos/stb.png" alt="">
                    </a>
                </div>
                <ul class="nav navbar-nav pull-right">
                    <!-- Menu -->
                    <!-- <li class="dropdown menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-th fa-lg" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right list-inline">
                            <li :class="{'active': activeLink == 'dashboard'}">
                                <a href="/dashboard">
                                    <i class="mdi mdi-dashboard mdi-3x"></i>
                                    <br>
                                    <span>Inicio</span>
                                </a>
                            </li>
                            <li :class="{'active': activeLink == 'platillos'}" v-if="user.hasPermission['read-news']">
                                <a href="/platillos">
                                    <i class="mdi mdi-local-dining mdi-3x"></i>
                                    <br>
                                    <span>Quiero comer</span>
                                </a>
                            </li>
                            <li :class="{'active': activeLink == 'mis_platillos'}" v-if="user.hasPermission['read-tasks']">
                                <a href="/mis_platillos">
                                    <i class="mdi mdi-room-service mdi-3x"></i>
                                    <br>
                                    <span>Quiero cocinar</span>
                                </a>
                            </li>
                            <hr>
                            <li :class="{'active': activeLink == 'users'}" v-if="user.hasPermission['read-users']">
                                <a href="/users">
                                    <i class="mdi mdi-people mdi-3x"></i>
                                    <br>
                                    <span>Usuarios</span>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- Notifications -->
                    <li class="dropdown notifications">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" @click="markAsRead">
                            <i class="fa fa-bell fa-lg"></i>
                            <span class="label label-danger" v-show="unReadNotifications.length>0">{{unReadNotifications.length}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="notifications" v-for="item in notifications">
                                    <div class="media">
                                        <div class="media-left">
                                            <img :src="item.data.user.avatar_url">
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading">{{item.data.user.name}}</h6>
                                            {{item.data.message.title}}
                                        </div>
                                        <div class="media-right" v-if="item.data.message.data.type == 2">
                                            <img class="media-object" :src="item.data.message.data.images[0].url">
                                        </div>
                                    </div>
                                    <hr>
                                </a>
                                <!-- Init Message  -->
                                <div class="init-message" v-if="notifications.length == 0">
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    <p class="lead">No existen notificaciones</p>
                                </div>
                            </li>
                            <li class="text-center">
                                <a href="/notifications"><small>Ver todas las notificaciones</small></a>
                            </li>
                        </ul>
                    </lli>
                    <!-- User -->
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img :src="user.avatar_url" alt="">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li class="dropdown-header">
                                <a :href="'/profile/'+user.id">
                                    <div class="media">
                                        <div class="media-left">
                                            <img :src="user.avatar_url" class="img-xs">
                                        </div>
                                        <div class="media-body">
                                            <p class="lead">{{user.name}}</p>
                                            <br>
                                            <span>{{user.email}}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Comensal</li>
                            <li>
                                <a href="/platillos">Quiero comer</a>
                            </li>
                            <li>
                                <a href="/reservaciones">Reservaciones</a>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Cocinero</li>
                            <li>
                                <a href="/mis_platillos">Quiero cocinar</a>
                            </li>
                            <li>
                                <a href="/mis_reservaciones">Recervaciones</a>
                            </li>
                            <li>
                                <a href="#" @click.prevent="logout">
                                    <i class="fa fa-fw fa-sign-out"></i> Salir
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                activeLink: '',
                user: Laravel.user,
                notifications: Laravel.notifications,
                unReadNotifications: Laravel.unReadNotifications,
            }
        },
        mounted() {
            var str = window.location.pathname;
            var res = str.split("/");

            if (res.length == 2) {
                this.activeLink = res[1];
            }

            Notification.requestPermission();

            Echo.private('App.User.' + this.user.id)
                .notification((e) => {

                    this.notifications.unshift({data:e});
                    this.unReadNotifications.unshift({data:e});

                    if (Notification.permission === "granted") {
                        var options = {
                            body: e.message.title,
                            icon: e.user.avatar_url,
                        }
                        if (e.message.data.type == 2) {
                            options.image = e.message.data.images[0].url;
                        }
                        new Notification(e.user.name, options);
                    }
                });

            // Echo.channel('news')
            //     .listen('StatusLiked', (e) => {
            //         this.notifications.push(e.message);
            //
            //         if (Notification.permission === "granted") {
            //             var options = {
            //                 body: e.message.message.title,
            //             }
            //             var notification = new Notification(e.message.user.name, options);
            //         }
            //     });
        },
        methods: {
            logout: function () {
                axios.post('/logout')
                .then(response => {
                    location.href = '/';
                });
            },
            markAsRead: function () {
                axios.get('/dashboard/markAsRead')
                .then(response => {
                    this.unReadNotifications = [];
                });
            }
        }
    }
</script>
