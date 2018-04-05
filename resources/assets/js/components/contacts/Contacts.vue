<template>
    <div class="contacts">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="form-group input-lg search" :class="{'active': active}">
                    <input type="text" class="form-control" ref="search" placeholder="Search"
                        v-model="search">
                    <button type="button" class="close" aria-label="Close"
                        @click="toggleSearchBtn(false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-xs-4 links">
                        <h5>Contacts</h5>
                    </div>
                    <div class="col-sm-8 text-right controls">
                        <a href="#" class="btn btn-link"
                            @click="toggleSearchBtn(true)">
                            <i class="mdi mdi-search mdi-lg"></i>
                        </a>
                        <a href="#" class="btn btn-link" @click.prevent="toggleLayout">
                            <i class="fa fa-lg" aria-hidden="true"
                               :class="{'fa-list': layout == 'grid', 'fa-th-large': layout == 'list'}">
                            </i>
                        </a>
                        <a href="#" class="btn btn-success" @click="comingSoon">
                            <i class="mdi mdi-person-add mdi-lg"></i>
                            <span class="hidden-xs">Contact</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Contacts -->
            <div class="row">
                <!-- Layout list -->
                <div class="col-md-12 list" v-show="layout == 'list'">
                    <table class="table table-hover">
                        <tbody>
                            <tr v-for="contact in filteredContacts">
                                <td @click="viewContact(contact.id)">
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="avatar-xs" :src="contact.avatar_url">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">{{contact.name}}</h4>
                                            <span class="text-muted">{{contact.email}}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- layout Grid -->
                <div class="col-xs-12 col-sm-4 col-md-3" v-show="layout == 'grid'"
                    v-for="contact in filteredContacts">
                    <div class="thumbnail" @click="viewContact(contact.id)">
                        <img class="avatar-md" :src="contact.avatar_url">
                        <div class="caption">
                            <h4 class="media-heading">{{contact.name}}</h4>
                            <span class="text-muted">{{contact.email}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal View Contact -->
        <div class="modal right sm" id="modalViewContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="avatar-md" :src="contactView.avatar_url">
                            <h4>{{contactView.name}}</h4>
                            <a :href="`mailto:${contactView.email}`">{{contactView.email}}</a>
                        </div>
                        <hr>
                        <div>
                            <dl>
                                <dd>Phone Number</dd>
                                <dt v-if="contactView.profile_contact && contactView.profile_contact.length"
                                    v-for="profile_contact in contactView.profile_contact">
                                    <span v-if="profile_contact.type_id == 2">{{profile_contact.contact}}</span>
                                </dt>
                            </dl>
                            <dl>
                                <dd>Cellphone Number</dd>
                                <dt v-if="contactView.profile_contact && contactView.profile_contact.length"
                                    v-for="profile_contact in contactView.profile_contact">
                                    <span v-if="profile_contact.type_id == 3">{{profile_contact.contact}}</span>
                                </dt>
                            </dl>
                            <dl>
                                <dd>Location</dd>
                                <dt v-if="contactView.profile_place && contactView.profile_place.length"
                                    v-for="profile_place in contactView.profile_place">
                                    <span v-if="profile_place.currently">{{profile_place.place}}</span>
                                </dt>
                            </dl>
                            <hr>
                            <dl v-if="contactView.profile_work">
                                <dd>Profession</dd>
                                <dt v-if="contactView.profile_work.profession">
                                    {{contactView.profile_work.profession.title}}
                                </dt>
                            </dl>
                            <dl v-if="contactView.profile_work">
                                <dd>Position</dd>
                                <dt v-if="contactView.profile_work.position">
                                    {{contactView.profile_work.position.title}}
                                </dt>
                            </dl>
                            <dl v-if="contactView.profile_work">
                                <dd>Department</dd>
                                <dt v-if="contactView.profile_work.department">
                                    {{contactView.profile_work.department.title}}
                                </dt>
                            </dl>
                            <dl v-if="contactView.profile_work">
                                <dd>Boss</dd>
                                <dt v-if="contactView.profile_work.boss">
                                    {{contactView.profile_work.boss.name}}
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
                search: '',
                active: false,
                layout: 'list',
                contacts: [],
                contactView: {},
            }
        },
        mounted() {
            this.getContacts();

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
            toggleSearchBtn (active) {
                this.search = null
                if (active) {
                    this.active = active
                    var self = this
                    setTimeout(function() {
                        self.$refs.search.focus()
                    }, 100);
                } else {
                    this.active = active
                }
            },
            getContacts () {
                axios.get('/api/contacts/all')
                .then(response => {
                    this.contacts = response.data;
                });
            },
            viewContact (contactId) {
                axios.get('/api/contacts/'+contactId)
                .then(response => {
                    this.contactView = response.data;
                    $('#modalViewContact').modal('show');
                });
            },
            toggleLayout () {
                this.layout == 'list' ? this.layout = 'grid' : this.layout = 'list';
                localStorage.setItem('contacts', JSON.stringify({'layout':this.layout}));
            },
            comingSoon () {
                swal('Coming soon...');
            },
        }
    }
</script>
