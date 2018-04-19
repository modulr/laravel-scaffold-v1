<template lang="html">
    <div>
        <!-- List Autoparts -->
        <div class="row autoparts-list" v-if="autoparts.length">
            <div class="col-md-12">
                <div class="media" v-for="(item, index) in autoparts"
                    @click="selectAutopart(item, index)">
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
                            {{item.name | truncate(20)}}
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
            <i class="mdi mdi-directions-car"></i>
            <p class="lead">Don't exist Autoparts!!</p>
        </div>
        <!-- Loading -->
        <infinite-loading
            @infinite="loadAutoparts"
            spinner="waveDots"
            :distance=300
            ref="infiniteLoading">
        </infinite-loading>
    </div>
</template>

<script>
import Vue2Filters from 'vue2-filters'
import InfiniteLoading from 'vue-infinite-loading'

export default {
    data () {
        return {
            autoparts: [],
            pagination: {
                current_page: 0
            }
        }
    },
    props: [
        'filters',
        'create',
        'update',
        'delete'
    ],
    components: {
        InfiniteLoading
    },
    watch: {
        filters: function (newValue, oldValue) {
            if (oldValue != newValue)
                this.filterUtoparts()
        },
        create: function (newValue, oldValue) {
            if (oldValue != newValue)
                this.createAutopart(newValue)
        },
        update: function (newValue, oldValue) {
            if (oldValue != newValue)
                this.updateAutopart(newValue)
        },
        delete: function (newValue, oldValue) {
            if (oldValue != newValue)
                this.deleteAutopart(newValue)
        }
    },
    methods: {
        loadAutoparts ($state) {
            var page = Number(this.pagination.current_page) + 1

            axios.post(`/api/autoparts/filter?page=${page}`, this.filters)
            .then(response => {
                this.pagination = response.data
                this.autoparts = this.autoparts.concat(response.data.data)

                if (this.autoparts.length) {
                    $state.loaded()

                    if (this.pagination.current_page == this.pagination.last_page)
                        $state.complete()

                } else {
                    $state.complete()
                }
            })
        },
        filterUtoparts () {
            this.autoparts = []
            this.pagination.current_page = 0

            this.$refs.infiniteLoading.stateChanger.reset()
        },
        selectAutopart (item, index) {
            item.index = index
            this.$emit('selected', item)
        },
        createAutopart (autopart) {
            this.autoparts.unshift(autopart)
        },
        updateAutopart (autopart) {
            this.$set(this.autoparts, autopart.index, autopart)
        },
        deleteAutopart (autopart) {
            this.autoparts.splice(autopart.index, 1)
        }
    }
}
</script>
