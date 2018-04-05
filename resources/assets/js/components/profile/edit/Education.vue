<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Education</h3>
        </div>
        <div class="panel-body">
            <dl v-for="(education, index) in user.profile_education">
                <a href="" class="btn btn-link pull-right" @click.prevent="destroyEducation(education.id, index)">
                    <i class="fa fa-trash"></i>
                </a>
                <dt>{{education.title}} - {{education.school_name}}</dt>
                <dd>
                    {{education.start_year}} - {{education.end_year}}
                    <span class="text-muted" v-show="education.description"> / {{education.description}}</span>
                </dd>
            </dl>
            <hr>
            <form @submit.prevent>
                <div class="form-group" :class="{'has-error': error.school_name}">
                    <label>School name *</label>
                    <input type="text" class="form-control"
                        v-model="education.school_name">
                    <span class="help-block" v-if="error.school_name">{{error.school_name[0]}}</span>
                </div>
                <div class="form-group" :class="{'has-error': error.title}">
                    <label>Title *</label>
                    <input type="text" class="form-control"
                        v-model="education.title">
                    <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.start_year}">
                            <label>Start year *</label>
                            <input type="text" class="form-control"
                                v-model="education.start_year">
                            <span class="help-block" v-if="error.start_year">{{error.start_year[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.end_year}">
                            <label>End year *</label>
                            <input type="text" class="form-control"
                                v-model="education.end_year">
                            <span class="help-block" v-if="error.end_year">{{error.end_year[0]}}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" @click="storeEducation">Add</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert'

export default {
    data() {
        return {
            education: {},
            error: {}
        }
    },
    props: ['user'],
    methods: {
        storeEducation: function (e) {
            var btn = $(e.target).button('loading')
            this.education.user_id = this.user.id;
            axios.post('/api/profile/education/store', this.education)
            .then(response => {
                this.user.profile_education.push(response.data);
                this.education = {};
                this.error = {};
                var btn = $(e.target).button('reset')
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyEducation: function (educationId, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this education!",
                icon: "warning",
                buttons: false
            })
            .then((value) => {
                if (value) {
                    axios.delete('/api/profile/education/destroy/' + educationId)
                    .then(response => {
                        self.user.profile_education.splice(index, 1);
                        swal({
                            title: "Deleted!",
                            text: "The education has been deleted.",
                            icon: "success",
                            buttons: false,
                            timer: 1000
                        });
                        self.error = {};
                    })
                    .catch(error => {
                        self.error = error.response.data;
                    });
                }
            });
        }
    }
}
</script>
