module.exports = {

    Transferencia: class {
        constructor(ticket, data, codigo, valor) {
            this.tipo = 'balance';
            this.ticket = ticket;
            this.data = data;
            this.codigo = codigo;
            this.valor = valor;
        }
    },

    Cabecalho: class {
        constructor(conta, nome, currency, alavancagem, data) {
            this.tipo = 'head';
            this.conta = conta;
            this.nome = nome;
            this.currency = currency;
            this.alavancagem = alavancagem;
            this.data = data;
            this.formataDados();
        }
        formataDados() {
            this.conta = this.conta.substr(this.conta.indexOf(':') + 2);
            this.nome = this.nome.substr(this.nome.indexOf(':') + 2);
            this.currency = this.currency.substr(this.currency.indexOf(':') + 2);
            this.alavancagem = this.alavancagem.substr(this.alavancagem.indexOf(':') + 2);
        };
    },
    Operacao: class {
        constructor(tipo, ticket, abertura, contratos, instrumento, preco_entrada, fechamento, preco_saida, comissao, impostos, swap, resultado) {
            this.tipo = tipo;
            this.ticket = ticket;
            this.abertura = abertura;
            this.contratos = contratos;
            this.instrumento = instrumento;
            this.preco_entrada = preco_entrada;
            this.fechamento = fechamento;
            this.preco_saida = preco_saida;
            this.comissao = comissao;
            this.impostos = impostos;
            this.swap = swap;
            this.resultado_bruto = (parseFloat(resultado) + parseFloat(swap) + parseFloat(impostos) + parseFloat(comissao));
            this.resultado = resultado;
            this.pontos = 0;
            this.tempo_operacao_dias = 0;
            this.tempo_operacao_horas = 0;
            this.calcularValores();
        }
        calcularValores () {
            if (this.fechamento) {
                if (this.tipo == 'sell') {
                    this.pontos = parseInt(this.preco_entrada.replace('.', '')) - parseInt(this.preco_saida.replace('.', ''));
                }
                else {
                    this.pontos = parseInt(this.preco_saida.replace('.', '')) - parseInt(this.preco_entrada.replace('.', ''));
                }

                var diff = Math.abs(new Date(this.fechamento) - new Date(this.abertura));
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
        get_tempo_operacao_formatado() {
            if (this.tempo_operacao_dias) {
                return this.tempo_operacao_dias + "D " + this.tempo_operacao_horas;
            }
            else {
                return this.tempo_operacao_horas;
            }
        };
    },
}

function formataHoraMinuto(val){
    if(val < 10)
        return '0'+val;
    else if(val == 0)
        return '00';
    else
        return ''+val;
}
