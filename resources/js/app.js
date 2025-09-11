import './bootstrap';
window.Vue = require("vue");
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";



// Import Bootstrap an BootstrapVue CSS files (order is important)
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

Vue.use(BootstrapVue);
Vue.component('pagination', require('laravel-vue-pagination'));
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component("debitor-list", require("./components/DebitorList.vue").default);
Vue.component("namelist-initimation", require("./components/NameListIntimation.vue").default);
Vue.component("pnr-reconciliation", require("./components/pnrReconciliation").default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
