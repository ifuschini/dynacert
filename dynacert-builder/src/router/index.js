import Vue from "vue";
import Router from "vue-router";
import container from "../components/container.vue"
import dynaManager from "../view/dynaManager.vue"
import dynaViewer from "../view/dynaViewer.vue"
import dynaMapper from "../view/dynaMapper.vue"
import dynaCategory from "../view/dynaCategory.vue"
import dynaAccount from "../view/dynaAccount.vue"
import singlePage from "../view/viewer/singlePage"
import allDyna from "../view/viewer/allDyna"
import page404 from "../view/pages/404";
import login from "../view/pages/login";




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
                    meta: { requiresAuth: true },
                },
                {
                    path: "dynaViewer",
                    name: "viewer",
                    component: dynaViewer,
                    meta: { requiresAuth: true },
                },
                {
                    path: "dynaMapper",
                    name: "mapper",
                    component: dynaMapper,
                    meta: { requiresAuth: true },
                },
                {
                    path: "dynaCategory",
                    name: "category",
                    component: dynaCategory,
                    meta: { requiresAuth: true },
                },
                {
                    path: "dynaAccount",
                    name: "account",
                    component: dynaAccount,
                    meta: { requiresAuth: true },
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
                    path: "login",
                    name: "Login",
                    component: login,
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

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
router.beforeEach((to, from, next) => {
    //read cookie
    let check=false
    if (localStorage.logedin) check=true
    if (to.name !== "Login" && to.meta.requiresAuth == true && check == false ) {
        next({ name: "Login" });
    }
    else next();
});
export default router;

