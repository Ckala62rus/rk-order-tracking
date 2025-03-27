import Vuex from 'vuex'
import Vue from "vue";

import auth from './modules/auth'
import logger from './modules/logger'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {},
    getters: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        logger,
    }
})


export default store
