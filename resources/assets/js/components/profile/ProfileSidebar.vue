<template lang="html">
    <div>
        <div class="text-center">
            <img :src="user.avatar_url" class="img-circle">
            <p class="lead">{{user.name}}</p>
            <small class="text-muted">Software Architect</small>
        </div>
        <hr>
        <ul class="nav nav-pills nav-stacked">
            <li :class="{'active': currentPath == 'profile'}">
                <a :href="'/profile/'+user.id">
                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Information
                </a>
            </li>
            <li><a href="" data-toggle="tab" @click.prevent="comingSoon"><i class="fa fa-fw fa-briefcase" aria-hidden="true"></i> Work</a></li>
            <li><a href="" data-toggle="tab" @click.prevent="comingSoon"><i class="fa fa-fw fa-sticky-note" aria-hidden="true"></i> Notes</a></li>
            <li :class="{'active': currentPath == 'notifications'}">
                <a :href="'/profile/notifications/'+user.id">
                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Notifications
                </a>
            </li>
            <li><a href="" data-toggle="tab" @click.prevent="comingSoon"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> Documents</a></li>
            <li><a href="" data-toggle="tab" @click.prevent="comingSoon"><i class="fa fa-fw fa-cog" aria-hidden="true"></i> Settings</a></li>
        </ul>
    </div>
</template>

<script>
import comingSoon from '../../mixins/comingSoon'
export default {
    data () {
        return {
            currentPath: ''
        }
    },
    props: [
        'user'
    ],
    mixins: [ comingSoon ],
    mounted() {
        var str = window.location.pathname;
        var res = str.split("/");

        if (res.length == 3) {
            this.currentPath = res[1];
        } else if (res.length == 4) {
            this.currentPath = res[2];
        }
    }
}
</script>
