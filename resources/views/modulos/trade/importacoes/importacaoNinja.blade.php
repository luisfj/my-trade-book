<div class="row">
    <div class="col-sm-5 col-md-5 col-lg-7 fs18 fbold text-info">
        ---  Ninja Trader  ---
    </div>
</div>
<div class="row" id="infoImpNinja">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpNinja" class="hidde-me spinner-border text-success"></div>
        <input type="file" id="fileImpNinja" class="form-control-sm" accept=".csv">
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnSalvarArquivoNinja"><i class="material-icons md-18">save</i> Salvar Dados Carregados</button>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <label class="fbold">Importar: </label>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-2">
            <label id="qtdTradesImportarNinja" class="fbold text-success">0</label>
            <label class="text-warning"> Operações</label>
    </div>
</div>
<div class="row hidde-me" id="infoImpConcluidaNinja">
    <div class="col-sm-5 col-md-5 col-lg-7">
        <div id="spinnerImpSaveNinja" class="hidde-me spinner-border text-success"></div>
        <div id="resImportNinja" class="text-info fbold"></div>
        <button type="button" class="btn btn-sm btn-info hidde-me" id="btnNovoImportNinja"><i class="material-icons md-18">refresh</i> Importar Outro</button>
        <button type="button" class="btn btn-sm btn-warning hidde-me" id="btnCorrigirImportNinja"><i class="material-icons md-18">refresh</i> Corrigir</button>
    </div>
</div>

<hr>
<div id="saidaImportNinja">
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-imp-Ninja" role="tablist">
            <a class="navbar-text padr-10"><b>Dados a Importar</b></a>

            <a class="nav-item nav-link active" id="nav-import-Ninja-operacoes-tab"
            data-toggle="tab" href="#nav-import-Ninja-operacoes" role="tab"
            aria-controls="nav-import-Ninja-operacoes" aria-selected="false">Operações</a>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContentImNinja">
        <div class="tab-pane fade active show" id="nav-import-Ninja-operacoes" role="tabpanel" aria-labelledby="nav-import-Ninja-operacoes-tab">
            <table id="tableOperacoesNinja"
                data-classes="table table-sm table-hover"
                data-toggle="bootstrap-table"
                data-show-footer="true"
                data-row-style="rowTradeStyleNinja"
                data-search="true">
                <thead>
                    <tr>
                        <th data-field="tipo" data-formatter="tipoTradeColumnNinjaFormatter">(Res) Tipo</th>
                        <th data-field="instrumento">Ativo</th>
                        <th data-field="ticket">Ticket</th>
                        <th data-field="codigo">Código</th>
                        <th data-field="abertura" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnNinjaFormatter">Abertura</th>
                        <th data-field="fechamento" data-halign="center" data-align="center" data-formatter="dataComSegundosColumnNinjaFormatter">Fechamento</th>
                        <th data-field="tempo_operacao_horas" data-formatter="tempoTradeColumnNinjaFormatter">Tempo</th>
                        <th data-field="contratos" data-halign="right" data-align="right">Contratos</th>
                        <th data-field="pontos" data-footer-formatter="footerTotalDescricaoNinja"
                                data-halign="right" data-align="right">Pontos</th>
                        <th data-field="resultado" data-formatter="valorTradeColumnNinjaFormatter" data-footer-formatter="valorTotalNinjaFormatter"
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

    var closedTradesNinja = [];
    var fileName = null;

    $(document).ready(function () {
        $('#tableOperacoesNinja').bootstrapTable({
            data: []
        });
        $('.search-input').addClass('form-control-sm');

    })

    function atualizarQuantidadeImportarNinja(qtdOperacoes) {
        $('#qtdTradesImportarNinja').html(qtdOperacoes);
    }

    function carregarArquivoInitNinja() {
        $('#spinnerImpNinja').removeClass('hidde-me');
        $('#fileImpNinja').addClass('hidde-me');
        $('#btnSalvarArquivoNinja').addClass('hidde-me');
    }

    function carregarArquivoConcluidoNinja() {
        $('#spinnerImpNinja').addClass('hidde-me');
        $('#fileImpNinja').removeClass('hidde-me');
        $('#btnSalvarArquivoNinja').removeClass('hidde-me');
        $('.search-input').addClass('form-control-sm');
    }

    function reiniciarPreferenciasNinja() {
        $('#fileImpNinja').val('');

        $('#infoImpNinja').removeClass('hidde-me');
        $('#infoImpConcluidaNinja').addClass('hidde-me');
        $('#spinnerImpSaveNinja').removeClass('hidde-me');
        $('#resImportNinja').addClass('hidde-me');
        $('#btnNovoImportNinja').addClass('hidde-me');
        $('#btnCorrigirImportNinja').addClass('hidde-me');

        $('#spinnerImpNinja').addClass('hidde-me');
        $('#btnSalvarArquivoNinja').addClass('hidde-me');

        closedTradesNinja = [];


        $('#tableOperacoesNinja').bootstrapTable('refreshOptions', {
            data: []
        });

        atualizarQuantidadeImportarNinja(0);

        $('.search-input').addClass('form-control-sm');
    }

    $('#btnSalvarArquivoNinja').on('click', function () {

        $('#infoImpNinja').addClass('hidde-me');
        $('#infoImpConcluidaNinja').removeClass('hidde-me');
        $('#spinnerImpSaveNinja').removeClass('hidde-me');
        $('#resImportNinja').addClass('hidde-me');
        $('#btnNovoImportNinja').addClass('hidde-me');
        $('#btnCorrigirImportNinja').addClass('hidde-me');

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        let head =
                {
                    conta_id :       getContaSelecionada().id,
                    closedTrades:    closedTradesNinja,
                    transferencias:  [],
                    openTrades:      [],
                    arquivo:         fileName,
                    primeiraData :   buscarPrimeiraData(closedTradesNinja),
                    ultimaData :     buscarUltimaData(closedTradesNinja),
                    numeroOperacoes: closedTradesNinja.length,
                    numeroTransferencias: 0,
                    valorOperacoes: calcularTotalResultado(closedTradesNinja),
                    valorTransferencias: 0
                }

        $.post('/operacoes/importar', {dados: JSON.stringify(head)}, function(data) {
                if(data.error){
                    $('#spinnerImpSaveNinja').addClass('hidde-me');
                    $('#resImportNinja').removeClass('hidde-me');
                    $('#resImportNinja').removeClass('text-success');
                    $('#resImportNinja').addClass('text-danger');
                    $('#resImportNinja').html( data.error );
                    $('#btnNovoImportNinja').addClass('hidde-me');
                    $('#btnCorrigirImportNinja').removeClass('hidde-me');
                } else {
                    $('#spinnerImpSaveNinja').addClass('hidde-me');
                    $('#resImportNinja').removeClass('hidde-me');
                    $('#resImportNinja').addClass('text-success');
                    $('#resImportNinja').removeClass('text-danger');
                    $('#resImportNinja').html( data.success );
                    $('#btnNovoImportNinja').removeClass('hidde-me');
                    $('#btnCorrigirImportNinja').addClass('hidde-me');
                }
            },
            'json' // I expect a JSON response
        ).fail(function (error) {
            $('#spinnerImpSaveNinja').addClass('hidde-me');
            $('#resImportNinja').removeClass('hidde-me');
            $('#resImportNinja').removeClass('text-success');
            $('#resImportNinja').addClass('text-danger');
            $('#resImportNinja').html( error );
            $('#btnNovoImportNinja').removeClass('hidde-me');
            $('#btnCorrigirImportNinja').addClass('hidde-me');
        });
    });

    $('#btnNovoImportNinja').on('click', function () {
        reiniciarPreferenciasNinja();
    });

    $('#btnCorrigirImportNinja').on('click', function () {
        $('#infoImpNinja').removeClass('hidde-me');
        $('#infoImpConcluidaNinja').addClass('hidde-me');
    });

    function validarDadosNoSistemaNinja() {
        let head =
                {
                    conta_id :      getContaSelecionada().id,
                    closedTrades:   closedTradesNinja,
                    openTrades:     [],
                    transferencias: []
                }

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

       // $('#tabTeste').bootstrapTable('refresh');

        $.post('/operacoes/validar-importacao', {dados: JSON.stringify(head)}, function(data) {

                closedTradesNinja = data.tradesFechados ?? [];

                $('#tableOperacoesNinja').bootstrapTable('refreshOptions', {
                    data: data.tradesFechados
                });

                atualizarQuantidadeImportarNinja(data.tradesFechados.length);
                carregarArquivoConcluidoNinja();
            },
            'json' // I expect a JSON response
        ).fail(function (erro) {
            console.log(erro);
            carregarArquivoConcluidoNinja();
        });
    }

    function valorTotalNinjaFormatter(data) {
        var contaCorretora = data && data.length > 0 ? data[0].conta : null;
        var field = this.field
        return /*'$' +*/formatarValor(data.map(function (row) {
            return +row[field]
        }).reduce(function (sum, i) {
            return sum + i
        }, 0), contaCorretora);
    }

    function footerTotalDescricaoNinja(data) {
        return "TOTAL:"
    }

    function valorTradeColumnNinjaFormatter(valor, row){
        return '<div class="fbold">' + formatarValor(valor, row.conta) +'</div>';
    }

    function tipoTradeColumnNinjaFormatter(tipo, row){
        let arrow = (row.resultado > 0 ? '<i class="material-icons md-18 text-success">arrow_upward</i>' : '<i class="material-icons md-18 text-danger">arrow_downward</i>');

        return (tipo == 'buy' ? '<div class="text-info">'+arrow +' Compra</div>'
                : '<div class="text-warning">'+arrow +' Venda</div>');
    }


    function rowTradeStyleNinja(row, index) {
        return {
            classes: (row.resultado > 0 ? 'text-success' : 'text-danger')
        }
    }

    function tempoTradeColumnNinjaFormatter(data, row) {
        let tempoFormatado = data;
        if(row.tempo_operacao_dias)
            tempoFormatado = (row.tempo_operacao_dias + 'd ' + tempoFormatado);
        return tempoFormatado;
    }

    $('#fileImpNinja').change(function(e){
        carregarArquivoInitNinja();
        fileName = e.target.files[0] ? e.target.files[0].name : null;

        if(!fileName){
            carregarArquivoConcluidoNinja();
            return;
        }

        loadTextFromFile(e, lerRetornoImportacaoNinja);
    });

    function lerRetornoImportacaoNinja(str){
        try{
            closedTradesNinja = [];

            Papa.parse(str, {
                complete: function(results) {
                    //console.log(results);
                    importarArquivoNinja(results.data, closedTradesNinja);
                }
            });
            //var corretora = importarArquivoNinja(str, closedTradesNinja)

            validarDadosNoSistemaNinja();
           /* console.log(closedTradesNinja);
            console.log(openTradesNinja);
            console.log(transferenciasNinja);
*/
        } catch (e) {
            console.log(e.message);
            console.log(e);
            carregarArquivoConcluidoNinja();
        }
    }

    function importarArquivoNinja(csv, closedTrades) {
        var indexInstrumento,
            indexContratos,
            indexDataAbertura,
            indexDataFechamento,
            indexPrecoEntrada,
            indexPrecoSaida,
            indexResultado,
            indexComissao;

        $.each(csv, function( index, row ) {
            if(index == 0){//cabeçalho
                row.forEach(function (label, idx) {
                    if(label.toLowerCase().includes('instrument')){
                        indexInstrumento = idx;
                    } else if(label.toLowerCase().includes('qty')){
                        indexContratos = idx;
                    } else if(label.toLowerCase().includes('entry time')){//hora abertura
                        indexDataAbertura = idx;
                    } else if(label.toLowerCase().includes('exit time')){//hora fechamento
                        indexDataFechamento = idx;
                    } else if(label.toLowerCase().includes('entry price')){//preço medio compra
                        indexPrecoEntrada = idx;
                    } else if(label.toLowerCase().includes('exit price')){//preco medio venda
                        indexPrecoSaida = idx;
                    } else if(label === 'Profit'){
                        indexResultado = idx;
                    } else if(label.toLowerCase().includes('commission')){
                        indexComissao = idx;
                    }
                });
            } else if(index > 0){
                if(row && row[0]){
                    var val_abertura     = row[indexDataAbertura],
                        val_tipo         = null,
                        val_contratos    = row[indexContratos],
                        val_instrumento  = converteAtivoEmSerieHistorica(row[indexInstrumento]),
                        val_entrada      = row[indexPrecoEntrada],
                        val_fechamento   = row[indexDataFechamento],
                        val_saida        = row[indexPrecoSaida],
                        val_resultado    = row[indexResultado].replace(/[R$|$]/g, ''),
                        val_comissao     = row[indexComissao].replace(/[R$|$]/g, '');

                    if(textToFloat(val_entrada) < textToFloat(val_saida))
                        val_tipo = (textToFloat(val_resultado) >= 0 ? 'buy' : 'sell');
                    else
                        val_tipo = (textToFloat(val_resultado) >= 0 ? 'sell' : 'buy');

                    var trade = createOperacao(val_tipo, null, val_abertura, val_contratos, val_instrumento,
                        val_entrada, val_fechamento, val_saida, val_comissao, null, null, val_resultado, null, null);
                    closedTrades.push(trade);
                }
            }
        });
        return true;
    };

    function dataComSegundosColumnNinjaFormatter(data, row) {
        return formatarDataHoraSegundos(data);
    }
</script>
@stop
