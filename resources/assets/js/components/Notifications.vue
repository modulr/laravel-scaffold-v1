<template>
    <div class="notifications">
        <!-- List -->
        <a href="notifications" v-for="item in notifications">
            <div class="media">
                <div class="media-left">
                    <img class="media-object" :src="item.data.user.avatar">
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ item.data.user.name }}</h4>
                    {{ item.data.message.title }}
                </div>
                <div class="media-right" v-if="item.data.message.data.type == 2">
                    <img class="media-object" :src="item.data.message.data.images[0].url">
                </div>
            </div>
            <hr>
        </a>

        <!-- Init Message  -->
        <div class="panel panel-default" v-if="notifications.length == 0">
            <div class="panel-body text-center">
                <br>
                <i class="fa fa-bell fa-5x text-muted" aria-hidden="true"></i>
                <p class="lead text-muted">Don't have notifications</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notifications: Laravel.notifications,
        }
    }
}
</script>

<style lang="scss">
.media {
    .media-left{
        img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
    }
    .media-right{
        img {
            width: 70px;
            height: 70px;
        }
    }
}
</style>
