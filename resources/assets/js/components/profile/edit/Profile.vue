<template lang="html">
    <div class="row">
        <div class="col-sm-3 profile-sidebar">
            <profile-sidebar-edit :user="usr"></profile-sidebar-edit>
        </div>
        <div class="col-sm-9 profile-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Profile</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" @submit.prevent>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">User ID</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">{{user.id}}</p>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.name}">
                                    <label class="col-md-3 control-label">Name *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control"
                                            v-model="user.name">
                                        <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group" :class="{'has-error': error.email}">
                                    <label class="col-md-3 control-label">E-mail *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control"
                                            v-model="user.email">
                                        <span class="help-block" v-if="error.email">{{error.email[0]}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-9 col-md-offset-3">
                                        <button type="button" class="btn btn-primary" @click="updateProfile">Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 text-center">
                                <img :src="usr.avatar_url" class="img-circle"><br>
                                <vue-core-image-upload
                                    class="btn btn-link"
                                    @imageuploaded="uploadAvatar"
                                    extensions="png,jpeg,jpg"
                                    :headers=headers
                                    :url="'/user/upload/avatar/'+user.id">
                                </vue-core-image-upload>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- personal -->
            <profile-personal-edit :user="user"></profile-personal-edit>
            <!-- contact -->
            <profile-contact-edit :user="user" id="contact"></profile-contact-edit>
            <!-- education -->
            <profile-education-edit :user="user" id="education"></profile-education-edit>
            <!-- family -->
            <profile-family-edit :user="user" id="family"></profile-family-edit>
            <!-- places -->
            <profile-place-edit :user="user" id="place"></profile-place-edit>
        </div>

    </div>
</template>

<script>
import ProfileSidebarEdit from './Sidebar.vue';
import ProfilePersonalEdit from './Personal.vue';
import ProfileContactEdit from './Contact.vue';
import ProfileEducationEdit from './Education.vue';
import ProfileFamilyEdit from './Family.vue';
import ProfilePlaceEdit from './Place.vue';

import VueCoreImageUpload from 'vue-core-image-upload';

import {Snotify} from 'vue-snotify';
import {SnotifyService} from 'vue-snotify';
Vue.use(Snotify)

export default {
    components: {
        'vue-core-image-upload': VueCoreImageUpload
    },
    data() {
        return {
            usr: {},
            headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
            error: {}
        }
    },
    props:['user'],
    mounted(){
        this.usr = _.clone(this.user);
    },
    methods: {
        updateProfile: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/user/update/'+this.user.id, this.user)
            .then(response => {
                this.user.name = response.data.name;
                this.user.email = response.data.email;
                this.usr.name = response.data.name;
                this.usr.email = response.data.email;
                if (this.user.id == Laravel.user.id) {
                    Laravel.user.name = response.data.name;
                    Laravel.user.email = response.data.email;
                }
                this.error = {};
                var btn = $(e.target).button('reset')
                SnotifyService.success('The profile has been saved success!');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        uploadAvatar(response) {
            this.user.avatar = response.avatar;
            this.user.avatar_url = response.avatar_url;
            this.usr.avatar = response.avatar;
            this.usr.avatar_url = response.avatar_url;
            if (this.user.id == Laravel.user.id) {
                Laravel.user.avatar = response.avatar;
                Laravel.user.avatar_url = response.avatar_url;
            }
        }
    }
}
</script>
