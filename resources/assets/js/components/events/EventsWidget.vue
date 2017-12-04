<template>
    <div class="events">
        <div v-if="events.length">
            <div class="panel card" v-for="(event, index) in events" :key="event.id">
                <div class="panel-heading">
                    <a :href="'/events/'+event.id">
                        <img :src="event.images[0].url_thumbnail" v-if="event.images.length">
                    </a>
                </div>
                <div class="panel-body">
                    <a :href="`/profile/${event.creator.id}`">
                        <img class="avatar-sm" :src="event.creator.avatar_url">
                    </a>
                    <h4>{{event.name}}</h4>
                    <p><i class="fa fa-fw fa-map-marker fa-lg" aria-hidden="true"></i> {{event.place}}</p>
                    <div v-show="event.date || event.start_time || event.end_time">
                        <i class="fa fa-fw fa-clock-o fa-lg" aria-hidden="true"></i>
                        <span v-if="event.date">{{event.date | moment('LL')}}.</span>
                        <span v-if="event.start_time && !event.end_time">
                            <small class="text-muted">At </small>{{'2018-01-01 '+event.start_time | moment('h:mm a')}}
                        </span>
                        <span v-if="event.start_time && event.end_time">
                            <small class="text-muted">From </small>{{'2018-01-01 '+event.start_time | moment('h:mm a')}}
                            <small class="text-muted">to </small>{{'2018-01-01 '+event.end_time | moment('h:mm a')}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Init Message -->
        <div class="init-message" v-else>
            <i class="mdi mdi-event" aria-hidden="true"></i>
            <p class="lead">Don't exist Events!!</p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                error: {},
                events: []
            }
        },
        mounted() {
            this.getEvents();
        },
        methods: {
            getEvents () {
                axios.get('/api/events/all')
                .then(response => {
                    this.events = response.data.data
                });
            }
        }
    }
</script>
