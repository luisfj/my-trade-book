<div class="col-md-6 marb-10 noPadding-with-lr-5">
    <div class="card marb-5 altura-min-100p">
        {{-- <div class="card-header">Dashboard</div> --}}
        <div class="card-body">

            <nav>
                <div class="nav nav-tabs fs12" id="nav-tab-SDM" role="tablist">
                    <a class="nav-item nav-link" id="nav-filter-SDM-tab" data-toggle="tab" href="#nav-filter-SDM" role="tab" aria-controls="nav-filter-SDM" aria-selected="false">Filtros</a>
                    <a class="nav-item nav-link active" id="nav-dados-SDM-tab" data-toggle="tab" href="#nav-dados-SDM" role="tab" aria-controls="nav-dados-SDM" aria-selected="true">Dados</a>
                    <div class="fs12 label-filtros-dash-md7">
                            <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
                        <label>Corretoras: [<b><i id="iFiltroCorretorasSDM"></i></b>]  Ativos: [<b><i id="iFiltroInstrumentosSDM"></i></b>]  Período: [<b><i id="iFiltroPeriodoSDM"></i></b>]</label>
                    </div>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent-SDM">
                <div class="tab-pane fade" id="nav-filter-SDM" role="tabpanel" aria-labelledby="nav-filter-SDM-tab">
                    <form id="formFiltroSDM" action="POST" target="{{route('dash.semana.do.mes')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                                <div class="form-group">
                                    <div class="input-group input-group-sm"  >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Corretoras</div>
                                        </div>
                                        <select id="corretoraSelecionadaSDM" name="corretoraSelecionadaSDM[]"
                                            style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-sm mb3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Ativo</div>
                                        </div>
                                        <select id="ativoSelecionadoSDM" name="ativoSelecionadoSDM[]" style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb3">
                                        <label>Salvar ativo como padrão</label>
                                        <input type="checkbox" id="salvarComoPadraoAtivoSDM" name="salvarComoPadraoAtivoSDM">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-sm ">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Período de:</div>
                                        </div>
                                        <input type="date" id="dataInicial" name="dataInicial">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group input-group-sm ">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">até:</div>
                                        </div>
                                        <input type="date" id="dataFinal" name="dataFinal" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="confirmarFiltrosBtnSDM" class="btn btn-secondary btn-sm"
                                        data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade show active" id="nav-dados-SDM" role="tabpanel" aria-labelledby="nav-dados-SDM-tab">
                    <div id="spinnerSDM" class="spinner-border text-success"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 marb-10 noPadding-with-lr-5">
                            <div class="altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartResultadosSDM" height="100px" width="130px" class="hidde-me"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 marb-10 noPadding-with-lr-5">
                            <div class="marb-5 altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartPontosSDM" height="100px" width="130px" class="hidde-me"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('page-script')
@parent
<script>

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraSemanaDoMes);

    function atualizouCorretoraSemanaDoMes(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#corretoraSelecionadaSDM').val(selectionCorr);
        $('#corretoraSelecionadaSDM').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosSemanaDoMes();
    }

    function atualizarListaMultiSDM() {
        $('#corretoraSelecionadaSDM').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });

        $('#ativoSelecionadoSDM').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var labelSDM = [];
    var dataValNrGainSDM = [];
    var dataValNrLossSDM = [];
    var dataValResGainSDM = [];
    var dataValResLossSDM = [];
    var operacoesSDM;
    var operacoesSDMFiltradas;
    var chartPontosSDM = null;
    var chartResultadoSDM = null;
    var ctxResultadoSDM = $('#chartResultadosSDM');
    var ctxPontosSDM = $('#chartPontosSDM');


    var urlSDM = $('#formFiltroSDM').attr('target');

//buscar e atualizar a lista de meses operados
    registrarQueroMesesOperados(atualizarMesesSDM);

    function atualizarMesesSDM(data){

        $('#ativoSelecionadoSDM')
                .find('option')
                .remove()
                .end();
        $('#corretoraSelecionadaSDM')
                .find('option')
                .remove()
                .end();

        /* inicio Ativos */
        var atvSel = [];
        $.each(data.ativosOperados, function(indice, ativo){
            atvSel[indice] = ativo.instrumento_id;
            $('#ativoSelecionadoSDM').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
        });

        var filtroPadraoAtivo = data.filtrosPadrao ?
            $.grep( data.filtrosPadrao, function( n ) { return n.tela === 'dashResultadoPorSemanaDoMes' && n.campo === 'ativo' })
            : null;

        if(!filtroPadraoAtivo || filtroPadraoAtivo.length <= 0)
            $('#ativoSelecionadoSDM').val(atvSel);
        else
            $('#ativoSelecionadoSDM').val(filtroPadraoAtivo[0].filtro.split(','));
        /* fim Ativos */

        /* inicio Corretoras */
        var selectionCorr = [];
        var selectionCorrStr = '';
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            selectionCorrStr += (selectionCorrStr.length > 0 ? ', ' : '') + conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')' ;
            $('#corretoraSelecionadaSDM').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')'
                    }));
        });

        atualizarListaMultiSDM();
        atualizarDescricaoFiltrosSDM();
        /* fim Corretoras */

        //atualizarDadosSDM();
    }

//ao alterar um mes deve buscar os dados
    function alterouFiltroSDM() {
        $('#confirmarFiltrosBtnSDM').tooltip('show');
        $('#confirmarFiltrosBtnSDM').removeClass('btn-secondary');
        $('#confirmarFiltrosBtnSDM').addClass('btn-warning');
    }

    $('#dataInicial').change(function(){
        alterouFiltroSDM();
    });
    $('#dataFinal').change(function(){
        alterouFiltroSDM();
    });
    $('#ativoSelecionadoSDM').change(function(){
        alterouFiltroSDM();
    });
    $('#corretoraSelecionadaSDM').change(function(){
        alterouFiltroSDM();
    });

    $('#confirmarFiltrosBtnSDM').on('click', function () {
        buscarDadosSemanaDoMes();
    });

    function buscarDadosSemanaDoMes(){
        $('#nav-dados-SDM-tab').click();
        atualizarDadosSDM();
        $('#confirmarFiltrosBtnSDM').tooltip('hide');
        $('#confirmarFiltrosBtnSDM').removeClass('btn-warning');
        $('#confirmarFiltrosBtnSDM').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltrosSDM(){
        var corretorasSelec = $('#corretoraSelecionadaSDM').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#corretoraSelecionadaSDM').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasSDM').html(corrSelDs);

        var atvSel = $('#ativoSelecionadoSDM').bsMultiSelect()[0].selectedOptions;
        var atvSelDs = '';
        if(atvSel.length == $('#ativoSelecionadoSDM').bsMultiSelect()[0].length){
            atvSelDs = 'Todos';
        } else {
            $.each(atvSel, function(indice, opt){
                atvSelDs += (atvSelDs.length > 0 ? ', ' : '') + opt.text.substring(0,6);
            });
        }

        var dataInicial = $('#dataInicial').val();
        var dataFinal = $('#dataFinal').val();
        if(!dataInicial && !dataFinal){
            $('#iFiltroPeriodoSDM').html('Todos');
        } else {
            var de = '';
            var ate = '';
            if(dataInicial)
                de = ' de: ' + dataInicial;
            if(dataFinal)
                ate = ' até: ' + dataFinal;

            $('#iFiltroPeriodoSDM').html((de + ate));
        }

        $('#iFiltroInstrumentosSDM').html(atvSelDs);
    }

//busca atualiza e renderiza o resultado
    function atualizarDadosSDM() {
        atualizarDescricaoFiltrosSDM();
        $('#spinnerSDM').removeClass('hidde-me');
        ctxPontosSDM.addClass('hidde-me');
        ctxResultadoSDM.addClass('hidde-me');

        labelSDM = [];
        dataValNrGainSDM = [];
        dataValNrLossSDM = [];
        dataValResGainSDM = [];
        dataValResLossSDM = [];

        operacoesSDM = null;
        $.post( urlSDM, $('#formFiltroSDM').serialize(), function(data) {
            operacoesSDM = data.resultado;
            atualizaDadosFiltrarSDM();
        },
        'json' // I expect a JSON response
        );

    }

    function atualizaDadosFiltrarSDM() {

        operacoesSDMFiltradas = operacoesSDM;
        if(operacoesSDMFiltradas != null){
            $.each(operacoesSDMFiltradas, function(id, res) {
                labelSDM[id] = res.diaDaSemana;
                dataValNrGainSDM[id] = (res.nrGains ?? 0);
                dataValNrLossSDM[id] = (res.nrLosses ?? 0);
                dataValResGainSDM[id] = (res.totalGainsValor ?? 0);
                dataValResLossSDM[id] = (res.totalLossesValor ?? 0) * -1;
            });
        }
        atualizaGraficoSDM();
        $('#spinnerSDM').addClass('hidde-me');
        ctxPontosSDM.removeClass('hidde-me');
        ctxResultadoSDM.removeClass('hidde-me');
    }

    function atualizaGraficoSDM() {
        if(chartPontosSDM == null){
            chartPontosSDM = new Chart(ctxPontosSDM, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: ' Resultado',
                        data: [],
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Nº de Operações por Semana do Mês'
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Semana'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Valor'
                            },
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
                        //intersect: false
                        callbacks: {
                            labelTextColor: function(tooltipItem, chart) {
                                return tooltipItem.datasetIndex === 1 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';//'#543453';
                            },
                        },
                    }
                }
            });

            chartResultadoSDM = new Chart(ctxResultadoSDM, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: ' Resultado',
                        data: [],
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Lucro e Prejuizo por Semana do Mês'
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Semana'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Valor'
                            },
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
                        callbacks: {
                            labelTextColor: function(tooltipItem, chart) {
                                return tooltipItem.datasetIndex === 1 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';//'#543453';
                            },
                            label: function(tooltipItem, data) {
                                return (tooltipItem.datasetIndex === 1 ? 'Prejuizo: ' : 'Lucro: ') + formatarValor(tooltipItem.value, contaCorretoraSelecionada);
                            },
                        },
                    }
                }
            });
        }
        removeDataDoGraficoSDM();
        addDataSDM();
    }

    function addDataSDM() {
        chartPontosSDM.data.labels = labelSDM;
        chartResultadoSDM.data.labels = labelSDM;

        addDatasetSDM('N° de Ganhos');
        addDatasetSDM('N° de Perdas');
    }

    function addDatasetSDM(labelDataSet) {
            //var colorNames = Object.keys(window.chartColors);
			//var colorName = colorNames[(chartEvolucaoSaldoAnual.data.datasets.length) % colorNames.length];
            //var colorName = colorNames[chartEvolucaoSaldoAnual.data.datasets.length];

			var newDataset = {
                type: 'bar',
				label: labelDataSet ? ' ' + labelDataSet : 'Sem Tipo Definido',
				//backgroundColor: newColor,
				//borderColor: newColor,
				data: [],
				fill: false,
                backgroundColor: function(context) {
                    if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                        return 'rgba(75, 192, 192, 0.4)';
                    } else {
                        return 'rgba(255, 99, 132, 0.4)';
                    }
                },
                borderColor: function(context) {
                    if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                        return 'rgba(75, 192, 192, 1)';
                    } else {
                        return 'rgba(255, 99, 132, 1)';
                    }
                },
                borderWidth: 1
			};

            var newDatasetResultado = {
                type: 'bar',
				label: labelDataSet ? (labelDataSet == 'N° de Ganhos' ? 'Lucro' : 'Prejuizo') : 'Sem Tipo Definido',
				//backgroundColor: newColor,
				//borderColor: newColor,
				data: [],
				fill: false,
                backgroundColor: function(context) {
                    if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                        return 'rgba(75, 192, 192, 0.4)';
                    } else {
                        return 'rgba(255, 99, 132, 0.4)';
                    }
                },
                borderColor: function(context) {
                    if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                        return 'rgba(75, 192, 192, 1)';
                    } else {
                        return 'rgba(255, 99, 132, 1)';
                    }
                },
                borderWidth: 1
			};

            if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                newDataset.data = dataValNrGainSDM;
            } else {
                newDataset.data = dataValNrLossSDM;
            }
            if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                newDatasetResultado.data = dataValResGainSDM;
            } else {
                newDatasetResultado.data = dataValResLossSDM;
            }
/*
            dataValNrGainSDM[id] = (res.nrGain ?? 0);
            dataValNrLossSDM[id] = (res.nrLosses ?? 0);
            dataValResGainSDM[id] = (res.totalGainValor ?? 0);
            dataValResLossSDM[id] = (res.totalLossesValor ?? 0);
    */
			chartPontosSDM.data.datasets.push(newDataset);
			chartPontosSDM.update();

			chartResultadoSDM.data.datasets.push(newDatasetResultado);
			chartResultadoSDM.update();
    }

    function removeDataDoGraficoSDM() {
        chartPontosSDM.data.labels = null;
        chartPontosSDM.data.datasets = [];
        chartPontosSDM.update();

        chartResultadoSDM.data.labels = null;
        chartResultadoSDM.data.datasets = [];
        chartResultadoSDM.update();
    }

</script>
@stop
