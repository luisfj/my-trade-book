@extends('layouts.app')

@section('content')
    <h1 class="text-active"><h1>
        <span class="material-icons text-success icon-v-bottom" style="font-size: 50px !important;">
            save_alt
        </span>
        <span>Importar Operações</span>
    </h1>
    <hr class="bg-warning">
    <!--<importacao-de-arquivo></importacao-de-arquivo>-->

    <div class="form-group row col-sm-12" style="text-align: right;">
        <div class="form-group row col-sm-4">
            {!! Form::label('conta_id', 'Conta', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
            <div class="col-sm-10">
                <select class="custom-select custom-select-sm" id="conta_id" name="conta_id">
                    <option selected="selected" value="">-- Selecione uma Conta --</option>
                </select>
            </div>
        </div>
    </div>
    <nav>
        <div class="nav nav-tabs fs12" id="nav-tab-evo-mes" role="tablist">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link altura-100p" id="nav-imp-mt4-lnk" data-toggle="tab" href="#nav-imp-mt4" role="tab"
                        aria-controls="nav-imp-mt4" aria-selected="false">
                        <img src="{{ asset('img/mt4.jpg') }}" alt="..." class="img-fluid rounded img-100px">
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link altura-100p"  id="nav-imp-mt5-lnk" data-toggle="tab" href="#nav-imp-mt5" role="tab"
                        aria-controls="nav-imp-mt5" aria-selected="false">
                        <img src="{{ asset('img/mt5.jpg') }}" alt="..." class="img-fluid rounded img-100px">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link altura-100p"  id="nav-imp-ctrader-lnk" data-toggle="tab" href="#nav-imp-ctrader" role="tab"
                        aria-controls="nav-imp-ctrader" aria-selected="false">
                        <img src="{{ asset('img/ctrader.png') }}" alt="..." class="img-fluid rounded img-90px">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link altura-100p"  id="nav-imp-ninja-lnk" data-toggle="tab" href="#nav-imp-ninja" role="tab"
                        aria-controls="nav-imp-ninja" aria-selected="false">
                        <img src="{{ asset('img/ninja.jpg') }}" alt="..." class="img-fluid rounded img-75px">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link altura-100p" id="nav-imp-profit-lnk" data-toggle="tab" href="#nav-imp-profit" role="tab"
                        aria-controls="nav-imp-profit" aria-selected="false">
                        <img src="{{ asset('img/profit2.jpg') }}" alt="..." class="img-fluid rounded img-140px">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link altura-100p" id="nav-imp-tryd-lnk" data-toggle="tab" href="#nav-imp-tryd" role="tab"
                        aria-controls="nav-imp-tryd" aria-selected="false">
                        <img src="{{ asset('img/tryd.png') }}" alt="..." class="img-fluid rounded img-85px">
                    </a>
                </li>
                @if(Auth::check() && Auth::User()->is_admin())
                    <li class="nav-item">
                        <a class="nav-link altura-100p" id="nav-imp-myFx-lnk" data-toggle="tab" href="#nav-imp-myFx" role="tab"
                            aria-controls="nav-imp-myFx" aria-selected="false">
                            MyFxBook
                        </a>
                    </li>
                @endif
                <li class="nav-item hidde-me">
                    <a class="nav-link" id="nav-imp-default-lnk" data-toggle="tab" href="#nav-imp-default" role="tab"
                        aria-controls="nav-imp-default" aria-selected="false">
                        Default
                    </a>
                </li>
            </ul>
        </div>
    </nav>
        <div class="tab-content card card-body" id="nav-tabContentDefault">
            <div class="tab-pane fade active show" id="nav-imp-default" role="tabpanel" aria-labelledby="nav-imp-default-tab" role="tabpanel">

                <div class="row justify-content-left">

                </div>

                <div class="row justify-content-left padt-20">

                </div>

                <div class="row justify-content-left padt-20">

                    <div class="col-md-1 marb-10 noPadding-with-lr-5">
                    </div>
                    <div class="col-md-9 noPadding-with-lr-5 fs18 fbold text-warning">
                        <label>Selecione a plataforma que gerou o relatório para importação</label>
                    </div>
                </div>

                <div class="row justify-content-left padt-25">
                </div>

                <div class="row justify-content-left padt-20">

                </div>

                <div class="row justify-content-left padt-20">

                </div>
            </div>

            <div class="tab-pane fade" id="nav-imp-mt4" role="tabpanel" aria-labelledby="nav-imp-mt4-tab">
                @include('modulos.trade.importacoes.importacaoMt4')
            </div>

            <div class="tab-pane fade" id="nav-imp-mt5" role="tabpanel" aria-labelledby="nav-imp-mt5-tab">
                @include('modulos.trade.importacoes.importacaoMt5')
            </div>

            <div class="tab-pane fade" id="nav-imp-ctrader" role="tabpanel" aria-labelledby="nav-imp-ctrader-tab">
                @include('modulos.trade.importacoes.importacaoCTrader')
            </div>

            <div class="tab-pane fade" id="nav-imp-ninja" role="tabpanel" aria-labelledby="nav-imp-ninja-tab">
                @include('modulos.trade.importacoes.importacaoNinja')
            </div>

            <div class="tab-pane fade" id="nav-imp-profit" role="tabpanel" aria-labelledby="nav-imp-profit-tab">
                Em breve importação de arquivos do PROFITCHART
            </div>

            <div class="tab-pane fade" id="nav-imp-tryd" role="tabpanel" aria-labelledby="nav-imp-tryd-tab">
                @include('modulos.trade.importacoes.importacaoTryd')
            </div>
            @if(Auth::check() && Auth::User()->is_admin())
                <div class="tab-pane fade" id="nav-imp-myFx" role="tabpanel" aria-labelledby="nav-imp-myFx-tab">
                    @include('modulos.trade.importacoes.importacaoMyFxBook')
                </div>
            @endif

        </div>


        <div class="mb-5"></div>
@endsection

@section('page-script')
@parent
<script>
    var listaDeContas = [];

    function calcularTotalResultado(array){
        return array.map(function (row) {
            return +row['resultado']
        }).reduce(function (sum, i) {
            return sum + (i ?? 0)
        }, 0);
    }

    function calcularTotalValor(array){
        return array.map(function (row) {
            return +row['valor']
        }).reduce(function (sum, i) {
            return sum + (i ?? 0)
        }, 0);
    }

    function buscarUltimaData(array) {
        if(!array || array.length <= 0) return null;

        return $.format.date(new Date(Math.max.apply(null, array.map(function(e) {
            return (e.fechamento ? toValidDate(e.fechamento) : null);
        }))), 'yyyy.MM.dd HH:mm:ss');
    }

    function buscarPrimeiraData(array) {
        if(!array || array.length <= 0) return null;

        return $.format.date(new Date(Math.min.apply(null, array.map(function(e) {
            return (e.fechamento ? toValidDate(e.fechamento) : null);
        }))), 'yyyy.MM.dd HH:mm:ss');
    }


    function getContaSelecionada(){
        let conta = listaDeContas.filter(c => c.id == $('#conta_id').val())
        return conta && conta.length > 0 ? conta[0] : null;
    }

    $('#conta_id').on('change', function (event) {
        atualizouConta();
    })

    function atualizouConta(){
        if($('#conta_id').val()){
            $('#nav-imp-mt4-lnk').removeClass('disabled');
            $('#nav-imp-mt5-lnk').removeClass('disabled');
            $('#nav-imp-ctrader-lnk').removeClass('disabled');
            $('#nav-imp-ninja-lnk').removeClass('disabled');
            $('#nav-imp-profit-lnk').removeClass('disabled');
            $('#nav-imp-tryd-lnk').removeClass('disabled');
        } else {
            $('#nav-imp-mt4-lnk').removeClass('active').addClass('disabled');
            $('#nav-imp-mt5-lnk').removeClass('active').addClass('disabled');
            $('#nav-imp-ctrader-lnk').removeClass('active').addClass('disabled');
            $('#nav-imp-ninja-lnk').removeClass('active').addClass('disabled');
            $('#nav-imp-profit-lnk').removeClass('active').addClass('disabled');
            $('#nav-imp-tryd-lnk').removeClass('active').addClass('disabled');
        }
        $('#nav-imp-default-lnk').click();

        reiniciarPreferenciasMt4();
        reiniciarPreferenciasMt5();
        reiniciarPreferenciasTryd();
    }

    $('#conta_id').find('option').remove().end();

    $.get('/contas-corretora-usuario', function(data){
        listaDeContas = data.contasEmCorretoras;

        $.each(listaDeContas, function(indice, conta){
            ultimaConta = conta.id;
            if(conta.padrao)
                contaPadrao = conta.id;
            $('#conta_id').append($('<option>', {
                        value: conta.id,
                        text : conta.corretora.nome.substring(0, 10) + ' ('+conta.identificador+')'
                    }));
        });
        contaPadrao = contaPadrao ?? ultimaConta;
        $('#conta_id').val(contaPadrao);
        atualizouConta();
    });

    function loadTextFromFile(ev, callbackText) {
        let file = ev.target.files[0];
        let reader = new FileReader();

        reader.onload = e => {
            callbackText(e.target.result);
        }
        //reader.readAsBinaryString(file);
        reader.readAsText(file);
    }

    function createTransferencia(val_ticket, val_abertura, val_codigo, val_valor, tipo = null) {
        var trans = new Transferencia(val_ticket, val_abertura, val_codigo, val_valor, tipo);
        //{   //(ticket, data, codigo, valor, tipo = null)

        //}
        return trans;
    }

    function createOperacao(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado, val_mep = null, val_men = null, dividePontos = null) {
        var operacao = new Operacao(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado, val_mep, val_men, dividePontos);

        return operacao;
    }

    function formataHoraMinuto(val){
        if(val < 10)
            return '0'+val;
        else if(val == 0)
            return '00';
        else
            return ''+val;
    }

    function converteCompraVendaEmBuySell(compraVenda) {
        if(!compraVenda)
            return null;
        if(compraVenda.toLowerCase().includes('c')){
            return 'buy';
        }
        if(compraVenda.toLowerCase().includes('v')){
            return 'sell';
        }
        return null;
    }

    function converteAtivoEmSerieHistorica(instrumento) {
        if(instrumento){
            if(instrumento.toLowerCase().includes('wdo')){
                return 'WDOFUT';
            } else
            if(instrumento.toLowerCase().includes('win')){
                return 'WINFUT';
            } else
            if(instrumento.toLowerCase().includes('es ')){
                return 'S&P';
            }
        }
        return instrumento;
    }

    function Transferencia(ticket, data, codigo, valor, tipo = null) {
            this.tipo = tipo == null ? ((valor * 1) > 0 ? 'D' : 'S' ) : tipo;
            this.ticket = ticket;
            this.data = formatarDataParaSalvar(data);
            this.codigo = codigo;
            this.valor = valor;
            this.dataFormatada = formatarDataHora(this.data);
    }

    function Operacao(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado, val_mep, val_men, dividePontos) {
        this.tipo = tipo;
        this.ticket = ticket;
        this.abertura = formatarDataParaSalvar(abertura);
        this.contratos = contratos;
        this.instrumento = instrumento;
        this.preco_entrada = textToFloat(preco_entrada);
        this.preco_entrada_str = preco_entrada;
        this.fechamento = formatarDataParaSalvar(fechamento);
        this.preco_saida = textToFloat(preco_saida);
        this.preco_saida_str = preco_saida;
        this.comissao = textToFloat(comissao);
        this.impostos = textToFloat(impostos);
        this.swap = textToFloat(swap);
        this.resultado = (textToFloat(resultado) + textToFloat(swap) + textToFloat(impostos) + textToFloat(comissao)).toFixed(2);
        this.resultado_bruto = textToFloat(resultado);
        this.pontos = 0;
        this.tempo_operacao_dias = 0;
        this.tempo_operacao_horas = 0;
        this.mep = val_mep;
        this.men = val_men;
        this.calcularValores(dividePontos);
    }

    Operacao.prototype.calcularValores = function (dividePontos) {
        if (this.fechamento) {
            if (this.tipo == 'sell') {
                if((this.instrumento.toLowerCase() == 'winfut' || this.instrumento.toLowerCase() == 'wdofut')){
                    this.pontos = (this.preco_entrada - this.preco_saida);
                } else {
                    this.pontos = parseInt(this.preco_entrada_str.replace(/\./gi,'').replace(/\,/gi,'.')) - parseInt(this.preco_saida_str.replace(/\./gi,'').replace(/\,/gi,'.'));
                }
            }
            else {
                if((this.instrumento.toLowerCase() == 'winfut' || this.instrumento.toLowerCase() == 'wdofut')){
                    this.pontos = (this.preco_saida - this.preco_entrada);
                } else {
                    this.pontos = parseInt(this.preco_saida_str.replace(/\./gi,'').replace(/\,/gi,'.')) - parseInt(this.preco_entrada_str.replace(/\./gi,'').replace(/\,/gi,'.'));
                }
            }
            if(dividePontos)
                this.pontos = this.pontos / dividePontos;

            if( (this.pontos+'').includes('\.') ){
                let pts = this.pontos.toFixed(2);
                if((pts+'').includes('\.00'))
                    pts = this.pontos.toFixed(0);

                this.pontos = pts;
            }

            var diff = Math.abs(toValidDate(this.fechamento) - toValidDate(this.abertura));
            var seconds = Math.floor(diff / 1000); //ignore any left over units smaller than a second
            var minutes = Math.floor(seconds / 60);
            seconds = seconds % 60;
            var hours = Math.floor(minutes / 60);
            minutes = minutes % 60;
            var days = Math.floor(hours / 24);
            hours = hours % 24;
            this.tempo_operacao_dias = days;
            this.tempo_operacao_horas = formataHoraMinuto(hours) + ":" + formataHoraMinuto(minutes) + ":" + formataHoraMinuto(seconds);
        }
    };
    Operacao.prototype.get_tempo_operacao_formatado = function () {
        if (this.tempo_operacao_dias) {
            return this.tempo_operacao_dias + "D " + this.tempo_operacao_horas;
        }
        else {
            return this.tempo_operacao_horas;
        }
    };
</script>
@stop
