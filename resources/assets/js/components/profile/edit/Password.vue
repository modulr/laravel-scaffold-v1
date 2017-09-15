<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Change password</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" @submit.prevent>
                <div class="form-group" :class="{'has-error': error.current}">
                    <label class="col-sm-3 control-label">Current password *</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control"
                            v-model="password.current">
                        <span class="help-block" v-if="error.current">{{error.current[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.password}">
                    <label class="col-sm-3 control-label">New password *</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control"
                            v-model="password.password">
                        <span class="help-block" v-if="error.password">{{error.password[0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error': error.password_confirmation}">
                    <label class="col-sm-3 control-label">Password confirmation *</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control"
                            v-model="password.password_confirmation">
                        <span class="help-block" v-if="error.password_confirmation">{{error.password_confirmation[0]}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-3">
                        <button type="button" class="btn btn-primary" @click="updatePassword">Change password</button>
                    </div>
                </div>
            </form>
        </div>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
import {Snotify} from 'vue-snotify';
import {SnotifyService} from 'vue-snotify';

Vue.use(Snotify)

export default {
    data() {
        return {
            password: {},
            error: {}
        }
    },
    props:['user'],
    methods: {
        updatePassword: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/user/updatePassword/'+this.user.id, this.password)
            .then(response => {
                this.password = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                SnotifyService.success('The password has been changed success!');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        }
    }
}
</script>
