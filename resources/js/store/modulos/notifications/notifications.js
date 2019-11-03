export default {
    state :{
        items: []
    },

    mutations: {
        LOAD_NOTIFICATIONS(state, notifications){
            state.items = notifications
        },

        MARK_AS_READ(state, idNotification){
            let index = state.items.filter(notification => notification.id == idNotification)
            state.items.splice(index, 1)
        },

        MARK_ALL_AS_READ(state){
            state.items = []
        }
    },

    actions: {
        loadNotifications(context){
            axios.get('/notifications')
                .then(response => {
                    context.commit('LOAD_NOTIFICATIONS', response.data.notifications)
                })
        },

        markAsRead(context, params) {
            axios.put('/notification-read', params)
            .then(() => context.commit('MARK_AS_READ', params.id))
        },

        markAllAsRead(context, params) {
            axios.put('/notification-read-all')
                .then(() => {
                    context.commit('MARK_ALL_AS_READ')
                })
        },
    }
}
