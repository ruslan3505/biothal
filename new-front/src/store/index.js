import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";

import session from './modules/session'
import basket from "./modules/basket";
import modals from "./modules/modals";

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        session,
        basket,
        modals
    },
    state: {},
    actions: {},
    mutations: {},
    getters: {},
    plugins: [
        createPersistedState()
    ]
})
