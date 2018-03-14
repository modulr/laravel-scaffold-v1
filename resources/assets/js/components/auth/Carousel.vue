<template>
    <swiper :options="swiperOption" ref="mySwiper">
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/auth.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-lock-outline mdi-lg text-primary"></i> Authentication
                </h3>
                <p>Sign Up, login or recover your lost password</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/contacts.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-contacts mdi-lg text-primary"></i> Contacts
                </h3>
                <p>All your contacts in one click</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/files.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-folder-open mdi-lg text-primary"></i> Files
                </h3>
                <p>Manage your files and share it</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/news.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-whatshot mdi-lg text-primary"></i> News
                </h3>
                <p>Keep up to date</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/notifications.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-notifications mdi-lg text-primary"></i> Notifications
                </h3>
                <p>You will know when there is news</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/tasks.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-view-list mdi-lg text-primary"></i> Tasks
                </h3>
                <p>Do not forget your obligations, order them</p>
            </div>
        </swiper-slide>
        <swiper-slide>
            <div class="text-center">
                <img src="/img/modules/users.png" class="img-responsive">
                <h3>
                    <i class="mdi mdi-vpn-key mdi-lg text-primary"></i> Users & Access
                </h3>
                <p>Access control to users and permissions</p>
            </div>
        </swiper-slide>
        <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
        <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
    </swiper>
</template>

<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
export default {
    data () {
        return {
            swiperOption: {
                autoplay: 5000,
                loop: true,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev'
            }
        }
    },
    components: {
        swiper,
        swiperSlide
    }
}
</script>
