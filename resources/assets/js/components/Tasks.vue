<template>
  <div>
      <!--Form -->
      <form v-on:submit.prevent="addTask">
          <input type="text" class="form-control" v-model="tasks.title" placeholder="Add new task">
      </form>
      <hr>
      <!-- Lista -->
      <ul class="list-group">
        <draggable v-model="tasks" @start="drag=true" @end="drag=false">
          <li class="list-group-item" v-for="task in tasks">
            <i class="fa fa-lg fa-circle-o" aria-hidden="true" v.model="task.done" type="checkbox"></i>
            {{task.title}}
            <button type="button" class="close" aria-label="Close" @click="deleteTask(task)">x</button>
          </li>
        </draggable>
      </ul>
  </div>
</template>

<!-- Scripts -->
<script>
  import draggable from 'vuedraggable'
    export default {
      mounted() {
        console.log('Component mounted.')
        this.getTasks();
      },
      data() {
        return{
          tasks: []
        }
      },
      props: [
              'user'
          ],
          components: {
            draggable,
          },
      methods: {
        getTasks: function(){
            axios.get('/task/'+ this.user.id)
            .then(response => {
              console.log(response);
                this.tasks = response.data;
            });
        },
        addTask: function (e) {
          this.tasks.unshift({
            title: this.tasks.title,
            done: false
          });
          this.tasks.title = '';
        },
        deleteTask: function (task) {
          this.tasks.splice(
            this.tasks.indexOf(task),
            1
          )
        }
      },
    }
</script>

<!-- Styles -->
<style>
      .taskDone {
        text-decoration: line-through;
      }
      .close {
        display: none;
      }
      li:hover .close{
        display: block;
      }
      .fa-lg {
        margin-right: inherit;
      }
</style>
