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
                            <h3>{{opportunities.active.length}}</h3>
                            <p><strong>Active opportunities</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{total}}</h3>
                            <p><strong>Registered opportunities</strong> (this month).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{opportunities.all.length}}</h3>
                            <p><strong>Opportunities</strong> (this year).</p>
                        </div>
                        <chartjs-bar
                            :datalabel="chartTitle"
                            :labels="chartLabels"
                            :data="chartData"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#f4d4a6'"
                            v-if="chartData.length > 0 && chartTitle"
                            ></chartjs-bar>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Spinner from 'vue-simple-spinner';
// chartjs package
require('chart.js');
// vue-charts package
require('hchs-vue-charts');
Vue.use(VueCharts);

export default {
    data() {
        return {
            loading: false,
            opportunities: {
                active: [],
                all: [],
            },
            opportunity: {},
            search: '',
            error: {},
            total: '',
            chartData: [],
            chartLabels: [],
            chartTitle: ''
        }
    },
    mounted() {
        this.getOpportunityInsights();
        this.getMonthOpportunities();
        this.getCharts();
    },
    methods: {
        getOpportunityInsights: function() {
            this.loading = true;
            axios.get('/widget/getOpportunityInsights')
                .then(response => {
                    this.opportunities.active = response.data.active;
                    this.opportunities.all = response.data.all;
                    this.opportunities.rejected = response.data.rejected;
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
        },
        getCharts: function() {
            axios.get('/widget/opportunity/charts')
                .then(response => {
                    this.chartTitle = response.data.title;
                    this.chartLabels = response.data.activeCharts.map(chart => chart.date);
                    this.chartData = response.data.activeCharts.map(chart => chart.projects);
                })
        }
    }
}
</script>
