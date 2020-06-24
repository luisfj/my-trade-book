<div class="row" id="resumoCorretoraContent">
    <div class="col-sm-12 col-md-12 col-lg-12" >
        <div class="row fs11">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 padb-5" >
                <table id="tableResGeralCapAloc"
                    data-toggle="bootstrap-table"
                    data-detail-view="true"
                    data-detail-view-by-click="true"
                    data-detail-formatter="detalheCapitalAlocadoFormatter"
                    data-classes="table"
                    data-show-footer="false">
                    <thead>
                        <tr>
                            <th data-field="nome" data-formatter="infoColumnFormatter">Capital Alocado</th>
                            <th data-field="depositos" data-halign="right" data-align="right" data-formatter="depositosTab1ColumnFormatter">Depósitos</th>
                            <th data-field="saques" data-halign="right" data-align="right" data-formatter="saquesTab1ColumnFormatter">Saques</th>
                            <th data-field="resultado_operacoes" data-halign="right" data-align="right" data-formatter="valorColumnFormatter">Resultado Operações</th>
                            <th data-field="depositos" data-halign="right" data-align="right" data-formatter="saldoTab1ColumnFormatter">Saldo</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

    </div>
</div>


@section('page-script')
@parent
<script>
    var dados = [];
    var capitaisAlocados = [];

    $(document).ready(function () {
        $('#tableResGeralCapAloc').bootstrapTable({
            data: []
        });
        buscarDados();
    });

    function buscarDados(){

        var urlGet = '/evolucao-capital-anual';

        $.get(urlGet, function(data){
            dados = data.dados;
            capitaisAlocados = data.resumoCapitais;
            atualizarDadosDataTable();
        });
    }

    function buscaPeriodosDeCapitalAlocado(capital_id) {
        if(!dados || dados.length <= 0){
            return [];
        } else {
            return dados.filter(function(dat){
                    return dat.capital_alocado_id == capital_id && (
                        1*(dat.depositos_cap_aloc_anterior??0)   ||
                        1*(dat.depositos_cap_aloc_mes??0) ||
                        1*(dat.depositos_conta_mes??0) ||
                        1*(dat.depositos_menos_saques_anterior??0) ||
                        1*(dat.resultado??0) ||
                        1*(dat.resultado_anterior??0) ||
                        1*(dat.saques_cap_aloc_anterior??0) ||
                        1*(dat.saques_cap_aloc_mes??0) ||
                        1*(dat.saques_conta_mes??0) ||
                        1*(dat.tranferencias_cap_aloc_anterior??0) ||
                        1*(dat.transferencias_anterior??0) ||
                        1*(dat.transferencias_cap_aloc_mes??0) ||
                        1*(dat.transferencias_mes??0) )
                }).reduce(function(memo, e1){
                var matches = memo.filter(function(e2){
                    return e1.ano == e2.ano && e1.mes == e2.mes
                });
                if (matches.length == 0)
                    memo.push(e1)
                return memo;
            }, []);
        }
    }

    function buscaContasDoPeriodoNoCapitalAlocado(row) {
        if(!dados || dados.length <= 0){
            return [];
        }
        return dados.filter(function(dat){
                return dat.capital_alocado_id == row.capital_alocado_id && dat.ano == row.ano && dat.mes == row.mes
                && (
                        1*(dat.depositos_cap_aloc_anterior??0)   ||
                        1*(dat.depositos_cap_aloc_mes??0) ||
                        1*(dat.depositos_conta_mes??0) ||
                        1*(dat.depositos_menos_saques_anterior??0) ||
                        1*(dat.resultado??0) ||
                        1*(dat.resultado_anterior??0) ||
                        1*(dat.saques_cap_aloc_anterior??0) ||
                        1*(dat.saques_cap_aloc_mes??0) ||
                        1*(dat.saques_conta_mes??0) ||
                        1*(dat.tranferencias_cap_aloc_anterior??0) ||
                        1*(dat.transferencias_anterior??0) ||
                        1*(dat.transferencias_cap_aloc_mes??0) ||
                        1*(dat.transferencias_mes??0) )
            });
    }

    function buscaContasDoPeriodoNoCapitalAlocadoComContaExterna(row) {
        let contas = buscaContasDoPeriodoNoCapitalAlocado(row);
        if(contas && contas.length > 0){
            let novo = {
                corretora_nome:'EXTERNO - -',
                conta_corretora_identificador:'-',
                depositos_conta_mes: contas[0].depositos_cap_aloc_mes,
                saques_conta_mes: contas[0].saques_cap_aloc_mes,
                transferencias_mes: contas[0].transferencias_cap_aloc_mes,
                transferencias_anterior: contas[0].tranferencias_cap_aloc_anterior,
                resultado_anterior: 0,
                resultado: 0,
                sigla: contas[0].sigla
            }
            if(
                1*(novo.depositos_conta_mes??0) ||
                1*(novo.saques_conta_mes??0) ||
                1*(novo.transferencias_mes??0) ||
                1*(novo.transferencias_anterior??0)
            )
                contas.push(novo);
        }
        return contas;
    }

    function atualizarDadosDataTable(){
        $('#tableResGeralCapAloc').bootstrapTable('refreshOptions', {
            data: capitaisAlocados
        });
        $('#tableResGeralCapAloc').bootstrapTable('expandAllRows');
    }

    function valorColumnFormatter(valor, row, infoAdicional = null){
        return '<div class="' + ( valor ? (valor > 0 ? 'text-success' : 'text-warning') : '') + '">' + formatarValor(valor, row) + (infoAdicional ?? '') +'</div>';
    }

    function depositosTab1ColumnFormatter(valor, row){
        let depositos = (row.depositos*1 + row.depositos_conta_externa*1);
        if(!depositos)
            return '<div class="text-info"> - </div>';
        return '<div class="text-info">' + formatarValor(depositos, row) +'</div>';
    }

    function saquesTab1ColumnFormatter(valor, row){
        let saques = (row.saques*1 + row.saques_conta_externa*1);
        if(!saques)
            return '<div class="text-info"> - </div>';
        return '<div class="text-info">' + formatarValor(saques, row) +'</div>';
    }

    function saldoTab1ColumnFormatter(valor, row){
        let saldo = (row.depositos*1 + row.depositos_conta_externa*1) + (row.saques*1 + row.saques_conta_externa*1) + (row.resultado_operacoes*1);
        if(!saldo)
            return '<div class="text-info"> - </div>';
        return valorColumnFormatter(saldo, row);
    }

    function infoColumnFormatter(valor, row){
        return '<div class="text-info">'+ valor +'</div>';
    }

    function corretoraTab3ColumnFormatter(value, row){
        return infoColumnFormatter((row.conta_corretora_identificador + ' - ' + value), row);
    }

    function saldoAnteriorTab3ColumnFormatter(value, row){
        return valorColumnFormatter(getSaldoAnteriorTab3(row), row);
    }

    function saldoAnteriorTab2ColumnFormatter(value, row){
        return valorColumnFormatter(getSaldoAnteriorTab2(row), row);
    }

    function saldoAtualTab3ColumnFormatter(value, row){
        let saldoAnterior = getSaldoAnteriorTab3(row);
        let noMes = ((row.resultado??0)*1) + ((row.depositos_conta_mes??0)*1) + ((row.saques_conta_mes??0)*1) + ((row.transferencias_mes??0)*1);
        return valorColumnFormatter((saldoAnterior + noMes), row);
    }

    function saldoAtualTab2ColumnFormatter(value, row){
        let saldoAnterior = getSaldoAnteriorTab2(row);

        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let noMes = contasDoMes.reduce(function(acumulado, row){
                acumulado += ((row.resultado??0)*1) + ((row.depositos_conta_mes??0)*1) + ((row.saques_conta_mes??0)*1);
                return acumulado;
            }, 0);

        noMes += (((contasDoMes[0].depositos_cap_aloc_mes ?? 0)*1) + ((contasDoMes[0].saques_cap_aloc_mes ?? 0)*1));
        return valorColumnFormatter((saldoAnterior + noMes), row);
    }

    function resultadoTab3ColumnFormatter(value, row){
        let porc = calculaPorcentagem(getSaldoAnteriorTab3(row), value);
        return valorColumnFormatter(value, row, (' (' + (porc > 0 ? '+'+porc : porc) + '%) <i class="material-icons md-18">'+(value ? (value > 0 ? 'arrow_upward' : 'arrow_downward') : '')+'</i>') );
    }

    function resultadoTab2ColumnFormatter(value, row){
        let saldoAnterior = getSaldoAnteriorTab2(row);

        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let resultadoAtual = contasDoMes.reduce(function(acumulado, row){
                acumulado += row.resultado*1;
                return acumulado;
            }, 0);

        let porc = calculaPorcentagem(saldoAnterior, resultadoAtual);

        return valorColumnFormatter(resultadoAtual, row, (' (' + (porc > 0 ? '+'+porc : porc) + '%) <i class="material-icons md-18">'+(resultadoAtual ? (resultadoAtual > 0 ? 'arrow_upward' : 'arrow_downward') : '')+'</i>') );
    }

    function depositosTab2ColumnFormatter(value, row){
        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let depositos = contasDoMes.reduce(function(acumulado, row){
                acumulado += (row.depositos_conta_mes ?? 0)*1;
                return acumulado;
            }, 0);

        depositos += ((contasDoMes[0].depositos_cap_aloc_mes ?? 0)*1);

        return valorAzulTab3ColumnFormatter(depositos, row);
    }

    function saquesTab2ColumnFormatter(value, row){
        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let saques = contasDoMes.reduce(function(acumulado, row){
                acumulado += (row.saques_conta_mes ?? 0 )*1;
                return acumulado;
            }, 0);

            saques += ((contasDoMes[0].saques_cap_aloc_mes ?? 0)*1);

        return valorAzulTab3ColumnFormatter(saques, row);
    }

    function transferenciasTab2ColumnFormatter(value, row){
        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let transf = contasDoMes.reduce(function(acumulado, row){
                acumulado += (row.transferencias_mes??0)*1;
                return acumulado;
            }, 0);
        return valorAzulTab3ColumnFormatter(transf, row);
    }

    function valorAzulTab3ColumnFormatter(valor, row){
        let val = ((valor??0)*1);
        if(!val)
            return '<div class="text-info"> - </div>';
        return '<div class="text-info">' + formatarValor(val, row) +'</div>';
    }

    function mesTab2ColumnFormatter(mes, row) {
        return '<div class="text-info">' + converteMesParaString(mes) + ' - ' + row.ano +'</div>';
    }

    function getSaldoAnteriorTab3(row) {
        return (((row.transferencias_anterior??0) *1) + ((row.resultado_anterior??0)*1));
    }

    function getSaldoAnteriorTab2(row) {
        let contasDoMes = buscaContasDoPeriodoNoCapitalAlocado(row);
        let saldoContasAnterior = contasDoMes.reduce(function(acumulado, row){
                let saldAntConta = (((row.depositos_menos_saques_anterior??0) *1) + ((row.resultado_anterior??0)*1));
                acumulado += saldAntConta;
                return acumulado;
            }, 0);

        saldoContasAnterior += ((contasDoMes[0].saques_cap_aloc_anterior ?? 0)*1 + (contasDoMes[0].depositos_cap_aloc_anterior ?? 0))*1;
        return saldoContasAnterior;
    }

    function calculaPorcentagem(total, valor) {
        if(!total || !valor) return '';
        let valTotal = total;
        if( (total < 0 && valor > 0) || (total > 0 && valor < 0) ){
            valTotal = total *-1;
        }
        let porc = (valor / valTotal)*100;
        if( (porc < 0 && valor > 0) || (porc > 0 && valor < 0) ){
            porc = (porc * -1);
        }
        return porc.toFixed(2);
    }

    function detalheCapitalAlocadoFormatter(index, row, element) {
        //$(element).addClass('table-secondary').addClass('noPadding-tlr').addClass('padb-5');
        //var table =
        $(element).html('<table class="table table-sm table-hover table-com-detalhe-primary" data-toggle="bootstrap-table" data-classes="table table-dark" style="margin-left: 4%; width: 95%;"></table>').find('table')
        .bootstrapTable({
            columns: [
                getColumn('mes', 'Mês', mesTab2ColumnFormatter),
                getColumn('resultado_anterior', 'Saldo Anterior', saldoAnteriorTab2ColumnFormatter, 'right'),
                getColumn('depositos_conta_mes', 'Depósitos', depositosTab2ColumnFormatter, 'right'),
                getColumn('saques_conta_mes', 'Saques', saquesTab2ColumnFormatter, 'right'),
                getColumn('transferencias_mes', 'Transferências', transferenciasTab2ColumnFormatter, 'right'),
                getColumn('resultado', 'RESULTADO', resultadoTab2ColumnFormatter, 'right'),
                getColumn('resultado', 'Saldo Atual', saldoAtualTab2ColumnFormatter, 'right'),
            ],
            data: buscaPeriodosDeCapitalAlocado(row.capital_alocado_id),
            detailView: true,
            detailViewByClick: true,
            onExpandRow: function (index, row, detail) {
                //$(detail).addClass('table-secondary').addClass('noPadding-tlr').addClass('padb-5');
                $(detail).html('<table class="table table-sm table-hover" data-toggle="bootstrap-table" data-classes="table" style="margin-left: 4%; width: 95%;"></table>').find('table')
                    .bootstrapTable({
                        columns: [//background-color #32383e !important;
                            getColumn('corretora_nome', 'Corretora', corretoraTab3ColumnFormatter),
                            getColumn('resultado_anterior', 'Saldo Anterior', saldoAnteriorTab3ColumnFormatter, 'right'),
                            getColumn('depositos_conta_mes', 'Depósitos', valorAzulTab3ColumnFormatter, 'right'),
                            getColumn('saques_conta_mes', 'Saques', valorAzulTab3ColumnFormatter, 'right'),
                            getColumn('transferencias_mes', 'Transferências', valorAzulTab3ColumnFormatter, 'right'),
                            getColumn('resultado', 'RESULTADO', resultadoTab3ColumnFormatter, 'right'),
                            getColumn('resultado', 'Saldo Atual', saldoAtualTab3ColumnFormatter, 'right'),
                        ],
                        data: buscaContasDoPeriodoNoCapitalAlocadoComContaExterna(row),
                    });
            }
        });
    }
    function getColumn(campo, titulo = null, format = null, alinhamento = null, ordena = false) {
        return {
                    field: campo,
                    title: (titulo ?? campo),
                    sortable: ordena,
                    formatter: format,
                    halign: alinhamento,
                    align: alinhamento
                };
    }
</script>
@stop
