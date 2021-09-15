/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import {ClientTable, ServerTable, Event} from 'vue-tables-2';
Vue.use(ClientTable);
Vue.use(ServerTable);
Vue.use(Event);

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.use(DatePicker)

import VModal from 'vue-js-modal'
Vue.use(VModal, {
    dynamic:true,
    dynamicDefaults: {
        height: 'auto',
        adaptive: true
    }
})

import Notifications from 'vue-notification';
Vue.use(Notifications);

import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
// import 'sweetalert2/dist/sweetalert2.min.css'; // с включенными стилями, уезжал footer
Vue.use(VueSweetalert2);

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en'
Vue.use(ElementUI, {locale});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('orders-table', require('./components/Order/OrderTable').default);
Vue.component('users-table', require('./components/User/UserIndex').default);
Vue.component('orders-manager-table', require('./components/Manager/OrderManagerTable').default);
Vue.component('simple-orders-table', require('./components/Order/SimpleOrderTable').default);
Vue.component('client-zip-orders-table', require('./components/Order/ClientZipOrderTable').default);
Vue.component('zip-detail-orders-table', require('./components/Manager/ZipDetailOrderTable').default);
Vue.component('zip-detail-orders-group-table', require('./components/Manager/ZipDetailOrderGroupTable').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
