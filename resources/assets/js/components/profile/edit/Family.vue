<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Family</h3>
        </div>
        <div class="panel-body">
            <dl v-for="(family, index) in user.profile_family">
                <a href="" class="btn btn-link pull-right" @click.prevent="destroyFamily(family.id, index)">
                    <i class="fa fa-trash"></i>
                </a>
                <dt>{{family.name}} - {{family.relation.title}}</dt>
                <dd>
                    {{family.gender.title}} - {{family.birthday | moment('LL')}}
                    <span class="text-muted" v-show="family.description"> / {{family.description}}</span>
                </dd>
            </dl>
            <hr>
            <form @submit.prevent>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.name}">
                            <label>Name *</label>
                            <input type="text" class="form-control"
                                v-model="family.name">
                            <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.relation_id}">
                            <label>Relation *</label>
                            <select class="form-control text-capitalize"
                                v-model="family.relation_id">
                                <option v-for="option in list.relations" :value="option.id">
                                    {{ option.title }}
                                </option>
                            </select>
                            <span class="help-block" v-if="error.relation_id">{{error.relation_id[0]}}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.birthday}">
                            <label>Birthday *</label>
                            <input type="date" class="form-control"
                                v-model="family.birthday">
                            <span class="help-block" v-if="error.birthday">{{error.birthday[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.gender_id}">
                            <label>Gender *</label>
                            <select class="form-control text-capitalize"
                                v-model="family.gender_id">
                                <option v-for="option in list.genders" :value="option.id">
                                    {{ option.title }}
                                </option>
                            </select>
                            <span class="help-block" v-if="error.gender_id">{{error.gender_id[0]}}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" @click="storeFamily">Add</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                list: {
                    genders: [],
                    relations: []
                },
                family: {},
                error: {}
            }
        },
        props: ['user'],
        mounted() {
            axios.get('/api/list/profile/gender')
            .then(response => {
                this.list.genders = response.data;
            });
            axios.get('/api/list/profile/relation')
            .then(response => {
                this.list.relations = response.data;
            });
        },
        methods: {
            storeFamily: function (e) {
                var btn = $(e.target).button('loading')
                this.family.user_id = this.user.id;
                axios.post('/api/profile/family/store', this.family)
                .then(response => {
                    this.user.profile_family.push(response.data);
                    this.family = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            },
            destroyFamily: function (familyId, index) {
                var self = this;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this family!",
                    type: "warning",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.delete('/api/profile/family/destroy/' + familyId)
                    .then(response => {
                        self.user.profile_family.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The family has been deleted.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                });
            }
        }
    }
</script>
