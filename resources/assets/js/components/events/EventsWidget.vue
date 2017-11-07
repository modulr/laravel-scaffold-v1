<template>
    <div class="events">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <!-- List -->
                    <table class="table table-hover" v-if="events.length">
                        <tbody>
                            <tr v-for="(item, index) in events" >
                                <td @click="viewEvent(item, index)">
                                    <div class="media">
                                        <div class="media-body">
                                            <p>{{item.name}}</p>
                                            <div class="event-info">
                                                <small v-if="item.date">{{item.date | moment('LL')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Init Message -->
                    <div class="init-message" v-else>
                        <i class="mdi mdi-event" aria-hidden="true"></i>
                        <p class="lead">Don't exist Events... create one!!</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal View Event -->
        <div class="modal right fade" data-backdrop="false" id="modalViewEvent">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Event</h4>
                    </div>
                    <div class="modal-body">
                        <dl>
                            <dd>Name</dd>
                            <dt><p class="lead">{{eventView.name}}</p></dt>
                        </dl>
                        <dl>
                            <dd>Description</dd>
                            <dt>{{eventView.description}}</dt>
                        </dl>
                        <dl>
                            <dd>Place</dd>
                            <dt>{{eventView.place}}</dt>
                        </dl>
                        <dl>
                            <dd>Date</dd>
                            <dt><small v-if="eventView.date">{{eventView.date | moment('LL')}}.</small></dt>
                        </dl>
                        <dl>
                            <dd>From</dd>
                            <dt>
                                <span v-if="eventView.start_time">
                                    {{eventView.date+' '+eventView.start_time | moment('h:mm a')}}
                                </span>
                            </dt>
                        </dl>
                        <dl>
                            <dd>To</dd>
                            <dt>
                                <span v-if="eventView.end_time">
                                    {{eventView.date+' '+eventView.end_time | moment('h:mm a')}}
                                </span>
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                error: {},
                events: [],
                eventView: {},
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
            viewEvent (item, index) {
                this.eventView = _.clone(item);
                $('#modalViewEvent').modal('show')
            },
        }
    }
</script>
