<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-info">
        ---  Tryd  ---
    </div>
</div>
<div class="row" id="infoImpTryd">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpTryd" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpTryd" class="form-control-sm" accept=".csv">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoTryd"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarTryd" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaTryd">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveTryd" class="hidde-me spinner-border text-success"></div>
        <div id="resImportTryd" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportTryd"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportTryd"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportTryd">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-imp-tryd" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-Tryd-operacoes-tab"
            data-toggle="tab" href="#nav-import-Tryd-operacoes" role="tab"
            aria-controls="nav-import-Tryd-operacoes" aria-selected="false">Operações</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentImTryd">
        <div class="tab-pane fade active show" id="nav-import-Tryd-operacoes" role="tabpanel" aria-labelledby="nav-import-Tryd-operacoes-tab">
            <table id="tableOperacoesTryd"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleTryd"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnTrydFormatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnTrydFormatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnTrydFormatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnTrydFormatter">Tempo</th>
                        <th data-field="contratos" data-halign="right" data-align="right">Contratos</th>
                        <th data-field="pontos" data-footer-formatter="footerTotalDescricaoTryd"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnTrydFormatter" data-footer-formatter="valorTotalTrydFormatter"
                            data-halign="right" data-align="right">Resultado</th>
                    </tr>
                  </thead>
            </table>
        </div>
    </div>
</div>
@section('page-script')
@parent
<script>

    var closedTradesTryd = [];
    var fileName = null;

    $(document).ready(function () {
        $('#tableOperacoesTryd').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    })

    function atualizarQuantidadeImportarTryd(qtdOperacoes) {
        $('#qtdTradesImportarTryd').html(qtdOperacoes);
    }

    function carregarArquivoInitTryd() {
        $('#spinnerImpTryd').removeClass('hidde-me');
        $('#fileImpTryd').addClass('hidde-me');
        $('#btnSalvarArquivoTryd').addClass('hidde-me');
    }

    function carregarArquivoConcluidoTryd() {
        $('#spinnerImpTryd').addClass('hidde-me');
        $('#fileImpTryd').removeClass('hidde-me');
        $('#btnSalvarArquivoTryd').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasTryd() {
        $('#fileImpTryd').val('');

        $('#infoImpTryd').removeClass('hidde-me');
        $('#infoImpConcluidaTryd').addClass('hidde-me');
        $('#spinnerImpSaveTryd').removeClass('hidde-me');
        $('#resImportTryd').addClass('hidde-me');
        $('#btnNovoImportTryd').addClass('hidde-me');
        $('#btnCorrigirImportTryd').addClass('hidde-me');

        $('#spinnerImpTryd').addClass('hidde-me');
        $('#btnSalvarArquivoTryd').addClass('hidde-me');

        closedTradesTryd = [];


        $('#tableOperacoesTryd').bootstrapTable('refreshOptions', {
            data: []
        });

        atualizarQuantidadeImportarTryd(0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoTryd').on('click', function () {

        $('#infoImpTryd').addClass('hidde-me');
        $('#infoImpConcluidaTryd').removeClass('hidde-me');
        $('#spinnerImpSaveTryd').removeClass('hidde-me');
        $('#resImportTryd').addClass('hidde-me');
        $('#btnNovoImportTryd').addClass('hidde-me');
        $('#btnCorrigirImportTryd').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {
                    conta_id :       getContaSelecionada().id,
                    closedTrades:    closedTradesTryd,
                    transferencias:  [],
                    openTrades:      [],
                    arquivo:         fileName,
                    primeiraData :   buscarPrimeiraData(closedTradesTryd),
                    ultimaData :     buscarUltimaData(closedTradesTryd),
                    numeroOperacoes: closedTradesTryd.length,
                    numeroTransferencias: 0,
                    valorOperacoes: calcularTotalResultado(closedTradesTryd),
                    valorTransferencias: 0
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){
                    $('#spinnerImpSaveTryd').addClass('hidde-me');
                    $('#resImportTryd').removeClass('hidde-me');
                    $('#resImportTryd').removeClass('text-success');
                    $('#resImportTryd').addClass('text-danger');
                    $('#resImportTryd').html( data.error );
                    $('#btnNovoImportTryd').addClass('hidde-me');
                    $('#btnCorrigirImportTryd').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveTryd').addClass('hidde-me');
                    $('#resImportTryd').removeClass('hidde-me');
                    $('#resImportTryd').addClass('text-success');
                    $('#resImportTryd').removeClass('text-danger');
                    $('#resImportTryd').html( data.success );
                    $('#btnNovoImportTryd').removeClass('hidde-me');
                    $('#btnCorrigirImportTryd').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveTryd').addClass('hidde-me');
            $('#resImportTryd').removeClass('hidde-me');
            $('#resImportTryd').removeClass('text-success');
            $('#resImportTryd').addClass('text-danger');
            $('#resImportTryd').html( error );
            $('#btnNovoImportTryd').removeClass('hidde-me');
            $('#btnCorrigirImportTryd').addClass('hidde-me');
        });
    });

    $('#btnNovoImportTryd').on('click', function () {
        reiniciarPreferenciasTryd();
    });

    $('#btnCorrigirImportTryd').on('click', function () {
        $('#infoImpTryd').removeClass('hidde-me');
        $('#infoImpConcluidaTryd').addClass('hidde-me');
    });

    function validarDadosNoSistemaTryd() {
        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    closedTrades:   closedTradesTryd,
                    openTrades:     [],
                    transferencias: []
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {

                closedTradesTryd = data.tradesFechados ?? [];

                $('#tableOperacoesTryd').bootstrapTable('refreshOptions', {
                    data: data.tradesFechados
                });

                atualizarQuantidadeImportarTryd(data.tradesFechados.length);
                carregarArquivoConcluidoTryd();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluidoTryd();
        });
    }

    function valorTotalTrydFormatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function footerTotalDescricaoTryd(data) {
        return "TOTAL:"
    }

    function valorTradeColumnTrydFormatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnTrydFormatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }


    function rowTradeStyleTryd(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }

    }

    function tempoTradeColumnTrydFormatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    $('#fileImpTryd').change(function(e){
        carregarArquivoInitTryd();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluidoTryd();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoTryd);
    });

    function lerRetornoImportacaoTryd(str){
        try{
            closedTradesTryd = [];

            Papa.parse(str, {
                complete: function(results) {
                    //console.log(results);
                    importarArquivoTryd(results.data, closedTradesTryd);
                }
            });
            //var corretora = importarArquivoTryd(str, closedTradesTryd)

            validarDadosNoSistemaTryd();
            /*console.log(closedTradesTryd);
            console.log(openTradesTryd);
            console.log(transferenciasTryd);
*/
        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluidoTryd();
        }
    }

    function importarArquivoTryd(csv, closedTrades) {
        var indexData,
            indexInstrumento,
            indexContratos,
            indexHoraAbertura,
            indexHoraFechamento,
            indexTipo,
            indexPrecoCompra,
            indexPrecoVenda,
            indexResultado,
            indexResulAberto,
            indexMep,
            indexMen;

        $.each(csv, function( index, row ) {
            if(index == 1){//cabeçalho
                row[0].split(',').forEach(function (label, idx) {
                    if(label.toLowerCase().includes('data')){
                        indexData = idx;
                    } else if(label.toLowerCase().includes('papel')){
                        indexInstrumento = idx;
                    } else if(label.toLowerCase().includes('qtd')){
                        indexContratos = idx;
                    } else if(label.toLowerCase().includes('abertura')){//hora abertura
                        indexHoraAbertura = idx;
                    } else if(label.toLowerCase().includes('fechamento')){//hora fechamento
                        indexHoraFechamento = idx;
                    } else if(label.toLowerCase().includes('c/v')){//tipo compra venda buy sell
                        indexTipo = idx;
                    } else if(label.toLowerCase().includes('dio cpa')){//preço medio compra
                        indexPrecoCompra = idx;
                    } else if(label.toLowerCase().includes('dio vda')){//preco medio venda
                        indexPrecoVenda = idx;
                    } else if(label.toLowerCase().includes('result fech')){
                        indexResultado = idx;
                    } else if(label.toLowerCase().includes('result aber')) {
                        indexResulAberto = idx;
                    } else if(label.toLowerCase().includes('mep')) {
                        indexMep = idx;
                    } else if(label.toLowerCase().includes('men')) {
                        indexMen = idx;
                    }
                });
            } else if(index > 1){

                var linha = row[0].split(',"');

                if(row[0] && linha && linha[indexResulAberto].includes('0,00')){
                    var val_abertura     = linha[indexData].replace('"','')+' '+linha[indexHoraAbertura].replace('"',''),
                        val_tipo         = converteCompraVendaEmBuySell(linha[indexTipo]),//converter em buy sell
                        val_contratos_aber = linha[indexContratos].replace('"',''),
                        val_contratos    = linha[indexContratos].replace('"',''),
                        val_instrumento  = converteAtivoEmSerieHistorica(linha[indexInstrumento]),
                        val_entrada      = (val_tipo == 'sell' ? linha[indexPrecoVenda].replace('"','') : linha[indexPrecoCompra].replace('"','')),
                        val_fechamento   = (!indexHoraFechamento ? null : linha[indexData].replace('"','')+' '+linha[indexHoraFechamento].replace('"','')),
                        val_saida        = (val_tipo == 'sell' ? linha[indexPrecoCompra].replace('"','') : linha[indexPrecoVenda].replace('"','')),
                        val_resultado    = linha[indexResultado].replace('"',''),
                        val_mep          = (indexMep ? linha[indexMep].replace('"','') : null),
                        val_men          = (indexMen ? linha[indexMen].replace('"','') : null);

                    var trade = createOperacao(val_tipo, null, val_abertura, val_contratos, val_instrumento,
                        val_entrada, val_fechamento, val_saida, null, null, null, val_resultado, val_mep, val_men);
                    closedTrades.push(trade);
                }
            }
        });
        return true;
    };

    function dataComSegundosColumnTrydFormatter(data, row) {
        return formatarDataHoraSegundos(data);
    }
</script>
@stop
