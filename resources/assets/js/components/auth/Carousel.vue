<template>
    <swiper :options="swiperOption" ref="mySwiper">
        <swiper-slide>
            <div>
                <div class="logo">
                    <img src="/img/logos/logo-white.png" alt="">
                </div>
                <div class="content">
                    <h3 class="heading">
                        Lorem ipsum dolor sit amet, consectetur.
                    </h3>
                    <p class="text">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque velit sapien, aliquam ac turpis a, varius condimentum risus. Integer eget velit id ex congue euismod.
                    </p>
                </div>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div>
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
            </div>
        </swiper-slide>
        <div class="swiper-button-prev" slot="button-prev"></div>
        <div class="swiper-button-next" slot="button-next"></div>
    </swiper>
</template>

<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper';
    export default {
        data () {
            return {
                swiperOption: {
                    loop: true,
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev'
                }
            }
        },
        components: {
            swiper,
            swiperSlide,
        },
    };
</script>
