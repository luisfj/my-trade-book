<nav>
    <div class="nav nav-tabs fs12" id="nav-tab" role="tablist">
        <a class="nav-item nav-link" id="nav-filter-trade-a-trade-tab" data-toggle="tab" href="#nav-filter-trade-a-trade" role="tab" aria-controls="nav-filter-trade-a-trade" aria-selected="false">Filtros</a>
        <a class="nav-item nav-link active" id="nav-dados-trade-a-trade-tab" data-toggle="tab" href="#nav-dados-trade-a-trade" role="tab" aria-controls="nav-dados-trade-a-trade" aria-selected="true">Dados</a>
        <div class="fs12 label-filtros-dash-md7">
                   <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
            <label>Corretoras: [<b><i id="iFiltroCorretorasTaT">Todas</i></b>]  Ativos: [<b><i id="iFiltroInstrumentosTaT">XAUUSD</i></b>]  Período: [<b><i id="iFiltroPeriodoTaT">Fev-2020</i></b>]</label>
        </div>
    </div>
</nav>

<div class="tab-content" id="nav-tabContentTaT">
    <div class="tab-pane fade" id="nav-filter-trade-a-trade" role="tabpanel" aria-labelledby="nav-filter-trade-a-trade-tab">
        <form id="formFiltroMensal" action="POST" target="{{route('dash.trade.a.trade')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                    <div class="form-group">
                        <div class="input-group input-group-sm"  >
                            <div class="input-group-prepend">
                                <div class="input-group-text">Corretoras</div>
                            </div>
                            <select id="corretoraSelecionada" name="corretoraSelecionada[]"
                                style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm mb3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Ativo</div>
                            </div>
                            <select id="ativoSelecionado" name="ativoSelecionado" class="custom-select">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Mês</div>
                            </div>
                            <select id="mesSelecionado" name="mesSelecionado" class="custom-select">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="confirmarFiltrosBtn" class="btn btn-secondary btn-sm"
                            data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade show active" id="nav-dados-trade-a-trade" role="tabpanel" aria-labelledby="nav-dados-trade-a-trade-tab">
        <div id="spinnerTradeATrade" class="spinner-border text-success"></div>
        <canvas id="myChart" height="100px" width="270px" class="hidde-me"></canvas>
    </div>
</div>
@section('page-script')
@parent
<script>

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraTaT);

    function atualizouCorretoraTaT(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#corretoraSelecionada').val(selectionCorr);
        $('#corretoraSelecionada').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosTaT();
    }

    function atualizarListaMulti() {
        $('#corretoraSelecionada').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var label = [];
    var dataVal = [];
    var operacoes;
    var operacoesFiltradas;
    var myChart = null;
    var ctx = $('#myChart');
    var atualizouMes = false;

    var url = $('#formFiltroMensal').attr('target'); //'http://localhost:8000/dashTradeATrade';
    var urlMesesOperados = url.replace('dashTradeATrade', 'buscarMesesOperados');// 'http://localhost:8000/buscarMesesOperados';

    $('#ativoSelecionado')
                .find('option')
                .remove()
                .end();
    $('#corretoraSelecionada')
                .find('option')
                .remove()
                .end();
    $('#mesSelecionado')
                .find('option')
                .remove()
                .end();


//buscar e atualizar a lista de meses operados

    $.get(urlMesesOperados, function(data){
        var now = new Date();
        var mesHoje = now.getMonth();
        var anoHoje = now.getFullYear();
        var mesAnoHohe = mesHoje + '-' + anoHoje;
        var essemestem = false;
        atualizouMes = true;

/* inicio Meses Operados */
        $.each(data.mesesOperados, function(indice, mes){
            if(mes.mes_ano == mesAnoHohe)
                essemestem = true;

            $('#mesSelecionado').append($('<option>', {
                        value: mes.mes_ano,
                        text : converteMesNumParaString(mes.mes) + "-" + mes.ano
                    }));
        });
        if(!essemestem){
            $('#mesSelecionado').prepend($('<option>', {
                        value: mesAnoHohe,
                        text : converteMesNumParaString(mesHoje) + "-" + anoHoje
                    }));
        }
        $('#mesSelecionado').val(mesAnoHohe);
        $('#iFiltroPeriodoTaT').html(converteMesNumParaString(mesHoje) + "-" + anoHoje);
        /* fim Meses Operados */
        /* inicio Ativos */
        $.each(data.ativosOperados, function(indice, ativo){
            $('#ativoSelecionado').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
            if(ativo.instrumento.sigla == 'XAUUSD'){
                $('#ativoSelecionado').val(ativo.instrumento_id);
                $('#iFiltroInstrumentosTaT').html(ativo.instrumento.sigla);
            }
        });
        /* fim Ativos */
        /* inicio Corretoras */
        var selectionCorr = [];
        var selectionCorrStr = '';
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            selectionCorrStr += (selectionCorrStr.length > 0 ? ', ' : '') + conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')' ;
            $('#corretoraSelecionada').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')'
                    }));
        });
        $('#corretoraSelecionada').val(selectionCorr);
        $('#iFiltroCorretorasTaT').html(selectionCorrStr);

        atualizarListaMulti();
        /* fim Corretoras */

        atualizarDados();
    });

//ao alterar um mes deve buscar os dados
    function alterouFiltro() {
        $('#confirmarFiltrosBtn').tooltip('show');
        $('#confirmarFiltrosBtn').removeClass('btn-secondary');
        $('#confirmarFiltrosBtn').addClass('btn-warning');
    }

    $('#mesSelecionado').change(function(){
        atualizouMes = true;
        alterouFiltro();
    });
    $('#ativoSelecionado').change(function(){
        alterouFiltro();
    });
    $('#corretoraSelecionada').change(function(){
        alterouFiltro();
    });

    $('#confirmarFiltrosBtn').on('click', function () {
        buscarDadosTaT();
    });

    function buscarDadosTaT(){
        $('#nav-dados-trade-a-trade-tab').click();
        atualizarDados();
        $('#confirmarFiltrosBtn').tooltip('hide');
        $('#confirmarFiltrosBtn').removeClass('btn-warning');
        $('#confirmarFiltrosBtn').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltros(){
        var corretorasSelec = $('#corretoraSelecionada').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#corretoraSelecionada').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasTaT').html(corrSelDs);

        var atvSel = $('#ativoSelecionado option:selected').text();
        var mesSel = $('#mesSelecionado option:selected').text();

        $('#iFiltroInstrumentosTaT').html(atvSel);
        $('#iFiltroPeriodoTaT').html(mesSel);
    }

//busca atualiza e renderiza o resultado
    function atualizarDados() {
        atualizarDescricaoFiltros();
        $('#spinnerTradeATrade').removeClass('hidde-me');
        ctx.addClass('hidde-me');

        label = [];
        dataVal = [];
        if(atualizouMes){
            operacoes = null;
            $.post( url, $('#formFiltroMensal').serialize(), function(data) {
                operacoes = data.operacoes;
                atualizouMes = false;
                atualizaDadosFiltrar();
            },
            'json' // I expect a JSON response
            );
        } else {
            atualizaDadosFiltrar();
        }
    }

    function atualizaDadosFiltrar() {
        var ativoSel = $('#ativoSelecionado').val();
        var corretorasSels = $('#corretoraSelecionada').val();

        operacoesFiltradas = operacoes == null ? null :
                    operacoes.filter(oper => oper.instrumento_id == ativoSel && corretorasSels.includes(''+oper.conta_corretora_id));
        if(operacoesFiltradas != null){
            $.each(operacoesFiltradas, function(id, res) {
                label[id] = (id + 1);
                dataVal[id] = (res.resultado ?? 0);
            });
        }
        atualizaGrafico();
        $('#spinnerTradeATrade').addClass('hidde-me');
        ctx.removeClass('hidde-me');
    }

    function atualizaGrafico() {
        if(myChart == null){

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [], //label,//['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: ' Resultado',
                        data: [],//dataVal, //[12, 19, -3, 5, -2, 0],
                        backgroundColor: function(context) {
                            var index = context.dataIndex;
                            var value = context.dataset.data[index];
                            return value < 0 ? 'rgba(255, 99, 132, 0.4)' : 'rgba(75, 192, 192, 0.4)';
                        },
                        borderColor: function(context) {
                            var index = context.dataIndex;
                            var value = context.dataset.data[index];
                            return value < 0 ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)';
                        },
                        borderWidth: 1
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Trade a Trade Mensal'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        //position: 'nearest',
                        intersect: false,
                        displayColors: false,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var retorno = [];
                                var oper = operacoesFiltradas[tooltipItem.index];

                                //var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                var abertura    = 'Abertura:        ' + oper.abertura;
                                var fechamento  = 'Fechamento:  ' + oper.fechamento;
                                var resultado   = 'Resultado:     ' + oper.resultado;
                                var corretora   = 'Corretora:       ' + oper.conta_corretora.corretora.nome;
                                var instrumento = 'Ativo:              ' + oper.instrumento.sigla;
                                instrumento += " ".repeat((32 - instrumento.length)) +
                                'Duração: ' + (oper.tempo_operacao_dias > 0 ? oper.tempo_operacao_dias + ' d, ' : '')
                                                                    + oper.tempo_operacao_horas;;
                                var contratos   = 'Lotes:             ' + oper.lotes;
                                contratos += " ".repeat((37 - contratos.length)) +
                                'Pontos: ' + oper.pips;

                                retorno[0] = abertura;
                                retorno[1] = fechamento;
                                retorno[2] = corretora;
                                retorno[3] = instrumento;
                                retorno[4] = contratos;
                                retorno[5] = resultado;

                                return retorno; //[label, (operacoes == null ? 'null' : 'teste ' + operacoes[tooltipItem.datasetIndex].instrumento.sigla)];
                            },
                            /*labelColor: function(tooltipItem, chart) {
                                var res = Math.round(tooltipItem.yLabel * 100) / 100;
                                console.log(tooltipItem);
                                return {
                                    borderColor: 'rgba(0, 0, 0, 1)',
                                    backgroundColor: 'rgba(0, 0, 0, 1)'
                                };
                            },*/
                            labelTextColor: function(tooltipItem, chart) {
                                var res = Math.round(tooltipItem.yLabel * 100) / 100;
                                return res < 0 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';//'#543453';
                            }/*,
                            footer: function(tooltipItems, data) {
                                var sum = 0;

                                tooltipItems.forEach(function(tooltipItem) {
                                    sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                });
                                return 'Sum: ' + sum;
                            },*/
                        },

                    }
                }
            });
        }
        removeDataDoGrafico();
        addData();
    }

    function addData() {
        myChart.data.labels = label;
        myChart.data.datasets.forEach((dataset) => {
            dataset.data = dataVal;
        });

        myChart.update();
    }

    function removeDataDoGrafico() {
        myChart.data.labels = null;
        myChart.data.datasets.forEach((dataset) => {
            dataset.data = null;
        });
        myChart.update();
    }

    function converteMesNumParaString(mes){
        switch (mes) {
            case 1:
                return 'Jan';
            case 2:
                return 'Fev';
            case 3:
                return 'Mar';
            case 4:
                return 'Abr';
            case 5:
                return 'Mai';
            case 6:
                return 'Jun';
            case 7:
                return 'Jul';
            case 8:
                return 'Ago';
            case 9:
                return 'Set';
            case 10:
                return 'Out';
            case 11:
                return 'Nov';
            case 12:
                return 'Dez';
            default:
                return '';
        }
    }
</script>
@stop
