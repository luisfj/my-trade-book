import Vue from 'vue'
import Vuex from 'vuex'

import notifications from './modulos/notifications/notifications'
import bugs from './modulos/bugs-report/bugs-report'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        notifications,
        bugs,
    },

})
