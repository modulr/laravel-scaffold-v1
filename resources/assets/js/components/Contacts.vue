<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <input type="text" class="form-control" placeholder="Search" v-model="search">
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tbody>
                        <tr v-for="contact in contacts">
                            <td @click="viewContact(contact)">
                                <img :src="contact.avatar" class=""> {{contact.name}}
                            </td>
                            <td @click="viewContact(contact)">
                                {{contact.email}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modalContact">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><img :src="contact.avatar" class=""> {{contact.name}}</h4>
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
                contactsCopy: [],
                contact: [],
                search: null,
            }
        },
        mounted() {
            this.getAll();
        },
        watch: {
            search: 'fetchContacts',
        },
        methods: {
            getAll: function () {
                axios.get('/contacts/all')
                .then(response => {
                    this.contacts = response.data;
                    this.contactsCopy = response.data;
                });
            },
            fetchContacts: function () {
                var self = this;
                if (this.search) {
                    this.contacts = this.contacts.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(self.search) > -1
                        })
                    })
                } else {
                    this.contacts = this.contactsCopy;
                }
            },
            viewContact: function (contact) {
                this.contact = contact;
                $('#modalContact').modal('show');
            }
        }
    }
</script>

<style lang="scss">
    .table {
        > tbody > tr:first-child > td {
            border-top-color: transparent;
        }
        img {
            border-radius: 50%;
            height: 35px;
            width: 35px;
            margin-right: 10px;
        }
    }
    .modal {
        .modal-header {
            img {
                border-radius: 50%;
                height: 65px;
                width: 65px;
                margin-left: 15px;
                margin-right: 15px;
                -webkit-box-shadow: 0 0 1px 6px #e8e8e8;
                box-shadow: 0 0 1px 6px #e8e8e8;
            }
        }
        .modal-body {
            padding: 30px;
            ul {
                margin: 25px;
                li {
                    margin-bottom: 15px;
                    i {
                        margin-right: 30px;
                    }
                }
            }
        }
    }
</style>
