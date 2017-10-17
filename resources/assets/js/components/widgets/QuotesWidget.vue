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
                            <h3>{{quotes.totalAmountMXN | currency}}<small>MXN</small></h3>
                            <h3>{{quotes.totalAmountUSD | currency}}<small>USD</small></h3>
                            <p><strong>Total amount quoted</strong> (this year).</p>
                        </div>
                        <!-- TODO: GRAFICA DE COLUMNAS DE 12 MESES CON CANTIDADES $ COTIZADAS MENSUALMENTE -->
                        <!-- <column-chart
                            :data="chartData"
                            :colors="['#FEAE3B']"
                            height="270px"></column-chart> -->
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
import Vue2Filters from 'vue2-filters';
import Chart from 'chart.js';
Vue.use(VueChartkick, { Chartkick })

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
            chartData: [['jan', 145200], ['feb', 50900], ['mar', 60980], ['apr', 89065], ['may', 140000], ['jun', 356700], ['jul', 23600], ['aug', 70000], ['sep', 320000], ['oct', 243000], ['nov', 0], ['dec', 0]],
        }
    },
    mounted() {
        this.getQuoteInsights();
    },
    components: {
        VueChartkick, Chartkick
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
    }
}
</script>
