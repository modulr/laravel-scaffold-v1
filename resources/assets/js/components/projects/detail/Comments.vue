<template>
    <div class="comments">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Comments</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <img :src="user.avatar_url" class="avatar-sm pull-left">
                                    </div>
                                    <div class="form-group" :class="{'has-error': error.comment}">
                                        <div class="col-sm-10">
                                            <textarea class="form-control" required v-model="comment.comment" placeholder="Write a comment..."></textarea>
                                            <span class="help-block" v-if="error.comment">{{error.comment[0]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row comment-input">
                                    <span>
                                        <button class="btn-primary pull-right" @click.prevent="addComment">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div v-if="comments.length == 0">
                        <p>No comments found.</p>
                    </div>
                    <div v-else class="panel panel-default" v-for="(item, index) in filteredComments">
                        <div class="panel-body">
                            <div class="col-xs-2">
                                <img class="avatar-sm" :src="item.user.avatar_url">
                            </div>
                            <div class="col-xs-10">
                                <span>
                                    <strong>{{item.user.name}}</strong>
                                </span>
                                -
                                <span>
                                    {{item.created_at | moment('from')}}
                                </span>
                                <p :class="{'lead': item.comment.length <= 50}" v-if="item.comment">{{item.comment}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['comments', 'project'],
        data() {
            return {
                user: Laravel.user,
                comment: {},
                error: {}
            }
        },
        computed: {
            filteredComments: function() {
                var filteredArray = this.comments;

                filteredArray = filteredArray.reverse(item => {
                    return item;
                });

                return filteredArray;
            }
        },
        methods: {
            addComment(e) {
                var btn = $(e.target).button('loading');
                axios.post('/comments/store/' + this.project.id, this.comment)
                    .then(response => {
                        this.comments.push(response.data);
                        this.comment = '';
                        this.error = {};
                        var btn = $(e.target).button('reset')
                    })
                    .catch(error => {
                        this.error = error.response.data;
                        var btn = $(e.target).button('reset')
                    });
            }
        },
        mounted() {
        }
    }
</script>