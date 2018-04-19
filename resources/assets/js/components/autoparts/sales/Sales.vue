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
            <autoparts-list
                @selected="editAutopart"
                :filters="filters"
                :update="autopart">
            </autoparts-list>
        </div>
        <!-- Modal Autopart -->
        <div class="modal right md" id="modalAutopart">
            <div class="modal-dialog">
                <autoparts-sales-item
                    :item="autopart"
                    @updated="autopart = $event">
                </autoparts-sales-item>
            </div>
        </div>
        <!-- Modal Filters -->
        <div class="modal left sm" id="modalFilters">
            <div class="modal-dialog">
                <autoparts-filters
                    :default-filters="filters"
                    @apply-filters="filters = $event">
                </autoparts-filters>
            </div>
        </div>
        <!-- Modal Search QR -->
        <div class="modal right md" id="modalSearchQR">
            <div class="modal-dialog">
                <autoparts-search-qr
                    @find="findAutopart">
                </autoparts-search-qr>
            </div>
        </div>
    </div>
</template>

<script>
import AutopartsList from '../List.vue'
import AutopartsFilters from '../Filters.vue'
import AutopartsSearchQr from '../SearchQr.vue'
import AutopartsSalesItem from './Item.vue'

export default {
    data () {
        return {
            filters: {
                make: {},
                model: {},
                years: [],
                available: true,
                separated: true,
                sold: false,
                notAvailable: false
            },
            autopart: {}
        }
    },
    methods: {
        editAutopart (autopart) {
            this.autopart = autopart
            $('#modalAutopart').modal('show')
        },
        showFilters () {
            $('#modalFilters').modal('show')
        },
        showSearchQR () {
            $('#modalSearchQR').modal('show')
        },
        findAutopart (autopart) {
            this.autopart = autopart
            $('#modalAutopart').modal('show')
        }
    }
}
</script>
