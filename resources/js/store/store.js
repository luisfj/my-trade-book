import Vue from 'vue'
import Vuex from 'vuex'

import notifications from './modulos/notifications/notifications'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        notifications,
    },

})
