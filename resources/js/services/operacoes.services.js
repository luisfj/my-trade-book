const classes = require('./classes');

module.exports = {
    createTransferencia : (val_ticket, val_abertura, val_contratos, val_instrumento, tipo = null) => {
        var trans = new classes.Transferencia(val_ticket, val_abertura, val_contratos, val_instrumento, tipo);
        return trans;
    },

    createCabecalho : (conta, nome, currency, alavancagem, data, tipo) => {
        var head = new classes.Cabecalho(conta, nome, currency, alavancagem, data, tipo);
        return head;
    },

    createOperacao : (tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado) => {
        var operacao = new classes.Operacao(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado);
        return operacao;
    },
}
