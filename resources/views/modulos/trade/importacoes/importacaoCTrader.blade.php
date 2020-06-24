<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-info">
        ---  cTrader  ---
    </div>
</div>
<div class="row" id="infoImpCtrader">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpCtrader" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpCtrader" class="form-control-sm" accept=".htm">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoCtrader"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarCtrader" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
    <div class="col-sm-2 col-md-2 col-lg-2">
        <label id="qtdTransacoesImportarCtrader" class="fbold text-success">0</label>
        <label class="text-warning"> de </label>
        <label id="qtdTransacoesImportarDeCtrader" class="text-info fbold">0</label>
        <label class="text-warning">Depósitos/Saques</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaCtrader">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveCtrader" class="hidde-me spinner-border text-success"></div>
        <div id="resImportCtrader" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportCtrader"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportCtrader"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportCtrader">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-imp-Ctrader" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-Ctrader-operacoes-tab"
            data-toggle="tab" href="#nav-import-Ctrader-operacoes" role="tab"
            aria-controls="nav-import-Ctrader-operacoes" aria-selected="false">Operações</a>

            <a class="text-warning nav-item nav-link" id="nav-import-Ctrader-transferencias-tab"
            data-toggle="tab" href="#nav-import-Ctrader-transferencias" role="tab"
            aria-controls="nav-import-Ctrader-transferencias" aria-selected="true">Depósitos/Saques**</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentImCtrader">
        <div class="tab-pane fade active show" id="nav-import-Ctrader-operacoes" role="tabpanel" aria-labelledby="nav-import-Ctrader-operacoes-tab">
            <table id="tableOperacoesCtrader"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleCtrader"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnCtraderFormatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnCtraderFormatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnCtraderFormatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnCtraderFormatter">Tempo</th>
                        <th data-field="contratos" data-halign="right" data-align="right">Contratos</th>
                        <th data-field="estrategia" data-formatter="estrategiaColumnFormatter" data-events="estrategiaInputEvents">Estratégia</th>
                        <th data-field="pontos" data-footer-formatter="footerTotalDescricaoCtrader"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnCtraderFormatter" data-footer-formatter="valorTotalCtraderFormatter"
                            data-halign="right" data-align="right">Resultado</th>
                    </tr>
                  </thead>
            </table>
        </div>

        <div class="tab-pane fade" id="nav-import-Ctrader-transferencias" role="tabpanel" aria-labelledby="nav-import-Ctrader-transferencias-tab">
            <table id="tableTransferenciasCtrader"
                data-classes="table table-hover"
                data-toggle="bootstrap-table"
                data-detail-view="true"
                data-detail-formatter="detalheTradeSimilarCtraderFormatter"
                data-detail-filter="detalheTradeSimilarCtraderFilter"
                data-show-footer="true"
                data-checkbox-header="true"
                data-click-to-select="false"
                data-row-style="rowStyleCtrader"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="tipo" data-formatter="tipoColumnCtraderFormatter">Tipo</th>
                        <th data-field="dataFormatada" data-halign="center" data-align="center">Data</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="capExt" data-formatter="capExtColumnCTraderFormatter" data-events="capExtInputEventsCTrader">Cap. Ext.?</th>
                        <th data-field="valor" data-formatter="valorColumnCtraderFormatter" data-footer-formatter="valorTotalCtraderFormatter"
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

    var closedTradesCtrader = [];
    var transferenciasCtrader = [];
    var fileName = null;

    $(document).ready(function () {

        $('#tableTransferenciasCtrader').bootstrapTable({
            data: []
        });

        $('#tableTransferenciasCtrader').on('check.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarCtrader(getTransferenciasSelecionadasCtrader().length);
        });

        $('#tableTransferenciasCtrader').on('uncheck.bs.table', function (row, $element) {
            atualizarQtdSelTransacoesImportarCtrader(getTransferenciasSelecionadasCtrader().length);
        });

        $('#tableTransferenciasCtrader').on('check-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarCtrader(getTransferenciasSelecionadasCtrader().length);
        });

        $('#tableTransferenciasCtrader').on('uncheck-all.bs.table', function (rowsAfter, rowsBefore) {
            atualizarQtdSelTransacoesImportarCtrader(getTransferenciasSelecionadasCtrader().length);
        });

        $('#tableOperacoesCtrader').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    });

    function getTransferenciasSelecionadasCtrader() {
        return $('#tableTransferenciasCtrader').bootstrapTable('getSelections');
    }

    function atualizarQuantidadeImportarCtrader(qtdOperacoes, qtdTransacoes) {
        $('#qtdTradesImportarCtrader').html(qtdOperacoes);
        $('#qtdTransacoesImportarDeCtrader').html(qtdTransacoes);
    }

    function atualizarQtdSelTransacoesImportarCtrader(qtdTransacoes) {
        $('#qtdTransacoesImportarCtrader').html(qtdTransacoes);
    }

    function carregarArquivoInitCtrader() {
        $('#spinnerImpCtrader').removeClass('hidde-me');
        $('#fileImpCtrader').addClass('hidde-me');
        $('#btnSalvarArquivoCtrader').addClass('hidde-me');
    }

    function carregarArquivoConcluidoCtrader() {
        $('#spinnerImpCtrader').addClass('hidde-me');
        $('#fileImpCtrader').removeClass('hidde-me');
        $('#btnSalvarArquivoCtrader').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasCtrader() {
        $('#fileImpCtrader').val('');

        $('#infoImpCtrader').removeClass('hidde-me');
        $('#infoImpConcluidaCtrader').addClass('hidde-me');
        $('#spinnerImpSaveCtrader').removeClass('hidde-me');
        $('#resImportCtrader').addClass('hidde-me');
        $('#btnNovoImportCtrader').addClass('hidde-me');
        $('#btnCorrigirImportCtrader').addClass('hidde-me');

        $('#spinnerImpCtrader').addClass('hidde-me');
        $('#btnSalvarArquivoCtrader').addClass('hidde-me');

        closedTradesCtrader = [];
        transferenciasCtrader = [];

        $('#tableTransferenciasCtrader').bootstrapTable('refreshOptions', {
            data: transferenciasCtrader
        });

        $('#tableOperacoesCtrader').bootstrapTable('refreshOptions', {
            data: []
        });

        atualizarQuantidadeImportarCtrader(0, 0);
        atualizarQtdSelTransacoesImportarCtrader(0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoCtrader').on('click', function () {

        $('#infoImpCtrader').addClass('hidde-me');
        $('#infoImpConcluidaCtrader').removeClass('hidde-me');
        $('#spinnerImpSaveCtrader').removeClass('hidde-me');
        $('#resImportCtrader').addClass('hidde-me');
        $('#btnNovoImportCtrader').addClass('hidde-me');
        $('#btnCorrigirImportCtrader').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {
                    conta_id :       getContaSelecionada().id,
                    closedTrades:    closedTradesCtrader,
                    transferencias:  getTransferenciasSelecionadasCtrader(),
                    openTrades:      [],
                    arquivo:         fileName,
                    primeiraData :   buscarPrimeiraData(closedTradesCtrader),
                    ultimaData :     buscarUltimaData(closedTradesCtrader),
                    numeroOperacoes: closedTradesCtrader.length,
                    numeroTransferencias: getTransferenciasSelecionadasCtrader().length,
                    valorOperacoes: calcularTotalResultado(closedTradesCtrader),
                    valorTransferencias: calcularTotalValor(getTransferenciasSelecionadasCtrader())
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){
                    $('#spinnerImpSaveCtrader').addClass('hidde-me');
                    $('#resImportCtrader').removeClass('hidde-me');
                    $('#resImportCtrader').removeClass('text-success');
                    $('#resImportCtrader').addClass('text-danger');
                    $('#resImportCtrader').html( data.error );
                    $('#btnNovoImportCtrader').addClass('hidde-me');
                    $('#btnCorrigirImportCtrader').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveCtrader').addClass('hidde-me');
                    $('#resImportCtrader').removeClass('hidde-me');
                    $('#resImportCtrader').addClass('text-success');
                    $('#resImportCtrader').removeClass('text-danger');
                    $('#resImportCtrader').html( data.success );
                    $('#btnNovoImportCtrader').removeClass('hidde-me');
                    $('#btnCorrigirImportCtrader').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveCtrader').addClass('hidde-me');
            $('#resImportCtrader').removeClass('hidde-me');
            $('#resImportCtrader').removeClass('text-success');
            $('#resImportCtrader').addClass('text-danger');
            $('#resImportCtrader').html( error );
            $('#btnNovoImportCtrader').removeClass('hidde-me');
            $('#btnCorrigirImportCtrader').addClass('hidde-me');
        });
    });

    $('#btnNovoImportCtrader').on('click', function () {
        reiniciarPreferenciasCtrader();
    });

    $('#btnCorrigirImportCtrader').on('click', function () {
        $('#infoImpCtrader').removeClass('hidde-me');
        $('#infoImpConcluidaCtrader').addClass('hidde-me');
    });

    function validarDadosNoSistemaCtrader() {
        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    closedTrades:   closedTradesCtrader,
                    openTrades:     [],
                    transferencias: transferenciasCtrader
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {

                $('#tableTransferenciasCtrader').bootstrapTable('refreshOptions', {
                    data: data.transferencias
                });
                $('#tableTransferenciasCtrader').bootstrapTable('expandAllRows');

                closedTradesCtrader = data.tradesFechados ?? [];

                $('#tableOperacoesCtrader').bootstrapTable('refreshOptions', {
                    data: data.tradesFechados
                });

                atualizarQuantidadeImportarCtrader(closedTradesCtrader.length, data.transferencias.length);
                carregarArquivoConcluidoCtrader();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluidoCtrader();
        });
    }

    function valorTotalCtraderFormatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function footerTotalDescricaoCtrader(data) {
        return "TOTAL:"
    }

    function valorColumnCtraderFormatter(valor, row){
        return '<div class="' + (row.tipo == 'D' ? 'text-success' : 'text-warning') + '">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoColumnCtraderFormatter(tipo, row){
        return tipo == 'D' ? '<div class="text-success"><i class="material-icons md-18">save_alt</i> Depósito</div>'
                : '<div class="text-warning"><i class="material-icons md-18">reply_all</i> Saque</div>';
    }

    function detalheTradeSimilarCtraderFormatter(index, row, element) {
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

    function detalheTradeSimilarCtraderFilter(index, row) {
        return row.similar ? true : false;
    }

    function valorTradeColumnCtraderFormatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnCtraderFormatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }


    function rowTradeStyleCtrader(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }
    }

    function tempoTradeColumnCtraderFormatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    $('#fileImpCtrader').change(function(e){
        carregarArquivoInitCtrader();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluidoCtrader();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoCtrader);
    });

    function lerRetornoImportacaoCtrader(str){
        try{
            closedTradesCtrader = [];
            transferenciasCtrader = [];

            var importou = importarArquivoCtrader(str, closedTradesCtrader, transferenciasCtrader);

            validarDadosNoSistemaCtrader();

        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluidoCtrader();
        }
    }

    function importarArquivoCtrader(html, closedTrades, transferencias) {
//console.log(html)
        $.each($(html).find('table.dataTable'), function( index, table ) {
            var indexTicket,
                indexInstrumento,
                indexContratos,
                indexAbertura,
                indexFechamento,
                indexTipo,
                indexPrecoEntrada,
                indexPrecoSaida,
                indexResultado,
                indexComissao,
                indexSwap,
                indexTicketTransf,
                indexDataTransf,
                indexValorTransf;
            if($(table).find('td.title-style').html().includes('History')){//resumo de transações
                $.each($(table).find('tr'), function( indx, tr ) {
                    var val_ticket,
                        val_tipo,
                        val_contratos,
                        val_instrumento,
                        val_entrada,
                        val_abertura,
                        val_fechamento,
                        val_saida,
                        val_comissao,
                        val_swap,
                        val_resultado;
                    $.each($(tr).find('td'), function( idx, td ) {
                        if($(td).hasClass('cell-header')){
                            var label = $(td).html();
                            if(label.toLowerCase().includes('id')){
                                indexTicket = idx;
                            } else if(label.toLowerCase().includes('symbol')){
                                indexInstrumento = idx;
                            } else if(label.toLowerCase().includes('closing quantity')){
                                indexContratos = idx;
                            } else if(label.toLowerCase().includes('opening time')){//hora abertura
                                indexAbertura = idx;
                            } else if(label.toLowerCase().includes('closing time')){//hora fechamento
                                indexFechamento = idx;
                            } else if(label.toLowerCase().includes('opening direction')){//tipo compra venda buy sell
                                indexTipo = idx;
                            } else if(label.toLowerCase().includes('entry price')){//preço medio compra
                                indexPrecoEntrada = idx;
                            } else if(label.toLowerCase().includes('closing price')){//preco medio venda
                                indexPrecoSaida = idx;
                            } else if(label.toLowerCase().includes('commission')){//preco medio venda
                                indexComissao = idx;
                            } else if(label.toLowerCase().includes('swap')){//preco medio venda
                                indexSwap = idx;
                            } else if(label.toLowerCase().includes('gross')){
                                indexResultado = idx;
                            }
                        } else if($(td).hasClass('cell-text')){
                            var content = $(td).find('nobr').html();

                            if(indexTicket === idx){
                                val_ticket       = content;
                            } else if(indexInstrumento === idx){
                                val_instrumento  = content;
                            } else if(indexContratos === idx){
                                val_contratos    = content.replace(' Lots', '');
                            } else if(indexAbertura === idx){//hora abertura
                                val_abertura     = content;
                            } else if(indexFechamento === idx){//hora fechamento
                                val_fechamento   = content;
                            } else if(indexTipo === idx){//tipo compra venda buy sell
                                val_tipo         = content.toLowerCase();
                            } else if(indexPrecoEntrada === idx){//preço medio compra
                                val_entrada      = content;
                            } else if(indexPrecoSaida === idx){//preco medio venda
                                val_saida        = content;
                            } else if(indexComissao === idx){//preco medio venda
                                val_comissao     = content;
                            } else if(indexSwap === idx){//preco medio venda
                                val_swap         = content;
                            } else if(indexResultado === idx){
                                val_resultado    = content;
                            }
                        }
                    });
                    if(val_tipo === 'sell' || val_tipo === 'buy'){
                        var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                            val_entrada, val_fechamento, val_saida, val_comissao, val_swap, null, val_resultado);
                        closedTrades.push(trade);
                    }
                });
            } else if($(table).find('td.title-style').html().includes('Transactions')){//resumo de transações
                $.each($(table).find('tr'), function( indx, tr ) {
                    var val_ticket,
                        val_data,
                        val_valor;
                    $.each($(tr).find('td'), function( idx, td ) {
                        if($(td).hasClass('cell-header')){
                            var label = $(td).html();
                            if(label.toLowerCase().includes('id')){
                                indexTicketTransf = idx;
                            } else if(label.toLowerCase().includes('time')){
                                indexDataTransf = idx;
                            } else if(label.toLowerCase().includes('amount')){
                                indexValorTransf = idx;
                            }
                        } else if($(td).hasClass('cell-text')){
                            var content = $(td).find('nobr').html();

                            if(indexTicketTransf === idx){
                                val_ticket       = content;
                            } else if(indexDataTransf === idx){
                                val_data  = content;
                            } else if(indexValorTransf === idx){
                                val_valor    = content;
                            }
                        }
                    });
                    if(val_valor){
                        var trans = createTransferencia(val_ticket, val_data, null, val_valor);
                        transferencias.push(trans);
                    }
                });
            }
        });
        return true;
    };

    function dataComSegundosColumnCtraderFormatter(data, row) {
        return formatarDataHoraSegundos(data);
    }

    function capExtColumnCTraderFormatter(value){
        var checked = value ? 'checked' : ''
        return '<input name="capExt" type="checkbox" ' + checked + ' />'
    }

    window.capExtInputEventsCTrader = {
        'change :checkbox': function (e, value, row, index) {
            row.capExt = $(e.target).prop('checked');
            $('#tableTransferenciasCtrader').bootstrapTable('updateRow', {
                index: index,
                row: row
            });
        }
    }

</script>
@stop
