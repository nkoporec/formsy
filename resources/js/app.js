//@todo remove.
import "alpinejs";
import Vue from "vue";
import "livewire-vue";
import Echo from "laravel-echo";
import Axios from "axios";
import moment from "moment";
import Datepicker from "vuejs-datepicker";
import vSelect from "vue-select";
import VueHcaptcha from "@hcaptcha/vue-hcaptcha";

window.Pusher = require("pusher-js");
window.axios = require("axios");
window.Vue = Vue;
Vue.prototype.$http = window.axios;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "a43d25ec6135bd2ef3fa",
    cluster: "eu",
    encrypted: true,
});

Vue.filter("formatDate", function(value) {
    if (value) {
        return moment(String(value)).format("LL hh:mm");
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context("./", true, /\.vue$/i);
files.keys().map((key) =>
    Vue.component(
        key
            .split("/")
            .pop()
            .split(".")[0],
        files(key).default
    )
);

Vue.component("vuejs-datepicker", Datepicker);
Vue.component("v-select", vSelect);
Vue.component("vue-hcaptcha", VueHcaptcha);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    data: {
        modalShowing: false,
    },
});
