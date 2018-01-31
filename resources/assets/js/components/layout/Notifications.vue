<template>
    <div>
        <!-- List -->
        <li v-for="notification in notifications">
            <a :href="notification.data.message.url">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object avatar-xs" :src="notification.data.message.userAvatarUrl">
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">{{ notification.data.message.userName }} <span>{{ notification.data.message.title }}</span></h6>
                        <small class="text-muted">{{ notification.created_at | moment('from') }}</small>
                    </div>
                    <div class="media-right" v-if="notification.data.message.imageUrl">
                        <img class="media-object" :src="notification.data.message.imageUrl">
                    </div>
                </div>
            </a>
        </li>

        <!-- Init Message  -->
        <div class="init-message" v-if="notifications.length == 0">
            <i class="fa fa-bell" aria-hidden="true"></i>
            <p class="lead">Don't have notifications</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notifications: Laravel.notifications
        }
    }
}
</script>
