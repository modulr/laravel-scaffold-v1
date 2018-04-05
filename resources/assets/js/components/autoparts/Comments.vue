<template>
  <div class="comments">
    <div class="card" v-for="(item, index) in filteredComments" :key="item.id">
      <div class="comment-top">
          <div class="comment-image">
            <span class="image">
              <img class="avatar-xs" :src="item.creator.avatar_url" alt="Generic placeholder image">
            </span>
          </div>
          <div class="comment-author">
            <h5 class="comment-heading">{{item.creator.name}}</h5>
            <span class="comment-date"><small>{{item.created_at | moment('from')}}</small></span>
          </div>
      </div>
      <div class="comment-body">
          <span class="lead">
              {{item.comment}}
          </span>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from './event-bus'
export default {
  props: ['autopart'],
  computed: {
    filteredComments () {
      var filteredComments = this.autopart.data.comments
      return _.reverse(filteredComments)
    }
  },
  mounted () {
    EventBus.$on('add-comment', (comment) => {
      this.storeComment(comment)
    })
  },
  data () {
    return {
      form: {
        comment: null,
        autopart_id: null,
      }
    }
  },
  methods: {
    storeComment (comment) {
      this.form.comment = comment
      this.form.autopart_id = this.autopart.data.id
      axios.post('/api/autoparts/comments/store', this.form)
      .then((response) => {
          this.$parent.autopart.data.comments.push(response.data);
          this.form = {}
      })
      .catch((err) => {
          console.log(err)
      })
    }
  }
}
</script>