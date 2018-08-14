<template lang="html">
    <div class="panel panel-default">
        <div class="panel-body">
            <a :href="`/profile/${user.id}/edit`" class="btn btn-link btn-edit"
                v-if="(authUser.hasPermission['update-profile'] && authUser.id == user.id) || authUser.hasPermission['create-profile']">
                <i class="fa fa-lg fa-pencil" aria-hidden="true"></i>
            </a>
            <div class="text-center">
                <img class="avatar-md" :src="user.avatar_url">
                <p class="lead">{{user.name}}</p>
                <small class="text-muted">{{user.email}}</small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                authUser: Laravel.user,
                activeLink: '',
            }
        },
        props:['user'],
        mounted() {
            var str = window.location.pathname;
            var res = str.split("/");

            if (res.length == 3) {
                this.activeLink = res[1];
            } else if (res.length == 4) {
                this.activeLink = res[3];
            }
        }
    }
</script>
