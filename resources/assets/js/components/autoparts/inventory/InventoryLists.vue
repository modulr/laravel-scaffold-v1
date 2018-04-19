<template>
    <div class="autoparts-lists">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Lists</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-7">
                    <h6 v-if="user.hasPermission['create-inventory-lists']">Create Makes & Models</h6>
                    <div class="form-group" :class="{'has-error': newMake.error.name}"
                        v-if="user.hasPermission['create-inventory-lists']">
                        <input type="text" class="form-control" placeholder="Make name" v-model="newMake.name" @keyup.enter="storeMake">
                        <span class="help-block" v-if="newMake.error.name">{{newMake.error.name[0]}}</span>
                    </div>
                    <ul class="list-group" id="accordion">
                        <li class="list-group-item" v-for="(make, index) in sortedMakes" :key="make.id">
                            <a class="text-muted" data-toggle="collapse" data-parent="#accordion" :href="'#collapse'+make.id">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                            {{make.name}}
                            <small class="badge" v-show="make.models.length > 0">
                                {{make.models.length}}
                            </small>
                            <a href="#" class="text-muted pull-right"
                                @click.prevent="destroyMake(make, index)"
                                v-if="user.hasPermission['delete-inventory-lists']">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <ul class="list-group collapse" :id="'collapse'+make.id">
                                <hr>
                                <div class="form-group" :class="{'has-error': make.error}"
                                    v-if="user.hasPermission['create-inventory-lists']">
                                    <input type="text" class="form-control" placeholder="Model name" v-model="make.newModel" @keyup.enter="storeModel(make)">
                                    <span class="help-block" v-if="make.error">{{make.error.name[0]}}</span>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(model, index) in make.models" :key="model.id">
                                        {{model.name}}
                                        <a href="#" class="text-muted pull-right"
                                            @click.prevent="destroyModel(model, index)"
                                            v-if="user.hasPermission['delete-inventory-lists']">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </li>
                                </ul>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-5">
                    <h6 v-if="user.hasPermission['create-inventory-lists']">Create Years</h6>
                    <div class="form-group" :class="{'has-error': newYear.error.name}"
                        v-if="user.hasPermission['create-inventory-lists']">
                        <input type="text" class="form-control" placeholder="Year name" v-model="newYear.name" @keyup.enter="storeYear">
                        <span class="help-block" v-if="newYear.error.name">{{newYear.error.name[0]}}</span>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item" v-for="(year, index) in sortedYears"  :key="year.id">
                            {{year.name}}
                            <a href="#" class="text-muted pull-right"
                            @click.prevent="destroyYear(year, index)"
                            v-if="user.hasPermission['delete-inventory-lists']">
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
            user: Laravel.user,
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
            axios.get('/api/autoparts/lists/makesFull')
            .then(response => {
                this.lists.makes = response.data
            })
            axios.get('/api/autoparts/lists/years')
            .then(response => {
                this.lists.years = response.data
            })
        },
        storeMake () {
            if (this.newMake.name) {
                axios.post('/api/autoparts/lists/makes/store', this.newMake)
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
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/autoparts/lists/makes/destroy/' + make.id)
                    .then(response => {
                        self.lists.makes.splice(index, 1)
                        swal({
                            title: "Deleted!",
                            text: "The Make has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        })
                    })
                    .catch(error => {
                        swal({
                            title: "Not Deleted!",
                            text: "The Make was not deleted, because it is in use.",
                            icon: "warning"
                        })
                    })
                }
            })
        },
        storeModel (make) {
            if (make.newModel) {
                axios.post('/api/autoparts/lists/models/store', {name:make.newModel, makeId:make.id})
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
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/autoparts/lists/models/destroy/' + model.id)
                    .then(response => {
                        self.lists.makes.forEach(function(val){
                            if (val.id == model.make_id) {
                                val.models.splice(index, 1)
                            }
                        })
                        swal({
                            title: "Deleted!",
                            text: "The Model has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        })
                    })
                    .catch(error => {
                        swal({
                            title: "Not Deleted!",
                            text: "The Model was not deleted, because it is in use.",
                            icon: "warning",
                        })
                    })
                }
            })
        },
        storeYear () {
            if (this.newYear.name) {
                axios.post('/api/autoparts/lists/years/store', this.newYear)
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
                icon: "warning",
                buttons: true
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/autoparts/lists/years/destroy/' + year.id)
                    .then(response => {
                        self.lists.years.splice(index, 1)
                        swal({
                            title: "Deleted!",
                            text: "The Year has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        })
                    })
                    .catch(error => {
                        swal({
                            title: "Not Deleted!",
                            text: "The Year was not deleted, because it is in use.",
                            icon: "warning",
                        })
                    })
                }
            })
        }
    }
}
</script>
