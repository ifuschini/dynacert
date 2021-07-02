import Vue from 'vue'
import App from './App.vue'
import router from "@/router"
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
//import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from '@fortawesome/fontawesome-svg-core'
import { faBuilding, faCopyright, faDraftingCompass, faEye, faUsers, faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import VueMask from 'v-mask'
import VueGtag from "vue-gtag";
import italian from '@/languages/italian'
import english from '@/languages/english'
import VueKonva from 'vue-konva'

Vue.use(VueKonva)
library.add(faUsers,faBuilding,faDraftingCompass,faEye)
library.add(faCopyright)




export const serverBus = new Vue();
Vue.prototype.config = {
  version: "0.0.1",
  productName: "Dyna-Cert Builder",
  timeCheck: 5000,
  recaptchaId: '<captha id>',
  //serviceBaseUrl: "http://localhost/api/",
  serviceBaseUrl:'/api/',
  url: {
    listForms: "admin/form",
    getForm: "admin/form/",
    saveForm: "admin/form/save/",
    saveFormMap: "admin/map/insert/",
    saveCategory: "admin/category/insert/",
    listCategory: "admin/category",
    deleteCategory: "admin/category/delete/",
    modifyCategory: "admin/category/update/",
    listAccount: "admin/account",
    insertAccount: "admin/account/insert/",
    updateAccount: "admin/account/update/",
    deleteAccount: "admin/account/delete/",
    getMap: "admin/map/",
    login: "login",
    logout: "logout",
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
Vue.prototype.$isAuthenticated = { value: false };
Vue.config.productionTip = false
Vue.prototype.dynaLogout = (object) => {
  if (localStorage.logedin) object.$router.push({ name: 'Login' })
  localStorage.removeItem('logedin');
}
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

