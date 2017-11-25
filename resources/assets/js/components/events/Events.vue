<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="lead">Cerca de ti</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar" v-model="search">
                        </div>
                    </div>
                    <div class="col-sm-4 text-right">
                        <span class="separator"></span>
                        <a href="#" class="btn btn-link">
                            <i class="fa fa-fw fa-lg fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Events -->
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3" v-if="events.length" v-for="(item, index) in events">
                    <div class="panel card">
                        <div class="panel-heading">
                            <a :href="'/platillos/'+item.id">
                                <img :src="item.images[0].url_thumbnail" v-if="item.images.length">
                            </a>
                        </div>
                        <div class="panel-body">
                            <img class="avatar-sm" :src="item.owner.avatar_url">
                            <h3>{{item.name}}</h3>
                            <p v-show="item.description">{{item.description}}</p>
                            <div v-show="item.date || item.start_time || item.end_time">
                                <i class="fa fa-clock-o fa-lg" aria-hidden="true"></i>
                                <span v-if="item.date">{{item.date | moment('LL')}}.</span>
                                <span v-if="item.start_time">
                                    <small class="text-muted">De </small>{{'2018-01-01 '+item.start_time | moment('h:mm a')}}
                                </span>
                                <span v-if="item.end_time">
                                    <small class="text-muted">a </small>{{'2018-01-01 '+item.end_time | moment('h:mm a')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Init Message -->
                <div class="init-message" v-else>
                    <i class="mdi mdi-event" aria-hidden="true"></i>
                    <p class="lead">Don't exist Events...</p>
                    <!-- <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalNewEvent"
                       v-if="user.hasPermission['create-events']">
                        <i class="fa fa-calendar-plus-o"></i> New Event
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                //user: Laravel.user,
                search: '',
                events: [],
            }
        },
        mounted() {
            this.getEvents();
        },
        methods: {
            getEvents () {
                axios.get('/events/all')
                .then(response => {
                    this.events = response.data.data
                });
            },
        }
    }
</script>
