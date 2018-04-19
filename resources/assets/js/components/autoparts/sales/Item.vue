<template lang="html">
    <div class="modal-content autoparts-item">
        <!-- Modal header -->
        <div class="modal-header">
            <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <swiper :options="swiperOption" ref="mySwiper"
                v-if="autopart.images.length > 0">
                <swiper-slide v-for="image in autopart.images" :key="image.id">
                    <div>
                        <img :src="image.url">
                    </div>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
            <div class="img-rounded" v-else>
                <i class="fa fa-picture-o fa-5x"></i>
            </div>
            <p>
                ID: <strong>{{autopart.id}}</strong>
                <span class="label pull-right" :class="{
                    'label-primary': autopart.status_id == 1,
                    'label-default': autopart.status_id == 2,
                    'label-info': autopart.status_id == 3,
                    'label-success': autopart.status_id == 4
                    }">
                    {{autopart.status.name}}
                </span>
            </p>
            <h4>{{autopart.name}}</h4>
            <p class="lead text-muted">
                {{autopart.make.name}}
                - {{autopart.model.name}}
                -
                <span v-for="(year, index) in autopart.years">
                    {{year.name}}<span v-if="index+1 < autopart.years.length">, </span>
                </span>
            </p>
            <p class="text-muted">{{autopart.description}}</p>
            <br>
            <h4>
                {{autopart.sale_price | currency}}
                <small class="pull-right" :class="{
                    'text-success': autopart.origin_id == 1,
                    'text-info': autopart.origin_id == 2,
                    'text-primary': autopart.origin_id == 3
                    }">
                    {{autopart.origin.name}}
                </small>
            </h4>
            <br>
            <div class="row">
                <div class="col-xs-6" v-show="showBtnAvailable">
                    <button type="button" class="btn btn-primary btn-lg btn-block"
                        @click="updateStatus($event, 1)"
                        v-if="user.hasPermission['update-sales']">
                        Available
                    </button>
                </div>
                <div class="col-xs-6" v-show="showBtnSeparated">
                    <button type="button" class="btn btn-info btn-lg btn-block"
                        @click="updateStatus($event, 3)"
                        v-if="user.hasPermission['update-sales']">
                        Separated
                    </button>
                </div>
                <div class="col-xs-6" v-show="showBtnSold">
                    <button type="button" class="btn btn-success btn-lg btn-block"
                        @click="updateStatus($event, 4)"
                        v-if="user.hasPermission['update-sales']">
                        Sold
                    </button>
                </div>
            </div>
            <br>
            <ul class="nav nav-tabs border-bottom" role="tablist">
                <li role="presentation" class="active">
                    <a href="#comments" data-toggle="tab">Comments</a>
                </li>
                <li role="presentation">
                    <a href="#activity" data-toggle="tab">Activity</a>
                </li>
            </ul>
            <br>
            <div class="tab-content">
                <div class="tab-pane active" id="comments">
                    <comments
                        model="autopart"
                        :itemId="autopart.id"
                        :comments="autopart.comments">
                    </comments>
                </div>
                <div class="tab-pane" id="activity">
                    <activity :item="autopart"></activity>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import Vue2Filters from 'vue2-filters'
import {SnotifyService} from 'vue-snotify'

export default {
    data() {
        return {
            user: Laravel.user,
            swiperOption: {
                loop: true,
                pagination: '.swiper-pagination'
            },
            autopart: {
                make: {},
                model: {},
                years: [],
                images: [],
                status: {},
                origin: {},
                creator: {}
            }
        }
    },
    props: {
        item: {
            type: Object
        }
    },
    components: {
        swiper,
        swiperSlide
    },
    computed: {
        showBtnAvailable () {
            if (this.autopart.status_id != 1 && this.autopart.status_id != 2)
                return true

            return false
        },
        showBtnSeparated () {
            if (this.autopart.status_id != 3 && this.autopart.status_id != 2)
                return true

            return false
        },
        showBtnSold () {
            if (this.autopart.status_id != 4 && this.autopart.status_id != 2)
                return true

            return false
        }
    },
    watch: {
        item: function (newValue, oldValue) {
            this.autopart = newValue
        }
    },
    methods: {
        updateStatus (e, statusId) {
            var btn = $(e.target).button('loading')

            axios.put(`/api/autoparts/updateStatus/${this.autopart.id}`, {
                status_id: statusId
            })
            .then(response => {
                var autopart = response.data
                autopart.index = this.item.index
                this.$emit('updated', autopart)

                btn.button('reset')
                $('#modalAutopart').modal('hide')
            })
            .catch(error => {
                SnotifyService.error('Has an error occurred, try it later.')
                btn.button('reset')
            })
        }
    }
}
</script>
