<template lang="html">
    <div class="project">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>        
        <div class="project-content">            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{project.name}}</h3>
                    <a href="#" class="pull-right" @click.prevent="viewComments">View comments</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-4">
                            <dl>
                                <dd>Description</dd>
                                <dt>{{project.description}}</dt>
                            </dl>
                            <dl>
                                <dd>Registered Date</dd>
                                <dt>{{project.registered_date | date}}</dt>
                            </dl>
                            <dl>
                                <dd>Quotes</dd>
                                <dt>{{project.quote.length}}</dt>
                            </dl>
                        </div>
                        <div class="col-xs-4">
                            <dl>
                                <dd>Priority</dd>
                                <dt>{{project.priority.name}}</dt>
                            </dl>
                            <dl>
                                <dd>Owner</dd>
                                <dt><a :href="'/profile/'+project.owner.id">{{project.owner.name}}</a>
                                </dt>
                            </dl>
                            <dl>
                                <dd>Area</dd>
                                <dt v-if="project.area">{{project.area.title}}</dt>
                            </dl>
                        </div>
                        <div class="col-xs-4">
                            <dl>
                                <dd>Customer</dd>
                                <dt v-if="project.client.customer"><a :href="'/clients'">{{project.client.customer.name}}</a></dt>
                                <dt v-else>No existe este registro.</dt>
                            </dl>
                            <dl>
                                <dd>Client</dd>
                                <dt v-if="project.client"><a :href="'/clients'">{{project.client.name}}</a></dt>
                                <dt v-else>No existe este registro.</dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <comments :project="project" :comments="project.comment" class="modal right fade" id="modalComments"></comments>
            <list-quotes-project :project="project"></list-quotes-project>
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
            error: {}            
        }
    },
    props: ['project'],
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
        viewComments: function() {
            $('#modalComments').modal('show');
        }
    }
}
</script>
