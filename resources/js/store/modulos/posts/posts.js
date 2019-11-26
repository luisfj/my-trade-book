export default {
    state :{

    },

    mutations: {

    },

    actions: {

        getPostByNotificationId(context, id){
            return axios.get('notification/post/' + id);
        },
        votarNaOpcao(contex, head){
            return axios.put('enquete/votar', head);
        }
    }
}

