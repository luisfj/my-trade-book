export default {
    state :{
        bugs: []
    },

    mutations: {
        LOAD_BUGS(state, bugs){
            state.bugs = bugs
        },

        RELOAD_BUG(state, bugNew){
            let index = state.bugs.filter(bug => bug.id == bugNew.id)
            state.bugs[index] = bugNew
        },
    },

    actions: {
        loadBugs(context){
            axios.get('/bugs')
                .then(response => {
                    context.commit('LOAD_BUGS', response.data.bugs)
                })
        },

        reloadBug(context, bug) {
            context.commit('RELOAD_BUG', bug)
        },

        addBug(context, postBody) {
            return axios.post('/bugs', postBody)
        },

        addBugMessage(context, message) {
            return axios.post('/bug-message', message)
        },

        marcarComoLida(context, head){
            return axios.post('/bug-verificado', head)
        }
    }
}
