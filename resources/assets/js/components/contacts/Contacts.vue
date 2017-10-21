<template>
    <div>
        <!-- Header -->
        <div class="container-fluid header">
            <div class="row">
                <div class="col-xs-6 header-title">
                    <h1>Contacts</h1>
                </div>
                <div class="col-xs-6 header-buttons">
                    <!-- <a href="#" class="btn btn-success">
                        <i class="mdi mdi-person-add mdi-lg"></i> New Contact
                    </a> -->
                </div>
            </div>
        </div>
        <div class="container-fluid contacts">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <!-- Actionbar -->
                    <div class="actionbar">
                        <div class="row">
                            <div class="col-xs-6 col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search">
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-8 text-right">
                                <div class="button-group">
                                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'list'}"
                                        @click.prevent="toggleLayout('list')">
                                        <i class="fa fa-lg fa-list" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'grid'}"
                                        @click.prevent="toggleLayout('grid')">
                                        <i class="fa fa-lg fa-th-large" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- List -->
                    <div class="row">
                        <!-- Layout list -->
                        <div class="col-md-12 list" v-if="layout == 'list'">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr v-for="contact in filteredContacts">
                                                <td @click="viewContact(contact)">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img class="avatar-sm" :src="contact.avatar_url">
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="media-heading">{{contact.name}}</p>
                                                            <span class="text-muted">{{contact.email}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- layout Grid -->
                        <div class="col-sm-4 col-lg-3 grid" v-if="layout == 'grid'"
                             v-for="contact in filteredContacts">
                            <div class="panel panel-default">
                                <div class="panel-body" @click="viewContact(contact)">
                                    <img class="avatar-md" :src="contact.avatar_url">
                                    <h4 class="media-heading">{{contact.name}}</h4>
                                    <span class="text-muted">{{contact.email}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal view contact -->
        <div class="modal right fade modal-contacts" data-backdrop="false" id="modalViewContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="contact-header">
                            <img class="avatar-md" :src="contact.avatar_url">
                            <h5>{{contact.name}}</h5>
                        </div>
                        <div class="contact-body">
                            <dl>
                                <dd>Email Address</dd>
                                <dt><a :href="`mailto:${contact.email}`">{{contact.email}}</a></dt>
                            </dl>
                            <dl>
                                <dd>Phone Number</dd>
                                <dt v-if="contact.profile_contact && contact.profile_contact.length"
                                    v-for="profile_contact in contact.profile_contact">
                                    <span v-if="profile_contact.type_id == 2">{{profile_contact.contact}}</span>
                                </dt>
                            </dl>
                            <dl>
                                <dd>Location</dd>
                                <dt v-if="contact.profile_place && contact.profile_place.length"
                                    v-for="profile_place in contact.profile_place">
                                    <span v-if="profile_place.currently">{{profile_place.place}}</span>
                                </dt>
                            </dl>
                            <hr>
                            <dl>
                                <dd>Profession</dd>
                                <dt v-if="contact.profile_work">
                                    {{contact.profile_work.profession.title}}
                                </dt>
                            </dl>
                            <dl>
                                <dd>Position</dd>
                                <dt v-if="contact.profile_work">
                                    {{contact.profile_work.position.title}}
                                </dt>
                            </dl>
                            <dl>
                                <dd>Department</dd>
                                <dt v-if="contact.profile_work">
                                    {{contact.profile_work.department.title}}
                                </dt>
                            </dl>
                            <dl>
                                <dd>Boss</dd>
                                <dt v-if="contact.profile_work">
                                    {{contact.profile_work.boss.name}}
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                contacts: [],
                contact: {},
                search: '',
                layout: 'list',
            }
        },
        mounted() {
            this.getAll();
            if (JSON.parse(localStorage.getItem('contacts'))) {
                this.layout = JSON.parse(localStorage.getItem('contacts')).layout;
            }
        },
        computed: {
            filteredContacts: function () {
                var filteredArray = this.contacts,
                    search = this.search;

                if(!search){
                    return filteredArray;
                }

                search = search.trim().toLowerCase();

                filteredArray = filteredArray.filter(function(item){
                    return Object.keys(item).some(function (key) {
                        return String(item[key]).toLowerCase().indexOf(search) !== -1
                    })
                })

                return filteredArray;;
            }
        },
        methods: {
            getAll: function () {
                axios.get('/contacts/all')
                .then(response => {
                    this.contacts = response.data;
                });
            },
            viewContact: function (contact) {
                axios.get('/contacts/'+contact.id)
                .then(response => {
                    this.contact = response.data;
                    $('#modalViewContact').modal('show');
                });
            },
            toggleLayout: function (layout) {
                this.layout = layout;
                localStorage.setItem('contacts', JSON.stringify({'layout':layout}));
            },
        }
    }
</script>
