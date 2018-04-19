<template lang="html">
    <div class="activity">
        <div class="media" v-if="item.editor">
            <div class="media-left">
                <img class="avatar-xs" :src="item.editor.avatar_url">
            </div>
            <div class="media-body">
                <strong>{{item.editor.name}}</strong>
                <small class="text-muted pull-right">
                    {{item.updated_at | moment('lll')}}
                </small>
                <br>
                <span>Last updated</span>
            </div>
        </div>
        <div class="media" v-for="activity in item.activity">
            <div class="media-left">
                <img class="avatar-xs" :src="activity.user.avatar_url">
            </div>
            <div class="media-body">
                <strong>{{activity.user.name}}</strong>
                <small class="text-muted pull-right">
                    {{activity.created_at | moment('lll')}}
                </small>
                <br>
                <span>{{activity.activity}}</span>
            </div>
        </div>
        <div class="media">
            <div class="media-left">
                <img class="avatar-xs" :src="item.creator.avatar_url">
            </div>
            <div class="media-body">
                <strong>{{item.creator.name}}</strong>
                <small class="text-muted pull-right">
                    {{item.created_at | moment('lll')}}
                </small>
                <br>
                <span>Created</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            type: Object
        }
    }
}
</script>
