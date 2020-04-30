<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-info">
        ---  MYFXBOOK  ---
    </div>
</div>
<div class="row" id="infoImpMyFx">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpMyFx" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpMyFx" class="form-control-sm" accept=".csv">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoMyFx"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarMyFx" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaMyFx">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveMyFx" class="hidde-me spinner-border text-success"></div>
        <div id="resImportMyFx" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportMyFx"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportMyFx"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportMyFx">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-imp-MyFx" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-MyFx-operacoes-tab"
            data-toggle="tab" href="#nav-import-MyFx-operacoes" role="tab"
            aria-controls="nav-import-MyFx-operacoes" aria-selected="false">Operações</a>

            <a class="text-warning nav-item nav-link" id="nav-import-MyFx-transferencias-tab"
            data-toggle="tab" href="#nav-import-MyFx-transferencias" role="tab"
            aria-controls="nav-import-MyFx-transferencias" aria-selected="true">Depósitos/Saques**</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentImMyFx">
        <div class="tab-pane fade active show" id="nav-import-MyFx-operacoes" role="tabpanel" aria-labelledby="nav-import-MyFx-operacoes-tab">
            <table id="tableOperacoesMyFx"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleMyFx"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnMyFxFormatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMyFxFormatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnMyFxFormatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnMyFxFormatter">Tempo</th>
                        <th data-field="contratos" data-halign="right" data-align="right">Contratos</th>
                        <th data-field="pontos" data-footer-formatter="footerTotalDescricaoMyFx"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnMyFxFormatter" data-footer-formatter="valorTotalMyFxFormatter"
                            data-halign="right" data-align="right">Resultado</th>
                    </tr>
                  </thead>
            </table>
        </div>

        <div class="tab-pane fade" id="nav-import-MyFx-transferencias" role="tabpanel" aria-labelledby="nav-import-MyFx-transferencias-tab">
            <table id="tableTransferenciasMyFx"
                data-classes="table table-hover"
                data-toggle="bootstrap-table"
                data-detail-view="true"
                data-detail-formatter="detalheTradeSimilarMyFxFormatter"
                data-detail-filter="detalheTradeSimilarMyFxFilter"
                data-show-footer="true"
                data-checkbox-header="true"
                data-click-to-select="false"
                data-row-style="rowStyleMyFx"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="tipo" data-formatter="tipoColumnMyFxFormatter">Tipo</th>
                        <th data-field="dataFormatada" data-halign="center" data-align="center">Data</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="valor" data-formatter="valorColumnMyFxFormatter" data-footer-formatter="valorTotalMyFxFormatter"
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

    var closedTradesMyFx = [];
    var transferenciasMyFx = [];
    var fileName = null;

    $(document).ready(function () {
        $('#tableTransferenciasMyFx').bootstrapTable({
            data: []
        });

        $('#tableOperacoesMyFx').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    });

    function getTransferenciasSelecionadasMyFx() {
        return $('#tableTransferenciasMyFx').bootstrapTable('getSelections');
    }

    function atualizarQuantidadeImportarMyFx(qtdOperacoes) {
        $('#qtdTradesImportarMyFx').html(qtdOperacoes);
    }

    function carregarArquivoInitMyFx() {
        $('#spinnerImpMyFx').removeClass('hidde-me');
        $('#fileImpMyFx').addClass('hidde-me');
        $('#btnSalvarArquivoMyFx').addClass('hidde-me');
    }

    function carregarArquivoConcluidoMyFx() {
        $('#spinnerImpMyFx').addClass('hidde-me');
        $('#fileImpMyFx').removeClass('hidde-me');
        $('#btnSalvarArquivoMyFx').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasMyFx() {
        $('#fileImpMyFx').val('');

        $('#infoImpMyFx').removeClass('hidde-me');
        $('#infoImpConcluidaMyFx').addClass('hidde-me');
        $('#spinnerImpSaveMyFx').removeClass('hidde-me');
        $('#resImportMyFx').addClass('hidde-me');
        $('#btnNovoImportMyFx').addClass('hidde-me');
        $('#btnCorrigirImportMyFx').addClass('hidde-me');

        $('#spinnerImpMyFx').addClass('hidde-me');
        $('#btnSalvarArquivoMyFx').addClass('hidde-me');

        closedTradesMyFx = [];
        transferenciasMyFx = [];

        $('#tableOperacoesMyFx').bootstrapTable('refreshOptions', {
            data: []
        });

        $('#tableTransferenciasMyFx').bootstrapTable('refreshOptions', {
            data: transferenciasMyFx
        });

        atualizarQuantidadeImportarMyFx(0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoMyFx').on('click', function () {

        $('#infoImpMyFx').addClass('hidde-me');
        $('#infoImpConcluidaMyFx').removeClass('hidde-me');
        $('#spinnerImpSaveMyFx').removeClass('hidde-me');
        $('#resImportMyFx').addClass('hidde-me');
        $('#btnNovoImportMyFx').addClass('hidde-me');
        $('#btnCorrigirImportMyFx').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {
                    conta_id :       getContaSelecionada().id,
                    closedTrades:    closedTradesMyFx,
                    transferencias:  getTransferenciasSelecionadasMyFx(),
                    openTrades:      [],
                    arquivo:         fileName,
                    primeiraData :   buscarPrimeiraData(closedTradesMyFx),
                    ultimaData :     buscarUltimaData(closedTradesMyFx),
                    numeroOperacoes: closedTradesMyFx.length,
                    numeroTransferencias: getTransferenciasSelecionadasMyFx().length,
                    valorOperacoes: calcularTotalResultado(closedTradesMyFx),
                    valorTransferencias: calcularTotalValor(getTransferenciasSelecionadasMyFx())
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){
                    $('#spinnerImpSaveMyFx').addClass('hidde-me');
                    $('#resImportMyFx').removeClass('hidde-me');
                    $('#resImportMyFx').removeClass('text-success');
                    $('#resImportMyFx').addClass('text-danger');
                    $('#resImportMyFx').html( data.error );
                    $('#btnNovoImportMyFx').addClass('hidde-me');
                    $('#btnCorrigirImportMyFx').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveMyFx').addClass('hidde-me');
                    $('#resImportMyFx').removeClass('hidde-me');
                    $('#resImportMyFx').addClass('text-success');
                    $('#resImportMyFx').removeClass('text-danger');
                    $('#resImportMyFx').html( data.success );
                    $('#btnNovoImportMyFx').removeClass('hidde-me');
                    $('#btnCorrigirImportMyFx').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveMyFx').addClass('hidde-me');
            $('#resImportMyFx').removeClass('hidde-me');
            $('#resImportMyFx').removeClass('text-success');
            $('#resImportMyFx').addClass('text-danger');
            $('#resImportMyFx').html( error );
            $('#btnNovoImportMyFx').removeClass('hidde-me');
            $('#btnCorrigirImportMyFx').addClass('hidde-me');
        });
    });

    $('#btnNovoImportMyFx').on('click', function () {
        reiniciarPreferenciasMyFx();
    });

    $('#btnCorrigirImportMyFx').on('click', function () {
        $('#infoImpMyFx').removeClass('hidde-me');
        $('#infoImpConcluidaMyFx').addClass('hidde-me');
    });

    function validarDadosNoSistemaMyFx() {
        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    closedTrades:   closedTradesMyFx,
                    openTrades:     [],
                    transferencias: transferenciasMyFx
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {
                $('#tableTransferenciasMyFx').bootstrapTable('refreshOptions', {
                    data: data.transferencias
                });
                $('#tableTransferenciasMyFx').bootstrapTable('expandAllRows');

                closedTradesMyFx = data.tradesFechados ?? [];

                $('#tableOperacoesMyFx').bootstrapTable('refreshOptions', {
                    data: data.tradesFechados
                });

                atualizarQuantidadeImportarMyFx(data.tradesFechados.length);
                carregarArquivoConcluidoMyFx();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluidoMyFx();
        });
    }

    function valorTotalMyFxFormatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function footerTotalDescricaoMyFx(data) {
        return "TOTAL:"
    }

    function valorTradeColumnMyFxFormatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnMyFxFormatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }


    function rowTradeStyleMyFx(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }

    }

    function tempoTradeColumnMyFxFormatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    $('#fileImpMyFx').change(function(e){
        carregarArquivoInitMyFx();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluidoMyFx();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoMyFx);
    });

    function lerRetornoImportacaoMyFx(str){
        try{
            closedTradesMyFx = [];
            transferenciasMyFx = [];

            Papa.parse(str, {
                complete: function(results) {
                    //console.log(results);
                    importarArquivoMyFx(results.data, closedTradesMyFx, transferenciasMyFx);
                }
            });
            validarDadosNoSistemaMyFx();

        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluidoMyFx();
        }
    }

    function importarArquivoMyFx(csv, closedTrades, transactionTrades) {
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
            indexSwap;

        $.each(csv, function( index, row ) {
            if(index == 0){//cabeçalho
                row.forEach(function (label, idx) {
                    if(label.toLowerCase().includes('ticket')){
                        indexTicket = idx;
                    } else if(label.toLowerCase().includes('symbol')){
                        indexInstrumento = idx;
                    } else if(label.toLowerCase().includes('units/lots')){
                        indexContratos = idx;
                    } else if(label.toLowerCase().includes('open date')){//hora abertura
                        indexAbertura = idx;
                    } else if(label.toLowerCase().includes('close date')){//hora fechamento
                        indexFechamento = idx;
                    } else if(label.toLowerCase().includes('action')){//tipo compra venda buy sell
                        indexTipo = idx;
                    } else if(label.toLowerCase().includes('open price')){//preço medio compra
                        indexPrecoEntrada = idx;
                    } else if(label.toLowerCase().includes('close price')){//preco medio venda
                        indexPrecoSaida = idx;
                    } else if(label.toLowerCase().includes('commission')){//preco medio venda
                        indexComissao = idx;
                    } else if(label.toLowerCase().includes('swap')){//preco medio venda
                        indexSwap = idx;
                    } else if(label.toLowerCase() === ('profit')){
                        indexResultado = idx;
                    }
                });
            } else if(index > 0){

                var linha = row;

                if(linha && linha.length > 1){
                    var val_ticket       = linha[indexTicket],
                        val_tipo         = linha[indexTipo].toLowerCase(),
                        val_contratos    = linha[indexContratos],
                        val_instrumento  = linha[indexInstrumento],
                        val_entrada      = linha[indexPrecoEntrada],
                        val_abertura     = toValidDate_MonthDayYear(linha[indexAbertura]),
                        val_fechamento   = toValidDate_MonthDayYear(linha[indexFechamento]),
                        val_saida        = linha[indexPrecoSaida],
                        val_comissao     = linha[indexComissao],
                        val_swap         = linha[indexSwap],
                        val_resultado    = linha[indexResultado];

                    if(val_tipo === 'sell' || val_tipo === 'buy'){
                        var dividePor = null;
                        if(val_instrumento === 'GOLD')
                            dividePor = 1000;
                        if(val_instrumento === 'USDJPY')
                            dividePor = 100;
                        var trade = createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                            val_entrada, val_fechamento, val_saida, null, null, null, val_resultado, null, null, dividePor);
                        closedTrades.push(trade);
                    } else
                    {
                        var transaction = createTransferencia(val_ticket, val_abertura, null, val_resultado);
                        transactionTrades.push(transaction);
                    }
                }
            }
        });
        return true;
    };

    function toValidDate_MonthDayYear(data) {
        if(!data || !(data+'').includes('/')) return data;

        let ar = data.split(' ');
        if(ar.length > 0){
            let dtArs = ar[0].split('/');
            return $.format.date(new Date(dtArs[2] + '-' + dtArs[0] + '-' + dtArs[1] + ' ' + ar[1]), 'yyyy.MM.dd HH:mm:ss');
        }
        return $.format.date(new Date(data), 'yyyy.MM.dd HH:mm:ss');
    }

    function dataComSegundosColumnMyFxFormatter(data, row) {
        return formatarDataHoraSegundos(data);
    }

    function valorColumnMyFxFormatter(valor, row){
        return '<div class="' + (row.tipo == 'D' ? 'text-success' : 'text-warning') + '">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoColumnMyFxFormatter(tipo, row){
        return tipo == 'D' ? '<div class="text-success"><i class="material-icons md-18">save_alt</i> Depósito</div>'
                : '<div class="text-warning"><i class="material-icons md-18">reply_all</i> Saque</div>';
    }

    function detalheTradeSimilarMyFxFormatter(index, row, element) {
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

    function detalheTradeSimilarMyFxFilter(index, row) {
        return row.similar ? true : false;
    }
</script>
@stop
