<template lang="html">
    <div>
        <!-- Container -->
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Inventory</h5>
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
                        <a href="#" class="btn btn-success"
                            v-if="user.hasPermission['create-inventory']"
                            @click.prevent="newAutopart">
                            <i class="fa fa-plus"></i>
                            <span class="hidden-xs">New</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- List Autoparts -->
            <autoparts-list
                @selected="editAutopart"
                :filters="filters"
                :create="autopartCreated"
                :update="autopartUpdated"
                :delete="autopartDeleted">
            </autoparts-list>
        </div>
        <!-- Modal Autopart -->
        <div class="modal right md" id="modalAutopart">
            <div class="modal-dialog">
                <autoparts-inventory-item
                    :item="autopart"
                    @created="autopartCreated = $event"
                    @updated="autopartUpdated = $event"
                    @deleted="autopartDeleted = $event">
                </autoparts-inventory-item>
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
import AutopartsInventoryItem from './Item.vue'

export default {
    data () {
        return {
            user: Laravel.user,
            filters: {
                make: {},
                model: {},
                years: [],
                available: true,
                separated: true,
                sold: true,
                notAvailable: true
            },
            autopart: {},
            autopartCreated: {},
            autopartUpdated: {},
            autopartDeleted: {}
        }
    },
    methods : {
        newAutopart () {
            this.autopart = {
                make: {},
                model: {},
                years: [],
                origin_id: 1,
                status_id: 1,
                images: [],
                creator: {}
            }
            $('#modalAutopart').modal('show');
        },
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
