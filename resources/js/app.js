import './bootstrap';

import {createApp} from 'vue'
import {createRouter, createWebHistory} from 'vue-router'
import App from './App.vue'

import BlogList from './components/BlogList.vue'
import SingleBlog from './components/SingleBlog.vue'

const routes =  [
    {
        path: '/',
        component: BlogList
      },
      {
        path: '/blog/:id',
        component: SingleBlog,
        name: 'post'
      },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

createApp(App).use(router).mount("#app")