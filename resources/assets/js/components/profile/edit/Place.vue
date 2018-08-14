<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ubicación</h3>
        </div>
        <div class="panel-body">
            <dl v-for="(place, index) in user.profile_place">
                <a href="#" class="btn btn-link pull-right" @click.prevent="destroyPlace(place.id, index)">
                    <i class="fa fa-trash"></i>
                </a>
                <dt>
                    {{place.place}}
                    <i class="fa fa-check text-success" aria-hidden="true"
                        data-toggle="tooltip" title="Currently"
                        v-show="place.currently"></i>
                </dt>
                <dd v-show="place.description"><span class="text-muted">{{place.description}}</span></dd>
            </dl>
            <hr>
            <form @submit.prevent>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.place}">
                            <input type="text" class="form-control" placeholder="CDMX"
                                v-model="place.place">
                            <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="place.currently"> Actual
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" @click="storePlace">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert'

export default {
    data() {
        return {
            place: {},
            error: {}
        }
    },
    props: ['user'],
    methods: {
        storePlace: function (e) {
            var btn = $(e.target).button('loading')
            this.place.user_id = this.user.id;
            if(!this.place.currently)
                this.place.currently = 0;
            axios.post('/api/profile/place/store', this.place)
            .then(response => {
                this.user.profile_place.push(response.data);
                this.place = {};
                this.error = {};
                var btn = $(e.target).button('reset')
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyPlace: function (placeId, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this place!",
                icon: "warning",
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/profile/place/destroy/' + placeId)
                    .then(response => {
                        self.user.profile_place.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The place has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        });
                        self.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                }
            });
        }
    }
}
</script>
