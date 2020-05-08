<nav>
    <div class="nav nav-tabs fs12" id="nav-tab-evo" role="tablist">
        <a class="nav-item nav-link" id="nav-filter-tab-evo" data-toggle="tab" href="#nav-filter-evo" role="tab" aria-controls="nav-filter-evo" aria-selected="false">Filtros</a>
        <a class="nav-item nav-link active" id="nav-dados-tab-evo" data-toggle="tab" href="#nav-dados-evo" role="tab" aria-controls="nav-dados-evo" aria-selected="true">Dados</a>
        <div class="fs12 label-filtros-dash-md5">
                   <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
            <label>Corretoras: [<b><i id="iFiltroCorretorasEvo">Todas</i></b>]  Ativos: [<b><i id="iFiltroInstrumentosEvo">XAUUSD</i></b>]  Período: [<b><i id="iFiltroPeriodoEvo">Fev-2020</i></b>]</label>
        </div>
    </div>
</nav>

<div class="tab-content" id="nav-tabContentEvo">
    <div class="tab-pane fade" id="nav-filter-evo" role="tabpanel" aria-labelledby="nav-filter-evo-tab">
        <form id="formFiltroEvolucaoAnual" action="POST" target="{{route('dash.evolucao.anual.do.saldo')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                    <div class="form-group">
                        <div class="input-group input-group-sm"  >
                            <div class="input-group-prepend">
                                <div class="input-group-text">Corretoras</div>
                            </div>
                            <select id="evoCorretoraSelecionada" name="evoCorretoraSelecionada[]"
                                style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm mb3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Ativo</div>
                            </div>
                            <select id="evoAtivoSelecionado" name="evoAtivoSelecionado[]" style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Ano</div>
                            </div>
                            <select id="evoAnoSelecionado" name="evoAnoSelecionado[]" style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="confirmarEvoSaldoBtn" class="btn btn-secondary btn-sm"
                            data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane fade show active" id="nav-dados-evo" role="tabpanel" aria-labelledby="nav-dados-tab-evo">
        <div id="spinnerEvo" class="spinner-border text-success"></div>
        <canvas id="chartEvolucaoSaldoAnual" height="100px" width="200px" class="hidde-me"></canvas>
    </div>
</div>

@section('page-script')
@parent
<script>

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraEvoAnual);

    function atualizouCorretoraEvoAnual(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#evoCorretoraSelecionada').val(selectionCorr);
        $('#evoCorretoraSelecionada').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosEvoAnual();
    }

    function atualizarListaMultiEvo() {
        $('#evoCorretoraSelecionada').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
        $('#evoAtivoSelecionado').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
        $('#evoAnoSelecionado').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var labelEvo = [];
    var dataValEvo = [];
    var operacoesEvo;
    var operacoesEvoFiltradas;
    var chartEvolucaoSaldoAnual = null;
    var ctxEvoSaldoAnual = $('#chartEvolucaoSaldoAnual');

    var urlEvo = $('#formFiltroEvolucaoAnual').attr('target'); //'http://localhost:8000/dashTradeATrade';

    registrarQueroMesesOperados(atualizarMesesEvoAnualDoSaldo);

    function atualizarMesesEvoAnualDoSaldo(data) {
        $('#evoAtivoSelecionado')
                .find('option')
                .remove()
                .end();
        $('#evoCorretoraSelecionada')
                .find('option')
                .remove()
                .end();
        $('#evoAnoSelecionado')
                .find('option')
                .remove()
                .end();


        /* inicio Anos Operados */
        var anosSel = [];
        $.each(data.anosOperados, function(indice, ano){
            anosSel[indice] = ano.ano;
            $('#evoAnoSelecionado').append($('<option>', {
                        value: ano.ano,
                        text : ano.ano
                    }));
        });
        $('#evoAnoSelecionado').val(anosSel);
        /* fim Anos Operados */

        /* inicio Ativos */
        var ativSel = [];
        $.each(data.ativosOperados, function(indice, ativo){
            ativSel[indice] = ativo.instrumento_id;
            $('#evoAtivoSelecionado').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
        });

        $('#evoAtivoSelecionado').val(ativSel);
        /* fim Ativos */

        /* inicio Corretoras */
        var selectionCorr = [];
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            $('#evoCorretoraSelecionada').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 5) + ' (' + conta.identificador + ')'
                    }));
        });
        //$('#evoCorretoraSelecionada').val(selectionCorr);

        atualizarListaMultiEvo();
        /* fim Corretoras */
    }

//ao alterar um mes deve buscar os dados
    function alterouFiltroEvo() {
        $('#confirmarEvoSaldoBtn').tooltip('show');
        $('#confirmarEvoSaldoBtn').removeClass('btn-secondary');
        $('#confirmarEvoSaldoBtn').addClass('btn-warning');
    }

    $('#evoAnoSelecionado').change(function(){
        alterouFiltroEvo();
    });
    $('#evoAtivoSelecionado').change(function(){
        alterouFiltroEvo();
    });
    $('#evoCorretoraSelecionada').change(function(){
        alterouFiltroEvo();
    });

    $('#confirmarEvoSaldoBtn').on('click', function () {
        buscarDadosEvoAnual();
    });

    function buscarDadosEvoAnual() {
        $('#nav-dados-tab-evo').click();
        operacoesEvo = null;
        $.post( urlEvo, $('#formFiltroEvolucaoAnual').serialize(), function(data) {
            operacoesEvo = data.operacoes;
            atualizarDadosEvo();
        },
        'json' // I expect a JSON response
        );

        $('#confirmarEvoSaldoBtn').tooltip('hide');
        $('#confirmarEvoSaldoBtn').removeClass('btn-warning');
        $('#confirmarEvoSaldoBtn').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltrosEvo(){
        var corretorasSelec = $('#evoCorretoraSelecionada').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#evoCorretoraSelecionada').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasEvo').html(corrSelDs);

        var atvSel = $('#evoAtivoSelecionado').bsMultiSelect()[0].selectedOptions;
        var atvSelDs = '';
        if(atvSel.length == $('#evoAtivoSelecionado').bsMultiSelect()[0].length){
            atvSelDs = 'Todos';
        } else {
            $.each(atvSel, function(indice, opt){
                atvSelDs += (atvSelDs.length > 0 ? ', ' : '') + opt.text.substring(0,6);
            });
        }
        $('#iFiltroInstrumentosEvo').html(atvSelDs);

        var anoSel = $('#evoAnoSelecionado').bsMultiSelect()[0].selectedOptions;
        var anoSelDs = '';
        if(anoSel.length == $('#evoAnoSelecionado').bsMultiSelect()[0].length){
            anoSelDs = 'Todos';
        } else {
            $.each(anoSel, function(indice, opt){
                anoSelDs += (anoSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroPeriodoEvo').html(anoSelDs);
    }

//busca atualiza e renderiza o resultado
    function atualizarDadosEvo() {
        atualizarDescricaoFiltrosEvo();
        $('#spinnerEvo').removeClass('hidde-me');
        ctxEvoSaldoAnual.addClass('hidde-me');

        labelEvo = [];
        dataValEvo = [];

        atualizaDadosFiltrarEvo();
    }

    function atualizaDadosFiltrarEvo() {
        $.each(operacoesEvo, function(id, res) {
            labelEvo[id] = (res.mes ?? 0);
            dataValEvo[id] = (res.saldo_atual ?? 0);
        });

        atualizaGraficoEvo();

        $('#spinnerEvo').addClass('hidde-me');
        ctxEvoSaldoAnual.removeClass('hidde-me');
    }

    function atualizaGraficoEvo() {
        if(chartEvolucaoSaldoAnual == null){

            chartEvolucaoSaldoAnual = new Chart(ctxEvoSaldoAnual, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'], //labelEvo,//['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: ' Resultado',
                        data: []
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Evolução Anual do Saldo'
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
                                labelString: 'Mês'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Valor'
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        //position: 'nearest',
                        intersect: false,
                        callbacks: {
                            labelTextColor: function(tooltipItem, chart) {
                                var res = Math.round(tooltipItem.yLabel * 100) / 100;
                                return res < 0 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(75, 192, 192, 0.7)';//'#543453';
                            },
                            footer: function(tooltipItems, data) {
                                var resMes = 0;
                                var mesDes = '';

                                tooltipItems.forEach(function(tooltipItem) {
                                    var ano = parseInt(data.datasets[tooltipItem.datasetIndex].label);
                                    var mes = tooltipItem.index + 1;
                                    mesDes  = tooltipItem.label;
                                    var op  = operacoesEvo ? operacoesEvo.filter(oper => oper.ano == ano && oper.mes == mes) : null;
                                    resMes += (op ? parseFloat(op[0].resultado) : 0);
                                });

                                return 'Res. ' + mesDes + ': ' + resMes;
                            },
                        },

                    }
                }
            });
        }
        removeDataDoGraficoEvo();
        addDataEvo();
    }

    window.onload = function() {
        window.chartColors = {
            orange: 'rgb(255, 159, 64)',
            green: 'rgb(75, 192, 192)',
            red: 'rgb(255, 99, 132)',
            blue: 'rgb(54, 162, 235)',
            yellow: 'rgb(255, 205, 86)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };
    }

	function addDataset(operacoesEvoFiltradas) {
        var colorNames = Object.keys(window.chartColors);
        //var colorName = colorNames[(chartEvolucaoSaldoAnual.data.datasets.length) % colorNames.length];
        var colorName = colorNames[chartEvolucaoSaldoAnual.data.datasets.length];
        var newColor = window.chartColors[colorName];
        var newDataset = {
            label: operacoesEvoFiltradas && operacoesEvoFiltradas.length > 0 ? ' ' + operacoesEvoFiltradas[0].ano : 'Sem Dados',
            backgroundColor: newColor,
            borderColor: newColor,
            data: [],
            fill: false
        };
        for (let index = 1; index < 13; index++) {
            var element = operacoesEvoFiltradas.filter(oper => oper.mes == index);
            if(element && element.length > 0){
                newDataset.data.push(element[0].saldo_atual);
            } else {
                newDataset.data.push(null);
            }
        }
        chartEvolucaoSaldoAnual.data.datasets.push(newDataset);
        chartEvolucaoSaldoAnual.update();
    }

    function addDataEvo() {
        var anos = $('#evoAnoSelecionado').val();
        if(anos){
            anos.forEach(ano => {
                addDataset(operacoesEvo.filter(oper => oper.ano == ano));
            });
        }
    }

    function removeDataDoGraficoEvo() {
        //chartEvolucaoSaldoAnual.data.labels = null;
        /*chartEvolucaoSaldoAnual.data.datasets.forEach((dataset) => {
            dataset.data = null;
        });*/
        chartEvolucaoSaldoAnual.data.datasets = [];
        chartEvolucaoSaldoAnual.update();
    }

</script>
@stop
