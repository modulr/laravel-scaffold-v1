<template>
    <div class="widget">
        <div class="panel panel-default panel-dashboard">
            <div class="panel-heading">
                <p class="lead">Opportunities</p>
                <a href="/opportunities" class="pull-right">View all</a>
            </div>
            <div class="panel-body" style="height:455px; overflow-y:scroll;">
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
                <div v-if="!loading">
                    <div class="info-block">
                        <div class="col-md-4">
                            <h3>34</h3>
                            <p><strong>Registered opportunities</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{total}}</h3>
                            <p><strong>Registered opportunities</strong> (this month).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>22</h3>
                            <p><strong>Opportunities</strong> (this year).</p>
                        </div>
                        <!-- TODO: GRAFICA DE COLUMNAS DE 12 MESES CON OPORTUNIDADES REGISTRADAS MENSUALMENTE -->
                        <column-chart
                            :data="chartData"
                            :colors="['#FEAE3B']"
                            height="270px"></column-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Spinner from 'vue-simple-spinner';
import Chartkick from 'chartkick';
import VueChartkick from 'vue-chartkick';
import Chart from 'chart.js';
Vue.use(VueChartkick, { Chartkick })

export default {
    data() {
        return {
            loading: false,
            opportunities: [],
            opportunity: {},
            search: '',
            error: {},
            total: '',
            chartData: [['jan', 6], ['feb', 3], ['mar', 5], ['apr', 7], ['may', 2], ['jun', 5], ['jul', 1], ['aug', 3], ['sep', 2], ['oct', 1], ['nov', 0], ['dec', 0]],
        }
    },
    mounted() {
        this.getAll();
        this.getMonthOpportunities();
    },
    components: {
        VueChartkick, Chartkick
    },
    methods: {
        getAll: function() {
            this.loading = true;
            axios.get('/opportunities/all')
                .then(response => {
                    this.opportunities = response.data;
                    this.loading = false;
                });
        },
        getMonthOpportunities: function() {
            this.loading = true;
            axios.get('widget/getMonthOpportunities')
                .then(response => {
                    this.total = response.data;
                    this.loading = false;
                })
        }
    }
}
</script>
