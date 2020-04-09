var eventReg = [];
function registrarEventoAlteracaoCorretoraPrincipal(evento){
    if(evento && typeof evento == "function"){
        eventReg[eventReg.length] = evento;
    }
}

function callEventosRegitradosAlteracaoCorretora(corretora) {
    if(eventReg.length > 0){
        for(var i = 0; i < eventReg.length; i++){
            eventReg[i](corretora);
        }
    }
}

function toValidDate(data) {
    if(!data || !(data+'').includes('/')) return new Date(data);

    let ar = data.split(' ');
    if(ar.length > 0){
        let dtArs = ar[0].split('/');
        return new Date(dtArs[2] + '-' + dtArs[1] + '-' + dtArs[0] + ' ' + ar[1]);
    }
    return new Date(data);
}

function textToFloat(valor) {
    if(!valor)
        return parseFloat('0');
    if((valor+'').includes('\,')){
        valor = (valor+'').replace('\.', '').replace('\,', '\.')
    }
    return parseFloat(valor);
}

function replaceVigulaPorPontoValor(valor) {
    if(!valor) return valor;
    if( (valor+'').includes('\,') ){
        return (valor+'').replace(/\./gi,'').replace(/\,/gi,'\.')
    }
}

function formatarDataParaSalvar(data){
    if(!data) return null;
    if((data+'').includes('/')){
        return $.format.date(toValidDate(data), 'yyyy.MM.dd HH:mm:ss');
    }
    return data;
}

function formatarDataHora(data){
    if(!data)
        return '';
    if((data+'').includes('.'))
        data = data.replace(/\./gi,'-');
    return $.format.date(data, 'dd/MM/yyyy HH:mm');
}

function formatarDataHoraSegundos(data){
    if(!data)
        return '';
    if((data+'').includes('.'))
        data = data.replace(/\./gi,'-');
    return $.format.date(data, 'dd/MM/yyyy HH:mm:ss');
}

function formatarData(data){
    if(!data)
        return '';
    if((data+'').includes('.'))
        data = data.replace(/\./gi,'-');
    return $.format.date(data, 'dd/MM/yyyy');
}

function formatarValor(valor, contaCorretora) {
    if(!valor && valor != 0)
        return '';
    var curr = (contaCorretora && contaCorretora.moeda && contaCorretora.moeda.sigla ? contaCorretora.moeda.sigla : 'BRL');

    return (valor * 1).toLocaleString('pt-BR', {
        style: 'currency',
        currency: curr,
    });
}

function converteSegundosParaTempoComDias(segundos) {
    if(!segundos)
        return "";

    var sec_num = parseInt(segundos, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    var days = parseInt(hours / 24);
    if(days > 0)
        hours = hours % 24;
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return (days ? days + 'd ' : '') + hours+':'+minutes+':'+seconds;
}

function converteSegundosParaTempoSemDias(segundos) {
    if(!segundos)
        return "";

    var sec_num = parseInt(segundos, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
}
function formatarDeAcordoComValor(valor, elemento) {
    limparTextClass(elemento);
    if(valor > 0)
        addTextSuccess(elemento);
    if(valor < 0)
        addTextDanger(elemento);
}
function addTextInfo(elemento) {
    limparTextClass(elemento);
    elemento.addClass('text-info');
}
function addTextWarning(elemento) {
    limparTextClass(elemento);
    elemento.addClass('text-warning');
}
function addTextSuccess(elemento) {
    limparTextClass(elemento);
    elemento.addClass('text-success');
}
function addTextDanger(elemento) {
    limparTextClass(elemento);
    elemento.addClass('text-danger');
}
function limparTextClass(elemento) {
    elemento.removeClass('text-success');
    elemento.removeClass('text-info');
    elemento.removeClass('text-danger');
    elemento.removeClass('text-warning');
}
