<template lang="html">
    <div class="opportunity">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="opportunity-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{opportunity.name}}</h3>
                    <a href="#" class="pull-right" @click.prevent="viewComments">View comments</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-4">
                            <dl>
                                <dd>Description</dd>
                                <dt>{{opportunity.description}}</dt>
                            </dl>
                            <dl>
                                <dd>Registered Date</dd>
                                <dt>{{opportunity.registered_date | date}}</dt>
                            </dl>
                            <dl>
                                <dd>Quotes</dd>
                                <dt>{{opportunity.quote.length}}</dt>
                            </dl>
                        </div>
                        <div class="col-xs-4">
                            <dl>
                                <dd>Priority</dd>
                                <dt>{{opportunity.priority.name}}</dt>
                            </dl>
                            <dl>
                                <dd>Owner</dd>
                                <dt><a :href="'/profile/'+opportunity.owner.id">{{opportunity.owner.name}}</a>
                                </dt>
                            </dl>
                            <dl>
                                <dd>Area</dd>
                                <dt v-if="opportunity.area">{{opportunity.area.title}}</dt>
                            </dl>
                        </div>
                        <div class="col-xs-4">
                            <dl>
                                <dd>Customer</dd>
                                <dt><a :href="'/clients'">{{opportunity.client.customer.name}}</a></dt>
                            </dl>
                            <dl>
                                <dd>Client</dd>
                                <dt><a :href="'/clients'">{{opportunity.client.name}}</a></dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <comments :opportunity="opportunity" :comments="opportunity.comment" class="modal right fade" id="modalComments"></comments>
            <list-quotes :opportunity="opportunity"></list-quotes>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
import Vue2Filters from 'vue2-filters';
export default {
    data() {
        return {
            loading: false,
            error: {},
        }
    },
    props: ['opportunity'],
    components: {
      Spinner
    },
    mounted() {

    },
    filters: {
        date(date) {
            return moment(date).format('LL');
        }
    },
    methods: {
        addQuote: function() {
            this.quote = {
            name: '',
            registered: '',
            description: '',
            };
            this.error = {};

            $('#modalAddQuote').show();
        },
        viewComments: function() {
            $('#modalComments').modal('show');
        }
    }
}
</script>
