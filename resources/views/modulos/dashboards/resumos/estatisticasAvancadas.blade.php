<nav>
    <div class="nav nav-tabs fs12" id="nav-tab-est-ava" role="tablist">
        <a class="navbar-text padr-10"><b>Estatísticas</b></a>
        <a id="corretoraNmEstAva" class="navbar-text padr-10 fbold">-</a>
    </div>
</nav>

<div class="tab-content" id="nav-tabContentEstAva">
    <div class="tab-pane fade show active" id="nav-operacoes-est-ava" role="tabpanel" aria-labelledby="nav-operacoes-tab-est-ava">

        <div class="row" style="padding-top:10px;">
            <div class="col-sm-12 col-md-6 col-lg-6 fs12 " >
                <div class="row">
                    <div class="col-sm-7 col-md-7 col-lg-7 padb-5">Operações:</div>
                    <div id="nrOperacoesEstAva" class="col-sm-5 col-md-5 col-lg-5 text-right">-</div>

                    <div class="col-sm-7 col-md-7 col-lg-7 padb-5">Ganhos/Perdas:</div>
                    <div class="col-sm-5 col-md-5 col-lg-5 text-right">
                        <div class="progress" id="gainLossBarEstAva" data-toggle="tooltip" data-placement="top"
                        title="-">
                            <div class="progress-bar bg-success" role="progressbar" id="gainBarEstAva"
                                style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" id="lossBarEstAva"
                                style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                    </div>

                    <div class="col-sm-7 col-md-7 col-lg-7 padb-5">Pontos:</div>
                    <div id="pontosSaldoEstAva" class="col-sm-5 col-md-5 col-lg-5 text-right">-</div>

                    <div class="col-sm-4 col-md-4 col-lg-4 padb-5">Média Ganho:</div>
                    <div id="medGanhoEstAva" class="col-sm-8 col-md-8 col-lg-8 text-right">-</div>

                    <div class="col-sm-4 col-md-4 col-lg-4 padb-5">Média Perda:</div>
                    <div id="medPerdaEstAva" class="col-sm-8 col-md-8 col-lg-8 text-right">-</div>

                    <div class="col-sm-7 col-md-7 col-lg-7 padb-5">Comissões:</div>
                    <div id="comissoesEstAva" class="col-sm-5 col-md-5 col-lg-5 text-right">-</div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 fs12 " >
                <div class="row">
                    <div class="col-sm-7 col-md-7 col-lg-7 padb-5 fbold"
                        id="ftLcEstAvLabel" data-toggle="tooltip" data-placement="top"
                        title="O FATOR DE LUCRO mostra quantas vezes o lucro bruto (Soma de todas as negociações vencedoras) excede a perda bruta (Soma de todas as negociações perdedoras). Quanto maior o valor, melhor."
                        ><b class="pontilhado">Fator de Lucro:</b></div>
                    <div id="fatorLucroEstAva" class="col-sm-5 col-md-5 col-lg-5 text-right fbold">-</div>

                    <div class="col-sm-5 col-md-5 col-lg-5 padb-5">Melhor Trade($):</div>
                    <div id="melhorOperValorEstAva" class="col-sm-7 col-md-7 col-lg-7 text-right" >-</div>

                    <div class="col-sm-5 col-md-5 col-lg-5 padb-5">Pior Trade($):</div>
                    <div id="piorOperValorEstAva" class="col-sm-7 col-md-7 col-lg-7 text-right" >-</div>

                    <div class="col-sm-5 col-md-5 col-lg-5 padb-5">Melhor Trade(Pts):</div>
                    <div id="melhorOperPontosEstAva" class="col-sm-7 col-md-7 col-lg-7 text-right" >-</div>

                    <div class="col-sm-5 col-md-5 col-lg-5 padb-5">Pior Trade(Pts):</div>
                    <div id="piorOperPontosEstAva" class="col-sm-7 col-md-7 col-lg-7 text-right" >-</div>

                    <div class="col-sm-5 col-md-5 col-lg-5 padb-5">Duração Média:</div>
                    <div id="durMediaEstAva" class="col-sm-7 col-md-7 col-lg-7 text-right" >-</div>
                </div>
            </div>
        </div>

        <hr class="hr3 mar-5" width="100%">

        <div class="row" style="padding-top:10px;">
            <div class="col-sm-12 col-md-12 col-lg-12 fs12 " >
                <div class="row">
                    <table id="tableAtivosEstAva" class="table table-sm table-striped table-scroll text-center fbold">
                        <thead>
                          <tr>
                            <th scope="col" >Ativo</th>
                            <th scope="col" >Trades</th>
                            <th scope="col" >Pontos</th>
                            <th scope="col" >Lucro ($)</th>
                            <th scope="col" >Acertos (%)</th>
                            <th scope="col" >Perda (%)</th>
                            <th scope="col" style="width: 1% !important"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="col text-body">-</th>
                            <td class="col text-body">-</td>
                            <td class="col text-body">-</td>
                            <td class="col text-body">-</td>
                            <td class="col table-success text-dark">-</td>
                            <td class="col table-danger text-dark">-</td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>

</div>

@section('page-script')
@parent
<script>
    $('#gainLossBarEstAva').tooltip();
    $('#ftLcEstAvLabel').tooltip();

    registrarEventoAlteracaoCorretoraPrincipal(atualizouCorretora);

    function atualizouCorretora(corretora){
        atualizarDadosEstAva(corretora);
    }

// FUNCOES
    function atualizarDadosEstAva(contaCorretora) {
        if(contaCorretora.real_demo == 'D'){
            addTextWarning($('#corretoraNmEstAva'));
        } else {
            addTextInfo($('#corretoraNmEstAva'));
        }
        $('#corretoraNmEstAva').html(contaCorretora.corretora.nome.substring(0, 10) + " (" + contaCorretora.identificador + ")");

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.post('/dashEstatisticasAvancadas', {contaCorretoraId : contaCorretora.id}, function(data) {
            atualizarDadosCampos(data, contaCorretora);
        },
        'json' // I expect a JSON response
        );
    }

    function atualizarDadosCampos(data, corretora) {
        var est = data.estatisticasConta;
        if(!est){
            $('#gainLossBarEstAva').attr('title', 'Sem dados para exibir').tooltip('_fixTitle').tooltip('setContent');

            $('#gainBarEstAva').attr('style', 'width: 50%;');
            $('#gainBarEstAva').attr('aria-valuenow', 50);
            $('#lossBarEstAva').attr('style', 'width: 50%;');
            $('#lossBarEstAva').attr('aria-valuenow', 50);

            $('#nrOperacoesEstAva').html('-');
            $('#pontosSaldoEstAva').html('-');
            $('#medGanhoEstAva').html('-');
            $('#medPerdaEstAva').html('-');
            $('#comissoesEstAva').html('-');
            $('#fatorLucroEstAva').html('-');
            $('#melhorOperValorEstAva').html('-');
            $('#piorOperValorEstAva').html('-');
            $('#melhorOperPontosEstAva').html('-');
            $('#piorOperPontosEstAva').html('-');
            $('#durMediaEstAva').html('-');
        } else {
            var porcGains = ((est.nrGains / est.nrOperacoes)*100).toFixed(0);
            var nrLoss = est.nrOperacoes - est.nrGains;
            var porcLoss = 100 - porcGains;

            $('#gainLossBarEstAva').attr('title', 'Ganhou '+est.nrGains+' de '+est.nrOperacoes+' negociações, o que representa '+porcGains+'%. Perdeu '+nrLoss+' de '+est.nrOperacoes+' negociações, o que representa '+porcLoss+'%.')
                .tooltip('_fixTitle').tooltip('setContent');

            $('#gainBarEstAva').attr('style', 'width: '+porcGains+'%;');
            $('#gainBarEstAva').attr('aria-valuenow', porcGains);
            $('#lossBarEstAva').attr('style', 'width: '+porcLoss+'%;');
            $('#lossBarEstAva').attr('aria-valuenow', porcLoss);

            $('#nrOperacoesEstAva').html(est.nrOperacoes);
            $('#pontosSaldoEstAva').html(est.totalPontos);
            formatarDeAcordoComValor(est.totalPontos, $('#pontosSaldoEstAva'));
            $('#medGanhoEstAva').html((1*est.mediaGainPontos).toFixed(2) + ' Pontos / ' + formatarValor(est.mediaGainValor, corretora));
            $('#medPerdaEstAva').html((1*est.mediaLossPontos).toFixed(2) + ' Pontos / ' + formatarValor(est.mediaLossValor, corretora));
            $('#comissoesEstAva').html(formatarValor(est.comissoesImpostos, corretora));

            if(data.melhorOperacaoValor)
                $('#melhorOperValorEstAva').html('('+data.melhorOperacaoValor.data+') '+ formatarValor(data.melhorOperacaoValor.resultado, corretora));
            else
                $('#melhorOperValorEstAva').html('-');

            if(data.piorOperacaoValor)
                $('#piorOperValorEstAva').html('('+data.piorOperacaoValor.data+') '+ formatarValor(data.piorOperacaoValor.resultado, corretora));
            else
                $('#piorOperValorEstAva').html('-');

            if(data.melhorOperacaoPontos)
                $('#melhorOperPontosEstAva').html('('+data.melhorOperacaoPontos.data+') '+ data.melhorOperacaoPontos.pips);
            else
                $('#melhorOperPontosEstAva').html('-');

            if(data.piorOperacaoPontos)
                $('#piorOperPontosEstAva').html('('+data.piorOperacaoPontos.data+') '+ data.piorOperacaoPontos.pips);
            else
                $('#piorOperPontosEstAva').html('-');

            $('#durMediaEstAva').html( converteSegundosParaTempoComDias(est.mediaTempoOperacaoSec));

            var fatLuc = calcularFatorDeLucro(est.totalGainsValor, est.totalLossesValor);
            $('#fatorLucroEstAva').html(fatLuc);
            if(fatLuc >= 1)
                addTextSuccess($('#fatorLucroEstAva'));
            else
                addTextDanger($('#fatorLucroEstAva'));

            atualizaDadosTabelaAtivosEstAva(data.totaisPorAtivo, corretora);
        }
    }

    function atualizaDadosTabelaAtivosEstAva(dados, corretora){
        $('#tableAtivosEstAva tBody').html('');
        var first = true;
        dados.forEach(element => {
            if(first)
                $('#tableAtivosEstAva tBody').html(montaLinhaTabelaEstAva(element, corretora));
            else
                $('#tableAtivosEstAva tBody').append(montaLinhaTabelaEstAva(element, corretora));
            first = false;
        });

    }

    function montaLinhaTabelaEstAva(linha, corretora) {
        var porcGain = ((linha.nrGains/linha.nrOperacoes)*100).toFixed(0);
        var porcLoss = 100 - porcGain;

        var ln = '<tr>';
        ln += '  <td class="col text-body">'+linha.instrumento.sigla+'</td>';
        ln += '  <td class="col text-body">'+linha.nrOperacoes+'</td>';
        ln += '  <td class="col '+((linha.totalPontos*1) > 0 ? 'text-success' : 'text-danger') +'">'+linha.totalPontos+'</td>';
        ln += '  <td class="col '+((linha.totalValor*1) > 0 ? 'text-success' : 'text-danger') +'">'+formatarValor(linha.totalValor, corretora)+'</td>';
        ln += '  <td class="col table-success text-dark">'+linha.nrGains+' ('+porcGain+'%)</td>';
        ln += '  <td class="col table-danger text-dark">'+linha.nrLosses+' ('+porcLoss+'%)</td>';
        ln += '</tr>';
        return ln;
    }

    function calcularFatorDeLucro(lucroBruto, prejuizoBruto) {
        if(!lucroBruto || !prejuizoBruto)
            return '';
        if(prejuizoBruto < 0)
            prejuizoBruto = prejuizoBruto * -1;
        return (lucroBruto / prejuizoBruto).toFixed(2);
    }
//FIM FUNCOES
</script>
@stop
