import { createRouter, createWebHistory } from "vue-router";
import store from "./store";
// Layouts
import DahboardLayout from "./components/layouts/Default.vue";

// Vue
import BlogList from "./components/BlogList.vue";
import SingleBlog from "./components/SingleBlog.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";

const routes = [
    {
        name: "login",
        path: "/connexion",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Connexion`,
        },
    },
    {
        name: "register",
        path: "/inscription",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Inscription`,
        },
    },
    

    // {
    //     path: "/",
    //     component: DahboardLayout,
    //     meta: {
    //         middleware: "auth",
    //     },
    //     children: [
            
    //     ],
    // },
    {
        name: "blog",
        path: "/",
        component: BlogList,
        meta: {
            title: `Blog`,
        },
    },
    {
        path: "/blog/:id",
        component: SingleBlog,
        name: "post",
        meta: {
            title: `Single Post`,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    if(to.meta.middleware){
        if (to.meta.middleware == "guest") {
            if (store.state.auth.authenticated) {
                next({ name: "blog" });
            }
            next();
        } else {
            if (store.state.auth.authenticated) {
                next();
            } else {
                next({ name: "login" });
            }
        }
    }else{
        next();
    }
});

export default router;
