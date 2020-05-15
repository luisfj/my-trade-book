<div class="col-md-12 marb-10 noPadding-with-lr-5">
    <div class="card marb-5 altura-min-100p">
        {{-- <div class="card-header">Dashboard</div> --}}
        <div class="card-body">

            <nav>
                <div class="nav nav-tabs fs12" id="nav-tab-HDD" role="tablist">
                    <a class="nav-item nav-link" id="nav-filter-HDD-tab" data-toggle="tab" href="#nav-filter-HDD" role="tab" aria-controls="nav-filter-HDD" aria-selected="false">Filtros</a>
                    <a class="nav-item nav-link active" id="nav-dados-HDD-tab" data-toggle="tab" href="#nav-dados-HDD" role="tab" aria-controls="nav-dados-HDD" aria-selected="true">Dados</a>
                    <div class="fs12 label-filtros-dash-md7">
                            <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
                        <label>Corretoras: [<b><i id="iFiltroCorretorasHDD"></i></b>]  Ativos: [<b><i id="iFiltroInstrumentosHDD"></i></b>]  Período: [<b><i id="iFiltroPeriodoHDD"></i></b>]</label>
                    </div>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent-HDD">
                <div class="tab-pane fade" id="nav-filter-HDD" role="tabpanel" aria-labelledby="nav-filter-HDD-tab">
                    <form id="formFiltroHDD" action="POST" target="{{route('dash.hora.do.dia')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                                <div class="form-group">
                                    <div class="input-group input-group-sm"  >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Corretoras</div>
                                        </div>
                                        <select id="corretoraSelecionadaHDD" name="corretoraSelecionadaHDD[]"
                                            style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-sm mb3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Ativo</div>
                                        </div>
                                        <select id="ativoSelecionadoHDD" name="ativoSelecionadoHDD[]" style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb3">
                                        <label>Salvar ativo como padrão</label>
                                        <input type="checkbox" id="salvarComoPadraoAtivoHDD" name="salvarComoPadraoAtivoHDD">
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
                                    <button type="button" id="confirmarFiltrosBtnHDD" class="btn btn-secondary btn-sm"
                                        data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade show active" id="nav-dados-HDD" role="tabpanel" aria-labelledby="nav-dados-HDD-tab">
                    <div id="spinnerHDD" class="spinner-border text-success"></div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-xl-6 marb-10 noPadding-with-lr-5">
                            <div class="altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartResultadosHDD" height="100px" width="230px" class="hidde-me"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-xl-6 marb-10 noPadding-with-lr-5">
                            <div class="marb-5 altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartPontosHDD" height="100px" width="230px" class="hidde-me"></canvas>
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

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraHDD);

    function atualizouCorretoraHDD(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#corretoraSelecionadaHDD').val(selectionCorr);
        $('#corretoraSelecionadaHDD').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosHDD();
    }

    function atualizarListaMultiHDD() {
        $('#corretoraSelecionadaHDD').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });

        $('#ativoSelecionadoHDD').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var labelHDD = [];
    var dataValNrGainHDD = [];
    var dataValNrLossHDD = [];
    var dataValResGainHDD = [];
    var dataValResLossHDD = [];
    var operacoesHDD;
    var operacoesHDDFiltradas;
    var chartPontosHDD = null;
    var chartResultadoHDD = null;
    var ctxResultadoHDD = $('#chartResultadosHDD');
    var ctxPontosHDD = $('#chartPontosHDD');


    var urlHDD = $('#formFiltroHDD').attr('target');

//buscar e atualizar a lista de meses operados
    registrarQueroMesesOperados(atualizarMesesHDD);

    function atualizarMesesHDD(data){

        $('#ativoSelecionadoHDD')
                .find('option')
                .remove()
                .end();
        $('#corretoraSelecionadaHDD')
                .find('option')
                .remove()
                .end();

        /* inicio Ativos */
        var atvSel = [];
        $.each(data.ativosOperados, function(indice, ativo){
            atvSel[indice] = ativo.instrumento_id;
            $('#ativoSelecionadoHDD').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
        });

        var filtroPadraoAtivo = data.filtrosPadrao ?
            $.grep( data.filtrosPadrao, function( n ) { return n.tela === 'dashResultadoPorHoraDoDia' && n.campo === 'ativo' })
            : null;

        if(!filtroPadraoAtivo || filtroPadraoAtivo.length <= 0)
            $('#ativoSelecionadoHDD').val(atvSel);
        else
            $('#ativoSelecionadoHDD').val(filtroPadraoAtivo[0].filtro.split(','));
        /* fim Ativos */

        /* inicio Corretoras */
        var selectionCorr = [];
        var selectionCorrStr = '';
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            selectionCorrStr += (selectionCorrStr.length > 0 ? ', ' : '') + conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')' ;
            $('#corretoraSelecionadaHDD').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')'
                    }));
        });

        atualizarListaMultiHDD();
        atualizarDescricaoFiltrosHDD();
        /* fim Corretoras */

        //atualizarDadosHDD();
    }

//ao alterar um mes deve buscar os dados
    function alterouFiltroHDD() {
        $('#confirmarFiltrosBtnHDD').tooltip('show');
        $('#confirmarFiltrosBtnHDD').removeClass('btn-secondary');
        $('#confirmarFiltrosBtnHDD').addClass('btn-warning');
    }

    $('#dataInicial').change(function(){
        alterouFiltroHDD();
    });
    $('#dataFinal').change(function(){
        alterouFiltroHDD();
    });
    $('#ativoSelecionadoHDD').change(function(){
        alterouFiltroHDD();
    });
    $('#corretoraSelecionadaHDD').change(function(){
        alterouFiltroHDD();
    });

    $('#confirmarFiltrosBtnHDD').on('click', function () {
        buscarDadosHDD();
    });

    function buscarDadosHDD(){
        $('#nav-dados-HDD-tab').click();
        atualizarDadosHDD();
        $('#confirmarFiltrosBtnHDD').tooltip('hide');
        $('#confirmarFiltrosBtnHDD').removeClass('btn-warning');
        $('#confirmarFiltrosBtnHDD').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltrosHDD(){
        var corretorasSelec = $('#corretoraSelecionadaHDD').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#corretoraSelecionadaHDD').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasHDD').html(corrSelDs);

        var atvSel = $('#ativoSelecionadoHDD').bsMultiSelect()[0].selectedOptions;
        var atvSelDs = '';
        if(atvSel.length == $('#ativoSelecionadoHDD').bsMultiSelect()[0].length){
            atvSelDs = 'Todos';
        } else {
            $.each(atvSel, function(indice, opt){
                atvSelDs += (atvSelDs.length > 0 ? ', ' : '') + opt.text.substring(0,6);
            });
        }

        var dataInicial = $('#dataInicial').val();
        var dataFinal = $('#dataFinal').val();
        if(!dataInicial && !dataFinal){
            $('#iFiltroPeriodoHDD').html('Todos');
        } else {
            var de = '';
            var ate = '';
            if(dataInicial)
                de = ' de: ' + dataInicial;
            if(dataFinal)
                ate = ' até: ' + dataFinal;

            $('#iFiltroPeriodoHDD').html((de + ate));
        }

        $('#iFiltroInstrumentosHDD').html(atvSelDs);
    }

//busca atualiza e renderiza o resultado
    function atualizarDadosHDD() {
        atualizarDescricaoFiltrosHDD();
        $('#spinnerHDD').removeClass('hidde-me');
        ctxPontosHDD.addClass('hidde-me');
        ctxResultadoHDD.addClass('hidde-me');

        labelHDD = [];
        dataValNrGainHDD = [];
        dataValNrLossHDD = [];
        dataValResGainHDD = [];
        dataValResLossHDD = [];

        operacoesHDD = null;
        $.post( urlHDD, $('#formFiltroHDD').serialize(), function(data) {
            operacoesHDD = data.resultado;
            atualizaDadosFiltrarHDD();
        },
        'json' // I expect a JSON response
        );

    }

    function atualizaDadosFiltrarHDD() {

        operacoesHDDFiltradas = operacoesHDD;
        if(operacoesHDDFiltradas != null){
            $.each(operacoesHDDFiltradas, function(id, res) {
                labelHDD[id] = res.horaDoDia;
                dataValNrGainHDD[id] = (res.nrGains ?? 0);
                dataValNrLossHDD[id] = (res.nrLosses ?? 0);
                dataValResGainHDD[id] = (res.totalGainsValor ?? 0);
                dataValResLossHDD[id] = (res.totalLossesValor ?? 0) * -1;
            });
        }
        atualizaGraficoHDD();
        $('#spinnerHDD').addClass('hidde-me');
        ctxPontosHDD.removeClass('hidde-me');
        ctxResultadoHDD.removeClass('hidde-me');
    }

    function atualizaGraficoHDD() {
        if(chartPontosHDD == null){
            chartPontosHDD = new Chart(ctxPontosHDD, {
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
                        text: 'Nº de Operações por Hora do Dia'
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Hora do Dia'
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
                        intersect: false,
                        callbacks: {
                            labelTextColor: function(tooltipItem, chart) {
                                return tooltipItem.datasetIndex === 1 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';//'#543453';
                            },
                        },
                    }
                }
            });

            chartResultadoHDD = new Chart(ctxResultadoHDD, {
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
                        text: 'Lucro e Prejuizo por Hora do Dia'
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
                                labelString: 'Hora do Dia'
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
                        intersect: false,
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
        removeDataDoGraficoHDD();
        addDataHDD();
    }

    function addDataHDD() {
        chartPontosHDD.data.labels = labelHDD;
        chartResultadoHDD.data.labels = labelHDD;

        addDatasetHDD('N° de Ganhos');
        addDatasetHDD('N° de Perdas');
    }

    function addDatasetHDD(labelDataSet) {
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
                newDataset.data = dataValNrGainHDD;
            } else {
                newDataset.data = dataValNrLossHDD;
            }
            if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                newDatasetResultado.data = dataValResGainHDD;
            } else {
                newDatasetResultado.data = dataValResLossHDD;
            }
/*
            dataValNrGainHDD[id] = (res.nrGain ?? 0);
            dataValNrLossHDD[id] = (res.nrLosses ?? 0);
            dataValResGainHDD[id] = (res.totalGainValor ?? 0);
            dataValResLossHDD[id] = (res.totalLossesValor ?? 0);
    */
			chartPontosHDD.data.datasets.push(newDataset);
			chartPontosHDD.update();

			chartResultadoHDD.data.datasets.push(newDatasetResultado);
			chartResultadoHDD.update();
    }

    function removeDataDoGraficoHDD() {
        chartPontosHDD.data.labels = null;
        chartPontosHDD.data.datasets = [];
        chartPontosHDD.update();

        chartResultadoHDD.data.labels = null;
        chartResultadoHDD.data.datasets = [];
        chartResultadoHDD.update();
    }

</script>
@stop
