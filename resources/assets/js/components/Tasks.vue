<template>
  <div class="container">
    <div id="taskApp" class="col-sm-8 col-sm-offset-2">

      <!--Form -->
      <div class="panel panel-default">
        <form v-on:submit="addTask" class="app-form">
            <input type="text" class="form-control" v-model="tasks.title" placeholder="Add new task">
        </form>
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
    </div>
  </div>
</template>

<!-- Scripts -->
<script>
  import draggable from 'vuedraggable'
    export default {
      mounted() {
        console.log('Component mounted.')
      },
      data() {
        return{
          tasks: []
        }
      },
      methods: {
        addTask: function (e) {
          e.preventDefault();
          this.tasks.push({
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
      components: {
        draggable,
      }
    }
</script>

<!-- Styles -->
<style>
      .app-form input{
        border-radius: 0;
      }
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
