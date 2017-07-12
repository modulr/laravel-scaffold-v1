<template>
    <div>
        <div class="media" v-for="notification in notifications">
          <div class="media-left">
            <a href="#">
              <img class="media-object" :src="notification.data.user.avatar">
            </a>
          </div>
          <div class="media-body">
            <h4 class="media-heading">{{ notification.data.user.name }}</h4>
            {{ notification.data.message }}
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
        img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
    }
</style>
