<template>
    <div class="widget">
        <div class="panel panel-default panel-dashboard">
            <div class="panel-heading">
                <p class="lead">Quotes</p>
                <a href="/quote" class="pull-right">View all</a>
            </div>
            <div class="panel-body" style="height:455px; overflow-y:scroll;">
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
                <div v-if="!loading">
                    <div class="info-block">
                        <div class="col-md-4">
                            <h5>{{quotes.totalAmountMXN | currency}}<small>MXN</small></h5>
                            <h5>{{quotes.totalAmountUSD | currency}}<small>USD</small></h5>
                            <p><strong>Total amount quoted</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{quotes.active.length}}</h3>
                            <p><strong>Active quotes</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{quotes.rejected.length}}</h3>
                            <p><strong>Rejected quotes</strong> (this year).</p>
                        </div>
                        <canvas id="quoteCanvas" count="2"></canvas>
                        <chartjs-bar
                            target="quoteCanvas"
                            :datalabel="activeQuotes.chartTitle"
                            :labels="chartLabels"
                            :data="activeQuotes.chartData"
                            v-if="activeQuotes.chartData.length > 0"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#f4d4a6'"
                            ></chartjs-bar>
                        <chartjs-bar
                            target="quoteCanvas"
                            :datalabel="rejectedQuotes.chartTitle"
                            :labels="chartLabels"
                            :data="rejectedQuotes.chartData"
                            v-if="rejectedQuotes.chartData.length > 0"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#b4a898'"
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
            quotes: {
                totalAmountMXN: '',
                totalAmountUSD: '',
                active: [],
                rejected: [],
                all: [],
            },
            opportunity: {},
            search: '',
            error: {},
            total: '',
            chartLabels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
            activeQuotes: {
                chartData: [],
                chartTitle: 'Active'
            },
            rejectedQuotes: {
                chartData: [],
                chartTitle: 'Rejected'
            },
        }
    },
    mounted() {
        this.getQuoteInsights();
        this.getCharts();
    },
    methods: {
        getQuoteInsights: function() {
            this.loading = true;
            axios.get('/widget/getQuoteInsights')
                .then(response => {
                    this.quotes.active = response.data.active;
                    this.quotes.all = response.data.all;
                    this.quotes.rejected = response.data.rejected;
                    this.quotes.totalAmountMXN = response.data.totalMXN;
                    this.quotes.totalAmountUSD = response.data.totalUSD;
                    this.loading = false;
                });
        },
        getCharts: function() {
            axios.get('/widget/quote/charts')
                .then(response => {
                    this.activeQuotes.chartData = Object.values(response.data.active);
                    this.rejectedQuotes.chartData = Object.values(response.data.rejected);
                })
        }
    }
}
</script>
