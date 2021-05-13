import Vue from 'vue'
import App from './App.vue'
import router from "@/router"
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VueMask from 'v-mask'
import VueGtag from "vue-gtag";
import italian from '../../builder/src/languages/italian'
import english from '../../builder/src/languages/english'
import { VueReCaptcha } from 'vue-recaptcha-v3'




library.add(faBars);


export const serverBus = new Vue();
Vue.prototype.config = {
  version: "1.0.3",
  productName: "dynaCer",
  timeCheck: 5000,
  recaptchaId: '<id>',
  //serviceBaseUrl: "https://localhost/dynacert/viewer/",
  serviceBaseUrl: "http://localhost/viewer/",
  //serviceBaseUrl: "",
  url: {
    getImage: "ws/getImage.php",
    getSurvey: "ws/getSurveyConfigDB.php",
    upload: "ws/upload.php",
    getDate: 'ws/getDate.php',
    getUsers: 'ws/getUsers.php',
    getListCertificate: 'ws/getListCertificate.php',
    getCategory: 'ws/listCategory.php',
    saveUser: 'ws/saveUser.php',
    getLinks: 'ws/getLinks.php',
    zip: 'ws/zip.php',
    auth: 'ws/auth.php',
    download: 'ws/download.php',
    exit: 'ws/exit.php',
    listCategory: "ws/listCategoryDb.php",
    listSurveys: "ws/listSurveyDb.php",
  },
  timeChecksession: 30000,
  currentLanguage: "en",
  projectId: null,
  demo:true
};
Vue.prototype.$isAuthenticated = { value: false };

Vue.prototype.language = {
  it: italian,
  en: english,
}
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueGtag, {
  config: { id: "G-5H9Q47P292" }
});
Vue.use(VueReCaptcha, { siteKey: '6LeaSDcaAAAAALTAMvxifdGUNfVly6DqMvOCZbKH' })

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

