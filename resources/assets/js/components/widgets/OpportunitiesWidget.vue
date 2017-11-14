<template>
    <div class="widget">
        <div class="panel panel-default panel-dashboard">
            <div class="panel-heading">
                <p class="lead">Opportunities</p>
                <a href="/opportunities" class="pull-right">View all</a>
            </div>
            <div class="panel-body">
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
                <div v-if="!loading">
                    <div class="info-block col-xs-12">
                        <div class="col-md-4">
                            <h3>{{opportunities.active.length}}</h3>
                            <p><strong>Active opportunities</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{monthlyOpportunities}}</h3>
                            <p><strong>Registered opportunities</strong> (this month).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{opportunities.all.length}}</h3>
                            <p><strong>Opportunities</strong> (this year).</p>
                        </div>
                    </div>
                    <div class="charts-block col-xs-12">
                        <div class="charts" v-if="total.active > 0 || total.rejected > 0">
                        <canvas id="opportunityCanvas" count="2"></canvas>
                        <chartjs-bar
                            :scalesdisplay="false"
                            target="opportunityCanvas"
                            :datalabel="activeOpportunities.chartTitle"
                            :labels="chartLabels"
                            :data="activeOpportunities.chartData"
                            v-if="activeOpportunities.chartData.length > 0"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#f4d4a6'"
                            ></chartjs-bar>
                        <chartjs-bar
                            :scalesdisplay="false"
                            target="opportunityCanvas"
                            :datalabel="rejectedOpportunities.chartTitle"
                            :labels="chartLabels"
                            :data="rejectedOpportunities.chartData"
                            v-if="rejectedOpportunities.chartData.length > 0"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#b4a898'"
                            ></chartjs-bar>
                        </div>
                        <div class="charts-error" v-else>
                            Not enough data to draw a chart.
                        </div>
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
            monthlyOpportunities: '',
            total: {
                active: '',
                rejected: ''
            },
            chartLabels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
            activeOpportunities: {
                chartData: [],
                chartTitle: 'Active'
            },
            rejectedOpportunities: {
                chartData: [],
                chartTitle: 'Rejected'
            },
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
                    this.monthlyOpportunities = response.data;
                    this.loading = false;
                })
        },
        getCharts: function() {
            axios.get('/widget/opportunity/charts')
                .then(response => {
                    this.activeOpportunities.chartData = Object.values(response.data.active);
                    this.rejectedOpportunities.chartData = Object.values(response.data.rejected);

                    this.total.active = this.activeOpportunities.chartData.reduce(function(a, b){ return a + b; });
                    this.total.rejected = this.rejectedOpportunities.chartData.reduce(function(a, b){ return a + b; });
                })
        }
    }
}
</script>
