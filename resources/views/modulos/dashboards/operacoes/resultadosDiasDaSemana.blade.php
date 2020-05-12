<div class="col-md-6 marb-10 noPadding-with-lr-5">
    <div class="card marb-5 altura-min-100p">
        {{-- <div class="card-header">Dashboard</div> --}}
        <div class="card-body">

            <nav>
                <div class="nav nav-tabs fs12" id="nav-tab-dds" role="tablist">
                    <a class="nav-item nav-link" id="nav-filter-dds-tab" data-toggle="tab" href="#nav-filter-dds" role="tab" aria-controls="nav-filter-dds" aria-selected="false">Filtros</a>
                    <a class="nav-item nav-link active" id="nav-dados-dds-tab" data-toggle="tab" href="#nav-dados-dds" role="tab" aria-controls="nav-dados-dds" aria-selected="true">Dados</a>
                    <div class="fs12 label-filtros-dash-md7">
                            <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
                        <label>Corretoras: [<b><i id="iFiltroCorretorasDDS"></i></b>]  Ativos: [<b><i id="iFiltroInstrumentosDDS"></i></b>]  Período: [<b><i id="iFiltroPeriodoDDS"></i></b>]</label>
                    </div>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent-dds">
                <div class="tab-pane fade" id="nav-filter-dds" role="tabpanel" aria-labelledby="nav-filter-dds-tab">
                    <form id="formFiltroDDS" action="POST" target="{{route('dash.dias.da.semana')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                                <div class="form-group">
                                    <div class="input-group input-group-sm"  >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Corretoras</div>
                                        </div>
                                        <select id="corretoraSelecionadaDDS" name="corretoraSelecionadaDDS[]"
                                            style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-sm mb3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Ativo</div>
                                        </div>
                                        <select id="ativoSelecionadoDDS" name="ativoSelecionadoDDS[]" style="display: none;" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="mb3">
                                        <label>Salvar ativo como padrão</label>
                                        <input type="checkbox" id="salvarComoPadraoAtivoDDS" name="salvarComoPadraoAtivoDDS">
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
                                    <button type="button" id="confirmarFiltrosBtnDDS" class="btn btn-secondary btn-sm"
                                        data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade show active" id="nav-dados-dds" role="tabpanel" aria-labelledby="nav-dados-dds-tab">
                    <div id="spinnerDDS" class="spinner-border text-success"></div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 marb-10 noPadding-with-lr-5">
                            <div class="altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartResultadosDDS" height="100px" width="130px" class="hidde-me"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 marb-10 noPadding-with-lr-5">
                            <div class="marb-5 altura-min-100p">
                                {{-- <div class="card-header">Dashboard</div> --}}
                                <div class="card-body">
                                    <canvas id="chartPontosDDS" height="100px" width="130px" class="hidde-me"></canvas>
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

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraDiasDaSemana);

    function atualizouCorretoraDiasDaSemana(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#corretoraSelecionadaDDS').val(selectionCorr);
        $('#corretoraSelecionadaDDS').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosDiasDaSemana();
    }

    function atualizarListaMultiDDS() {
        $('#corretoraSelecionadaDDS').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });

        $('#ativoSelecionadoDDS').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var labelDDS = [];
    var dataValNrGainDDS = [];
    var dataValNrLossDDS = [];
    var dataValResGainDDS = [];
    var dataValResLossDDS = [];
    var operacoesDDS;
    var operacoesDDSFiltradas;
    var chartPontosDDS = null;
    var chartResultadoDDS = null;
    var ctxResultadoDDS = $('#chartResultadosDDS');
    var ctxPontosDDS = $('#chartPontosDDS');


    var urlDDS = $('#formFiltroDDS').attr('target');

//buscar e atualizar a lista de meses operados
    registrarQueroMesesOperados(atualizarMesesDDS);

    function atualizarMesesDDS(data){
        $('#ativoSelecionadoDDS')
                .find('option')
                .remove()
                .end();
        $('#corretoraSelecionadaDDS')
                .find('option')
                .remove()
                .end();
        /* inicio Ativos */
        var atvSel = [];
        $.each(data.ativosOperados, function(indice, ativo){
            atvSel[indice] = ativo.instrumento_id;
            $('#ativoSelecionadoDDS').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
        });
        var filtroPadraoAtivo = data.filtrosPadrao ?
            $.grep( data.filtrosPadrao, function( n ) { return n.tela === 'dashResultadoDiasDaSemana' && n.campo === 'ativo' })
            : null;

        if(!filtroPadraoAtivo || filtroPadraoAtivo.length <= 0)
            $('#ativoSelecionadoDDS').val(atvSel);
        else
            $('#ativoSelecionadoDDS').val(filtroPadraoAtivo[0].filtro.split(','));
        /* fim Ativos */

        /* inicio Corretoras */
        var selectionCorr = [];
        var selectionCorrStr = '';
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            selectionCorrStr += (selectionCorrStr.length > 0 ? ', ' : '') + conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')' ;
            $('#corretoraSelecionadaDDS').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')'
                    }));
        });

        atualizarListaMultiDDS();
        atualizarDescricaoFiltrosDDS();
        /* fim Corretoras */

        //atualizarDadosDDS();
    }

//ao alterar um mes deve buscar os dados
    function alterouFiltroDDS() {
        $('#confirmarFiltrosBtnDDS').tooltip('show');
        $('#confirmarFiltrosBtnDDS').removeClass('btn-secondary');
        $('#confirmarFiltrosBtnDDS').addClass('btn-warning');
    }

    $('#dataInicial').change(function(){
        alterouFiltroDDS();
    });
    $('#dataFinal').change(function(){
        alterouFiltroDDS();
    });
    $('#ativoSelecionadoDDS').change(function(){
        alterouFiltroDDS();
    });
    $('#corretoraSelecionadaDDS').change(function(){
        alterouFiltroDDS();
    });

    $('#confirmarFiltrosBtnDDS').on('click', function () {
        buscarDadosDiasDaSemana();
    });

    function buscarDadosDiasDaSemana(){
        $('#nav-dados-dds-tab').click();
        atualizarDadosDDS();
        $('#confirmarFiltrosBtnDDS').tooltip('hide');
        $('#confirmarFiltrosBtnDDS').removeClass('btn-warning');
        $('#confirmarFiltrosBtnDDS').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltrosDDS(){
        var corretorasSelec = $('#corretoraSelecionadaDDS').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#corretoraSelecionadaDDS').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasDDS').html(corrSelDs);

        var atvSel = $('#ativoSelecionadoDDS').bsMultiSelect()[0].selectedOptions;
        var atvSelDs = '';
        if(atvSel.length == $('#ativoSelecionadoDDS').bsMultiSelect()[0].length){
            atvSelDs = 'Todos';
        } else {
            $.each(atvSel, function(indice, opt){
                atvSelDs += (atvSelDs.length > 0 ? ', ' : '') + opt.text.substring(0,6);
            });
        }

        var dataInicial = $('#dataInicial').val();
        var dataFinal = $('#dataFinal').val();
        if(!dataInicial && !dataFinal){
            $('#iFiltroPeriodoDDS').html('Todos');
        } else {
            var de = '';
            var ate = '';
            if(dataInicial)
                de = ' de: ' + dataInicial;
            if(dataFinal)
                ate = ' até: ' + dataFinal;

            $('#iFiltroPeriodoDDS').html((de + ate));
        }

        $('#iFiltroInstrumentosDDS').html(atvSelDs);
    }

//busca atualiza e renderiza o resultado
    function atualizarDadosDDS() {
        atualizarDescricaoFiltrosDDS();
        $('#spinnerDDS').removeClass('hidde-me');
        ctxPontosDDS.addClass('hidde-me');
        ctxResultadoDDS.addClass('hidde-me');

        labelDDS = [];
        dataValNrGainDDS = [];
        dataValNrLossDDS = [];
        dataValResGainDDS = [];
        dataValResLossDDS = [];

        operacoesDDS = null;
        $.post( urlDDS, $('#formFiltroDDS').serialize(), function(data) {
            operacoesDDS = data.resultado;
            atualizaDadosFiltrarDDS();
        },
        'json' // I expect a JSON response
        );

    }

    function atualizaDadosFiltrarDDS() {

        operacoesDDSFiltradas = operacoesDDS;
        if(operacoesDDSFiltradas != null){
            $.each(operacoesDDSFiltradas, function(id, res) {
                switch (res.diaDaSemana) {
                    case 'Monday':
                        labelDDS[id] = 'Segunda';
                        break;
                    case 'Tuesday':
                        labelDDS[id] = 'Terça';
                        break;
                    case 'Wednesday':
                        labelDDS[id] = 'Quarta';
                        break;
                    case 'Thursday':
                        labelDDS[id] = 'Quinta';
                        break;
                    case 'Friday':
                        labelDDS[id] = 'Sexta';
                        break;
                    case 'Saturday':
                        labelDDS[id] = 'Sábado';
                        break;
                    case 'Sunday':
                        labelDDS[id] = 'Domingo';
                        break;
                    default:
                        break;
                }
                dataValNrGainDDS[id] = (res.nrGains ?? 0);
                dataValNrLossDDS[id] = (res.nrLosses ?? 0);
                dataValResGainDDS[id] = (res.totalGainsValor ?? 0);
                dataValResLossDDS[id] = (res.totalLossesValor ?? 0) * -1;
            });
        }
        atualizaGraficoDDS();
        $('#spinnerDDS').addClass('hidde-me');
        ctxPontosDDS.removeClass('hidde-me');
        ctxResultadoDDS.removeClass('hidde-me');
    }

    function atualizaGraficoDDS() {
        if(chartPontosDDS == null){
            chartPontosDDS = new Chart(ctxPontosDDS, {
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
                        text: 'Nº de Operações por Dia da Semana'
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Dia da Semana'
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

            chartResultadoDDS = new Chart(ctxResultadoDDS, {
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
                        text: 'Lucro e Prejuizo por Dia da Semana'
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Dia da Semana'
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
        removeDataDoGraficoDDS();
        addDataDDS();
    }

    function addDataDDS() {
        chartPontosDDS.data.labels = labelDDS;
        chartResultadoDDS.data.labels = labelDDS;

        addDatasetDDS('N° de Ganhos');
        addDatasetDDS('N° de Perdas');
    }

    function addDatasetDDS(labelDataSet) {
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
                newDataset.data = dataValNrGainDDS;
            } else {
                newDataset.data = dataValNrLossDDS;
            }
            if(labelDataSet && labelDataSet == 'N° de Ganhos'){
                newDatasetResultado.data = dataValResGainDDS;
            } else {
                newDatasetResultado.data = dataValResLossDDS;
            }
/*
            dataValNrGainDDS[id] = (res.nrGain ?? 0);
            dataValNrLossDDS[id] = (res.nrLosses ?? 0);
            dataValResGainDDS[id] = (res.totalGainValor ?? 0);
            dataValResLossDDS[id] = (res.totalLossesValor ?? 0);
    */
			chartPontosDDS.data.datasets.push(newDataset);
			chartPontosDDS.update();

			chartResultadoDDS.data.datasets.push(newDatasetResultado);
			chartResultadoDDS.update();
    }

    function removeDataDoGraficoDDS() {
        chartPontosDDS.data.labels = null;
        chartPontosDDS.data.datasets = [];
        chartPontosDDS.update();

        chartResultadoDDS.data.labels = null;
        chartResultadoDDS.data.datasets = [];
        chartResultadoDDS.update();
    }

</script>
@stop
