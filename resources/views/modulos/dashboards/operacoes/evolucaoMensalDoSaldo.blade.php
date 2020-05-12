<nav>
    <div class="nav nav-tabs fs12" id="nav-tab-evo-mes" role="tablist">
        <a class="nav-item nav-link" id="nav-filter-tab-evo-mes" data-toggle="tab" href="#nav-filter-evo-mes" role="tab" aria-controls="nav-filter-evo-mes" aria-selected="false">Filtros</a>
        <a class="nav-item nav-link active" id="nav-dados-tab-evo-mes" data-toggle="tab" href="#nav-dados-evo-mes" role="tab" aria-controls="nav-dados-evo-mes" aria-selected="true">Dados</a>
        <div class="fs12 label-filtros-dash-md5">
                   <!--filtros aqui teste com um texto bem grande pra garantir o que vai ser exibido se vai mostrar corretamente-->
            <label>Corretoras: [<b><i id="iFiltroCorretorasEvoMes">Todas</i></b>]  Ativos: [<b><i id="iFiltroInstrumentosEvoMes">XAUUSD</i></b>]  Período: [<b><i id="iFiltroPeriodoEvoMes">Fev-2020</i></b>]</label>
        </div>
    </div>
</nav>

<div class="tab-content" id="nav-tabContentEvoMes">
    <div class="tab-pane fade" id="nav-filter-evo-mes" role="tabpanel" aria-labelledby="nav-filter-evo-mes-tab">
        <form id="formFiltroEvoMes" action="POST" target="{{route('dash.evolucao.mensal.do.saldo')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mb-3" style="padding-top:15px;">
                    <div class="form-group">
                        <div class="input-group input-group-sm"  >
                            <div class="input-group-prepend">
                                <div class="input-group-text">Corretoras</div>
                            </div>
                            <select id="corretorasSelecionadasEvoMes" name="corretorasSelecionadasEvoMes[]"
                                style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm mb3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Ativo</div>
                            </div>
                            <select id="ativoSelecionadoEvoMes" name="ativoSelecionadoEvoMes[]" style="display: none;" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm ">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Mês</div>
                            </div>
                            <select id="mesSelecionadoEvoMes" name="mesSelecionadoEvoMes" class="custom-select">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="confirmarFiltrosBtnEvoMes" class="btn btn-secondary btn-sm"
                            data-toggle="tooltip" data-trigger="manual" title="Filtros alterados, atualize a tela">Atualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane fade show active" id="nav-dados-evo-mes" role="tabpanel" aria-labelledby="nav-dados-tab-evo-mes">
        <div id="spinnerEvoMes" class="spinner-border text-success"></div>
        <canvas id="chartEvoMes" height="250px" width="500px" class="hidde-me"></canvas>
    </div>
</div>

@section('page-script')
@parent
<script>
    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretoraEvoMe);

    function atualizouCorretoraEvoMe(corretora){
        var selectionCorr = [];
        selectionCorr[0] = corretora.id;
        $('#corretorasSelecionadasEvoMes').val(selectionCorr);
        $('#corretorasSelecionadasEvoMes').bsMultiSelect("UpdateOptionsSelected");
        buscarDadosEvoMensalSaldo();
    }

    function atualizarListaMultiEvoMes() {
        $('#corretorasSelecionadasEvoMes').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });

        $('#ativoSelecionadoEvoMes').bsMultiSelect({
            useCssPatch: true,
            css: {
                choices:'dropdown-menu',
                choice_hover:'hover',
                choice_selected:'text-warning',
            }
        });
    }

    var labelsEvoMes = [];
    var dataValEvoMes = [];
    var operacoesEvoMes;
    var chartEvoMes = null;
    var ctxEvoMes = $('#chartEvoMes');

    var urlEvoMes = $('#formFiltroEvoMes').attr('target');

//buscar e atualizar a lista de meses operados

    registrarQueroMesesOperados(atualizarMesesEvoMesSaldo);

    function atualizarMesesEvoMesSaldo(data){

        $('#ativoSelecionadoEvoMes')
                .find('option')
                .remove()
                .end();
        $('#corretorasSelecionadasEvoMes')
                .find('option')
                .remove()
                .end();
        $('#mesSelecionadoEvoMes')
                .find('option')
                .remove()
                .end();

        var now = new Date();
        var mesHoje = (now.getMonth()+1);
        var anoHoje = now.getFullYear();
        var mesAnoHohe = mesHoje + '-' + anoHoje;
        var essemestem = false;

/* inicio Meses Operados */
        $.each(data.mesesOperados, function(indice, mes){
            if(mes.mes_ano == mesAnoHohe)
                essemestem = true;

            $('#mesSelecionadoEvoMes').append($('<option>', {
                        value: mes.mes_ano,
                        text : converteMesNumParaString(mes.mes) + "-" + mes.ano
                    }));
        });
        if(!essemestem){
            $('#mesSelecionadoEvoMes').prepend($('<option>', {
                        value: mesAnoHohe,
                        text : converteMesNumParaString(mesHoje) + "-" + anoHoje
                    }));
        }
        $('#mesSelecionadoEvoMes').val(mesAnoHohe);
        /* fim Meses Operados */

        /* inicio Ativos */
        var atvSel = [];
        $.each(data.ativosOperados, function(indice, ativo){
            atvSel[indice] = ativo.instrumento_id;
            $('#ativoSelecionadoEvoMes').append($('<option>', {
                        value: ativo.instrumento_id,
                        text : ativo.instrumento.sigla
                    }));
        });
        $('#ativoSelecionadoEvoMes').val(atvSel);
        /* fim Ativos */

        /* inicio Corretoras */
        var selectionCorr = [];
        $.each(data.corretorasOperadas, function(indice, conta){
            selectionCorr[indice] = conta.id;
            $('#corretorasSelecionadasEvoMes').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substr(0, 15) + ' (' + conta.identificador + ')'
                    }));
        });
        //$('#corretorasSelecionadasEvoMes').val(selectionCorr);

        atualizarListaMultiEvoMes();
        /* fim Corretoras */

        //atualizarDadosEvoMes();
    }

//ao alterar um mes deve buscar os dados
    function alterouFiltroEvoMes() {
        $('#confirmarFiltrosBtnEvoMes').tooltip('show');
        $('#confirmarFiltrosBtnEvoMes').removeClass('btn-secondary');
        $('#confirmarFiltrosBtnEvoMes').addClass('btn-warning');
    }

    $('#mesSelecionadoEvoMes').change(function(){
        alterouFiltroEvoMes();
    });
    $('#ativoSelecionadoEvoMes').change(function(){
        alterouFiltroEvoMes();
    });
    $('#corretorasSelecionadasEvoMes').change(function(){
        alterouFiltroEvoMes();
    });

    $('#confirmarFiltrosBtnEvoMes').on('click', function () {
        buscarDadosEvoMensalSaldo();
    });

    function buscarDadosEvoMensalSaldo(){
        $('#nav-dados-tab-evo-mes').click();
        atualizarDadosEvoMes();
        $('#confirmarFiltrosBtnEvoMes').tooltip('hide');
        $('#confirmarFiltrosBtnEvoMes').removeClass('btn-warning');
        $('#confirmarFiltrosBtnEvoMes').addClass('btn-secondary');
    }

    function atualizarDescricaoFiltrosEvoMes(){
        var corretorasSelec = $('#corretorasSelecionadasEvoMes').bsMultiSelect()[0].selectedOptions;
        var corrSelDs = '';
        if(corretorasSelec.length == $('#corretorasSelecionadasEvoMes').bsMultiSelect()[0].length){
            corrSelDs = 'Todas';
        } else {
            $.each(corretorasSelec, function(indice, opt){
                corrSelDs += (corrSelDs.length > 0 ? ', ' : '') + opt.text;
            });
        }

        $('#iFiltroCorretorasEvoMes').html(corrSelDs);

        var atvSel = $('#ativoSelecionadoEvoMes').bsMultiSelect()[0].selectedOptions;
        var atvSelDs = '';
        if(atvSel.length == $('#ativoSelecionadoEvoMes').bsMultiSelect()[0].length){
            atvSelDs = 'Todos';
        } else {
            $.each(atvSel, function(indice, opt){
                atvSelDs += (atvSelDs.length > 0 ? ', ' : '') + opt.text.substring(0,6);
            });
        }
        $('#iFiltroInstrumentosEvoMes').html(atvSelDs);

        var mesSel = $('#mesSelecionadoEvoMes option:selected').text();
        $('#iFiltroPeriodoEvoMes').html(mesSel);
    }

//busca atualiza e renderiza o resultado
    function atualizarDadosEvoMes() {
        atualizarDescricaoFiltrosEvoMes();

        $('#spinnerEvoMes').removeClass('hidde-me');
        ctxEvoMes.addClass('hidde-me');

        labelsEvoMes = [];
        dataValEvoMes = [];
        operacoesEvoMes = null;
        $.post( urlEvoMes, $('#formFiltroEvoMes').serialize(), function(data) {
            operacoesEvoMes = data.operacoes;
            atualizaDadosFiltrarEvoMes();
        },
        'json' // I expect a JSON response
        );

    }

    function atualizaDadosFiltrarEvoMes() {
        var ativoSel = $('#ativoSelecionadoEvoMes').val();
        var corretorasSels = $('#corretorasSelecionadasEvoMes').val();

        if(operacoesEvoMes != null){
            $.each(operacoesEvoMes, function(id, res) {
                labelsEvoMes[id] = (res.dia);
                dataValEvoMes[id] = (res.resultado ?? 0);
            });
        }
        atualizaGraficoEvoMes();
        $('#spinnerEvoMes').addClass('hidde-me');
        ctxEvoMes.removeClass('hidde-me');
    }

    function atualizaGraficoEvoMes() {
        if(chartEvoMes == null){

            chartEvoMes = new Chart(ctxEvoMes, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: ' Resultado',
                        data: [],
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Evolução Mensal do Saldo'
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
                                labelString: 'Dia'
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
                        },

                    }
                }
            });
        }
        removeDataDoGraficoEvoMes();
        addDataEvoMes();
    }

    function addDatasetEvoMes(operacoesEvoFiltradas, labelDataSet) {
            //var colorNames = Object.keys(window.chartColors);
			//var colorName = colorNames[(chartEvolucaoSaldoAnual.data.datasets.length) % colorNames.length];
            //var colorName = colorNames[chartEvolucaoSaldoAnual.data.datasets.length];
			var newColor = 'rgb(255, 159, 64)';
			var newDataset = {
                type: labelDataSet && labelDataSet == 'Res. Diário' ? 'bar' : 'line',
				label: labelDataSet ? ' ' + labelDataSet : 'Sem Tipo Definido',
				//backgroundColor: newColor,
				//borderColor: newColor,
				data: [],
				fill: false,
                backgroundColor: function(context) {
                    if(labelDataSet && labelDataSet != 'Res. Diário'){
                        return newColor;
                    }
                    var index = context.dataIndex;
                    var value = context.dataset.data[index];
                    return value < 0 ? 'rgba(255, 99, 132, 0.4)' : 'rgba(75, 192, 192, 0.4)';
                },
                borderColor: function(context) {
                    if(labelDataSet && labelDataSet != 'Res. Diário'){
                        return newColor;
                    }
                    var index = context.dataIndex;
                    var value = context.dataset.data[index];
                    return value < 0 ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)';
                },
                borderWidth: 1
			};
            operacoesEvoFiltradas.sort(function(a, b){
                return (b.dia < a.dia ? 1 : -1);
            });
            operacoesEvoFiltradas.forEach(element => {
                if(element){
                    if(labelDataSet && labelDataSet == 'Res. Diário'){
                        newDataset.data.push(element.resultado);
                    } else {
                        newDataset.data.push(element.saldo_atual);
                    }
                } else {
                    newDataset.data.push(null);
                }
            });

			chartEvoMes.data.datasets.push(newDataset);
			chartEvoMes.update();
    }

    function addDataEvoMes() {
        chartEvoMes.data.labels = labelsEvoMes;

        addDatasetEvoMes(operacoesEvoMes, 'Res. Diário');
        addDatasetEvoMes(operacoesEvoMes, 'Evol. Saldo');

        chartEvoMes.update();
    }

    function removeDataDoGraficoEvoMes() {
        chartEvoMes.data.labels = null;
        chartEvoMes.data.datasets = [];
        chartEvoMes.update();
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
