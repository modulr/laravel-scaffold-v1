<template>
    <div class="comments">
        <div class="form-group comment-input" :class="{'has-error': errors.comment}">
            <input type="text" class="form-control" placeholder="Write a comment..."
                v-model="comment" @keyup.enter="storeComment">
            <span class="help-block" v-if="errors.comment">{{errors.comment[0]}}</span>
        </div>
        <div class="comments-list">
            <div class="media comment" v-for="comment in this.comments">
                <div class="media-left">
                    <img class="avatar-xs" :src="comment.creator.avatar_url">
                </div>
                <div class="media-body">
                    <strong>{{comment.creator.name}}</strong>
                    <small class="text-muted pull-right">
                        {{comment.created_at | moment('from')}}
                    </small>
                    <br>
                    <span>{{comment.comment}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            comment: '',
            errors: {},
            url: null,
            id: null
        }
    },
    props: {
        model: {
            type: String
        },
        itemId: {
            type: Number
        },
        comments: {
            type: Array
        }
    },
    mounted () {
        if (this.model == 'autopart') {
            this.url = '/api/autoparts/comments/store'
            this.id = 'autopart_id'
        }
    },
    methods: {
        storeComment () {
            var comment = {
                comment: this.comment
            }
            comment[this.id] = this.itemId

            axios.post(this.url, comment)
            .then(response => {
                this.comments.unshift(response.data)
                this.comment = ''
                this.errors = {}
            })
            .catch(error => {
                this.errors = error.response.data
            })
        }
    }
}
</script>
