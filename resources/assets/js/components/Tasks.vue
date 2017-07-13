<template>
    <div >
        <!--Form -->
        <input @keyup.enter="store" type="text" class="form-control" v-model="newTask.title" placeholder="Add new task">
        <hr>
        <!-- Lista -->
        <ul class="list-group">
            <draggable v-model="tasks" @end="updateOrder">
                <li class="list-group-item" v-for="(task, index) in tasks">
                    <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
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
        this.getAll();
    },
    data() {
        return{
            tasks: [],
            newTask:{}
        }
    },
    props: [
        'user'
    ],
    components: {
        draggable,
    },
    methods: {
        getAll: function(){
            axios.get('/task/byUser')
            .then(response => {
                console.log(response);
                this.tasks = response.data;
                console.log(this.tasks);
            });
        },
        deleteTask: function (task) {
            axios.delete('/task/destroy/' + task.id)
                this.tasks.splice(
                    this.tasks.indexOf(task),
                    1
                );
        },
        store: function () {
            axios.post('/task/store', this.newTask)
            .then(response => {
                this.tasks.unshift(response.data);
                this.newTask = {};
            });
        },
        updateOrder: function (e) {
            console.log(e);
            console.log(this.tasks);
            axios.post('/task/updateOrder', {tasks:this.tasks})
            .then(response => {
                //this.tasks = response.data;
            });
        }
    }
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
