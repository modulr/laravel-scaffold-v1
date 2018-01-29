<template>
    <div class="widget">
        <div class="panel panel-default panel-dashboard">
            <div class="panel-heading">
                <p class="lead">Projects</p>
                <a href="/projects" class="pull-right">View all</a>
            </div>
            <div class="panel-body">
                <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
                <div v-if="!loading">
                    <div class="info-block col-xs-12">
                        <div class="col-md-4">
                            <h3>{{projects.active.length}}</h3>
                            <p><strong>Active projects</strong> (this year).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{monthlyProjects}}</h3>
                            <p><strong>Registered projects</strong> (this month).</p>
                        </div>
                        <div class="col-md-4">
                            <h3>{{projects.all.length}}</h3>
                            <p><strong>Projects</strong> (this year).</p>
                        </div>
                    </div>
                    <div class="charts-block col-xs-12">
                        <div class="charts" v-if="total.active > 0 || total.rejected > 0">
                        <canvas id="projectCanvas" count="2"></canvas>
                        <chartjs-bar
                            :scalesdisplay="false"
                            target="projectCanvas"
                            :datalabel="activeProjects.chartTitle"
                            :labels="chartLabels"
                            :data="activeProjects.chartData"
                            v-if="activeProjects.chartData.length > 0"
                            :height="100"
                            :bordercolor="'#FEAE3B'"
                            :backgroundcolor="'#f4d4a6'"
                            ></chartjs-bar>
                        <chartjs-bar
                            :scalesdisplay="false"
                            target="projectCanvas"
                            :datalabel="rejectedProjects.chartTitle"
                            :labels="chartLabels"
                            :data="rejectedProjects.chartData"
                            v-if="rejectedProjects.chartData.length > 0"
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
            projects: {
                active: [],
                all: [],
            },
            project: {},
            search: '',
            error: {},
            monthlyProjects: '',
            total: {
                active: '',
                rejected: ''
            },
            chartLabels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
            activeProjects: {
                chartData: [],
                chartTitle: 'Active'
            },
            rejectedProjects: {
                chartData: [],
                chartTitle: 'Rejected'
            },
        }
    },
    mounted() {
        this.getProjectInsights();
        this.getMonthProjects();
        this.getCharts();
    },
    methods: {
        getProjectInsights: function() {
            this.loading = true;
            axios.get('/widget/getProjectInsights')
                .then(response => {
                    this.projects.active = response.data.active;
                    this.projects.all = response.data.all;
                    this.projects.rejected = response.data.rejected;
                    this.loading = false;
                });
        },
        getMonthProjects: function() {
            this.loading = true;
            axios.get('widget/getMonthProjects')
                .then(response => {
                    this.monthlyProjects = response.data;
                    this.loading = false;
                })
        },
        getCharts: function() {
            axios.get('/widget/project/charts')
                .then(response => {
                    this.activeProjects.chartData = Object.values(response.data.active);
                    this.rejectedProjects.chartData = Object.values(response.data.rejected);

                    this.total.active = this.activeProjects.chartData.reduce(function(a, b){ return a + b; });
                    this.total.rejected = this.rejectedProjects.chartData.reduce(function(a, b){ return a + b; });
                })
        }
    }
}
</script>
