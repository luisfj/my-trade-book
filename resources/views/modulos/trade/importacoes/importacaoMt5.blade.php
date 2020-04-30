<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-success">
        ---  MetaTrader 5  ---
    </div>
</div>
<div class="row" id="infoImpMt5">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpMt5" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpMt5" class="form-control-sm" accept=".html">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoMt5"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarMt5" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
    <div class="col-sm-2 col-md-2 col-lg-2">
        <label id="qtdTransacoesImportarMt5" class="fbold text-success">0</label>
        <label class="text-warning"> de </label>
        <label id="qtdTransacoesImportarDeMt5" class="text-info fbold">0</label>
        <label class="text-warning">Depósitos/Saques</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaMt5">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveMt5" class="hidde-me spinner-border text-success"></div>
        <div id="resImportMt5" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportMt5"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportMt5"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportMt5">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-imp-mt5" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-Mt5-operacoes-tab"
            data-toggle="tab" href="#nav-import-Mt5-operacoes" role="tab"
            aria-controls="nav-import-Mt5-operacoes" aria-selected="false">Operações</a>

            <a class="text-warning nav-item nav-link" id="nav-import-Mt5-transferencias-tab"
            data-toggle="tab" href="#nav-import-Mt5-transferencias" role="tab"
            aria-controls="nav-import-Mt5-transferencias" aria-selected="true">Depósitos/Saques**</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentImMt5">
        <div class="tab-pane fade active show" id="nav-import-Mt5-operacoes" role="tabpanel" aria-labelledby="nav-import-Mt5-operacoes-tab">
            <table id="tableOperacoesMt5"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleMt5"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnMt5Formatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMt5Formatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMt5Formatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnMt5Formatter">Tempo</th>
                        <th data-field="contratos" data-footer-formatter="footerTotalDescricaoMt5"
                                data-halign="right" data-align="right">Contratos</th>
                        <th data-field="pontos" data-footer-formatter="pontosTotalMt5Formatter"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnMt5Formatter" data-footer-formatter="valorTotalMt5Formatter"
                            data-halign="right" data-align="right">Resultado</th>
                    </tr>
                  </thead>
            </table>
        </div>

        <div class="tab-pane fade" id="nav-import-Mt5-transferencias" role="tabpanel" aria-labelledby="nav-import-Mt5-transferencias-tab">
            <table id="tableTransferenciasMt5"
                data-classes="table table-hover"
                data-toggle="bootstrap-table"
                data-detail-view="true"
                data-detail-formatter="detalheTradeSimilarMt5Formatter"
                data-detail-filter="detalheTradeSimilarMt5Filter"
                data-show-footer="true"
                data-checkbox-header="true"
                data-click-to-select="false"
                data-row-style="rowStyleMt5"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="tipo" data-formatter="tipoColumnMt5Formatter">Tipo</th>
                        <th data-field="dataFormatada" data-halign="center" data-align="center">Data</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="valor" data-formatter="valorColumnMt5Formatter" data-footer-formatter="valorTotalMt5Formatter"
                            data-halign="right" data-align="right">Valor</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@section('page-script')
@parent
<script>

//alert('getSelections: ' + JSON.stringify($table.bootstrapTable('getSelections')))

    var closedTradesMt5 = [];
    var openTradesMt5 = [];
    var transferenciasMt5 = [];
    var fileName = null;

    $(document).ready(function () {
        $('#tableTransferenciasMt5').bootstrapTable({
            data: []
        });

        $('#tableTransferenciasMt5').on('check.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarMt5(getTransferenciasSelecionadasMt5().length);
        });

        $('#tableTransferenciasMt5').on('uncheck.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarMt5(getTransferenciasSelecionadasMt5().length);
        });

        $('#tableTransferenciasMt5').on('check-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarMt5(getTransferenciasSelecionadasMt5().length);
        });

        $('#tableTransferenciasMt5').on('uncheck-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarMt5(getTransferenciasSelecionadasMt5().length);
        });

        $('#tableOperacoesMt5').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    })

    function getTransferenciasSelecionadasMt5() {
        return $('#tableTransferenciasMt5').bootstrapTable('getSelections');
    }

    function atualizarQuantidadeImportarMt5(qtdOperacoes, qtdTransacoes) {
        $('#qtdTradesImportarMt5').html(qtdOperacoes);
        $('#qtdTransacoesImportarDeMt5').html(qtdTransacoes);
    }

    function atualizarQtdSelTransacoesImportarMt5(qtdTransacoes) {
        $('#qtdTransacoesImportarMt5').html(qtdTransacoes);
    }

    function carregarArquivoInitMt5() {
        $('#spinnerImpMt5').removeClass('hidde-me');
        $('#fileImpMt5').addClass('hidde-me');
        $('#btnSalvarArquivoMt5').addClass('hidde-me');
    }

    function carregarArquivoConcluidoMt5() {
        $('#spinnerImpMt5').addClass('hidde-me');
        $('#fileImpMt5').removeClass('hidde-me');
        $('#btnSalvarArquivoMt5').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasMt5() {
        $('#fileImpMt5').val('');

        $('#infoImpMt5').removeClass('hidde-me');
        $('#infoImpConcluidaMt5').addClass('hidde-me');
        $('#spinnerImpSaveMt5').removeClass('hidde-me');
        $('#resImportMt5').addClass('hidde-me');
        $('#btnNovoImportMt5').addClass('hidde-me');
        $('#btnCorrigirImportMt5').addClass('hidde-me');

        $('#spinnerImpMt5').addClass('hidde-me');
        $('#btnSalvarArquivoMt5').addClass('hidde-me');

        closedTradesMt5 = [];
        openTradesMt5 = [];
        transferenciasMt5 = [];

        $('#tableTransferenciasMt5').bootstrapTable('refreshOptions', {
            data: transferenciasMt5
        });

        $('#tableOperacoesMt5').bootstrapTable('refreshOptions', {
            data: []
        });

        atualizarQuantidadeImportarMt5(0, 0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoMt5').on('click', function () {

        $('#infoImpMt5').addClass('hidde-me');
        $('#infoImpConcluidaMt5').removeClass('hidde-me');
        $('#spinnerImpSaveMt5').removeClass('hidde-me');
        $('#resImportMt5').addClass('hidde-me');
        $('#btnNovoImportMt5').addClass('hidde-me');
        $('#btnCorrigirImportMt5').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {   conta_id :      getContaSelecionada().id,
                    transferencias: getTransferenciasSelecionadasMt5(),
                    openTrades:     openTradesMt5,
                    closedTrades:   closedTradesMt5,
                    arquivo:        fileName,
                    primeiraData : buscarPrimeiraData(closedTradesMt5),
                    ultimaData : buscarUltimaData(closedTradesMt5),
                    numeroOperacoes: (openTradesMt5.length + closedTradesMt5.length),
                    numeroTransferencias: getTransferenciasSelecionadasMt5().length,
                    valorOperacoes: calcularTotalResultado(closedTradesMt5),
                    valorTransferencias: calcularTotalValor(getTransferenciasSelecionadasMt5())
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){
                    $('#spinnerImpSaveMt5').addClass('hidde-me');
                    $('#resImportMt5').removeClass('hidde-me');
                    $('#resImportMt5').removeClass('text-success');
                    $('#resImportMt5').addClass('text-danger');
                    $('#resImportMt5').html( data.error );
                    $('#btnNovoImportMt5').addClass('hidde-me');
                    $('#btnCorrigirImportMt5').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveMt5').addClass('hidde-me');
                    $('#resImportMt5').removeClass('hidde-me');
                    $('#resImportMt5').addClass('text-success');
                    $('#resImportMt5').removeClass('text-danger');
                    $('#resImportMt5').html( data.success );
                    $('#btnNovoImportMt5').removeClass('hidde-me');
                    $('#btnCorrigirImportMt5').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveMt5').addClass('hidde-me');
            $('#resImportMt5').removeClass('hidde-me');
            $('#resImportMt5').removeClass('text-success');
            $('#resImportMt5').addClass('text-danger');
            $('#resImportMt5').html( error );
            $('#btnNovoImportMt5').removeClass('hidde-me');
            $('#btnCorrigirImportMt5').addClass('hidde-me');
        });
    });

    $('#btnNovoImportMt5').on('click', function () {
        reiniciarPreferenciasMt5();
    });

    $('#btnCorrigirImportMt5').on('click', function () {
        $('#infoImpMt5').removeClass('hidde-me');
        $('#infoImpConcluidaMt5').addClass('hidde-me');
    });

    function validarDadosNoSistemaMt5() {

        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    transferencias: transferenciasMt5,
                    openTrades:     openTradesMt5,
                    closedTrades:   closedTradesMt5
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {

                $('#tableTransferenciasMt5').bootstrapTable('refreshOptions', {
                    data: data.transferencias
                });
                $('#tableTransferenciasMt5').bootstrapTable('expandAllRows');

                let opers = [];

                openTradesMt5 = data.tradesAbertos ?? [];
                closedTradesMt5 = data.tradesFechados ?? [];

                 $.merge(opers, data.tradesAbertos);
                 $.merge(opers, data.tradesFechados);

                $('#tableOperacoesMt5').bootstrapTable('refreshOptions', {
                    data: opers
                });

                atualizarQuantidadeImportarMt5(opers.length, data.transferencias.length);
                carregarArquivoConcluidoMt5();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluidoMt5();
        });
    }

    function valorTotalMt5Formatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function pontosTotalMt5Formatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0);
    }

    function footerTotalDescricaoMt5(data) {
        return "TOTAL:"
    }

    function valorColumnMt5Formatter(valor, row){
        return '<div class="' + (row.tipo == 'D' ? 'text-success' : 'text-warning') + '">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoColumnMt5Formatter(tipo, row){
        return tipo == 'D' ? '<div class="text-success"><i class="material-icons md-18">save_alt</i> Depósito</div>'
                : '<div class="text-warning"><i class="material-icons md-18">reply_all</i> Saque</div>';
    }

    function valorTradeColumnMt5Formatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnMt5Formatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }

    function detalheTradeSimilarMt5Formatter(index, row, element) {
        $(element).addClass('table-secondary').addClass('noPadding-tlr').addClass('padb-5');
        if(!row.similar)
            return null;
        var table = '<table class="table table-sm " style="margin-left: 4%; width: 95%;">'+
                        '<thead class="hidde-me">'+
                            '<tr>   '+
                                '<th style="width: 70px;"></th>'+
                                '<th>Tipo</th>'+
                                '<th>Data</th>'+
                                '<th>Ticket</th>'+
                                '<th>Código</th>'+
                                '<th class="text-right">Valor</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            '<tr> <td class="text-info">SIMILAR</td>'+
                                '<td>'+(row.similar.tipo == 'D' ?
                                        '<div class="text-success"><i class="material-icons md-18">save_alt</i> Depósito</div>'
                                        : '<div class="text-warning"><i class="material-icons md-18">reply_all</i> Saque</div>')+'</td>'+
                                '<td>'+formatarDataHora(row.similar.data)+'</td>'+
                                '<td>'+(row.similar.ticket ?? '')+'</td>'+
                                '<td>'+(row.similar.codigo_transacao ?? '')+'</td>'+
                                '<td class="text-right ' + (row.tipo == 'D' ? 'text-success' : 'text-warning') + '">'+formatarValor(row.similar.valor, row.conta)+'</td>'+
                            '</tr>'+
                        '</tbody>'+
                    '</table>';

        return table;
    }

    function detalheTradeSimilarMt5Filter(index, row) {
        return row.similar ? true : false;
    }

    function rowStyleMt5(row, index) {
        if (row.similar) {
            return {
                classes: 'table-secondary'
            }
        }
        return {};
    }

    function rowTradeStyleMt5(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }

    }

    function tempoTradeColumnMt5Formatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    function dataComSegundosColumnMt5Formatter(data, row) {
        return formatarDataHoraSegundos(data);
    }

    $('#fileImpMt5').change(function(e){
        carregarArquivoInitMt5();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluidoMt5();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoMt5);
    });

    function lerRetornoImportacaoMt5(str){
        try{
            closedTradesMt5 = [];
            openTradesMt5 = [];
            transferenciasMt5 = [];

            var html = $.parseHTML( str );
            var corretora = importarArquivoMt5(html, closedTradesMt5, openTradesMt5, transferenciasMt5)

            validarDadosNoSistemaMt5();
            /*console.log(closedTradesMt5);
            console.log(openTradesMt5);
            console.log(transferenciasMt5);
*/
        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluidoMt5();
        }
    }

    function importarArquivoMt5(html, closedTrades, openTrades, transferencias) {
        var corretora_div = $(html).find('div b');
        var corretora = $(corretora_div).text();

        var posicao = '';
        var tipo = '';

        $(html).find('tr').each (function( rowIndex, tr) {
            var tds = $(tr).find('td');
            var ths = $(tr).find('th');

            if(tipo == ''){
                tipo = $(tds[0]).find('div b').text();
                if(tipo.includes('Relatório da Conta de Negociação')){
                    throw new Error('ATENÇÂO: gere o relatório na aba histórico do MT5 para ser possível importar!');
                }
                return;
            }

            if(ths.length == 1){//titulo
                posicao = $(ths[0]).find('div b').text();
                return;
            }

            if(ths.length == 2){//cabecalho
                return;
            }

            if(tds.length == 0 && ths.length == 0){
                return;
            }

            var td_abertura     = tds[0],
                td_ticket       = tds[1],
                td_instrumento  = tds[2],
                td_tipo         = tds[3],
                td_contratos_aber = tds[4],
                td_contratos    = tds[5],
                td_entrada      = tds[6],
                td_fechamento   = tds[9],
                td_saida        = tds[10],
                td_comissao     = tds[11],
                td_swap         = tds[12],
                td_resultado    = tds[13];

            var val_ticket       = $(td_ticket).text(),
                val_abertura     = $(td_abertura).text(),
                val_tipo         = $(td_tipo).text(),
                val_contratos_aber    = $(td_contratos_aber).text(),
                val_contratos    = $(td_contratos).text(),
                val_instrumento  = $(td_instrumento).text(),
                val_entrada      = $(td_entrada).text(),
                val_fechamento   = $(td_fechamento).text(),
                val_saida        = $(td_saida).text(),
                val_comissao     = $(td_comissao).text(),
                val_swap         = $(td_swap).text(),
                val_resultado    = $(td_resultado).text();


            if(posicao == 'Ofertas') { //são depositos e saques
                if(val_tipo == 'balance' || val_tipo == 'credit'){
                    var trans = createTransferencia(val_ticket, val_abertura, val_swap, val_saida);
                    transferencias.push(trans);
                }
            } else if(posicao == 'Posições'){//operações fechadas
                if(val_tipo == 'buy' || val_tipo == 'sell'){
                    var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                        val_entrada, val_fechamento, val_saida, val_comissao, null, val_swap, val_resultado);
                    closedTrades.push(trade);
                }
            }/* else if(posicao == 'Posições Abertas'){//operações abertas
                if(val_tipo == 'buy' || val_tipo == 'sell'){
                    var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos_aber, val_instrumento,
                        val_contratos, null, null, null, null, null, null);
                    openTrades.push(trade);
                }
            }*/
        });
        return corretora;
    };
</script>
@stop
