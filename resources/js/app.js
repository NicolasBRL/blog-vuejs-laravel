import './bootstrap';

import 'flowbite'

import { createApp } from 'vue';
import App from './components/App.vue';

import router from './router';
import store from './store/index'

import mavonEditor from 'mavon-editor';
import 'mavon-editor/dist/css/index.css'

createApp(App).use(router).use(store).use(mavonEditor).mount("#app");