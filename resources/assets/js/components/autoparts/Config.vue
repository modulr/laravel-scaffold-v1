<template>
    <div class="autoparts config">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-7">
                    <h4>Makes & Models Lists</h4>
                    <hr>
                    <div class="form-group" :class="{'has-error': newMake.error.name}">
                        <input type="text" class="form-control" placeholder="Make" v-model="newMake.name" @keyup.enter="storeMake">
                        <span class="help-block" v-if="newMake.error.name">{{newMake.error.name[0]}}</span>
                    </div>
                    <draggable class="list-group" id="accordion" v-model="lists.makes" element="ul" :options="{handle:'.handle'}" @end="updateMakesOrder">
                        <li class="list-group-item" v-for="(make, index) in lists.makes" :key="make.id">
                            <a class="pull-right text-muted" data-toggle="collapse" data-parent="#accordion" :href="'#collapse'+make.id">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                            <a href="#" class="text-muted pull-right" @click.prevent="destroyMake(make, index)">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <span class="handle">::</span>
                            {{make.name}} <small>({{make.models.length}})</small>
                            <ul class="list-group collapse" :id="'collapse'+make.id">
                                <hr>
                                <div class="form-group" :class="{'has-error': make.error}">
                                    <input type="text" class="form-control" placeholder="Model" v-model="make.newModel" @keyup.enter="storeModel(make)">
                                    <span class="help-block" v-if="make.error">{{make.error.name[0]}}</span>
                                </div>
                                <draggable v-model="make.models" :options="{handle:'.handle'}" @end="updateModelsOrder(make.id)">
                                    <li class="list-group-item" v-for="(model, index) in make.models" :key="model.id">
                                        <a href="#" class="text-muted pull-right" @click.prevent="destroyModel(model, index)">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        <span class="handle">::</span>
                                        {{model.name}}
                                    </li>
                                </draggable>
                            </ul>
                        </li>
                    </draggable>
                </div>
                <div class="col-sm-5">
                    <h4>Years List</h4>
                    <hr>
                    <div class="form-group" :class="{'has-error': newYear.error.name}">
                        <input type="text" class="form-control" placeholder="Year" v-model="newYear.name" @keyup.enter="storeYear">
                        <span class="help-block" v-if="newYear.error.name">{{newYear.error.name[0]}}</span>
                    </div>
                    <draggable element="ul" class="list-group" v-model="lists.years" :options="{handle:'.handle'}" @end="updateYearsOrder">
                        <li class="list-group-item" v-for="(year, index) in lists.years"  :key="year.id">
                            <a href="#" class="text-muted pull-right" @click.prevent="destroyYear(year, index)">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <span class="handle">::</span>
                            {{year.name}}
                        </li>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
    data() {
        return {
            lists: {
                makes: [],
                years: []
            },
            newMake: {
                error: {}
            },
            newYear: {
                error: {}
            }
        }
    },
    components: {
        draggable
    },
    mounted () {
        this.getLists()
    },
    methods: {
        getLists () {
            axios.get('/api/autoparts/list/makesFull')
            .then(response => {
                this.lists.makes = response.data
            })
            axios.get('/api/autoparts/list/years')
            .then(response => {
                this.lists.years = response.data
            })
        },
        storeMake () {
            axios.post('/api/autoparts/list/makes/store', this.newMake)
            .then(response => {
                this.lists.makes.push(response.data)
                this.newMake = {
                    error: {}
                }
            })
            .catch(error => {
                this.newMake.error = error.response.data
            })
        },
        destroyMake (make, index) {
            var self = this
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Make!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/api/autoparts/list/makes/destroy/' + make.id)
                .then(response => {
                    self.lists.makes.splice(index, 1)
                    swal({
                        title: "Deleted!",
                        text: "The Make has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    })
                })
            })
        },
        updateMakesOrder: function () {
            axios.put('/api/autoparts/list/makes/order', this.lists.makes)
        },
        storeModel (make) {
            axios.post('/api/autoparts/list/models/store', {name:make.newModel, makeId:make.id})
            .then(response => {
                make.models.push(response.data)
                make.newModel = null
                make.error = null
            })
            .catch(error => {
                make.error = error.response.data
            })
        },
        destroyModel (model, index) {
            var self = this
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Model!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/api/autoparts/list/models/destroy/' + model.id)
                .then(response => {
                    self.lists.makes.forEach(function(val){
                        if (val.id == model.make_id) {
                            val.models.splice(index, 1)
                        }
                    })
                    swal({
                        title: "Deleted!",
                        text: "The Model has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    })
                })
            })
        },
        updateModelsOrder: function (makeId) {
            var models = [];
            this.lists.makes.forEach(function(val){
                if (val.id == makeId) {
                    models = val.models
                }
            })
            axios.put('/api/autoparts/list/models/order', models)
        },
        storeYear () {
            axios.post('/api/autoparts/list/years/store', this.newYear)
            .then(response => {
                this.lists.years.unshift(response.data)
                this.newYear = {
                    error: {}
                }
            })
            .catch(error => {
                this.newYear.error = error.response.data;
            })
        },
        destroyYear (year, index) {
            var self = this
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Year!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/api/autoparts/list/years/destroy/' + year.id)
                .then(response => {
                    self.lists.years.splice(index, 1)
                    swal({
                        title: "Deleted!",
                        text: "The Year has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    })
                })
            })
        },
        updateYearsOrder: function () {
            axios.put('/api/autoparts/list/years/order', this.lists.years)
        },
    }
}
</script>
