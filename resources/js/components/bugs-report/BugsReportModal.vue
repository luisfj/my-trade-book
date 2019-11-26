<template>

    <b-modal id="modal-reportar-problema" size="lg" title="Informar Erro/Melhoria"
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

        <div class="form-group">
            <label class="col-form-label" for="pagina">Pagina:</label>
            <label class="col-form-label" style="" v-if="bug_edit"><b>{{ pagina }} {{ bug_edit && bug_edit.data_verificacao ? ' - Verificada em: ' + bug_edit.data_verificacao : '' }}</b></label>
            <input class="form-control" v-else placeholder="Pagina" id="pagina" v-model="pagina" type="text" name="pagina">
        </div>

        <div class="form-group">
            <label for="tipo">Tipo:</label>
            <label class="col-form-label" style="" v-if="bug_edit"><b>{{ tipo }}</b></label>
            <select v-else v-model="tipo" name="tipo" class="form-control" id="tipo">
                <option>Bug</option>
                <option>Melhoria</option>
            </select>
        </div>

        <div class="form-group">
            <label for="relato">Relato:</label>
            <label class="col-form-label" style="" v-if="bug_edit"><b>{{ descricao }}</b></label>
            <textarea v-else class="form-control " v-bind:class="{ 'is-invalid': errors.length && descricao.length < 3 }"
                 name="relato" v-model="descricao" id="relato" rows="3"></textarea>
        </div>

        <div v-if="bug_edit">
            Mensagens:
            <hr>
            <div class="card mb-3"
                :class="[{'border-warning': msg.autor.id == bug_edit.autor_id}, {'border-danger': msg.autor.id != bug_edit.autor_id}]"
                v-for="msg in bugMessages" :key="msg.id">
                <div class="card-body" style="padding-top: 0.6rem !important; padding-bottom: 0.35rem !important;">
                    <h5 class="card-title">{{ msg.autor.name }}</h5>
                    <p class="card-text" style="font-size: 0.85rem;">{{ msg.descricao }}.</p>
                </div>
            </div>
            <div class="form-group">
                <label for="msg">Mensagem:</label>

                <div v-if="useradmin && !bug_edit.data_resolucao" class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="is_resolvido">
                    <label class="custom-control-label" for="customSwitch1">Marcar como resolvida</label>
                </div>
                <textarea class="form-control " v-bind:class="{ 'is-invalid': errors.length && mensagem.length < 3 }"
                    name="msg" v-model="mensagem" id="msg" rows="3"></textarea>
            </div>
        </div>

        <template v-slot:modal-footer="{ cancel, ok }">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button-group class="mx-1">
                <b-button style="width: 200px;" v-if="bug_edit && !bug_edit.data_verificacao" size="sm" variant="info"  @click.prevent="marcarComoLida">
                    Marcar como verificada
                </b-button>
                <b-button style="width: 80px;" size="sm" variant="danger" @click="cancel()">
                    Cancelar
                </b-button>
                <b-button style="width: 100px;" v-if="bug_edit" size="sm" variant="warning"  @click.prevent="addMensagem">
                    Add Message
                </b-button>
                <b-button style="width: 80px;" v-else size="sm" variant="success"  @click.prevent="addBug">
                    OK
                </b-button>
            </b-button-group>

        </template>
    </b-modal>
</template>

<script>

export default {
    props : ['useradmin'],

    data: function(){
     //   tipolist: ['Bug', 'Melhoria'],
     return{
        errors      : [],
        success     : [],
        pagina      : window.location.pathname,
        tipo        : 'Bug',
        descricao   : '',
        mensagem    : '',
        bug_edit    : undefined,
        is_resolvido: false,
        modificou   : false
        }
    },

    created() {
        bus.$on('editBug', (obj) => {
            this.bug_edit   = obj;
            this.pagina     = obj.pagina
            this.tipo       = obj.tipo
            this.descricao  = obj.descricao
            this.$bvModal.show('modal-reportar-problema')
        });
    },

    computed:{
        bugMessages() {
            return this.bug_edit.messages
        }
    },

    methods: {
        addBug() {
            this.errors = [];
            this.success = [];

            if (!this.tipo) {
                this.errors.push('O tipo é obrigatório.');
            }
            if (!this.descricao || this.descricao.length < 3) {
                this.errors.push('O relato é obrigatório e deve ter mais de 3 caracteres!');
            }

            if (this.errors.length) {
                return true;
            }

            let head =
                {pagina: this.pagina, tipo: this.tipo, descricao: this.descricao }

            //this.$message('confirm', 'Confirmação', 'Tem certeza que deseja marcar todas como lidas?', () =>{
                this.$store.dispatch('addBug', head).then((res) => {
                    this.limparTela()
                    this.modificou = true
                    this.success = [res.data]
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error];
                });
           // })
        },
        addMensagem() {
            this.errors = [];
            this.success = [];

            if (!this.mensagem || this.mensagem.length < 3) {
                this.errors.push('A mensagem é obrigatória e deve ter mais de 3 caracteres!');
            }

            if (this.errors.length) {
                return true;
            }

            let head =
                {mensagem: this.mensagem, bug_id: this.bug_edit.id, is_resolvido: this.is_resolvido }

            //this.$message('confirm', 'Confirmação', 'Tem certeza que deseja marcar todas como lidas?', () =>{
                this.$store.dispatch('addBugMessage', head).then((res) => {
                    console.log(res)
                    this.mensagem = ''
                    this.success = [res.data.success]
                    this.bug_edit = res.data.bug
                    this.modificou = true
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error]
                });
           // })
        },
        marcarComoLida(){
            this.errors = [];
            this.success = [];

            let head =
                { bug_id: this.bug_edit.id }

            //this.$message('confirm', 'Confirmação', 'Tem certeza que deseja marcar todas como lidas?', () =>{
                this.$store.dispatch('marcarComoLida', head).then((res) => {
                    console.log(res)
                    this.success = [res.data.success]
                    this.bug_edit = res.data.bug
                    this.modificou = true
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error]
                });
           // })
        },
        limparTela(){
            this.errors    = []
            this.success   = []
            this.pagina    = window.location.pathname
            this.tipo      = 'Bug'
            this.descricao = ''
            this.mensagem  = ''
            this.bug_edit  = undefined
            this.modificou = false
        },
        hidden(){
            if(this.modificou && window.location.pathname.includes('painel-comunicacao')){
                location.reload()
            }

            this.limparTela()
        }

    }
}
</script>
