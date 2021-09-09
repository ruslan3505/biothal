import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from "./router";
import "./plugins/vue-the-mask";
import globalMixins from "./mixins/global"
import store from './store/index';
import axios from 'axios';
import vue_axios from 'vue-axios';
import VueAgile from 'vue-agile';
import Notification from 'vue-notification';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import VueLoading from 'vuejs-loading-plugin';
import VueMeta from 'vue-meta';
import VueGtm from '@gtm-support/vue2-gtm';
import VueFacebookPixel from 'vue-facebook-pixel';

axios.defaults.baseURL = process.env.VUE_APP_REQUEST_BASE_URL + process.env.VUE_APP_REQUEST_PREFIX;

axios.defaults.headers = {
    'Content-Type': 'application/json;',
    Accept: 'application/json, */*'
}

axios.interceptors.request.use(config =>{
    config.headers['Content-Type'] = 'application/json'

    return config;
})



axios.interceptors.response.use(
    response => {
        return Promise.resolve(response)
    },
    error => {
        if (error.response.status) {
            switch (error.response.status) {
                case 400:

                    //do something
                    break;

                case 401:
                    this.$store.commit("SET_TOKEN", null);
                    break;
                case 403:
                    // router.replace({
                    //     path: "/login",
                    //     query: { redirect: router.currentRoute.fullPath }
                    // });
                    break;
                case 404:
                    break;
                case 502:
                    setTimeout(() => {
                        router.replace({
                            path: "/login",
                            query: {
                                redirect: router.currentRoute.fullPath
                            }
                        });
                    }, 1000);
            }

            return Promise.reject(error.response);
        }
    }
);

Vue.use(VueMeta)
Vue.use(VueAgile)
Vue.use(vue_axios, axios);
Vue.use(Notification);
Vue.use(VueLodash, { lodash: lodash });
Vue.use(VueLoading, {
    dark: true, // default false
    text: 'Загрузка....', // default 'Loading'
    loading: false, // default false
    background: 'rgb(255,255,255)', // set custom background
    classes: ['loader_opacity'] // array, object or string
})
Vue.use(  VueGtm, {
    id: "GTM-57NZVR4", // Your GTM single container ID, array of container ids ['GTM-xxxxxx', 'GTM-yyyyyy'] or array of objects [{id: 'GTM-xxxxxx', queryParams: { gtm_auth: 'abc123', gtm_preview: 'env-4', gtm_cookies_win: 'x'}}, {id: 'GTM-yyyyyy', queryParams: {gtm_auth: 'abc234', gtm_preview: 'env-5', gtm_cookies_win: 'x'}}], // Your GTM single container ID or array of container ids ['GTM-xxxxxx', 'GTM-yyyyyy']
    defer: false, // Script can be set to `defer` to speed up page load at the cost of less accurate results (in case visitor leaves before script is loaded, which is unlikely but possible). Defaults to false, so the script is loaded `async` by default
    compatibility: false, // Will add `async` and `defer` to the script tag to not block requests for old browsers that do not support `async`// Will add `nonce` to the script tag
    enabled: true, // defaults to true. Plugin can be disabled by setting this to false for Ex: enabled: !!GDPR_Cookie (optional)
    debug: true, // Whether or not display console logs debugs (optional)
    loadScript: true, // Whether or not to load the GTM Script (Helpful if you are including GTM manually, but need the dataLayer functionality in your components) (optional)
    vueRouter: router, // Pass the router instance to automatically sync with router (optional)// Don't trigger events for specified router names (case insensitive) (optional)
    trackOnNextTick: false, // Whether or not call trackView in Vue.nextTick
})
Vue.use(VueFacebookPixel)

Vue.analytics.fbq.init('223046382786714', {
    em: 'niksgreek@gmail.com'
})

import '@/styles/main.scss';

Vue.config.productionTip = false

Vue.mixin(globalMixins)

router.beforeEach((to, from, next) => {
    const token = store.getters.getToken;
    if (['authorization', 'registration'].includes(to.name) && token) {
        next({name: 'account-settings'})
    } else if (['account-settings', 'order-list', 'group-discount-participants', 'bank-cards'].includes(to.name) && !token) {
        next({name: 'authorization'})
    } else {
        next()
    }
})

new Vue({
    vuetify,
    store,
    router,
    icons: {
        iconfont: ['mdi', 'md']
    },
    render: h => h(App),
}).$mount('#app')

