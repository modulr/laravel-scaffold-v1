<template>
    <div class="autoparts-lists">
        <!-- Container -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-7">
                    <h5>Makes & Models Lists</h5>
                    <hr>
                    <div class="form-group" :class="{'has-error': newMake.error.name}">
                        <input type="text" class="form-control" placeholder="Make" v-model="newMake.name" @keyup.enter="storeMake">
                        <span class="help-block" v-if="newMake.error.name">{{newMake.error.name[0]}}</span>
                    </div>
                    <ul class="list-group" id="accordion">
                        <li class="list-group-item" v-for="(make, index) in sortedMakes" :key="make.id">
                            {{make.name}} <small>({{make.models.length}})</small>
                            <a class="pull-right text-muted" data-toggle="collapse" data-parent="#accordion" :href="'#collapse'+make.id">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                            <a href="#" class="text-muted pull-right" @click.prevent="destroyMake(make, index)">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <ul class="list-group collapse" :id="'collapse'+make.id">
                                <hr>
                                <div class="form-group" :class="{'has-error': make.error}">
                                    <input type="text" class="form-control" placeholder="Model" v-model="make.newModel" @keyup.enter="storeModel(make)">
                                    <span class="help-block" v-if="make.error">{{make.error.name[0]}}</span>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(model, index) in make.models" :key="model.id">
                                        {{model.name}}
                                        <a href="#" class="text-muted pull-right" @click.prevent="destroyModel(model, index)">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </li>
                                </ul>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-5">
                    <h5>Years List</h5>
                    <hr>
                    <div class="form-group" :class="{'has-error': newYear.error.name}">
                        <input type="text" class="form-control" placeholder="Year" v-model="newYear.name" @keyup.enter="storeYear">
                        <span class="help-block" v-if="newYear.error.name">{{newYear.error.name[0]}}</span>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item" v-for="(year, index) in sortedYears"  :key="year.id">
                            {{year.name}}
                            <a href="#" class="text-muted pull-right" @click.prevent="destroyYear(year, index)">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
    mounted () {
        this.getLists()
    },
    computed: {
        sortedMakes () {
            return this.lists.makes.sort(function(a, b) {
                if (a.models.length) {
                    a.models.sort(function(x, y) {
                        var nameX = x.name.toUpperCase();
                        var nameY = y.name.toUpperCase();
                        if (nameX < nameY) {
                            return -1;
                        }
                        if (nameX > nameY) {
                            return 1;
                        }
                        // names must be equal
                        return 0;
                    });
                }
                var nameA = a.name.toUpperCase();
                var nameB = b.name.toUpperCase();
                if (nameA < nameB) {
                    return -1;
                }
                if (nameA > nameB) {
                    return 1;
                }
                // names must be equal
                return 0;
            });
        },
        sortedYears () {
            return this.lists.years.sort(function(a, b) {
                var nameA = a.name.toUpperCase();
                var nameB = b.name.toUpperCase();
                if (nameA > nameB) {
                    return -1;
                }
                if (nameA < nameB) {
                    return 1;
                }
                // names must be equal
                return 0;
            });
        }
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
            if (this.newMake.name) {
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
            }
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
                .catch(error => {
                    swal({
                        title: "Not Deleted!",
                        text: "The Make was not deleted, because it is in use.",
                        type: "warning",
                    })
                })
            })
        },
        storeModel (make) {
            if (make.newModel) {
                axios.post('/api/autoparts/list/models/store', {name:make.newModel, makeId:make.id})
                .then(response => {
                    make.models.push(response.data)
                    make.newModel = null
                    make.error = null
                })
                .catch(error => {
                    make.error = error.response.data
                })
            }
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
                .catch(error => {
                    swal({
                        title: "Not Deleted!",
                        text: "The Model was not deleted, because it is in use.",
                        type: "warning",
                    })
                })
            })
        },
        storeYear () {
            if (this.newYear.name) {
                axios.post('/api/autoparts/list/years/store', this.newYear)
                .then(response => {
                    this.lists.years.push(response.data)
                    this.newYear = {
                        error: {}
                    }
                })
                .catch(error => {
                    this.newYear.error = error.response.data;
                })
            }
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
                .catch(error => {
                    swal({
                        title: "Not Deleted!",
                        text: "The Year was not deleted, because it is in use.",
                        type: "warning",
                    })
                })
            })
        }
    }
}
</script>
