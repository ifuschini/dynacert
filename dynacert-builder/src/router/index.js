import Vue from "vue";
import Router from "vue-router";
import container from "../components/container.vue"
import dynaManager from "../view/dynaManager.vue"
import dynaViewer from "../view/dynaViewer.vue"
import dynaMapper from "../view/dynaMapper.vue"
import dynaCategory from "../view/dynaCategory.vue"
import singlePage from "../view/viewer/singlePage"
import allDyna from "../view/viewer/allDyna"
import page404 from "../view/pages/404";



Vue.use(Router);
const router = new Router({
    mode: "hash", // https://router.vuejs.org/api/#mode
    linkActiveClass: "active",
    routes: [
        {
            path: "/",
            redirect: "/dynaManager",
            name: "Container",
            component: container,
            children: [
                {
                    path: "dynaManager",
                    name: "manager",
                    component: dynaManager,
                    meta: { requiresAuth: false },
                },
                {
                    path: "dynaViewer",
                    name: "viewer",
                    component: dynaViewer,
                    meta: { requiresAuth: false },
                },
                {
                    path: "dynaMapper",
                    name: "mapper",
                    component: dynaMapper,
                    meta: { requiresAuth: false },
                },
                {
                    path: "dynaCategory",
                    name: "category",
                    component: dynaCategory,
                    meta: { requiresAuth: false },
                },
            ],
        },
        {
            path: "/pages",
            redirect: "/pages/404",
            name: "pages",
            component: {
                render(c) {
                    return c("router-view");
                },
            },
            meta: { requiresAuth: false },
            children: [
                {
                    path: "singlepage/:id",
                    name: "FormSinglePage",
                    component: singlePage,
                },            
                {
                    path: "all/:id",
                    name: "FormAll",
                    component: allDyna,
                }, 
                {
                    path: "404",
                    name: "Page404",
                    component: page404,
                },
            ],
        },
        {
            path: "*",
            redirect: "/pages/404",
        },
    ],
});

export default router;
