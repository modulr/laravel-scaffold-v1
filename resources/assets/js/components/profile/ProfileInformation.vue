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
                                <label class="col-sm-3 control-label">Birthday</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control"
                                        v-model="profile.personal.birthday">
                                    <span class="help-block" v-if="error.birthday">{{error.birthday[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.place_of_birth}">
                                <label class="col-sm-3 control-label">Place of birth</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Chihuahua"
                                        v-model="profile.personal.place_of_birth">
                                    <span class="help-block" v-if="error.place_of_birth">{{error.place_of_birth[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.gender_id}">
                                <label class="col-sm-3 control-label">Gender</label>
                                <div class="col-sm-9">
                                    <select class="form-control text-capitalize"
                                        v-model="profile.personal.gender_id">
                                        <option v-for="option in list.genders" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.gender_id">{{error.gender_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.relationship_id}">
                                <label class="col-sm-3 control-label">Relationship</label>
                                <div class="col-sm-9">
                                    <select class="form-control text-capitalize"
                                        v-model="profile.personal.relationship_id">
                                        <option v-for="option in list.relationships" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.relationship_id">{{error.relationship_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.rfc}">
                                <label class="col-sm-3 control-label">RFC</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.personal.rfc">
                                    <span class="help-block" v-if="error.rfc">{{error.rfc[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.curp}">
                                <label class="col-sm-3 control-label">CURP</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.personal.curp">
                                    <span class="help-block" v-if="error.curp">{{error.curp[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.nss}">
                                <label class="col-sm-3 control-label">NSS</label>
                                <div class="col-sm-9">
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
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-address-book" aria-hidden="true"></i> Contact
                    <a href="#" class="pull-right" @click.prevent="addContact">
                        <span class="fa fa-plus"></span>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <strong><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> {{user.email}}</strong>
                <br><br>
                <dl v-for="(contact, index) in user.profile_contact">
                    <button type="button" class="close" aria-label="Close" @click="destroyContact(contact.id, index)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <dt>
                        <i class="fa fa-fw" :class="{
                            'fa-envelope': contact.type_id == 1,
                            'fa-phone': contact.type_id == 2,
                            'fa-mobile': contact.type_id == 3,
                            }" aria-hidden="true"></i> {{contact.contact}}
                    </dt>
                </dl>
            </div>
        </div>
        <!-- Add Contact -->
        <div class="modal fade" id="modalContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Contact</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.type_id}">
                                <label class="col-sm-3 control-label">Type</label>
                                <div class="col-sm-9">
                                    <select class="form-control text-capitalize"
                                        v-model="profile.contact.type_id">
                                        <option v-for="option in list.contacts" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.type_id">{{error.type_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.contact}">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <input type="text" class="form-control"
                                        v-model="profile.contact.contact">
                                    <span class="help-block" v-if="error.contact">{{error.contact[0]}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="storeContact">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Education -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-mortar-board" aria-hidden="true"></i> Education
                    <a href="#" class="pull-right" @click.prevent="addEducation">
                        <span class="fa fa-plus"></span>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(education, index) in user.profile_education">
                    <button type="button" class="close" aria-label="Close" @click="destroyEducation(education.id, index)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <dt>{{education.title}} - {{education.school_name}}</dt>
                    <dd>
                        {{education.start_year}} - {{education.end_year}}
                        <span class="text-muted" v-show="education.description"> / {{education.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>
        <!-- Add Education -->
        <div class="modal fade" id="modalEducation">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Education</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.school_name}">
                                <label class="col-sm-3 control-label">School name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.education.school_name">
                                    <span class="help-block" v-if="error.school_name">{{error.school_name[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.title}">
                                <label class="col-sm-3 control-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.education.title">
                                    <span class="help-block" v-if="error.title">{{error.title[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.start_year}">
                                <label class="col-sm-3 control-label">Start year</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.education.start_year">
                                    <span class="help-block" v-if="error.start_year">{{error.start_year[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.end_year}">
                                <label class="col-sm-3 control-label">End year</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.education.end_year">
                                    <span class="help-block" v-if="error.end_year">{{error.end_year[0]}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="storeEducation">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Family -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-users" aria-hidden="true"></i> Family
                    <a href="#" class="pull-right" @click.prevent="addFamily">
                        <span class="fa fa-plus"></span>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(family, index) in user.profile_family">
                    <button type="button" class="close" aria-label="Close" @click="destroyFamily(family.id, index)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <dt>{{family.name}} - {{family.relation.title}}</dt>
                    <dd>
                        {{family.gender.title}} - {{family.birthday | date-format}}
                        <span class="text-muted" v-show="family.description"> / {{family.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>
        <!-- Add Family -->
        <div class="modal fade" id="modalFamily">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Family</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.family.name">
                                    <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.relation_id}">
                                <label class="col-sm-3 control-label">Relation</label>
                                <div class="col-sm-9">
                                    <select class="form-control text-capitalize"
                                        v-model="profile.family.relation_id">
                                        <option v-for="option in list.relations" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.relation_id">{{error.relation_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.gender_id}">
                                <label class="col-sm-3 control-label">Gender</label>
                                <div class="col-sm-9">
                                    <select class="form-control text-capitalize"
                                        v-model="profile.family.gender_id">
                                        <option v-for="option in list.genders" :value="option.id">
                                            {{ option.title }}
                                        </option>
                                    </select>
                                    <span class="help-block" v-if="error.gender_id">{{error.gender_id[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.birthday}">
                                <label class="col-sm-3 control-label">Birthday</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control"
                                        v-model="profile.family.birthday">
                                    <span class="help-block" v-if="error.birthday">{{error.birthday[0]}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="storeFamily">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Places -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-map-marker" aria-hidden="true"></i> Places
                    <a href="#" class="pull-right" @click.prevent="addPlace">
                        <span class="fa fa-plus"></span>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(place, index) in user.profile_place">
                    <button type="button" class="close" aria-label="Close" @click="destroyPlace(place.id, index)">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
        <!-- Add Place -->
        <div class="modal fade" id="modalPlace">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Place</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.place}">
                                <label class="col-sm-3 control-label">Plce</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"
                                        v-model="profile.place.place">
                                    <span class="help-block" v-if="error.place">{{error.place[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="profile.place.currently"> Currently
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="storePlace">Save</button>
                    </div>
                </div>
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
                genders: [],
                relationships: [],
                contacts: [],
                relations: []
            },
            profile: {
                personal: {},
                contact: {},
                education: {},
                family: {},
                place: {}
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

            if (this.list.genders.length == 0) {
                axios.get('/list/gender')
                .then(response => {
                    this.list.genders = response.data;
                    this.profile.personal = _.clone(this.user.profile_personal);
                });
            }

            if (this.list.relationships.length == 0) {
                axios.get('/list/relationship')
                .then(response => {
                    this.list.relationships = response.data;
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
        },

        addContact: function () {
            if (this.list.contacts.length == 0) {
                axios.get('/list/contact')
                .then(response => {
                    this.list.contacts = response.data;
                    this.profile.contact.type_id = 1;
                });
            }

            $('#modalContact').modal('show');
        },
        storeContact: function (e) {
            var btn = $(e.target).button('loading')
            this.profile.contact.user_id = this.user.id;
            axios.post('/profile/contact/store', this.profile.contact)
            .then(response => {
                this.user.profile_contact.push(response.data);
                this.profile.contact.contact = null;
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalContact').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyContact: function (contactId, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this contact!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/profile/contact/destroy/' + contactId)
                .then(response => {
                    self.user.profile_contact.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The contact has been deleted.",
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
        },

        addEducation: function () {
            $('#modalEducation').modal('show');
        },
        storeEducation: function (e) {
            var btn = $(e.target).button('loading')
            this.profile.education.user_id = this.user.id;
            axios.post('/profile/education/store', this.profile.education)
            .then(response => {
                this.user.profile_education.push(response.data);
                this.profile.education = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalEducation').modal('hide');
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
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/profile/education/destroy/' + educationId)
                .then(response => {
                    self.user.profile_education.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The education has been deleted.",
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
        },

        addFamily: function () {
            if (this.list.genders.length == 0) {
                axios.get('/list/gender')
                .then(response => {
                    this.list.genders = response.data;
                });
            }

            if (this.list.relations.length == 0) {
                axios.get('/list/relation')
                .then(response => {
                    this.list.relations = response.data;
                });
            }
            $('#modalFamily').modal('show');
        },
        storeFamily: function (e) {
            var btn = $(e.target).button('loading')
            this.profile.family.user_id = this.user.id;
            axios.post('/profile/family/store', this.profile.family)
            .then(response => {
                this.user.profile_family.push(response.data);
                this.profile.family = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalFamily').modal('hide');
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
                axios.delete('/profile/family/destroy/' + familyId)
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
        },

        addPlace: function () {
            this.profile.place.currently = false;
            $('#modalPlace').modal('show');
        },
        storePlace: function (e) {
            var btn = $(e.target).button('loading')
            this.profile.place.user_id = this.user.id;
            axios.post('/profile/place/store', this.profile.place)
            .then(response => {
                this.user.profile_place.push(response.data);
                this.profile.place = {};
                this.error = {};
                var btn = $(e.target).button('reset')
                $('#modalPlace').modal('hide');
            })
            .catch(error => {
                this.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        },
        destroyPlace: function (placeId, index) {
            var self = this;
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this place!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                axios.delete('/profile/place/destroy/' + placeId)
                .then(response => {
                    self.user.profile_place.splice(index, 1);
                    swal({
                        title: "Deleted!",
                        text: "The place has been deleted.",
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
