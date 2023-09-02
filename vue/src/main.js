import { createApp } from 'vue'
import store from './store'
import router from './router'
import './index.css'
import 'v-calendar/dist/style.css';
import App from './App.vue'
import VCalendar from "v-calendar";
import mitt from 'mitt';
const emitter = mitt();
const app = createApp(App);
app.use(store);
app.use(VCalendar, {});
app.use(router);
app.provide('emitter', emitter);
app.mount('#app');  
