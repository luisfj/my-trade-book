import Vue from 'vue'
import Vuex from 'vuex'

import notifications from './modulos/notifications/notifications'
import bugs from './modulos/bugs-report/bugs-report'
import posts from './modulos/posts/posts'
import importacao from './modulos/importacao/importacao'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        notifications,
        bugs,
        posts,
        importacao,
    },

})
