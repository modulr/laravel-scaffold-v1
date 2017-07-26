<template>
    <div class="contacts">
        <div class="row">
            <div class="col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Search" v-model="search">
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <div class="button-group">
                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'list'}"
                        @click.prevent="toggleLayout('list')">
                        <i class="fa fa-lg fa-list" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="btn btn-link" :class="{ 'active': layout == 'grid'}"
                        @click.prevent="toggleLayout('grid')">
                        <i class="fa fa-lg fa-th" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 list" v-if="layout == 'list'">
                <table class="table table-hover">
                    <tbody>
                        <tr v-for="contact in filteredContacts">
                            <td @click="viewContact(contact)">
                                <img :src="contact.avatar_url">
                            </td>
                            <td @click="viewContact(contact)">
                                {{contact.name}}<br>
                                <small class="text-muted">{{contact.email}}</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4 col-md-3 grid" v-if="layout == 'grid'" v-for="contact in filteredContacts">
                <div class="panel panel-default">
                    <div class="panel-body" @click="viewContact(contact)">
                        <div class="media">
                            <div class="media">
                                <a href="#">
                                    <img :src="contact.avatar_url">
                                </a>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading contact-name">{{contact.name}}</h6>
                                <span class="contact-mail"><small>{{contact.email}}</small></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><img :src="contact.avatar_url">{{contact.name}}</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Contact details</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-fw fa-envelope" aria-hidden="true"></i><a :href="`mailto:${contact.email}`">{{contact.email}}</a></li>
                            <li><i class="fa fa-fw fa-phone" aria-hidden="true"></i>811123123</li>
                            <li><i class="fa fa-fw fa-birthday-cake" aria-hidden="true"></i>July 10, 1997</li>
                            <li><i class="fa fa-fw fa-calendar" aria-hidden="true"></i>July 10, 2017</li>
                            <li><i class="fa fa-fw fa-mortar-board" aria-hidden="true"></i>Tecnologico de Parral</li>
                            <li><i class="fa fa-fw fa-map-marker" aria-hidden="true"></i>Parral Chih.</li>
                            <li><i class="fa fa-fw fa-link" aria-hidden="true"></i><a href="http://www.example.com" target="_blank">www.example.com</a></li>
                        </ul>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                contacts: [],
                contact: [],
                search: '',
                layout: 'grid',
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
                this.contact = contact;
                $('#modalContact').modal('show');
            },
            toggleLayout: function (layout) {
                this.layout = layout;
                localStorage.setItem('contacts', JSON.stringify({'layout':layout}));
            },
        }
    }
</script>
