import Vue from "vue";
import Router from "vue-router";
import container from "../components/container.vue"
import startPage from "../view/viewer.vue"
import sign from "../view/sign.vue"
import forms from "../view/forms.vue"
import page404 from "../view/pages/404";
import cookie from "../view/pages/cookie";



Vue.use(Router);
const router = new Router({
    mode: "hash", // https://router.vuejs.org/api/#mode
    linkActiveClass: "active",
    routes: [
        {
            path: "/",
            redirect: "/list",
            name: "Private",
            component: container,
            children: [
                {
                    path: "cookie",
                    name: "Cookie",
                    component: cookie,
                    meta: { requiresAuth: false },
                },
                {
                    path: "list",
                    name: "list",
                    component: forms,
                    meta: { requiresAuth: false },
                },
                {
                    path: "form/:data",
                    name: "Form",
                    component: startPage,
                    meta: { requiresAuth: false },
                },
                {
                    path: "sign/:data",
                    name: "Sign",
                    component: sign,
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

//login

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
    const cname = 'token'
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    var cookie = null
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            cookie = c.substring(name.length, c.length);
        }
    }
    console.log('token:' + cookie)
    if (to.name !== "Login" && to.meta.requiresAuth == true && Vue.prototype.$isAuthenticated.value == false && cookie == null) next({ name: "Login" });
    else next();
});


export default router;
