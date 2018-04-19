
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-moment'));
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
// Profile
Vue.component('profileSidebar', require('./components/profile/read/Sidebar.vue'));
Vue.component('profile', require('./components/profile/read/Profile.vue'));
Vue.component('profileWork', require('./components/profile/read/Work.vue'));
// Profile Edit
Vue.component('profileSidebarEdit', require('./components/profile/edit/Sidebar.vue'));
Vue.component('profileEdit', require('./components/profile/edit/Profile.vue'));
Vue.component('profilePersonalEdit', require('./components/profile/edit/Personal.vue'));
Vue.component('profileContactEdit', require('./components/profile/edit/Contact.vue'));
Vue.component('profileEducationEdit', require('./components/profile/edit/Education.vue'));
Vue.component('profileFamilyEdit', require('./components/profile/edit/Family.vue'));
Vue.component('profilePlaceEdit', require('./components/profile/edit/Place.vue'));
Vue.component('profileWorkEdit', require('./components/profile/edit/Work.vue'));
Vue.component('profilePasswordEdit', require('./components/profile/edit/Password.vue'));
// News
//Vue.component('news', require('./components/News.vue'));
Vue.component('newsPublish', require('./components/news/NewsPublish.vue'));
Vue.component('newsList', require('./components/news/NewsList.vue'));
Vue.component('news', require('./components/news/News.vue'));
// Tasks
Vue.component('tasks', require('./components/tasks/Tasks.vue'));
// Contacts
Vue.component('contacts', require('./components/contacts/Contacts.vue'));
Vue.component('contacts-widget', require('./components/contacts/ContactsWidget.vue'));
// Files
Vue.component('files', require('./components/files/Files.vue'));
Vue.component('files-widget', require('./components/files/FilesWidget.vue'));
// Events
Vue.component('events', require('./components/events/Events.vue'));
Vue.component('event', require('./components/events/Event.vue'));
Vue.component('events-widget', require('./components/events/EventsWidget.vue'));
// Autoparts
Vue.component('autoparts-list', require('./components/autoparts/List.vue'));
Vue.component('autoparts-filters', require('./components/autoparts/Filters.vue'));
Vue.component('autoparts-search-qr', require('./components/autoparts/SearchQr.vue'));

Vue.component('autoparts-sales', require('./components/autoparts/sales/Sales.vue'));
Vue.component('autoparts-sales-item', require('./components/autoparts/sales/Item.vue'));

Vue.component('autoparts-inventory', require('./components/autoparts/inventory/Inventory.vue'));
Vue.component('autoparts-inventory-item', require('./components/autoparts/inventory/Item.vue'));
Vue.component('autoparts-inventory-lists', require('./components/autoparts/inventory/InventoryLists.vue'));

Vue.component('comments', require('./components/helpers/comments/Comments.vue'));
Vue.component('activity', require('./components/helpers/activity/Activity.vue'));


const app = new Vue({
    el: '#app'
});
