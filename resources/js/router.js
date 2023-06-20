import { createRouter, createWebHistory } from "vue-router";
import store from './store/index'

// Views
import Home from "./components/views/Home.vue";
import Login from './components/views/Login.vue'
import Register from './components/views/Register.vue'
import SingleBlog from './components/views/SingleBlog.vue'
import AddPost from './components/views/AddPost.vue'
import EditPost from './components/views/EditPost.vue'
import Profil from './components/views/Profile.vue'

const routes = [
    {
        name: "home",
        path: "/",
        component: Home
    },
    {
        name: "profil",
        path: "/profil",
        component: Profil,
        meta: {requiresAuth: true}
    },
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {requiresNotAuth: true}
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {requiresNotAuth: true}
    },
    {
        path: "/blog/:id",
        component: SingleBlog,
        name: "post",
    },{
        path: "/blog/edit/:id",
        component: EditPost,
        name: "edit-post",
    },
    {
        path: "/blog/new",
        component: AddPost,
        name: "write-post",
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.getters.isAuthenticated) {
      next('/login')
    }else if (to.meta.requiresNotAuth && store.getters.isAuthenticated) {
        next('/')
    } else {
      next()
    }
})


export default router;