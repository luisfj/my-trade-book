const service = require('./operacoes.services');

export const isMt4File = (html) => {
    var meta = $(html).filter('meta[name=generator]');
    return $(meta).attr('content').includes('MetaQuotes');
}

export const importarArquivo = (html, header, closedTrades, openTrades, transferencias) => {
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

        if(posicao == 'HEAD'){// é o cabeçalho
            var head = service.createCabecalho(val_ticket, val_abertura, val_tipo, val_contratos, val_instrumento, 'MT4');
            header.push(head);
        } else if(posicao == 'CLOSED') { //são operações fechadas
            if(val_tipo == 'balance'){
                var trans = service.createTransferencia(val_ticket, val_abertura, val_contratos, val_instrumento);
                transferencias.push(trans);
            } else
                if(val_tipo == 'sell' || val_tipo == 'buy'){
                    var trade = service.createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                                    val_entrada, val_fechamento, val_saida, val_comissao, val_impostos, val_swap, val_resultado);
                    closedTrades.push(trade);
                }
        } else if(posicao == 'OPEN'){//operações abertas
            if(val_tipo == 'sell' || val_tipo == 'buy'){
                var trade = service.createOperacao(val_tipo, val_ticket, val_abertura, val_contratos, val_instrumento,
                                val_entrada, val_fechamento, val_saida, val_comissao, val_impostos, val_swap, val_resultado);
                openTrades.push(trade);
            }
        }
    });
    return corretora;
};
