const service = require('./operacoes.services');

export const isMt5File = (html) => {
    var meta = $(html).filter('meta[name=generator]');
    if($(meta).attr('content').includes('client terminal')){
        var link = $(html).filter('link[rel=help]');
        return $(link).attr('href').includes('metaquotes')
    }
    return false;
}

export const importarArquivo = (html, header, closedTrades, openTrades, transferencias) => {
    var corretora = ''
    var conta = '';
    var nomeConta = '';
    var data = '';

    var posicao = 'HEAD';
    var tipo = '';//primeiro fazendo esse: 'Relatório do Histórico de Negociação' depois qdo fechar posições ver e fazer 'Relatório da Conta de Negociação '

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
            if($(ths[0]).text().includes('Nome')){
                nomeConta = $(ths[1]).text();
            } else if($(ths[0]).text().includes('Conta')){
                conta = $(ths[1]).text();
            } else if($(ths[0]).text().includes('Corretora')){
                corretora = $(ths[1]).text();
            } else if($(ths[0]).text().includes('Data')){
                data = $(ths[1]).text();
                var head = service.createCabecalho(conta, nomeConta, null, null, data, 'MT5');
                header.push(head);
            }
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
                var trans = service.createTransferencia(val_ticket, val_abertura, val_swap, val_saida, val_tipo);
                transferencias.push(trans);
            }
        } else if(posicao == 'Posições'){//operações fechadas
            if(val_tipo == 'buy' || val_tipo == 'sell'){
                var trade = service.createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                    val_entrada, val_fechamento, val_saida, val_comissao, null, val_swap, val_resultado);
                closedTrades.push(trade);
            }
        } else if(posicao == 'Posições Abertas'){//operações abertas
            if(val_tipo == 'buy' || val_tipo == 'sell'){
                var trade = service.createOperacao(val_tipo, val_ticket, val_abertura, val_contratos_aber, val_instrumento,
                    val_contratos, null, null, null, null, null, null);
                openTrades.push(trade);
            }
        }
    });
    return corretora;
};
