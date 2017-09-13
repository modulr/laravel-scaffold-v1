<template lang="html">
    <div>
        <!-- Personal -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-address-card" aria-hidden="true"></i> Personal
                    <a href="#" class="pull-right" @click.prevent="editPersonal">
                        <span class="fa fa-pencil"></span>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dd>Birthday</dd>
                            <dt>{{user.profile_personal.birthday | date-format}}</dt>
                        </dl>
                        <dl>
                            <dd>Place of birth</dd>
                            <dt>{{user.profile_personal.place_of_birth}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>Gender</dd>
                            <dt class="text-capitalize">{{user.profile_personal.gender.title}}</dt>
                        </dl>
                        <dl>
                            <dd>Relationship</dd>
                            <dt class="text-capitalize">{{user.profile_personal.relationship.title}}</dt>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dd>RFC</dd>
                            <dt>{{user.profile_personal.rfc}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>CURP</dd>
                            <dt>{{user.profile_personal.curp}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>NSS</dd>
                            <dt>{{user.profile_personal.nss}}</dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Personal -->
        <div class="modal fade" id="modalPersonal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Edit Personal</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.birthday}">
                                <label class="col-sm-2 control-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control"
                                        v-model="profile.personal.birthday">
                                    <span class="help-block" v-if="error.birthday">{{error.birthday[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.place_of_birth}">
                                <label class="col-sm-2 control-label">Place Birthday</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Chihuahua"
                                        v-model="profile.personal.place_of_birth">
                                    <span class="help-block" v-if="error.place_of_birth">{{error.place_of_birth[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.gender_id}">
                                <label class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-control text-capitalize" v-model="profile.personal.gender_id">
                                        <option v-for="option in list.gender" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.gender_id">{{error.gender_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.relationship_id}">
                                <label class="col-sm-2 control-label">Relationship</label>
                                <div class="col-sm-10">
                                    <select class="form-control text-capitalize" v-model="profile.personal.relationship_id">
                                        <option v-for="option in list.relationship" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.relationship_id">{{error.relationship_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.rfc}">
                                <label class="col-sm-2 control-label">RFC</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        v-model="profile.personal.rfc">
                                    <span class="help-block" v-if="error.rfc">{{error.rfc[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.curp}">
                                <label class="col-sm-2 control-label">CURP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        v-model="profile.personal.curp">
                                    <span class="help-block" v-if="error.curp">{{error.curp[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.nss}">
                                <label class="col-sm-2 control-label">NSS</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                        v-model="profile.personal.nss">
                                    <span class="help-block" v-if="error.nss">{{error.nss[0]}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="updatePersonal">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-fw fa-address-book" aria-hidden="true"></i> Contact</h3>
            </div>
            <div class="panel-body">
                <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> {{user.email}}
                <ul class="list-unstyled">
                    <li v-for="contact in user.profile_contact">
                        <i class="fa fa-fw" :class="{
                            'fa-envelope': contact.type_id == 1,
                            'fa-phone': contact.type_id == 2,
                            'fa-mobile': contact.type_id == 3,
                            }" aria-hidden="true"></i> {{contact.contact}}
                    </li>
                </ul>
            </div>
        </div>
        <!-- Education -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-fw fa-mortar-board" aria-hidden="true"></i> Education</h3>
            </div>
            <div class="panel-body">
                <dl v-for="education in user.profile_education">
                    <dt>{{education.title}} - {{education.school_name}}</dt>
                    <dd>
                        {{education.start_year}} - {{education.end_year}}
                        <span class="text-muted" v-show="education.description"> / {{education.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>
        <!-- Family -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-fw fa-users" aria-hidden="true"></i> Family</h3>
            </div>
            <div class="panel-body">
                <dl v-for="family in user.profile_family">
                    <dt>{{family.name}} - {{family.relation.title}}</dt>
                    <dd>
                        {{family.gender.title}} - {{family.birthday | date-format}}
                        <span class="text-muted" v-show="family.description"> / {{family.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>
        <!-- Places -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-fw fa-map-marker" aria-hidden="true"></i> Places</h3>
            </div>
            <div class="panel-body">
                <dl v-for="place in user.profile_place">
                    <dt>
                        {{place.place}}
                        <i class="fa fa-check text-success" aria-hidden="true"
                            data-toggle="tooltip" title="Currently"
                            v-show="place.currently"></i>
                    </dt>
                    <dd v-show="place.description"><span class="text-muted">{{place.description}}</span></dd>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
import dateFormat from '../../filters/date-format'
Vue.use(dateFormat)

export default {
    data() {
        return {
            list: {
                gender: [],
                relationship: []
            },
            profile: {
                personal: {}
            },
            error: {}
        }
    },
    props: [
        'user'
    ],
    methods: {
        editPersonal: function () {
            this.profile.personal = _.clone(this.user.profile_personal);

            if (this.list.gender.length == 0) {
                axios.get('/list/gender')
                .then(response => {
                    this.list.gender = response.data;
                    this.profile.personal = _.clone(this.user.profile_personal);
                });
            }

            if (this.list.relationship.length == 0) {
                axios.get('/list/relationship')
                .then(response => {
                    this.list.relationship = response.data;
                    this.profile.personal = _.clone(this.user.profile_personal);
                });
            }

            $('#modalPersonal').modal('show');

        },
        updatePersonal: function (e) {
            var btn = $(e.target).button('loading')
            axios.put('/profile/personal/update/'+this.profile.personal.id, this.profile.personal)
            .then(response => {
                this.user.profile_personal = response.data;
                this.profile.personal = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalPersonal').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        }
    }
}
</script>
