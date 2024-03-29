/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import store from './store/store'

import message from './helpers/alerts/alerts'
import BootstrapVue from 'bootstrap-vue'
import moment from 'moment'
import VueGoogleCharts from 'vue-google-charts'

window.Vue = require('vue');


const plugin = {
	install (Vue, options) {
		Vue.prototype.$message = message; // we use $ because it's the Vue convention
	}
};

Vue.use(plugin);
Vue.use(BootstrapVue);
Vue.use(VueGoogleCharts)


Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY')
  }
});
Vue.filter('formatDateTime', function(value) {
    if (value) {
      return moment(String(value)).format('DD/MM/YYYY hh:mm')
    }
});
Vue.filter('formatarMoeda', function(value) {
    if (value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }
});


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
Vue.component('notificacaomodal', require('./components/notifications/NotificacaoModal.vue').default);
Vue.component('bugsmodal', require('./components/bugs-report/BugsReportModal.vue').default);
Vue.component('icon-a-link', require('./components/helpers/IconALink.vue').default);
Vue.component('importacao-de-arquivo', require('./components/operacoes/ImportacaoDeArquivo.vue').default);
Vue.component('fechamento-mensal-grid', require('./components/charts/fechamento-mes/FechamentoMensalGrid.vue').default);
Vue.component('fechamento-grafico-barras', require('./components/charts/fechamento-mes/FechamentoGraficoBarras.vue').default);
Vue.component('evolucao-saldo-fechamento-grafico', require('./components/charts/fechamento-mes/EvolucaoSaldoFechamentoGrafico.vue').default);

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
