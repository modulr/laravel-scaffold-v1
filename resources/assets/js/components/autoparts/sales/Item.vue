<template lang="html">
    <div>
        <span class="label" :class="{
            'label-primary': autopart.status_id == 1,
            'label-default': autopart.status_id == 2,
            'label-info': autopart.status_id == 3,
            'label-success': autopart.status_id == 4
            }">
            {{autopart.status.name}}
        </span>
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
        <p>ID: <strong>{{autopart.id}}</strong></p>
        <h4>{{autopart.name}}</h4>
        <p class="lead text-muted">
            {{autopart.make.name}}
            - {{autopart.model.name}}
            -
            <span v-for="(year, index) in autopart.years">
                {{year.name}}<span v-if="index+1 < autopart.years.length">, </span>
            </span>
        </p>
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
                    @click="updateAutopart($event, 1)"
                    v-if="user.hasPermission['update-sales']">
                    Available
                </button>
            </div>
            <div class="col-xs-6" v-show="showBtnSeparated">
                <button type="button" class="btn btn-info btn-lg btn-block"
                    @click="updateAutopart($event, 3)"
                    v-if="user.hasPermission['update-sales']">
                    Separated
                </button>
            </div>
            <div class="col-xs-6" v-show="showBtnSold">
                <button type="button" class="btn btn-success btn-lg btn-block"
                    @click="updateAutopart($event, 4)"
                    v-if="user.hasPermission['update-sales']">
                    Sold
                </button>
            </div>
        </div>
        <hr>
        <p>Description</p>
        <p class="text-muted">{{autopart.description}}</p>
        <fieldset class="separator">
            <legend>Activity</legend>
            <p class="text-muted">
                <small>Created at </small>
                {{autopart.created_at | moment('lll')}},
                <small>by </small>{{autopart.creator.name}}
            </p>
        </fieldset>
    </div>
</template>

<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
export default {
    data() {
        return {
            user: Laravel.user,
            swiperOption: {
                loop: true,
                pagination: '.swiper-pagination'
            }
        }
    },
    props:['model'],
    components: {
        swiper,
        swiperSlide
    },
    computed: {
        autopart () {
            var autopart = {
                make: {},
                model: {},
                years: [],
                images: [],
                status: {},
                origin: {},
                creator: {}
            }
            if (this.model.id) {
                autopart = this.model
            }

            return autopart
        },
        showBtnAvailable () {
            if (this.autopart.status_id != 2 && this.autopart.status_id != 1) {
                return true
            } else {
                return false
            }
        },
        showBtnSeparated () {
            if (this.autopart.status_id != 2 && this.autopart.status_id != 3) {
                return true
            } else {
                return false
            }
        },
        showBtnSold () {
            if (this.autopart.status_id != 2 && this.autopart.status_id != 4) {
                return true
            } else {
                return false
            }
        }
    },
    methods: {
        updateAutopart (e, statusId) {
            var btn = $(e.target).button('loading')

            axios.put(`/api/autoparts/updateStatus/${this.autopart.id}`,{
                status_id: statusId
            })
            .then(response => {
                this.$emit('updateStatus', response.data)
                btn.button('reset')
                $('#modalAutopart').modal('hide')
            })
            .catch(error => {
                this.autopart.error = error.response.data;
                btn.button('reset')
            });
        }
    }
}
</script>
