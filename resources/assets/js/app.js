
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
Vue.component('navbar', require('./components/Navbar.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('news', require('./components/News.vue'));
Vue.component('newsPublish', require('./components/news/newsPublish.vue'));
Vue.component('newsList', require('./components/news/newsList.vue'));
Vue.component('tasks', require('./components/Tasks.vue'));
Vue.component('contacts', require('./components/Contacts.vue'));
Vue.component('files', require('./components/files/Files.vue'));


const app = new Vue({
    el: '#app',
    data: {
        user: Laravel.user,
        guest: Laravel.guest,
    },
});
