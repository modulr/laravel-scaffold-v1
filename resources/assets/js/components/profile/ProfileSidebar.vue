<template lang="html">
    <div>
        <!-- User -->
        <div class="text-center">
            <div class="avatar-upload">
                <img :src="user.avatar_url" class="img-circle">
                <vue-core-image-upload
                    class="btn-upload"
                    @imageuploaded="uploadAvatar"
                    extensions="png,jpeg,jpg"
                    :headers=headers
                    :url="'/user/upload/avatar/'+user.id">
                    <i class="fa fa-camera fa-inverse fa-3x" aria-hidden="true"></i>
                </vue-core-image-upload>
            </div>
            <p class="lead">{{user.name}}</p>
            <small class="text-muted">{{user.profile_work.position.title}}</small>
        </div>
        <hr>
        <!-- Buttons -->
        <ul class="nav nav-pills nav-stacked">
            <li :class="{'active': activeLink == 'profile'}">
                <a :href="'/profile/'+user.id">
                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Information
                </a>
            </li>
            <li :class="{'active': activeLink == 'work'}">
                <a :href="'/profile/work/'+user.id">
                    <i class="fa fa-fw fa-briefcase" aria-hidden="true"></i> Work
                </a>
            </li>
            <li><a href="" @click.prevent="comingSoon"><i class="fa fa-fw fa-sticky-note" aria-hidden="true"></i> Notes</a></li>
            <li :class="{'active': activeLink == 'notifications'}">
                <a :href="'/profile/notifications/'+user.id">
                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Notifications
                </a>
            </li>
            <li><a href="" @click.prevent="comingSoon"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Documents</a></li>
            <li><a href="" @click.prevent="comingSoon"><i class="fa fa-fw fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>
    </div>
</template>

<script>
import VueCoreImageUpload from 'vue-core-image-upload';
import comingSoon from '../../mixins/comingSoon'
export default {
    data () {
        return {
            user: this.usr,
            activeLink: '',
            headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
        }
    },
    props: [
        'usr'
    ],
    components: {
        'vue-core-image-upload': VueCoreImageUpload,
    },
    mixins: [ comingSoon ],
    mounted() {
        var str = window.location.pathname;
        var res = str.split("/");

        if (res.length == 3) {
            this.activeLink = res[1];
        } else if (res.length == 4) {
            this.activeLink = res[2];
        }
    },
    methods: {
        uploadAvatar(response) {
            this.user.avatar = response.avatar;
            this.user.avatar_url = response.avatar_url;

            if (this.user.id == Laravel.user.id) {
                Laravel.user.avatar_url = response.avatar_url;
            }
        }
    }
}
</script>
