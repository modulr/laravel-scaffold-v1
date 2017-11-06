<template>
    <div class="tasks">
        <!-- New Task -->
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Add new task"
                   v-model="newTask.title"
                   v-if="user.hasPermission['create-tasks']"
                   @keyup.enter="storeTask">
        </div>
        <!-- List Tasks -->
        <ul class="list-group" v-if="tasks.length">
            <draggable :options="{handle:'.my-handle'}" v-model="tasks" @end="updateOrderTasks">
                <li class="list-group-item" v-for="(task, index) in tasks" :key="task.id">
                    <div class="media">
                        <div class="media-left my-handle">
                            <i class="mdi mdi-more-vert"></i>
                        </div>
                        <div class="media-left">
                            <a href="#"
                               v-if="user.hasPermission['update-tasks']"
                               @click="doneTask(task)">
                                <i class="check mdi mdi-2x" aria-hidden="true"
                                   :class="{'mdi-radio-button-unchecked': !task.done, 'mdi-check': task.done}"></i>
                            </a>
                        </div>
                        <div class="media-body">
                            <span class="title" :class="{'task-done': task.done}">{{task.title}}</span>
                        </div>
                        <div class="media-right">
                            <a href="#" class="delete"
                               v-if="user.hasPermission['delete-tasks']"
                               @click.prevent="destroyTask(task)">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </li>
            </draggable>
        </ul>
        <!-- Init Message -->
        <div class="init-message" v-else>
            <i class="mdi mdi-assignment" aria-hidden="true"></i>
            <p class="lead">Don't exist Tasks... create one!!</p>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        data() {
            return{
                user: Laravel.user,
                tasks: [],
                newTask: {},
            }
        },
        mounted() {
            this.getTasks();
        },
        components: {
            draggable,
        },
        methods: {
            getTasks: function(){
                axios.get('/tasks/byUser')
                .then(response => {
                    this.tasks = response.data;
                });
            },
            storeTask: function () {
                if (this.newTask.title) {
                    axios.post('/tasks/store', this.newTask)
                    .then(response => {
                        this.tasks.unshift(response.data);
                        this.newTask = {};
                    });
                }
            },
            destroyTask: function (task) {
                axios.delete('/tasks/destroy/'+task.id)
                    this.tasks.splice(this.tasks.indexOf(task), 1);
            },
            doneTask: function (task) {
                task.done = !task.done
                axios.put('/tasks/markDone/'+task.id, task);
            },
            updateOrderTasks: function () {
                axios.put('/tasks/updateOrder', {tasks:this.tasks});
            },
        }
    }
</script>
