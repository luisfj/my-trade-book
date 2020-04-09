<template>
<div>
    <div class="alert alert-success" v-if="success.length">
        <p>Importando da Corretora: {{ corretora }}</p>
        <b v-for="msg in success" v-bind:key="msg">{{ msg }}</b>
    </div>
    <div class="alert alert-danger" v-if="errors.length">
        <b>Ocorreu o(s) seguinte(s) erro(s):</b>
        <ul>
            <li v-for="error in errors" v-bind:key="error">{{ error }}</li>
        </ul>
    </div>

    <label class="text-reader text-warning">
        <input class="form-control-file" type="file" @change="loadTextFromFile">
    </label>
    <br />
    <button class="btn btn-primary" style="font-size: 18px;" @click="importar()">
        <i class="material-icons md-18">import_export</i>
        Importar
    </button>

</div>
</template>

<script>
import { importarArquivo as importarMT4 } from '../../services/importacaoMt4';
import { importarArquivo as importarMT5 } from '../../services/importacaoMt5';
import { isMt4File as isMt4File } from '../../services/importacaoMt4';
import { isMt5File as isMt5File } from '../../services/importacaoMt5';

export default {
    data: function(){
     return{
        errors         : [],
        success        : [],
        text           : "",
        corretora      : "",
        header         : [],
        transferencias : [],
        closedTrades   : [],
        openTrades     : [],
        }
    },

    methods: {
        loadTextFromFile(ev) {
            const file = ev.target.files[0];
            const reader = new FileReader();

            reader.onload = e => {
                this.text = e.target.result;
                this.errors = [];
                this.success = [];
            }
            reader.readAsText(file);
        },

        importar(){
            this.errors = [];
            this.success = [];
            this.corretora = "";
            this.header         = [];
            this.transferencias = [];
            this.closedTrades   = [];
            this.openTrades     = [];

            if (!this.text || this.text.length < 1) {
                this.errors.push('Selecione um arquivo para importar!');
                return true;
            }
            try{
                var html = $.parseHTML( this.text );
                var importou = 0;

                if(isMt4File(html)){
                    this.corretora = importarMT4(html,  this.header, this.closedTrades, this.openTrades, this.transferencias)
                    importou = 1;
                } else if(isMt5File(html)){
                    this.corretora = importarMT5(html,  this.header, this.closedTrades, this.openTrades, this.transferencias)
                    importou = 1;
                }

                if (!importou) {
                    this.errors.push('Selecione um arquivo válido!');
                    return true;
                }

            } catch (e) {
                this.errors.push(e.message);
                return;
            }

            let head =
                {   corretora:      this.corretora,
                    cabecalho:      this.header,
                    transferencias: this.transferencias,
                    openTrades:     this.openTrades,
                    closedTrades:   this.closedTrades
                }

            this.$store.dispatch('importarArquivos', head).then((res) => {
                console.log(res);
                console.log(res.data);
                if(res.data.error)
                    this.errors = [res.data.error]
                else
                    this.success = [res.data.success]
                this.text = '';
            }).catch(function (error) {
                console.log(error)
                this.errors = [error]
                this.text = '';
            });
        }
    }
};
//0/0 : Account: 4104053
//0/1 : Name: Luis Fernando Johann
//0/2 : Currency: USD
//0/3 : Leverage: 1:500
//0/4 : 2019 November 27, 18:37

//1/0 : Closed Transactions:

//2/0 : Ticket
//2/1 : Open Time
//2/2 : Type
//2/3 : Size
//2/4 : Item
//2/5 : Price
//2/6 : S / L
//2/7 : T / P
//2/8 : Close Time
//2/9 : Price
//2/10 : Commission
//2/11 : Taxes
//2/12 : Swap
//2/13 : Profit

// 11/0 : 54499864
// 11/1 : 2019.11.06 14:44:13
// 11/2 : balance
// 11/3 : WDL PSSK 0243286 000102021USD
// 11/4 : -30.00
</script>
