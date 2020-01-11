<template>
    <div>
        <div class="form-group row " style="text-align: right; margin-bottom: 0px;">
            <div class="form-group row col-sm-3">
                <label class="col-sm-4 col-form-label col-form-label-sm">Filtro</label>
                <div class="col-sm-8">
                    <select v-model="contaSelecionado" class="form-control form-control-sm" name="s" id="s">
                        <option value="todas">Todas</option>
                        <option v-for="conta in contas" :key="conta.id" v-bind:value="conta.id">
                            {{ conta.nome }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row col-sm-3">
                <label class="col-sm-4 col-form-label col-form-label-sm">Periodo</label>
                <div class="col-sm-8">
                    <select v-model="periodoSelecionado" class="form-control form-control-sm" name="s" id="s">
                        <option value="mes_atual">Mês Atual</option>
                        <option value="mes_anterior">Mês Anterior</option>
                        <option value="12_meses">Ultimos 12 Meses</option>
                        <option value="6_meses">Ultimos 6 Meses</option>
                        <option value="ano_atual">Ano Atual</option>
                        <option value="ano_anterior">Ano Anterior</option>
                        <option value="todos">Todos</option>
                        <option value="personalizado">Personalizar</option>
                    </select>
                </div>
            </div>

            <div v-if="filtro_personalizado" class="form-group row col-sm-3">
                <label class="col-sm-4 col-form-label col-form-label-sm">De</label>
                <div class="col-sm-8">
                    <input type="date" v-model="dataInicial" name="dtInicio" id="dtInicio" class="form-control form-control-sm">
                </div>
            </div>

            <div v-if="filtro_personalizado" class="form-group row col-sm-3">
                <label class="col-sm-4 col-form-label col-form-label-sm">Até</label>
                <div class="col-sm-8">
                    <input type="date" v-model="dataFinal" name="dtFim" id="dtFim" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="form-group row " style="text-align: right; margin-bottom: 0px;">
            <div class="form-group row col-sm-4">
                <button class="btn btn-sm btn-warning" style="margin-left: 35px;" @click="atualizarGrafico()">Atualizar</button>
            </div>
        </div>

    <b-table striped hover title="Dados grid Fechamentos" :items="gridData" :fields="fields" :tbody-tr-class="rowClass"
                    :busy="isBusy" responsive="sm" sticky-header>
        <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
            </div>
        </template>
        <template v-slot:cell(mes_ano)="data">
            <b class="text-info">{{ data.value.split('-')[1] }}</b> - <b>{{ data.value.split('-')[0] }}</b>
        </template>

        <template v-slot:cell(receitas)="data">
            <b class="text-info">{{ data.value | formatarMoeda }}</b>
        </template>
        <template v-slot:cell(despesas)="data">
            <b class="text-danger">{{ data.value | formatarMoeda }}</b>
        </template>
        <template v-slot:cell(resultado_mes)="data">
            <b :class="data.value >= 0 ? 'text-success' : 'text-warning'">{{ data.value | formatarMoeda }}</b>
        </template>
    </b-table>

    </div>
</template>

<script>
export default {
    props : ['contas'],

    data () {
        return {
            isBusy: false,
            // Array will be automatically processed with visualization.arrayToDataTable function
            fields: [
                {
                    key: 'mes_ano',
                    label: 'Data',
                    sortable: true
                },
                {
                    key: 'conta_fechamento.nome',
                    label: 'Conta',
                    sortable: true
                },
                {
                    key: 'receitas',
                    sortable: true
                },
                {
                    key: 'despesas',
                    sortable: true,
                    // Variant applies to the whole column, including the header and footer
                    //variant: 'danger'
                },
                {
                    key: 'resultado_mes',
                    label: 'Resultado',
                    sortable: true
                },
            ],

            gridData : [],

            chartOptions: {
                chart: {
                title: 'Company Performance',
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                },
                height: 250,
                colors: ['blue', 'green', 'red', '#f3b49f', '#f6c7b6'],
                vAxis: { format: 'currency' }
            },

            periodoSelecionado : '6_meses',
            contaSelecionado  : 'todas',
            dataInicial        : '',
            dataFinal          : ''
        }
    },

    mounted: function() {
        this.atualizarGrafico() // Calls the method before page loads
    },

    computed: {
            filtro_personalizado() {
                return this.periodoSelecionado == 'personalizado';
            }
        },

    methods: {
        atualizarGrafico(){
            this.isBusy = !this.isBusy
            let head =
                {
                    periodoSelecionado   : this.periodoSelecionado,
                    contaSelecionado     : this.contaSelecionado,
                    dataInicial          : this.dataInicial,
                    dataFinal            : this.dataFinal
                };

            axios.post('/sa/fechamento-mes/grid/filtrado', head)
                .then((response) => {
                    //context.commit('LOAD_NOTIFICATIONS', response.data.relatorio)
                    console.log(response);
                    let res = [];//response.data.relatorio;
                    //delete res['receitas'];
                    //delete res['despesas'];
                    this.gridData = response.data.relatorio;
                    console.log(response.data.relatorio);
                    this.isBusy = !this.isBusy
                    //this.chartData = this.formatarRespostaGrafico(response.data.relatorio);
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error];
                    this.isBusy = !this.isBusy
                });
        },

        rowClass(item, type) {
            if (!item || type !== 'row') return
            let mes = item.mes_ano.split('-');

            if (mes[1] === '01') return 'table-jan'
            if (mes[1] === '02') return 'table-fev'
            if (mes[1] === '03') return 'table-mar'
            if (mes[1] === '04') return 'table-abr'
            if (mes[1] === '05') return 'table-mai'
            if (mes[1] === '06') return 'table-jun'
            if (mes[1] === '07') return 'table-jul'
            if (mes[1] === '08') return 'table-ago'
            if (mes[1] === '09') return 'table-set'
            if (mes[1] === '10') return 'table-out'
            if (mes[1] === '11') return 'table-nov'
            if (mes[1] === '12') return 'table-dez'
        }

    }

}
</script>
