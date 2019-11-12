/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import store from './store/store'

import message from './helpers/alerts/alerts'
import BootstrapVue from 'bootstrap-vue'
//import moment from 'moment'

window.Vue = require('vue');


const plugin = {
	install (Vue, options) {
		Vue.prototype.$message = message; // we use $ because it's the Vue convention
	}
};

Vue.use(plugin);
Vue.use(BootstrapVue);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('notifications', require('./components/notifications/Notifications.vue').default);
Vue.component('notification', require('./components/notifications/Notification.vue').default);
Vue.component('bugsmodal', require('./components/bugs-report/BugsReportModal.vue').default);
Vue.component('icon-a-link', require('./components/helpers/IconALink.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 Next, we will cnotifications fresh Vue application notifications/Notifications and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 * para usar globalmente eventos
 */
window.bus = new Vue({});

const app = new Vue({
    store,
    el: '#app',
});
