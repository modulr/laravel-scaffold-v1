<template>
    <div class="">
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="row">
                    <!-- Logo -->
                    <div class="col-xs-6">
                        <!-- <div class="dropdown menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <div class="menu-content">
                                    <div class="row text-center">
                                        <li class="col-xs-3" v-tooltip:bottom="'Dashboard'" :class="{'active': activeLink == 'dashboard'}">
                                            <a href="/dashboard">
                                                <i class="mdi mdi-dashboard mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'News'" :class="{'active': activeLink == 'news'}">
                                            <a href="/news">
                                                <i class="mdi mdi-poll mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Tasks'" :class="{'active': activeLink == 'tasks'}">
                                            <a href="/tasks">
                                                <i class="mdi mdi-assignment mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Files'" :class="{'active': activeLink == 'files'}">
                                            <a href="/files">
                                                <i class="mdi mdi-folder mdi-3x"></i>
                                            </a>
                                        </li>
                                    </div>
                                    <div class="row text-center">
                                        <li class="col-xs-3" v-tooltip:bottom="'Contacts'" :class="{'active': activeLink == 'contacts'}">
                                            <a href="/contacts">
                                                <i class="mdi mdi-contacts mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Opportunities'" :class="{'active': activeLink == 'opportunities'}">
                                            <a href="/opportunities">
                                                <i class="mdi mdi-turned-in mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Quotes'" :class="{'active': activeLink == 'quote'}">
                                            <a href="/quote">
                                                <i class="mdi mdi-receipt mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Clients'" :class="{'active': activeLink == 'clients'}">
                                            <a href="/clients">
                                                <i class="mdi mdi-call mdi-3x"></i>
                                            </a>
                                        </li>
                                    </div>
                                    <div class="row text-center">
                                        <li class="col-xs-3" v-tooltip:bottom="'Employees'" :class="{'active': activeLink == 'employees'}">
                                            <a href="/employees">
                                                <i class="mdi mdi-group-add mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" v-tooltip:bottom="'Users'" :class="{'active': activeLink == 'users'}">
                                            <a href="/users">
                                                <i class="mdi mdi-people mdi-3x"></i>
                                            </a>
                                        </li>
                                    </div>
                                </div>
                                <div class="menu-bottom">
                                    <div class="divider"></div>
                                    <div class="row text-center">
                                        <li class="col-xs-12" v-tooltip:bottom="'Sign out'">
                                            <a href="#" @click.prevent="logout">
                                                <i class="mdi mdi-exit-to-app mdi-2x"></i>
                                            </a>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        </div>
                        <a href="/dashboard">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="-527.2 390.3 55.9 29.6" enable-background="new -527.2 390.3 55.9 29.6" xml:space="preserve">
                              <g>
                                <path fill="#feae3b" d="M-489.6,399.1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7
                                C-490.6,398.6-490.1,399.1-489.6,399.1z"/>
                                <path fill="#feae3b" d="M-510.9,399.1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7
                                C-511.9,398.6-511.5,399.1-510.9,399.1z"/>
                                <path fill="#feae3b" d="M-526.2,399.1h11.4c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-11.4c-0.6,0-1,0.5-1,1v6.7
                                C-527.2,398.6-526.7,399.1-526.2,399.1z"/>
                                <path fill="#feae3b" d="M-472.2,411.2h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7
                                C-471.2,411.7-471.7,411.2-472.2,411.2z"/>
                                <path fill="#feae3b" d="M-493.6,411.2h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7
                                C-492.6,411.7-493,411.2-493.6,411.2z"/>
                                <path fill="#feae3b" d="M-514.8,411.2h-11.4c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h11.4c0.6,0,1-0.5,1-1v-6.7
                                C-513.7,411.7-514.2,411.2-514.8,411.2z"/>
                                <path fill="#feae3b" d="M-508.8,409.6c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1h-17.3c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1H-508.8z"
                                />
                                <path fill="#feae3b" d="M-504.7,400.8c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h17.3c0.6,0,1-0.5,1-1v-6.7c0-0.6-0.5-1-1-1H-504.7z"/>
                                <path fill="#feae3b" d="M-472.2,400.8h-11.4c-0.6,0-1,0.5-1,1v6.7c0,0.6,0.5,1,1,1h11.4c0.6,0,1-0.5,1-1v-6.7
                                C-471.2,401.3-471.7,400.8-472.2,400.8z"/>
                              </g>
                            </svg>
                        </a>
                        <span class="breadcrumbs hidden-xs">{{breadcrumb}}</span>
                    </div>
                    <div class="col-xs-6 text-right">
                        <!-- Menu -->
                        <div class="dropdown menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-th fa-lg" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <div class="menu-content">
                                    <div class="row text-center">
                                        <li class="col-sm-3" v-tooltip:bottom="'Dashboard'" :class="{'active': activeLink == 'dashboard'}">
                                            <a href="/dashboard">
                                                <i class="mdi mdi-dashboard mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-sm-3" v-tooltip:bottom="'News'" :class="{'active': activeLink == 'news'}">
                                            <a href="/news">
                                                <i class="mdi mdi-poll mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-sm-3" v-tooltip:bottom="'Tasks'" :class="{'active': activeLink == 'tasks'}">
                                            <a href="/tasks">
                                                <i class="mdi mdi-assignment mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-sm-3" v-tooltip:bottom="'Files'" :class="{'active': activeLink == 'files'}">
                                            <a href="/files">
                                                <i class="mdi mdi-folder mdi-3x"></i>
                                            </a>
                                        </li>
                                    </div>
                                    <div class="row text-center">
                                        <li class="col-sm-3" v-tooltip:bottom="'Contacts'" :class="{'active': activeLink == 'contacts'}">
                                            <a href="/contacts">
                                                <i class="mdi mdi-contacts mdi-3x"></i>
                                            </a>
                                        </li>
                                        <li class="col-sm-3" v-tooltip:bottom="'Users'" :class="{'active': activeLink == 'users'}">
                                            <a href="/users">
                                                <i class="mdi mdi-people mdi-3x"></i>
                                            </a>
                                        </li>
                                    </div>
                                </div>
                                <div class="menu-bottom">
                                    <div class="divider"></div>
                                    <div class="row text-center">
                                        <li class="col-sm-12" v-tooltip:bottom="'Log out'">
                                            <a href="#" @click.prevent="logout">
                                                <i class="mdi mdi-exit-to-app mdi-2x"></i>
                                            </a>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        </div>
                        <!-- Notifications -->
                        <div class="dropdown notifications">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" @click="markAsRead">
                                <i class="fa fa-bell fa-lg"></i>
                                <span class="label label-danger" v-show="unReadNotifications.length>0">{{unReadNotifications.length}}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <div class="panel-default">
                                    <div class="panel-body">
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
                                        <div class="text-center" v-if="notifications.length == 0">
                                            <br>
                                            <i class="fa fa-bell fa-5x text-muted" aria-hidden="true"></i>
                                            <p class="lead text-muted">Don't have notifications</p>
                                        </div>
                                    </div>
                                    <div class="panel-footer text-center">
                                        <a href="/notifications"><small>View all notifications</small></a>
                                    </div>
                                </div>
                            </ul>
                        </div>
                        <!-- User -->
                        <div class="dropdown user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <img :src="user.avatar_url" alt="">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li class="dropdown-header">
                                    <span class="lead">{{user.name}}</span>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a :href="'/profile/'+user.id">
                                        <i class="fa fa-fw fa-user-circle-o"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a :href="'/profile/'+user.id+'/edit'">
                                        <i class="fa fa-fw fa-pencil"></i> Edit profile
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" @click.prevent="logout">
                                        <i class="fa fa-fw fa-sign-out"></i> Sign out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
             title: binding.value,
             placement: binding.arg,
             trigger: 'hover'
         })
    })
    export default {
        data() {
            return {
                activeLink: '',
                user: Laravel.user,
                notifications: Laravel.notifications,
                unReadNotifications: Laravel.unReadNotifications,
            }
        },
        components() {
            Tooltip
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

                    console.log(e);
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
        props: [
            'breadcrumb'
        ],
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
                    console.log(response);
                    this.unReadNotifications = [];
                });
            }
        }
    }
</script>
