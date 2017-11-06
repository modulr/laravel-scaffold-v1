<template lang="html">
    <div class="opportunity">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <!-- Actions Buttons -->
        <div class="opportunity-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{opportunity.name}}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <dl>
                                <dd>Description</dd>
                                <dt>{{opportunity.description}}</dt>
                            </dl>
                            <dl>
                                <dd>Registered Date</dd>
                                <dt>{{opportunity.start_date | date}}</dt>
                            </dl>
                            <dl>
                                <dd>Quotes</dd>
                                <dt>{{opportunity.quote.length}}</dt>
                            </dl>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
            <div class="row" v-if="opportunity.quote.length == 0">
              <div class="col-md-12">
                <h3>No quotes found.</h3>
              </div>
            </div>
            <div class="row" v-if="opportunity.quote.length != 0">
                <h3>Quotes</h3>
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Request Date</th>
                                <th>Designer</th>
                                <th>Salesman</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in opportunity.quote">
                                <td>
                                    {{item.id}}
                                </td>
                                <td>
                                    {{item.name}}
                                </td>
                                <td>
                                    {{item.description}}
                                </td>
                                <td>
                                    {{item.amount | currency}}
                                </td>
                                <td>
                                    {{item.request_date | date}}
                                </td>
                                <td v-if="item.designer">
                                    {{item.designer.name}}
                                </td>
                                <td v-if="item.salesman">
                                    {{item.salesman.name}}
                                </td>
                                <td>
                                    {{item.status.title}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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

    }
}
</script>
