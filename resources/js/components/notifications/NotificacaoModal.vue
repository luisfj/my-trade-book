<template>

    <b-modal id="modal-notificacao" size="lg" title="Notificação"
        scrollable
        header-bg-variant="dark"
        header-text-variant="light"
        body-bg-variant="secondary"
        body-text-variant="light"
        footer-bg-variant="secondary"
        footer-text-variant="light"
        @hidden="hidden">

        <div class="alert alert-success" v-if="success.length">
            <b v-for="msg in success" v-bind:key="msg">{{ msg }}</b>
        </div>
        <div class="alert alert-danger" v-if="errors.length">
            <b>Por favor, corrija o(s) seguinte(s) erro(s):</b>
            <ul>
                <li v-for="error in errors" v-bind:key="error">{{ error }}</li>
            </ul>
        </div>

        <div class="form-group row">
            <label for="title" class="col-sm-2 col-form-label">Titulo:</label>
            <div class="col-sm-10">
                <input type="text" readonly="" class="form-control" id="title" v-model="title">
            </div>
        </div>

        <div class="form-group row">
            <label for="descricao"  class="col-sm-2 col-form-label">Descrição:</label>
            <div class="col-sm-10">
                <textarea readonly="" class="form-control" name="descricao"
                    v-model="body" id="descricao" rows="3"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="tipo"  class="col-sm-2 col-form-label">Tipo:</label>
            <div class="col-sm-10">
                <input type="text" readonly="" class="form-control" id="tipo" v-model="tipods">
            </div>
        </div>

        <div class="form-group row" v-if="tipo == 'E'">
            <label for="dtFim"  class="col-sm-2 col-form-label">Data Final Enq.:</label>
            <div class="col-sm-10">
                <input type="date" readonly="" class="form-control" id="dtFim" v-model="data_fim_enquete">
            </div>
        </div>
        <div class="form-group row" v-if="tipo == 'E'">
            <label class="col-sm-2 col-form-label">{{!votar ? 'Votar' : 'Votou'}} em:</label>

            <b-form-group  class="col-sm-10">
                <b-form-checkbox-group
                    v-model="votos_usuario_check"
                    text-field="nome"
                    value-field="id"
                    :disabled="(read_at != '' && read_at != undefined) || !verificaVotacaoDataValida()"
                    :options="opcoes"
                    size="lg"
                    name="buttons-2">
                </b-form-checkbox-group>
            </b-form-group>
            <div class="col-sm-12">
                <b-card>
                    <b-card-text>
                        <ul>
                            <li v-for="opcao in opcoes" :key="opcao.id" :class="votos_usuario_check.find(votoid => votoid == opcao.id) >= 0 ? 'text-success' : ''">{{ opcao.nome }} - ({{ opcao.detalhamento }})</li>
                        </ul>
                    </b-card-text>
                </b-card>
            </div>
        </div>

        <template v-slot:modal-footer="{ cancel, ok }">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-badge variant="info" v-if="tipo == 'E' && !verificaVotacaoDataValida()">Votação encerrada!</b-badge>
            <b-badge variant="light" v-if="read_at && votos_usuario_check.length > 0">{{tipo == 'E' ? 'Votado' : 'Lida'}} em: {{ read_at | formatDate }}</b-badge>
            <b-button-group class="mx-1">
                <b-button v-if="tipo == 'E' && read_at && verificaVotacaoDataValida()" size="sm"
                    variant="primary" style="width: 200px;"
                    class="text-warning"
                    @click="alterarVoto()" >
                    Alterar Voto
                </b-button>
                <b-button v-if="tipo == 'E' && !read_at && verificaVotacaoDataValida()" size="sm"
                    variant="success" style="width: 200px;"
                    @click="votarNasOpcoes();" >
                    Votar
                </b-button>

                <b-button style="width: 200px;" v-if="(tipo == 'M' || !verificaVotacaoDataValida()) && !read_at" size="sm" variant="info"  @click.prevent="marcarComoLida">
                    Marcar como lida
                </b-button>
                <b-button style="width: 80px;" size="sm" variant="danger" @click="cancel()">
                    Fechar
                </b-button>
            </b-button-group>
        </template>
    </b-modal>
</template>

<script>
import moment from 'moment';

export default {
    props : ['useradmin'],

    data: function(){
     //   tipolist: ['Bug', 'Melhoria'],
     return{
        errors              : [],
        success             : [],
        opcoes              : [],
        votos_usuario       : [],
        votos_usuario_check : [],
        title               : '',
        body                : '',
        tipo                : '',
        tipods              : '',
        data_fim_enquete    : '',
        resultado_publico   : '',
        multiescolha        : false,
        exibir              : '',
        id_post             : '',
        id_notificacao      : '',
        read_at             : '',
        post_edit           : undefined,
        is_resolvido        : false,
        modificou           : false
        }
    },

    created() {
        bus.$on('editarNotificacao', (obj) => {
            this.id_notificacao     = obj.id;
            this.id_post            = obj.data.post.id;
            this.read_at            = obj.read_at;
            this.carregarPost();
            this.$bvModal.show('modal-notificacao')
        });
    },

    computed:{
        notificacaoData() {
            return this.post_edit.data
        },
        votar(){
            return this.votos_usuario.length > 0 ? true : false;
        },
    },
    watch: {
      votos_usuario_check(newVal, oldVal) {
          //não permite selecionar mais que um caso não seja de multi escolha
        if(this.read_at){
            console.log(newVal)
            newVal.splice(0,newVal.length)
        }else
        if (newVal.length > 1 && !this.multiescolha) {
            newVal[0] = newVal[1]
            newVal.splice(1,1)
        }
      }
    },
    methods: {
        verificaVotacaoDataValida(){
            if(this.tipo == 'E'){
                let dateHj = moment(new Date()).format("MM-DD-YYYY"); // replace format by your one
                let dateFim = moment(this.data_fim_enquete).format("MM-DD-YYYY");
                if (Date.parse(dateHj) > Date.parse(dateFim)) {
                    return false;
                }
            }
            return true;
        },
        alterarVoto(){
            this.votos_usuario = [];
            this.read_at = '';
        },
        votarNasOpcoes() {//votos_usuario_check
            this.errors = [];
            this.success = [];

            if(this.votos_usuario_check.length === 0){
                this.errors = ["Selecione seu's' voto's'"];
                return false;
            }

            let head =
                { idopcoes: this.votos_usuario_check, idnotificacao: this.id_notificacao, idpost : this.id_post }

            this.$message('confirm', 'Confirma o voto?', '', () =>{
                this.$store.dispatch('votarNaOpcao', head).then((res) => {
                    console.log(res)
                    if(res.erro){
                        this.errors = [res.erro]
                    } if(res.data.erro){
                        this.errors = [res.data.erro]
                    } else {
                        this.carregarPost();
                        this.success = [res.data.success];
                        this.modificou = true
                    }
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error];
                })
            })
        },
        carregarPost() {
            this.errors = [];
            this.success = [];

            this.$store.dispatch('getPostByNotificationId', this.id_notificacao).then((res) => {
                if(res.erro){
                    this.errors = [res.erro]
                }else{
                    this.montarPost(res.data.post)
                }
            }).catch(function (error) {
                this.errors = [error]
            });

        },
        montarPost(obj){
            this.post_edit          = obj
            this.title              = obj.title
            this.body               = obj.body
            this.tipo               = obj.tipo
            this.tipods             = (obj.tipo == 'E' ? 'Enquete' : 'Mensagem')
            this.data_fim_enquete   = obj.data_fim_enquete
            this.resultado_publico  = obj.resultado_publico
            this.exibir             = obj.exibir
            this.opcoes             = obj.opcoes_enquete
            this.multiescolha       = obj.multiescolha
            this.votos_usuario      = obj.votos_usuario
            for (let index = 0; index < this.votos_usuario.length; index++) {
                const element = this.votos_usuario[index];
                this.votos_usuario_check[index] = element.opcao_id;
            }
        },
        marcarComoLida(){
            this.errors = [];
            this.success = [];

            let head =
                { id: this.id_notificacao }


            this.$store.dispatch('markAsRead', head).then((res) => {
                if(res.erro){
                    this.errors = [res.erro]
                } if(res.data.erro){
                    this.errors = [res.data.erro]
                } else {
                    this.carregarPost();
                    this.success = [res.data.success];
                    this.modificou = true
                }
            }).catch(function (error) {
                console.log(error)
                this.errors = [error]
            });

        },
        limparTela(){
            this.errors              = [];
            this.success             = [];
            this.opcoes              = [];
            this.votos_usuario       = [];
            this.votos_usuario_check = [];
            this.title               = '';
            this.body                = '';
            this.tipo                = '';
            this.tipods              = '';
            this.data_fim_enquete    = '';
            this.resultado_publico   = '';
            this.exibir              = '';
            this.id_post             = '';
            this.id_notificacao      = '';
            this.read_at             = '';
            this.multiescolha        = false;
            this.post_edit           = undefined;
            this.is_resolvido        = false;
            this.modificou           = false;
        },
        hidden(){
            if(this.modificou && window.location.pathname.includes('painel-notificacoes')){
                location.reload()
            }

            this.limparTela()
        }

    }
}
</script>
