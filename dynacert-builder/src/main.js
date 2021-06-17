import Vue from 'vue'
import App from './App.vue'
import router from "@/router"
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VueMask from 'v-mask'
import VueGtag from "vue-gtag";
import italian from '@/languages/italian'
import english from '@/languages/english'
import VueKonva from 'vue-konva'

Vue.use(VueKonva)




export const serverBus = new Vue();
Vue.prototype.config = {
  version: "0.0.1",
  productName: "Dyna-Cert Builder",
  timeCheck: 5000,
  recaptchaId: '<captha id>',
  //serviceBaseUrl: "http://localhost/builder/",
  serviceBaseUrl:'/api/',
  url: {
    listForms: "form",
    getForm: "form/",
    saveForm: "form/insert/",
    saveFormMap: "map/insert/",
    saveCategory: "category/insert/",
    listCategory: "category",
    deleteCategory: "category/delete/",
    modifyCategory: "category/update/",
    getMap: "map/",
  },
  demo: false,
  timeChecksession: 30000,
  currentLanguage: "en",
  projectId: null,
};
Vue.prototype.language = {
  it: italian,
  en: english,
}
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueGtag, {
  config: { id: "G-XK6NPR75TT" }
});
Vue.use(VueMask)

Vue.config.productionTip = false

const app = new Vue({
  el: "#app",
  router,
  data: { loading: false },
  template: "<App/>",
  components: {
    App,
  },
});
router.beforeEach((to, from, next) => {
  //app.loading = true
  next()
})

