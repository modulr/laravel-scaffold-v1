<template>
    <swiper :options="swiperOption" ref="mySwiper">
        <swiper-slide>
            <div>
                <h1>Comparte tu Sazón y ahorra dinero</h1>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div>
                <h1>Comida tradicional, rica y accesible</h1>
            </div>
        </swiper-slide>
    </swiper>
</template>

<script>
    import { swiper, swiperSlide } from 'vue-awesome-swiper';

    export default {
        data () {
            return {
                swiperOption: {
                    autoplay: 5000,
                    loop: true,
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                }
            }
        },
        components: {
            swiper,
            swiperSlide,
        },
    };
</script>
