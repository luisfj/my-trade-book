<template>
    <div>
        <div class="form-group row " style="text-align: right; margin-bottom: 0px;">
            <div class="form-group row col-sm-4">
                <label class="col-sm-4 col-form-label col-form-label-sm">Tipo</label>
                <div class="col-sm-8">
                    <select v-model="tipoSelecionado" class="form-control form-control-sm" name="s" id="s">
                        <option value="mensal">Mensal</option>
                        <option value="anual">Anual</option>
                        <option value="mensal/ano">Mensal/Ano</option>
                    </select>
                </div>
            </div>

            <div class="form-group row col-sm-4">
                <label class="col-sm-4 col-form-label col-form-label-sm">Filtro</label>
                <div class="col-sm-8">
                    <select v-model="filtroSelecionado" class="form-control form-control-sm" name="s" id="s">
                        <option value="completo">Completo</option>
                        <option value="despesas">Despesas</option>
                        <option value="receitas">Receitas</option>
                        <option value="receitas_despesas">Receitas & Despesas</option>
                        <option value="resultado">Resultado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group row " style="text-align: right; margin-bottom: 0px;">
            <div class="form-group row col-sm-4">
                <label class="col-sm-4 col-form-label col-form-label-sm">Periodo</label>
                <div class="col-sm-8">
                    <select v-model="periodoSelecionado" class="form-control form-control-sm" name="s" id="s">
                        <option value="12_meses">Ultimos 12 Meses</option>
                        <option value="6_meses">Ultimos 6 Meses</option>
                        <option value="ano_atual">Ano Atual</option>
                        <option value="ano_anterior">Ano Anterior</option>
                        <option value="todos">Todos</option>
                        <option value="personalizado">Personalizar</option>
                    </select>
                </div>
            </div>

            <div v-if="filtro_personalizado" class="form-group row col-sm-4">
                <label class="col-sm-4 col-form-label col-form-label-sm">De</label>
                <div class="col-sm-8">
                    <input type="date" v-model="dataInicial" name="dtInicio" id="dtInicio" class="form-control form-control-sm">
                </div>
            </div>

            <div v-if="filtro_personalizado" class="form-group row col-sm-4">
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

        <GChart
            type="ColumnChart"
            :data="chartData"
            :options="chartOptions"
            @ready="onChartReady"
        />
    </div>
</template>

<script>
export default {
    data () {
        return {
            // Array will be automatically processed with visualization.arrayToDataTable function
            chartData: [
                ['Year', 'Resultado', 'Receitas', 'Despesas'],
                ['Sem Dados', 0, 0, 0],
            ],

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
            tipoSelecionado    : 'mensal',
            filtroSelecionado  : 'completo',
            dataInicial        : '',
            dataFinal          : ''
        }
    },

    computed: {
            filtro_personalizado() {
                return this.periodoSelecionado == 'personalizado';
            }
        },

    methods: {
        atualizarGrafico(){
            let head =
                {
                    periodoSelecionado   : this.periodoSelecionado,
                    tipoSelecionado      : this.tipoSelecionado,
                    dataInicial          : this.dataInicial,
                    dataFinal            : this.dataFinal
                };

            axios.post('/sa/fechamento-mes/grafico/filtrado', head)
                .then((response) => {
                    //context.commit('LOAD_NOTIFICATIONS', response.data.relatorio)
                    //console.log(response);
                    let res = [];//response.data.relatorio;
                    //delete res['receitas'];
                    //delete res['despesas'];
                    this.chartData = this.formatarRespostaGrafico(response.data.relatorio);
                }).catch(function (error) {
                    console.log(error)
                    this.errors = [error];
                });
        },

        onChartReady (chart, google) {
            /*const query = new google.visualization.Query('https://url-to-spreadsheet...')
            query.send(response => {
                const options = {
                // some custom options
                }
                const data = response.getDataTable()
                chart.draw(data, options)
            })*/
            this.atualizarGrafico();
        },

        formatarRespostaGrafico(dados){
            let res = [];
            this.chartOptions.colors =['blue', 'green', 'red'];

            if(this.tipoSelecionado === 'mensal/ano'){
                return dados;
            }

            switch (this.filtroSelecionado) {
                case 'despesas':
                    this.chartOptions.colors =['red'];

                    dados.forEach(linha => {
                        res.push(linha.slice(0, 1).concat(linha.slice(3, 4)));
                    });
                    break;
                case 'receitas':
                    this.chartOptions.colors =['green'];

                    dados.forEach(linha => {
                        res.push(linha.slice(0, 1).concat(linha.slice(2, 3)));
                    });
                    break;
                case 'receitas_despesas':
                    this.chartOptions.colors =['green', 'red'];

                    dados.forEach(linha => {
                        res.push(linha.slice(0, 1).concat(linha.slice(2, 4)));
                    });
                    break;
                case 'resultado':
                    dados.forEach(linha => {
                        res.push(linha.slice(0, 2));
                    });
                    break;
                default:
                    res = dados;
                    break;
            }
            return res;
        }
    }

}
</script>
