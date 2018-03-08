<template lang="html">
    <div class="row">
        <div class="col-sm-3 profile-sidebar">
            <profile-sidebar-edit :user="user"></profile-sidebar-edit>
        </div>
        <div class="col-sm-9 profile-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Work</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" @submit.prevent>
                        <div class="form-group" :class="{'has-error': error.profession_id}">
                            <label class="col-sm-3 control-label">Profession</label>
                            <div class="col-sm-9">
                                <select class="form-control text-capitalize"
                                    v-model="user.profile_work.profession_id">
                                    <option v-for="option in list.professions" :value="option.id">
                                        {{ option.title }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.profession_id">{{error.profession_id[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.position_id}">
                            <label class="col-sm-3 control-label">Position</label>
                            <div class="col-sm-9">
                                <select class="form-control text-capitalize"
                                    v-model="user.profile_work.position_id">
                                    <option v-for="option in list.positions" :value="option.id">
                                        {{ option.title }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.position_id">{{error.position_id[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.department_id}">
                            <label class="col-sm-3 control-label">Department</label>
                            <div class="col-sm-9">
                                <select class="form-control text-capitalize"
                                    v-model="user.profile_work.department_id">
                                    <option v-for="option in list.departments" :value="option.id">
                                        {{ option.title }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.department_id">{{error.department_id[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.boss_id}">
                            <label class="col-sm-3 control-label">Boss</label>
                            <div class="col-sm-9">
                                <select class="form-control text-capitalize"
                                    v-model="user.profile_work.boss_id">
                                    <option v-for="option in list.bosses" :value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                                <span class="help-block" v-if="error.boss_id">{{error.boss_id[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.starting_from}">
                            <label class="col-sm-3 control-label">Starting from</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control"
                                    v-model="user.profile_work.starting_from">
                                <span class="help-block" v-if="error.starting_from">{{error.starting_from[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="button" class="btn btn-primary" @click="updateWork">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ProfileSidebarEdit from './Sidebar.vue';
    //import Snotify from 'vue-snotify';

    export default {
        data() {
            return {
                list: {
                    professions: [],
                    positions: [],
                    departments: [],
                    bosses: []
                },
                error: {}
            }
        },
        props: ['user'],
        mounted() {
            axios.get('/api/list/profile/profession')
            .then(response => {
                this.list.professions = response.data;
            });

            axios.get('/api/list/profile/position')
            .then(response => {
                this.list.positions = response.data;
            });

            axios.get('/api/list/profile/department')
            .then(response => {
                this.list.departments = response.data;
            });

            axios.get('/api/users/all')
            .then(response => {
                this.list.bosses = response.data;
            });
        },
        methods: {
            updateWork (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/profile/work/update/'+this.user.profile_work.id, this.user.profile_work)
                .then(response => {
                    this.user.profile_work = response.data;
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    //this.$snotify.success('The work has been saved success!');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
            }
        }
    }
</script>
