<template lang="html">
    <div>
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Sales</h5>
                    </div>
                    <div class="col-xs-6 text-right controls">
                        <!-- <input type="text" class="form-control" placeholder="Search" v-model="search"> -->
                        <a href="#" class="btn btn-default"
                            @click.prevent="showFilters">
                            <i class="fa fa-sliders fa-lg" aria-hidden="true"></i>
                            <span class="hidden-xs">Filters</span>
                        </a>
                        <a href="#" class="btn btn-default"
                            @click.prevent="showSearchQR">
                            <i class="fa fa-qrcode fa-lg" aria-hidden="true"></i>
                            <span class="hidden-xs">Search</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Autoparts -->
            <div class="row autoparts-list" v-if="autoparts.data.length">
                <div class="col-md-12">
                    <div class="media" v-for="(item, index) in autoparts.data"
                        @click="showAutopart(item)">
                        <div class="media-left">
                            <span class="label" :class="{
                                'label-primary': item.status_id == 1,
                                'label-default': item.status_id == 2,
                                'label-info': item.status_id == 3,
                                'label-success': item.status_id == 4
                                }">
                                {{item.status.name}}
                            </span>
                            <img class="img-rounded" :src="item.images[0].url_thumbnail"
                                v-if="item.images.length">
                            <div class="img-rounded" v-else>
                                <i class="fa fa-picture-o fa-5x"></i>
                            </div>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading">
                                {{item.name}}
                                <span class="pull-right hidden-xs">
                                    ID: <strong>{{item.id}}</strong>
                                </span>
                            </h5>
                            <p class="text-muted">
                                {{item.make.name}} - {{item.model.name}} -
                                <span v-for="(year, index) in item.years">
                                    {{year.name}}<span v-if="index+1 < item.years.length">, </span>
                                </span>
                                <small class="text-muted pull-right hidden-xs">
                                    <small>Created at </small>
                                    {{item.created_at | moment('lll')}},
                                    <small>by </small>{{item.creator.name}}
                                </small>
                            </p>
                            <h5>
                                {{item.sale_price | currency}}
                                <small class="pull-right" :class="{
                                    'text-success': item.origin_id == 1,
                                    'text-info': item.origin_id == 2,
                                    'text-primary': item.origin_id == 3
                                    }">
                                    {{item.origin.name}}
                                </small>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Init Message -->
            <div class="init-message" v-else>
                <i class="mdi mdi-directions-car" aria-hidden="true"></i>
                <p class="lead">Don't exist Autoparts!!</p>
            </div>
        </div>
        <!-- Modal Autopart -->
        <div class="modal right md autoparts-item" id="modalAutopart">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <autoparts-sales-item :model="autopart" @updateStatus="updateStatus"></autoparts-sales-item>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Filters -->
        <div class="modal left sm" id="modalFilters">
            <div class="modal-dialog">
                <div class="modal-content">
                    <autoparts-sales-filters @filterAutoparts="autoparts = $event"></autoparts-sales-filters>
                </div>
            </div>
        </div>
        <!-- Modal Search QR -->
        <div class="modal fade autoparts-qr" id="modalSearchQR">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p class="modal-title">Scan the QR code</p>
                    </div>
                    <div class="modal-body">
                        <autoparts-sales-search-qr @findAutopart="autopart = $event"></autoparts-sales-search-qr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AutopartsSalesFilters from './Filters.vue'
import AutopartsSalesSearchQr from './SearchQr.vue'
import AutopartsSalesItem from './Item.vue'
export default {
    data () {
        return {
            autoparts: {
                data: []
                // paginate info
                // ...
            },
            autopart: {}
        }
    },
    methods: {
        showAutopart (autopart) {
            this.autopart = autopart
            $('#modalAutopart').modal('show')
        },
        showFilters () {
            $('#modalFilters').modal('show')
        },
        showSearchQR () {
            $('#modalSearchQR').modal('show')
        },
        updateStatus (autopart) {
            this.autoparts.data.forEach(function(val){
                if (val.id == autopart.id) {
                    val.status = autopart.status
                    val.status_id = autopart.status_id
                }
            })
        }
    }
}
</script>
