<template>
    <div class="contacts">
        <!-- List Contacts -->
        <div class="row">
            <!-- Layout list -->
            <div class="col-md-12">
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
        </div>
        <!-- Modal View Contact -->
        <div class="modal fade modal-contacts" id="modalViewContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="contact-heading">
                            <img class="avatar-md" :src="contactView.avatar_url">
                            <h4>{{contactView.name}}</h4>
                            <a :href="`mailto:${contactView.email}`">{{contactView.email}}</a>
                        </div>
                        <div class="contact-body">
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
                contacts: [],
                contactView: {},
            }
        },
        mounted() {
            this.getContacts();
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
        }
    }
</script>
