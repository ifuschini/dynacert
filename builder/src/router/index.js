import Vue from "vue";
import Router from "vue-router";
import container from "../components/container.vue"
import surveyManager from "../view/surveyManager.vue"
import surveyViewer from "../view/surveyViewer.vue"
import surveyMapper from "../view/surveyMapper.vue"
import surveyCategory from "../view/surveyCategory.vue"
import singlePage from "../view/viewer/singlePage"
import allSurvey from "../view/viewer/allSurvey"


Vue.use(Router);
const router = new Router({
    mode: "hash", // https://router.vuejs.org/api/#mode
    linkActiveClass: "active",
    routes: [
        {
            path: "/",
            redirect: "/surveyManager",
            name: "Container",
            component: container,
            children: [
                {
                    path: "surveyManager",
                    name: "manager",
                    component: surveyManager,
                    meta: { requiresAuth: false },
                },
                {
                    path: "surveyViewer",
                    name: "viewer",
                    component: surveyViewer,
                    meta: { requiresAuth: false },
                },
                {
                    path: "surveyMapper",
                    name: "mapper",
                    component: surveyMapper,
                    meta: { requiresAuth: false },
                },
                {
                    path: "surveyCategory",
                    name: "category",
                    component: surveyCategory,
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
                    name: "SurveySinglePage",
                    component: singlePage,
                },            
                {
                    path: "all/:id",
                    name: "SurveyAll",
                    component: allSurvey,
                },            
            ],
        },
        /*{
            path: "*",
            redirect: "/pages/404",
        },*/
    ],
});

export default router;
