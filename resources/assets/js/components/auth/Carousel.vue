<template>
    <swiper>
        <swiper-slide>
            <div class="logo">
                <img src="/img/logos/logowhite.png" alt="">
            </div>
            <div class="content">
                <h3 class="heading">
                    Lorem ipsum dolor sit amet, consectetur.
                </h3>
                <p class="text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque velit sapien, aliquam ac turpis a, varius condimentum risus. Integer eget velit id ex congue euismod.
                </p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="logo">
                <img src="/img/logos/logowhite.png" alt="">
            </div>
            <div class="content">
                <h3 class="heading">
                    Lorem ipsum dolor sit amet, consectetur.
                </h3>
                <p class="text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque velit sapien, aliquam ac turpis a, varius condimentum risus. Integer eget velit id ex congue euismod.
                </p>
            </div>
        </swiper-slide>
        <div class="swiper-pagination" slot="pagination"></div>
    </swiper>
</template>

<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper';
    export default {
        data () {
            return {
                swiperOption: {
                    pagination : '.swiper-pagination',
                    paginationClickable: true,
                }
            }
        },
        components: {
            swiper,
            swiperSlide,
        },
    };
</script>
