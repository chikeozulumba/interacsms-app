/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import { store } from "./helpers/state";
import $ from "jquery";
import iziModal from "izimodal";
Vue.component("app", require("./components/main/Main.vue"));
Vue.prototype.$vueEventBus = new Vue();
const app = new Vue({
    el: "#app",
    store: store
});
$.fn.iziModal = iziModal;
