const classes = require('./classes');

module.exports = {
    createTransferencia : (val_ticket, val_abertura, val_contratos, val_instrumento) => {
        var trans = new classes.Transferencia(val_ticket, val_abertura, val_contratos, val_instrumento);
        return trans;
    },

    createCabecalho : (conta, nome, currency, alavancagem, data) => {
        var head = new classes.Cabecalho(conta, nome, currency, alavancagem, data);
        return head;
    },

    createOperacao : (tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado) => {
        var operacao = new classes.Operacao(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado);
        return operacao;
    },
}
