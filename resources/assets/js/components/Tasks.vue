<template>
    <div class="tasks">
        <!-- newTask -->
        <div class="form-group">
            <input @keyup.enter="storeTask" type="text" class="form-control" placeholder="Add new task" v-model="newTask.title">
        </div>
        <!-- List -->
        <ul class="list-group">
            <draggable v-model="tasks" @end="updateOrder" :options="{handle:'.my-handle'}">
                <li class="list-group-item" v-for="(task, index) in tasks" :key="task.id">
                    <span class="my-handle"><i class="mdi mdi-reorder"></i></span>
                    <span v-if="!task.done">
                        <a href="#" @click="doneTask(task)">
                            <i class="fa fa-lg fa-fw fa-2x" :class="{'fa-circle-thin': !task.done, 'fa-check-circle': task.done}" aria-hidden="true"></i>
                        </a>
                        <span :class="{'task-done': task.done}" @click="'contentEditable',true" >{{task.title}}</span>
                        <a href="#" class="close" aria-label="Close" @click.prevent="deleteTask(task)">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </span>
                    <span v-if="task.done">
                        <a href="#" @click="doneTask(task)">
                            <i class="fa fa-lg fa-fw fa-2x" :class="{'fa-circle-thin': !task.done, 'fa-check-circle': task.done}" aria-hidden="true"></i>
                        </a>
                        <span :class="{'task-done': task.done}" @click="'contentEditable',true" >{{task.title}}</span>
                        <a href="#" class="close" aria-label="Close" @click.prevent="deleteTask(task)">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </span>
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
        this.getAll();
    },
    data() {
        return{
            tasks: [],
            newTask:{},
            user: Laravel.user
        }
    },
    components: {
        draggable,
    },
    methods: {
        getAll: function(){
            axios.get('/task/byUser')
            .then(response => {
                this.tasks = response.data;
            });
        },
        deleteTask: function (task) {
            axios.delete('/task/destroy/' + task.id)
                this.tasks.splice(this.tasks.indexOf(task),1);
        },
        storeTask: function () {
            axios.post('/task/store', this.newTask)
            .then(response => {
                this.tasks.unshift(response.data);
                this.newTask = {};
            });
        },
        doneTask: function (task) {
            task.done = !task.done
            axios.put('/task/markDone/'+ task.id , task)
            .then(response => {
                console.log(response);
            });
        },
        updateOrder: function (e) {
            axios.put('/task/updateOrder', {tasks:this.tasks})
            .then(response => {
                console.log(response);
            });
        },
    }
}
</script>
