<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Contact</h3>
        </div>
        <div class="panel-body">
            <dl v-for="(contact, index) in user.profile_contact">
                <a href="#" class="btn btn-link pull-right" @click.prevent="destroyContact(contact.id, index)">
                    <i class="fa fa-trash"></i>
                </a>
                <dt>
                    <i class="fa fa-fw" :class="{
                        'fa-envelope': contact.type_id == 1,
                        'fa-phone': contact.type_id == 2,
                        'fa-mobile': contact.type_id == 3,
                        }" aria-hidden="true"></i> {{contact.contact}}
                </dt>
            </dl>
            <hr>
            <form @submit.prevent>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error': error.contact}">
                            <input type="text" class="form-control" placeholder="example@modulr.io"
                                v-model="contact.contact">
                            <span class="help-block" v-if="error.contact">{{error.contact[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-error': error.type_id}">
                            <select class="form-control text-capitalize"
                                v-model="contact.type_id">
                                <option v-for="option in list.contacts"
                                    :value="option.id">
                                    {{ option.title }}
                                </option>
                            </select>
                            <span class="help-block" v-if="error.type_id">{{error.type_id[0]}}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" @click="storeContact">Add</button>
                    </div>
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
                contacts: []
            },
            contact: {
                type_id: 1
            },
            error: {}
        }
    },
    props: ['user'],
    mounted() {
        axios.get('/api/list/profile/contact')
        .then(response => {
            this.list.contacts = response.data;
        });
    },
    methods: {
        storeContact: function (e) {
            var btn = $(e.target).button('loading')
            this.contact.user_id = this.user.id;
            axios.post('/api/profile/contact/store', this.contact)
            .then(response => {
                this.user.profile_contact.push(response.data);
                this.contact.contact = null;
                this.error = {};
                var btn = $(e.target).button('reset')
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
                axios.delete('/api/profile/contact/destroy/' + contactId)
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
        }
    }
}
</script>
