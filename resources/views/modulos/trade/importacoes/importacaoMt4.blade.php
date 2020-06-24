<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-info">
        ---  MetaTrader 4  ---
    </div>
</div>
<div class="row" id="infoImpMt4">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpMt4" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpMt4" class="form-control-sm" accept=".htm">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoMt4"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarMt4" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
    <div class="col-sm-2 col-md-2 col-lg-2">
        <label id="qtdTransacoesImportarMt4" class="fbold text-success">0</label>
        <label class="text-warning"> de </label>
        <label id="qtdTransacoesImportarDeMt4" class="text-info fbold">0</label>
        <label class="text-warning">Depósitos/Saques</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaMt4">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveMt4" class="hidde-me spinner-border text-success"></div>
        <div id="resImportMt4" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportMt4"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportMt4"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportMt4">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-mt4-operacoes-tab"
            data-toggle="tab" href="#nav-import-mt4-operacoes" role="tab"
            aria-controls="nav-import-mt4-operacoes" aria-selected="false">Operações</a>

            <a class="text-warning nav-item nav-link" id="nav-import-mt4-transferencias-tab"
            data-toggle="tab" href="#nav-import-mt4-transferencias" role="tab"
            aria-controls="nav-import-mt4-transferencias" aria-selected="true">Depósitos/Saques**</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentTaT">
        <div class="tab-pane fade active show" id="nav-import-mt4-operacoes" role="tabpanel" aria-labelledby="nav-import-mt4-operacoes-tab">
            <table id="tableOperacoes"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleMt4"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnMt4Formatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMt4Formatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMt4Formatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnMt4Formatter">Tempo</th>
                        <th data-field="contratos" data-footer-formatter="footerTotalDescricaoMt4"
                                data-halign="right" data-align="right">Contratos</th>
                        <th data-field="estrategia" data-formatter="estrategiaColumnFormatter" data-events="estrategiaInputEvents">Estratégia</th>
                        <th data-field="pontos" data-footer-formatter="pontosTotalMt4Formatter"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnMt4Formatter" data-footer-formatter="valorTotalMt4Formatter"
                            data-halign="right" data-align="right">Resultado</th>
                    </tr>
                  </thead>
            </table>
        </div>

        <div class="tab-pane fade" id="nav-import-mt4-transferencias" role="tabpanel" aria-labelledby="nav-import-mt4-transferencias-tab">
            <table id="tableTransferencias"
            data-classes="table table-hover"
            data-toggle="bootstrap-table"
            data-detail-view="true"
            data-detail-formatter="detalheTradeSimilarMt4Formatter"
            data-detail-filter="detalheTradeSimilarMt4Filter"
            data-show-footer="true"
            data-checkbox-header="true"
            data-click-to-select="false"
            data-row-style="rowStyleMt4"
            data-search="true">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="tipo" data-formatter="tipoColumnMt4Formatter">Tipo</th>
                    <th data-field="dataFormatada" data-halign="center" data-align="center">Data</th>
                    <th data-field="ticket">Ticket</th>
                    <th data-field="codigo">Código</th>
                    <th data-field="capExt" data-formatter="capExtColumnMt4Formatter" data-events="capExtInputEvents">Cap. Ext.?</th>
                    <th data-field="valor" data-formatter="valorColumnMt4Formatter" data-footer-formatter="valorTotalMt4Formatter"
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

    var closedTradesMt4 = [];
    var openTradesMt4 = [];
    var transferenciasMt4 = [];
    var fileName = null;

    $(document).ready(function () {
        $('#tableTransferencias').bootstrapTable({
            data: []
        });

        $('#tableTransferencias').on('check.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarMt4(getTransferenciasSelecionadasMt4().length);
        });

        $('#tableTransferencias').on('uncheck.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarMt4(getTransferenciasSelecionadasMt4().length);
        });

        $('#tableTransferencias').on('check-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarMt4(getTransferenciasSelecionadasMt4().length);
        });

        $('#tableTransferencias').on('uncheck-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarMt4(getTransferenciasSelecionadasMt4().length);
        });

        $('#tableOperacoes').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    })

    function getTransferenciasSelecionadasMt4() {
        return $('#tableTransferencias').bootstrapTable('getSelections');
    }

    function atualizarQuantidadeImportarMt4(qtdOperacoes, qtdTransacoes) {
        $('#qtdTradesImportarMt4').html(qtdOperacoes);
        $('#qtdTransacoesImportarDeMt4').html(qtdTransacoes);
    }

    function atualizarQtdSelTransacoesImportarMt4(qtdTransacoes) {
        $('#qtdTransacoesImportarMt4').html(qtdTransacoes);
    }

    function carregarArquivoInit() {
        $('#spinnerImpMt4').removeClass('hidde-me');
        $('#fileImpMt4').addClass('hidde-me');
        $('#btnSalvarArquivoMt4').addClass('hidde-me');
    }

    function carregarArquivoConcluido() {
        $('#spinnerImpMt4').addClass('hidde-me');
        $('#fileImpMt4').removeClass('hidde-me');
        $('#btnSalvarArquivoMt4').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasMt4() {
        $('#fileImpMt4').val('');

        $('#infoImpMt4').removeClass('hidde-me');
        $('#infoImpConcluidaMt4').addClass('hidde-me');
        $('#spinnerImpSaveMt4').removeClass('hidde-me');
        $('#resImportMt4').addClass('hidde-me');
        $('#btnNovoImportMt4').addClass('hidde-me');
        $('#btnCorrigirImportMt4').addClass('hidde-me');

        $('#spinnerImpMt4').addClass('hidde-me');
        $('#btnSalvarArquivoMt4').addClass('hidde-me');

        closedTradesMt4 = [];
        openTradesMt4 = [];
        transferenciasMt4 = [];

        $('#tableTransferencias').bootstrapTable('refreshOptions', {
            data: transferenciasMt4
        });

        $('#tableOperacoes').bootstrapTable('refreshOptions', {
            data: []
        });

        atualizarQuantidadeImportarMt4(0, 0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoMt4').on('click', function () {

        $('#infoImpMt4').addClass('hidde-me');
        $('#infoImpConcluidaMt4').removeClass('hidde-me');
        $('#spinnerImpSaveMt4').removeClass('hidde-me');
        $('#resImportMt4').addClass('hidde-me');
        $('#btnNovoImportMt4').addClass('hidde-me');
        $('#btnCorrigirImportMt4').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {   conta_id :      getContaSelecionada().id,
                    transferencias: getTransferenciasSelecionadasMt4(),
                    openTrades:     openTradesMt4,
                    closedTrades:   closedTradesMt4,
                    arquivo:        fileName,
                    primeiraData : buscarPrimeiraData(closedTradesMt4),
                    ultimaData : buscarUltimaData(closedTradesMt4),
                    numeroOperacoes: (openTradesMt4.length + closedTradesMt4.length),
                    numeroTransferencias: getTransferenciasSelecionadasMt4().length,
                    valorOperacoes: calcularTotalResultado(closedTradesMt4),
                    valorTransferencias: calcularTotalValor(getTransferenciasSelecionadasMt4())
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){console.log(data.error);
                    $('#spinnerImpSaveMt4').addClass('hidde-me');
                    $('#resImportMt4').removeClass('hidde-me');
                    $('#resImportMt4').removeClass('text-success');
                    $('#resImportMt4').addClass('text-danger');
                    $('#resImportMt4').html( data.error );
                    $('#btnNovoImportMt4').addClass('hidde-me');
                    $('#btnCorrigirImportMt4').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveMt4').addClass('hidde-me');
                    $('#resImportMt4').removeClass('hidde-me');
                    $('#resImportMt4').addClass('text-success');
                    $('#resImportMt4').removeClass('text-danger');
                    $('#resImportMt4').html( data.success );
                    $('#btnNovoImportMt4').removeClass('hidde-me');
                    $('#btnCorrigirImportMt4').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveMt4').addClass('hidde-me');
            $('#resImportMt4').removeClass('hidde-me');
            $('#resImportMt4').removeClass('text-success');
            $('#resImportMt4').addClass('text-danger');
            $('#resImportMt4').html( error );
            $('#btnNovoImportMt4').removeClass('hidde-me');
            $('#btnCorrigirImportMt4').addClass('hidde-me');
        });
    });

    $('#btnNovoImportMt4').on('click', function () {
        reiniciarPreferenciasMt4();
    });

    $('#btnCorrigirImportMt4').on('click', function () {
        $('#infoImpMt4').removeClass('hidde-me');
        $('#infoImpConcluidaMt4').addClass('hidde-me');
    });

    function validarDadosNoSistema() {
        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    transferencias: transferenciasMt4,
                    openTrades:     openTradesMt4,
                    closedTrades:   closedTradesMt4
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {

                $('#tableTransferencias').bootstrapTable('refreshOptions', {
                    data: data.transferencias
                });
                $('#tableTransferencias').bootstrapTable('expandAllRows');

                let opers = [];

                openTradesMt4 = data.tradesAbertos ?? [];
                closedTradesMt4 = data.tradesFechados ?? [];

                 $.merge(opers, data.tradesAbertos);
                 $.merge(opers, data.tradesFechados);

                $('#tableOperacoes').bootstrapTable('refreshOptions', {
                    data: opers
                });

                atualizarQuantidadeImportarMt4(opers.length, data.transferencias.length);
                carregarArquivoConcluido();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluido();
        });
    }

    $('#fileImpMt4').change(function(e){
        carregarArquivoInit();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluido();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoMt4);
    });

    function lerRetornoImportacaoMt4(str){
        try{
            closedTradesMt4 = [];
            openTradesMt4 = [];
            transferenciasMt4 = [];

            var html = $.parseHTML( str );
            var corretora = importarArquivoMt4(html, closedTradesMt4, openTradesMt4, transferenciasMt4)

            validarDadosNoSistema();
            /*console.log(closedTradesMt4);
            console.log(openTradesMt4);
            console.log(transferenciasMt4);
*/
        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluido();
        }
    }

    function importarArquivoMt4(html, closedTrades, openTrades, transferencias) {
        var corretora_div = $(html).find('div b');
        var corretora = $(corretora_div).text();

        var posicao = '';

        $(html).find('tr').each (function( rowIndex, tr) {
            var tds = $(tr).find('td');

            var td_ticket       = tds[0],
                td_abertura     = tds[1],
                td_tipo         = tds[2],
                td_contratos    = tds[3],
                td_instrumento  = tds[4],
                td_entrada      = tds[5],
                td_fechamento   = tds[8],
                td_saida        = tds[9],
                td_comissao     = tds[10],
                td_impostos     = tds[11],
                td_swap         = tds[12],
                td_resultado    = tds[13];

            var val_ticket       = $(td_ticket).text(),
                val_abertura     = $(td_abertura).text(),
                val_tipo         = $(td_tipo).text(),
                val_contratos    = $(td_contratos).text(),
                val_instrumento  = $(td_instrumento).text(),
                val_entrada      = $(td_entrada).text(),
                val_fechamento   = $(td_fechamento).text(),
                val_saida        = $(td_saida).text(),
                val_comissao     = $(td_comissao).text(),
                val_impostos     = $(td_impostos).text(),
                val_swap         = $(td_swap).text(),
                val_resultado    = $(td_resultado).text();

            posicao = val_ticket.includes('Account') ? 'HEAD'
                        : val_ticket.includes('Closed Transactions') ? 'CLOSED'
                        : val_ticket.includes('Open Trades') ? 'OPEN'
                        : val_ticket.includes('Working') ? 'END'
                        : posicao;

            if(posicao == 'CLOSED') { //são operações fechadas
                if(val_tipo == 'balance'){
                    var trans = createTransferencia(val_ticket, val_abertura, val_contratos, val_instrumento);
                    transferencias.push(trans);
                } else
                    if(val_tipo == 'sell' || val_tipo == 'buy'){
                        var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                                        val_entrada, val_fechamento, val_saida, val_comissao, val_impostos, val_swap, val_resultado);
                        closedTrades.push(trade);
                    }
            }/* else if(posicao == 'OPEN'){//operações abertas
                if(val_tipo == 'sell' || val_tipo == 'buy'){
                    var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                                    val_entrada, val_fechamento, val_saida, val_comissao, val_impostos, val_swap, val_resultado);
                    openTrades.push(trade);
                }
            }*/
        });
        return corretora;
    };

    function valorTotalMt4Formatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function pontosTotalMt4Formatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0);
    }

    function footerTotalDescricaoMt4(data) {
        return "TOTAL:"
    }

    function valorColumnMt4Formatter(valor, row){
        return '<div class="' + (row.tipo == 'D' ? 'text-success' : 'text-warning') + '">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoColumnMt4Formatter(tipo, row){
        return tipo == 'D' ? '<div class="text-success"><i class="material-icons md-18">save_alt</i> Depósito</div>'
                : '<div class="text-warning"><i class="material-icons md-18">reply_all</i> Saque</div>';
    }

    function capExtColumnMt4Formatter(value){
        var checked = value ? 'checked' : ''
        return '<input name="capExt" type="checkbox" ' + checked + ' />'
    }

    window.capExtInputEvents = {
        'change :checkbox': function (e, value, row, index) {
            row.capExt = $(e.target).prop('checked');
            $('#tableTransferencias').bootstrapTable('updateRow', {
                index: index,
                row: row
            });
        }
    }

    function valorTradeColumnMt4Formatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnMt4Formatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }

    function detalheTradeSimilarMt4Formatter(index, row, element) {
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

    function detalheTradeSimilarMt4Filter(index, row) {
        return row.similar ? true : false;
    }

    function rowStyleMt4(row, index) {
        if (row.similar) {
            return {
                classes: 'table-secondary'
            }
        }
        return {};
    }

    function rowTradeStyleMt4(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }

    }

    function tempoTradeColumnMt4Formatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    function dataComSegundosColumnMt4Formatter(data, row) {
        return formatarDataHoraSegundos(data);
    }
</script>
@stop
