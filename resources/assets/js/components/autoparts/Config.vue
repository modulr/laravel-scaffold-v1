<template>
    <div class="autoparts config">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <h4>Makes & Models Lists</h4>
                    <hr>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Make" v-model="newMake.data.name">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="storeMake">Add</button>
                            </span>
                        </div>
                    </div>
                    <ul class="list-group">
                        <draggable v-model="lists.makes" @end="updateMakesOrder">
                            <li class="list-group-item" v-for="(make, index) in lists.makes" :key="make.id">
                                <a href="#" class="pull-right text-muted" data-toggle="collapse" aria-expanded="false" :data-target="'#collapse'+make.id">
                                    <i class="fa fa-chevron-down"></i>
                                </a>
                                <a href="#" class="pull-right" @click.prevent="destroyMake(make, index)">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                                <i class="mdi mdi-more-vert"></i>
                                {{make.name}}
                                <ul class="list-group collapse" :id="'collapse'+make.id">
                                    <li>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Model" v-model="make.newModel">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" @click="storeModel($event, make)">Add</button>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <draggable v-model="make.models" @end="updateModelsOrder(make.id)">
                                        <li class="list-group-item" v-for="(model, index) in make.models" :key="model.id">
                                            <a href="#" class="pull-right" @click.prevent="destroyModel(model, index)">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <i class="mdi mdi-more-vert"></i>
                                            {{model.name}}
                                        </li>
                                    </draggable>
                                </ul>
                            </li>
                        </draggable>
                    </ul>
                </div>
                <div class="col-md-5">
                    <h4>Years List</h4>
                    <hr>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Year" v-model="newYear.data.name">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="storeYear">Add</button>
                            </span>
                        </div>
                    </div>
                    <ul class="list-group">
                        <draggable v-model="lists.years" @end="updateYearsOrder">
                            <li class="list-group-item" v-for="(year, index) in lists.years"  :key="year.id">
                                <a href="#" class="pull-right" @click.prevent="destroyYear(year, index)">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                                <i class="mdi mdi-more-vert"></i>
                                {{year.name}}
                            </li>
                        </draggable>
                    </ul>
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
                data: {},
                error: {}
            },
            newYear: {
                data: {},
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
                this.lists.makes = response.data;
            });
            axios.get('/api/autoparts/list/years')
            .then(response => {
                this.lists.years = response.data;
            });
        },
        storeMake (e) {
            var btn = $(e.target).button('loading')
            axios.post('/api/autoparts/list/makes/store', this.newMake.data)
            .then(response => {
                this.lists.makes.push(response.data);
                this.newMake = {
                    data: {},
                    error: {}
                };
                var btn = $(e.target).button('reset')
            })
            .catch(error => {
                this.newMake.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyMake (make, index) {
            var self = this;
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
                    self.lists.makes.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The Make has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            });
        },
        updateMakesOrder: function () {
            axios.put('/api/autoparts/list/makes/order', this.lists.makes);
        },
        storeModel (e, make) {
            var btn = $(e.target).button('loading')
            axios.post('/api/autoparts/list/models/store', {name:make.newModel, makeId:make.id})
            .then(response => {
                make.models.push(response.data);
                make.newModel = null;
                make.error = null;
                var btn = $(e.target).button('reset')
            })
            .catch(error => {
                make.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyModel (model, index) {
            var self = this;
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
                    });
                    swal({
                        title: "Deleted!",
                        text: "The Model has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            });
        },
        updateModelsOrder: function (makeId) {
            var models = [];
            this.lists.makes.forEach(function(val){
                if (val.id == makeId) {
                    models = val.models
                }
            });
            axios.put('/api/autoparts/list/models/order', models);
        },
        storeYear (e) {
            var btn = $(e.target).button('loading')
            axios.post('/api/autoparts/list/years/store', this.newYear.data)
            .then(response => {
                this.lists.years.unshift(response.data);
                this.newYear = {
                    data: {},
                    error: {}
                };
                var btn = $(e.target).button('reset')
            })
            .catch(error => {
                this.newYear.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyYear (year, index) {
            var self = this;
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
                    self.lists.years.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The Year has been deleted.",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            });
        },
        updateYearsOrder: function () {
            axios.put('/api/autoparts/list/years/order', this.lists.years);
        },
    }
}
</script>
