import { createApp } from 'vue'
import store from './store'
import router from './router'
import './index.css'
import 'v-calendar/dist/style.css';
import App from './App.vue'
import VCalendar from "v-calendar";
import mitt from 'mitt';
import VueApexCharts from "vue3-apexcharts";

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'




library.add(faUserSecret)

const emitter = mitt();

const app = createApp(App);
app.use(VueApexCharts);
app.use(store);
app.use(VCalendar, {});
app.use(router);
app.provide('emitter', emitter);

app.component('font-awesome-icon', FontAwesomeIcon)
app.config.productionTip = false

app.mount('#app');
