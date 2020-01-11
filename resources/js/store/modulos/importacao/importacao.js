export default {
    state :{
    },
    mutations: {
    },

    actions: {
        importarArquivos(context, arquivos) {
            return axios.post('/operacoes/importar', arquivos)
        },
    }
}
