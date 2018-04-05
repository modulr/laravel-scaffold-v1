<template lang="html">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- User -->
            <div class="text-center">
                <vue-clip class="vue-clip-avatar"
                          :options="optionsAvatar"
                          :on-sending="sendingAvatar"
                          :on-complete="completeAvatar">
                    <template slot="clip-uploader-action">
                        <div>
                            <div class="dz-message">
                                <img class="avatar-md" :src="user.avatar_url">
                                <span class="fa-stack fa-lg text-primary">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-camera fa-stack-1x fa-inverse"></i>
                                </span>
                            </div>
                        </div>
                    </template>
                </vue-clip>
                <p class="lead">{{user.name}}</p>
                <small class="text-muted">{{user.email}}</small>
            </div>
        </div>
        <!-- Buttons -->
        <ul class="nav nav-pills nav-stacked">
            <li :class="{'active': activeLink == 'profile'}">
                <a :href="'/profile/'+user.id+'/edit'">
                    <i class="fa fa-fw fa-lg fa-user-circle-o" aria-hidden="true"></i> Profile
                </a>
            </li>
            <li :class="{'active': activeLink == 'work'}">
                <a :href="'/profile/'+user.id+'/work/edit'">
                    <i class="fa fa-fw fa-lg fa-briefcase" aria-hidden="true"></i> Work
                </a>
            </li>
            <li :class="{'active': activeLink == 'password'}">
                <a :href="'/profile/'+user.id+'/password/edit'">
                    <i class="fa fa-fw fa-lg fa-key" aria-hidden="true"></i> Password
                </a>
            </li>
            <li><a href="" @click.prevent="comingSoon"><i class="fa fa-fw fa-lg fa-sticky-note" aria-hidden="true"></i> Notes</a></li>
            <li><a href="" @click.prevent="comingSoon"><i class="fa fa-fw fa-lg fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>
    </div>
</template>

<script>
    import VueClip from 'vue-clip';
    import comingSoon from '../../../mixins/comingSoon'

    export default {
        mixins: [ comingSoon ],
        data () {
            return {
                error: {},
                activeLink: '',
                optionsAvatar: {
                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                    url: '/api/users/upload/avatar',
                    paramName: 'file',
                    parallelUploads: 1,
                    maxFilesize: {
                        limit: 10,
                        message: '{{filesize}} is greater than the {{maxFilesize}}MB'
                    },
                    maxFiles: {
                        limit: 10,
                        message: 'You can only upload a max of 10 files'
                    },
                    acceptedFiles: {
                        extensions: ['image/*'],
                        message: 'You are uploading an invalid file'
                    },
                },
            }
        },
        props:['user'],
        mounted() {
            var str = window.location.pathname;
            var res = str.split("/");

            if (res.length == 4) {
                this.activeLink = res[1];
            } else if (res.length == 5) {
                this.activeLink = res[3];
            }
        },
        methods: {
            sendingAvatar (file, xhr, formData) {
                formData.append('id', this.user.id)
            },
            completeAvatar (file, status, xhr) {
                if (status == 'success') {
                    this.user.avatar_url = JSON.parse(xhr.response).avatar_url
                    if (Laravel.user.id == this.user.id)
                        Laravel.user.avatar_url = JSON.parse(xhr.response).avatar_url
                }
            }
        }
    }
</script>
