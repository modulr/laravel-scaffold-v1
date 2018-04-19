<template lang="html">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- User -->
            <div class="text-center">
                <img class="avatar-md" :src="user.avatar_url">
                <p class="lead">{{user.name}}</p>
                <small class="text-muted">{{user.email}}</small>
            </div>
        </div>
        <!-- Buttons -->
        <ul class="nav nav-pills nav-stacked">
            <li :class="{'active': activeLink == 'profile'}">
                <a :href="'/profile/'+user.id+'/edit'">
                    <i class="fa fa-fw fa-lg fa-user-circle-o" aria-hidden="true"></i> Perfil
                </a>
            </li>
            <li :class="{'active': activeLink == 'password'}">
                <a :href="'/profile/'+user.id+'/password/edit'">
                    <i class="fa fa-fw fa-lg fa-key" aria-hidden="true"></i> Cambiar contraseña
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import comingSoon from '../../../mixins/comingSoon'

    export default {
        mixins: [ comingSoon ],
        data () {
            return {
                activeLink: '',
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
        }
    }
</script>
