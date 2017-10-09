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
                        <h3>{{'$1,499,445'}}</h3>
                        <p><strong>Total amount quoted</strong> (this year).</p>
                        <!-- TODO: GRAFICA DE COLUMNAS DE 12 MESES CON CANTIDADES $ COTIZADAS MENSUALMENTE -->
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
            chartData: [['jan', 145200], ['feb', 50900], ['mar', 60980], ['apr', 89065], ['may', 140000], ['jun', 356700], ['jul', 23600], ['aug', 70000], ['sep', 320000], ['oct', 243000], ['nov', 0], ['dec', 0]],
        }
    },
    mounted() {
        this.delay();
    },
    components: {
        VueChartkick, Chartkick
    },
    methods: {
        delay: function() {
            this.loading = true;
            setTimeout(() => {
                this.loading = false;
            }, 2000);
        }
    }
}
</script>
