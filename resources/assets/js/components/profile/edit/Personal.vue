<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Personal</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" @submit.prevent>
                <div class="form-group" :class="{'has-error': error.birthday}">
                    <label class="col-md-3 control-label">Birthday</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control"
                            v-model="user.profile_personal.birthday">
                        <span class="help-block" v-if="error.birthday">{{error.birthday[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.place_of_birth}">
                    <label class="col-md-3 control-label">Place of birth</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control"
                            v-model="user.profile_personal.place_of_birth">
                        <span class="help-block" v-if="error.place_of_birth">{{error.place_of_birth[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.gender_id}">
                    <label class="col-md-3 control-label">Gender</label>
                    <div class="col-md-9">
                        <select class="form-control text-capitalize"
                            v-model="user.profile_personal.gender_id">
                            <option v-for="option in list.genders" :value="option.id">
                                {{ option.title }}
                            </option>
                        </select>
                        <span class="help-block" v-if="error.gender_id">{{error.gender_id[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.relationship_id}">
                    <label class="col-md-3 control-label">Relationship</label>
                    <div class="col-md-9">
                        <select class="form-control text-capitalize"
                            v-model="user.profile_personal.relationship_id">
                            <option v-for="option in list.relationships" :value="option.id">
                                {{ option.title }}
                            </option>
                        </select>
                        <span class="help-block" v-if="error.relationship_id">{{error.relationship_id[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.rfc}">
                    <label class="col-md-3 control-label">RFC</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control"
                            v-model="user.profile_personal.rfc">
                        <span class="help-block" v-if="error.rfc">{{error.rfc[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.curp}">
                    <label class="col-md-3 control-label">CURP</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control"
                            v-model="user.profile_personal.curp">
                        <span class="help-block" v-if="error.curp">{{error.curp[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.nss}">
                    <label class="col-md-3 control-label">NSS</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control"
                            v-model="user.profile_personal.nss">
                        <span class="help-block" v-if="error.nss">{{error.nss[0]}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <button type="button" class="btn btn-primary" @click="updatePersonal">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {Snotify} from 'vue-snotify';
import {SnotifyService} from 'vue-snotify';
Vue.use(Snotify)
export default {
    data() {
        return {
            list: {
                genders: [],
                relationships: []
            },
            error: {}
        }
    },
    props: ['user'],
    mounted() {
        axios.get('/list/gender')
        .then(response => {
            this.list.genders = response.data;
        });

        axios.get('/list/relationship')
        .then(response => {
            this.list.relationships = response.data;
        });
    },
    methods: {
        updatePersonal: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/profile/personal/update/'+this.user.id, this.user.profile_personal)
            .then(response => {
                this.user.profile_personal = response.data;
                this.error = {};
                var btn = $(e.target).button('reset')
                SnotifyService.success('The profile has been saved success!')
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        }
    }
}
</script>
