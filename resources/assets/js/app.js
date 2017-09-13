
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//Vue.component('example', require('./components/Example.vue'));
// Auth
Vue.component('carousel', require('./components/auth/Carousel.vue'));
// Layout
Vue.component('navbar', require('./components/Navbar.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));
// Users
Vue.component('users', require('./components/users/Users.vue'));
// Profile
Vue.component('ProfileSidebar', require('./components/profile/ProfileSidebar.vue'));
Vue.component('ProfileInformation', require('./components/profile/ProfileInformation.vue'));
Vue.component('ProfileWork', require('./components/profile/ProfileWork.vue'));
// News
//Vue.component('news', require('./components/News.vue'));
Vue.component('newsPublish', require('./components/news/NewsPublish.vue'));
Vue.component('newsList', require('./components/news/NewsList.vue'));
// Tasks
Vue.component('tasks', require('./components/Tasks.vue'));
// Contacts
Vue.component('contacts', require('./components/Contacts.vue'));
// Files
Vue.component('files', require('./components/files/Files.vue'));
// Projects & Opportunities
Vue.component('opportunities', require('./components/opportunities/Opportunities.vue'));
//Quotes
Vue.component('quotes', require('./components/Quotes.vue'));
// Customers
Vue.component('customers', require('./components/customers/Customers.vue'));

const app = new Vue({
    el: '#app',
    data: {
        user: Laravel.user,
        guest: Laravel.guest,
    },
});
