<template>
    <div class="widget">
        <div class="panel panel-default panel-dashboard">
            <div class="panel-heading">
                <p class="lead">Projects</p>
                <a href="/projects" class="pull-right">View all</a>
            </div>
            <div class="panel-body" style="height:450px; overflow-y:scroll;">
                <div class="info-block">
                    <h3>22 <span class="inner-text"> active projects.</span></h3>
                    <!-- TODO: PIECHART DE PROCENTAJE DE PROYECTOS ACTIVOS CONTRA CANCELADOS -->
                    <div class="col-sm-4">
                        <pie-chart
                            :data="[['Accepted', 22], ['Pending', 12]]"
                            :colors="['#FEAE3B', '#7E76EE']"
                        ></pie-chart>
                    </div>
                    <div class="col-sm-8">
                        <line-chart :data="{'2017-10-11 00:00:00 -0800': 11, '2017-10-22 00:00:00 -0800': 6, '2017-10-22 00:00:00 -0800': 22, '2017-11-11 00:00:00 -0800': 6, '2017-11-22 00:00:00 -0800': 31}"></line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

    }
</script>
