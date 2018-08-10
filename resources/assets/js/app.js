
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const moment = require('moment');
require('moment/locale/es');

Vue.use(require('vue-moment'), {
    moment
});
Vue.use(require('./filters/pretty-bytes'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//Vue.component('example', require('./components/Example.vue'));
// Auth
Vue.component('carousel', require('./components/auth/Carousel.vue'));
// Layout
Vue.component('navbar', require('./components/layout/Navbar.vue'));
Vue.component('notifications', require('./components/layout/Notifications.vue'));
// Users
Vue.component('users', require('./components/users/Users.vue'));
Vue.component('roles', require('./components/users/Roles.vue'));
// Profile Read
Vue.component('profileSidebar', require('./components/profile/read/Sidebar.vue'));
Vue.component('profile', require('./components/profile/read/Profile.vue'));
Vue.component('profileWork', require('./components/profile/read/Work.vue'));
// Profile Edit
Vue.component('profileSidebarEdit', require('./components/profile/edit/Sidebar.vue'));
Vue.component('profileEdit', require('./components/profile/edit/Profile.vue'));
Vue.component('profileContactEdit', require('./components/profile/edit/Contact.vue'));
Vue.component('profilePlaceEdit', require('./components/profile/edit/Place.vue'));
Vue.component('profilePasswordEdit', require('./components/profile/edit/Password.vue'));
// Stb
Vue.component('saucers', require('./components/stb/Saucers.vue'));
Vue.component('saucer', require('./components/stb/Saucer.vue'));
Vue.component('reservations', require('./components/stb/Reservations.vue'));
Vue.component('my-saucers', require('./components/stb/MySaucers.vue'));
Vue.component('my-saucer', require('./components/stb/MySaucer.vue'));
Vue.component('my-reservations', require('./components/stb/MyReservations.vue'));

const app = new Vue({
    el: '#app'
});
