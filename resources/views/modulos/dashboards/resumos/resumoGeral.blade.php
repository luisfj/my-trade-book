<div class="row" id="resumoCorretoraContent">
    <div class="col-sm-12 col-md-12 col-lg-12" >
        <form id="formFiltroResCorrSel" action="POST" target="{{route('dash.historico.conta.corretora')}}">
            {{ csrf_field() }}
            <div class="form-group form-group-sm padb-5">
                <label for="resCorrSelecionada" >Corretora</label>
                <select id="resCorrSelecionada" name="resCorrSelecionada" class="form-control form-control-sm">
                </select>
            </div>
        </form>

        <div class="row fs11">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4 padb-5" >
                <label class="marb-0 fs12">Tipo:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 padb-5 text-right" >
                <label id="resCorrTipo" class="marb-0 fs12 fbold">-</label>
            </div>

            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">Abertura:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrDtAbert" class="marb-0">-</label>
            </div>

            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">1° Trade:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrPrimTrade" class="marb-0">-</label>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

        <div class="row fs11">
            <div class="col-7 col-sm-7 col-md-7 col-lg-7" >
                <label class="marb-0">% Retorno:</label>
            </div>
            <div class="col-5 col-sm-5 col-md-5 col-lg-5 text-right" >
                <label id="resCorrGain" class="marb-0">-</label>
            </div>

            <div class="col-7 col-sm-7 col-md-7 col-lg-7" id="lucAbsResConCor"
                data-toggle="tooltip" data-placement="top"
                title="Retorno do investimento é a porcentagem do total de depósitos. Por definição, novos depósitos afetam o ganho absoluto." >
                <label class="marb-0 pontilhado">GANHO ABS:</label>
            </div>
            <div class="col-5 col-sm-5 col-md-5 col-lg-5 text-right" >
                <label id="resCorrGainAbs" class="marb-0">-</label>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

        <div class="row fs11">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">Diário:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrPercDiaria" class="marb-0">-</label>
            </div>

            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">Mensal:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrPercMensal" class="marb-0">-</label>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

        <div class="row fs11">
            <div class="col-3 col-sm-3 col-md-3 col-lg-3" >
                <label class="marb-0 fs12 fbold">Saldo:</label>
            </div>
            <div class="col-9 col-sm-9 col-md-9 col-lg-9 text-right" >
                <label id="resCorrSaldo" class="marb-0 fs12 fbold">-</label>
            </div>

            <div class="col-3 col-sm-3 col-md-3 col-lg-3" >
                <label class="marb-0">Máximo:</label>
            </div>
            <div class="col-9 col-sm-9 col-md-9 col-lg-9 text-right">
                <label id="resCorrMaxSaldo" class="marb-0">-</label>
            </div>

            <div class="col-3 col-sm-3 col-md-3 col-lg-3" >
                <label class="marb-0 fs12">Lucro:</label>
            </div>
            <div class="col-9 col-sm-9 col-md-9 col-lg-9 text-right">
                <label id="resCorrLucro" class="marb-0 fs12 fbold">-</label>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

        <div class="row fs11">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">Depositos:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrDepositos" class="marb-0">-</label>
            </div>

            <div class="col-4 col-sm-4 col-md-4 col-lg-4" >
                <label class="marb-0">Retiradas:</label>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 text-right" >
                <label id="resCorrRetiradas" class="marb-0">-</label>
            </div>
        </div>
    </div>
</div>


@section('page-script')
@parent
<script>
    var resContasCorretoras = [];
    var contaCorretoraSelecionada = null;
    var urlPost = $('#formFiltroResCorrSel').attr('target');


    $('#lucAbsResConCor').tooltip();

    atualizarContaCorretoraComboRes();

    function atualizarContaCorretoraComboRes(){
        $('#resCorrSelecionada').find('option').remove().end();

        var urlGet = urlPost.replace('dashHistoricoContaCorretora', 'contas-corretora-usuario');
        var ultimaConta = null;
        var contaPadrao = null;

        $.get(urlGet, function(data){
            resContasCorretoras = data.contasEmCorretoras;

            $.each(resContasCorretoras, function(indice, conta){
                ultimaConta = conta.id;
                if(conta.padrao)
                    contaPadrao = conta.id;
                $('#resCorrSelecionada').append($('<option>', {
                            value: conta.id,
                            text : conta.corretora.nome.substring(0, 10) + ' ('+conta.identificador+')'
                        }));
            });
            contaPadrao = contaPadrao ?? ultimaConta;
            $('#resCorrSelecionada').val(contaPadrao);
            atualizarResumoContaCorretoraSelecionada();
        });
    }

    function atualizarResumoContaCorretoraSelecionada(){
        contaCorretoraSelecionada = resContasCorretoras.filter(conta => conta.id == $('#resCorrSelecionada').val())[0];
        if(!contaCorretoraSelecionada){return;}


        $.post( urlPost, $('#formFiltroResCorrSel').serialize(), function(data) {
                atualizarDadosPainelResCorr(contaCorretoraSelecionada, data);
            },
            'json' // I expect a JSON response
            );
    }

    function atualizarDadosPainelResCorr(contaCorretora, data) {
        //var sifrao = contaCorretoraSelecionada.moeda.sifrao + ' ';

        $('#resCorrTipo').html(contaCorretora.real_demo == 'D' ? 'DEMO' : 'REAL');

        $('#resCorrDtAbert').html(contaCorretora.dtabertura ? new Date(contaCorretora.dtabertura).toLocaleDateString("pt-BR") : '-');
        $('#resCorrPrimTrade').html(data.dataPrimeiroTrade ? new Date(data.dataPrimeiroTrade).toLocaleDateString("pt-BR") : '-');//formatar certo

        var percRet = calcularPercRetornoResCorr(contaCorretora.saldo, data.totalDepositos, data.totalSaques);
        var ganhoAbs = calcularGanhoAbsResCorr(data.resultadoTotal, data.totalDepositos);
        $('#resCorrGain').html( percRet.toFixed(2) + '%');
        $('#resCorrGainAbs').html(ganhoAbs.toFixed(2) + '%');

        if(percRet > 0)
            addTextSuccess($('#resCorrGain'));
        else
            addTextDanger($('#resCorrGain'));

        if(ganhoAbs > 0)
            addTextSuccess($('#resCorrGainAbs'));
        else
            addTextDanger($('#resCorrGainAbs'));


        //$('#resCorrPercDiaria').html();
        //$('#resCorrPercMensal').html();


        $('#resCorrSaldo').html(formatarValor(contaCorretora.saldo, contaCorretora));
        if(data.maiorSaldoDiario){
            $('#resCorrMaxSaldo').html('<i class="fs10">(' +
                data.maiorSaldoDiario.dia + '/' + data.maiorSaldoDiario.mes + '/' + (''+data.maiorSaldoDiario.ano).substring(2,4)
            +')</i> '
                        + formatarValor(data.maiorSaldoDiario.saldo_atual, contaCorretora));//<i class="fs10">(22/10/19)</i> R$ 3.000.000,00
        } else {
            $('#resCorrMaxSaldo').html('-');
        }
        $('#resCorrLucro').html(formatarValor(data.resultadoTotal, contaCorretora));//converter padrao moeda com virgula

        if(contaCorretora.saldo > 0)
            addTextSuccess($('#resCorrSaldo'));
        else
            addTextDanger($('#resCorrSaldo'));

        if(data.resultadoTotal > 0)
            addTextSuccess($('#resCorrLucro'));
        else
            addTextDanger($('#resCorrLucro'));


        $('#resCorrDepositos').html(formatarValor(data.totalDepositos, contaCorretora));
        $('#resCorrRetiradas').html(formatarValor(data.totalSaques, contaCorretora));

        atualizarDestaquesResCorr();

        callEventosRegitradosAlteracaoCorretora(contaCorretora);
    }

    function atualizarDestaquesResCorr() {
        var hrs = $('#resumoCorretoraContent').find('hr');

        if($('#resCorrTipo').html() == 'REAL'){
            addTextInfo($('#resCorrTipo'));
            hrs.each((index, el) => {
                $(el).removeClass('hr3');
                $(el).removeClass('hr3-warning');
                $(el).addClass('hr3');
            });
        } else {
            addTextWarning($('#resCorrTipo'));
            hrs.each((index, el) => {
                $(el).removeClass('hr3');
                $(el).removeClass('hr3-warning');
                $(el).addClass('hr3-warning');
            });
        }

    }

    function calcularGanhoAbsResCorr(resOperacoesConta, depositos) {
        if(!resOperacoesConta || !depositos)
            return 0;
        return (resOperacoesConta * 100) / depositos;
    }

    function calcularPercRetornoResCorr(saldoAtual, depositos, saques) {
        if(!saldoAtual && !depositos && !saques)
            return 0;
        var initVal = depositos - saques;
        return ((saldoAtual - initVal) / initVal) * 100;
    }

    $('#resCorrSelecionada').change(function(){
        atualizarResumoContaCorretoraSelecionada();
    });
</script>
@stop
