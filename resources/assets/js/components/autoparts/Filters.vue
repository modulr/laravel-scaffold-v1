<template lang="html">
    <div class="modal-content">
        <!-- Modal header -->
        <div class="modal-header">
            <button type="button" class="close pull-left"
                data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="pull-right">
                <button type="button" class="btn btn-link"
                    @click="clearFilters">Clear
                </button>
                <button type="button" class="btn btn-link"
                    @click="applyFilters">Apply
                </button>
            </div>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <h5>Filters</h5>
            <br>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <multiselect v-model="filters.make"
                            :options="lists.makes"
                            track-by="id"
                            label="name"
                            selectLabel=""
                            placeholder="Make"
                            @remove="filters.make={}">
                        </multiselect>
                    </div>
                    <div class="form-group">
                        <multiselect v-model="filters.model"
                            :options="filteredFiltersModels"
                            track-by="id"
                            label="name"
                            selectLabel=""
                            placeholder="Model"
                            @remove="filters.model={}">
                        </multiselect>
                    </div>
                    <div class="form-group">
                        <multiselect v-model="filters.years"
                            :options="lists.years"
                            track-by="id"
                            label="name"
                            selectLabel=""
                            placeholder="Years"
                            :multiple="true"
                            :close-on-select="false">
                        </multiselect>
                    </div>
                </div>
            </div>
            <fieldset class="separator">
                <legend>Status</legend>
                <div class="switch switch-block">
                    <label class="switch-label">
                        <input type="checkbox" v-model="filters.available">
                        <span class="slider round"></span>
                    </label>
                    <span class="switch-text">Available</span>
                </div>
                <div class="switch switch-block">
                    <label class="switch-label">
                        <input type="checkbox" v-model="filters.separated">
                        <span class="slider round"></span>
                    </label>
                    <span class="switch-text">Separated</span>
                </div>
                <div class="switch switch-block">
                    <label class="switch-label">
                        <input type="checkbox" v-model="filters.sold">
                        <span class="slider round"></span>
                    </label>
                    <span class="switch-text">Sold</span>
                </div>
                <div class="switch switch-block">
                    <label class="switch-label">
                        <input type="checkbox" v-model="filters.notAvailable">
                        <span class="slider round"></span>
                    </label>
                    <span class="switch-text">Not Available</span>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
    data() {
        return {
            lists: {
                makes: [],
                models: [],
                years: []
            },
            initFilters: {},
            filters: {
                make: {},
                model: {},
                years: [],
                available: true,
                separated: true,
                sold: true,
                notAvailable: true
            }
        }
    },
    props: [
        'defaultFilters'
    ],
    components: {
        Multiselect
    },
    computed: {
        filteredFiltersModels () {
            var filteredArray = this.lists.models

            if (this.filters.make.id != this.filters.model.make_id)
                this.filters.model = {}

            var makeId = this.filters.make.id
            filteredArray = filteredArray.filter(function(item)  {
                return item.make_id == makeId
            })

            return filteredArray
        }
    },
    mounted () {
        this.getLists()
        this.setFilters()
    },
    methods: {
        getLists () {
            axios.get('/api/autoparts/lists/makes')
            .then(response => {
                this.lists.makes = response.data
            })
            axios.get('/api/autoparts/lists/models')
            .then(response => {
                this.lists.models = response.data
            })
            axios.get('/api/autoparts/lists/years')
            .then(response => {
                this.lists.years = response.data
            })
        },
        applyFilters () {
            var years = []

            if (this.filters.years.length) {
                this.filters.years.forEach(function(val){
                    years.push(val.id)
                })
            }

            var filters = {
                make: this.filters.make,
                model: this.filters.model,
                years: years,
                available: this.filters.available,
                notAvailable: this.filters.notAvailable,
                separated: this.filters.separated,
                sold: this.filters.sold
            }

            this.$emit('apply-filters', filters)

            $('#modalFilters').modal('hide')
        },
        clearFilters () {
            this.filters = _.clone(this.initFilters)

            this.$emit('apply-filters', this.filters)

            $('#modalFilters').modal('hide')
        },
        setFilters () {
            if (this.defaultFilters)
                this.filters = _.clone(this.defaultFilters)

            this.initFilters = _.clone(this.filters)
        }
    }
}
</script>
