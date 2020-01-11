<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>
            Dados
        </h4>
        <hr>

        <div class="form-group row " style="text-align: right;">
            <div class="form-group row col-sm-4">
                {!! Form::label('mes_ano', 'Mês - Ano', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    <input readonly type="month" name="mes_ano" value="{!! $mes_ano !!}" class="form-control form-control-sm">
                </div>
            </div>

            <div class="form-group row col-sm-4">
                <a class="btn btn-warning btn-sm md-18" href="javascript:history.back()">
                    <i class="material-icons md-18">search</i>
                    Alterar Mês de Fechamento
                </a>
            </div>

            <div class="form-group row col-sm-4">
                {!! Form::label('saldo_anterior', 'Saldo Anterior', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    <input type="text" readonly name="saldo_anterior" id="saldo_anterior" class="form-control form-control-sm decimal-mask" value="{{ $saldo_anterior }}">
                </div>
            </div>
        </div>
        <div class="form-group row" style="text-align: right;">
            <div class="form-group row col-sm-4">
                {!! Form::label('saldo_mes', 'Saldo Mês', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    <input type="text" readonly name="saldo_mes" id="saldo_mes" class="form-control form-control-sm decimal-mask">
                </div>
            </div>
        </div>

        <div class="form-group row" style="text-align: right;">
            <div class="form-group row col-sm-4">
                {!! Form::label('conta_fechamento_id', 'Conta', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                   {!! Form::select('conta_fechamento_id', $contas_fechamento, $fechamento->conta_fechamento_id ?? null,
                                    ['placeholder' => '-- Conta --', 'class' => 'form-control form-control-sm'])  !!}
                </div>
            </div>

            <div class="form-group row col-sm-5">
                <button type="button" name="add" id="add" class="btn btn-info btn-sm md-18">
                    <i class="material-icons md-18">add</i>
                    Add Conta
                </button>
            </div>
        </div>

        <table id="fechamentos" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="30%">Conta</th>
                    <th width="20%">Receitas</th>
                    <th width="20%">Despesas</th>
                    <th width="20%">Resultado</th>
                    <th width="10%">Ações</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}


@section('page-script')
<script>
    $(window).on('load', function (e) {
        $("input[name*='receitas']").inputmask('valor');
        $("input[name*='despesas']").inputmask('valor');
        $("input[name*='resultados']").inputmask('valor');
    })

    $(document).ready(function(){
        var row_inc = 0;

        loadData();

        function loadData(){
            let a = {!! $fechamentos_mes !!};
            $.each(a, function (index, value) {
                dynamic_field(value.conta_fechamento_id, value.conta_fechamento.nome, value.receitas, value.despesas, value.resultado_mes, value.id)
            });
            atualizarTotalFechamento();
        }

        function dynamic_field(contaid, contanome, receita, despesa, resultado, fech_id){
            var html = '<tr id="fech_'+row_inc+'">';
            html += '<td> <input type="hidden" name="fechamentosid[]" class="form-control form-control-sm" value="'+ (fech_id != null ? fech_id : '') + '" /> '
                        +'<input type="hidden" name="contasid[]" class="form-control form-control-sm" value="'+ (contaid != null ? contaid : '') + '" />' + contanome + '</td>';
            html += '<td> <input type="text" name="receitas[]" class="form-control form-control-sm" value="'+ (receita != null ? receita : '') + '" /> </td>';
            html += '<td> <input type="text" name="despesas[]" class="form-control form-control-sm" value="'+ (despesa != null ? despesa : '') + '" /> </td>';
            html += '<td> <input type="text" name="resultados[]" class="form-control form-control-sm" value="'+ (resultado != null ? resultado : '') + '" /> </td>';
            html += '<td> <button type="button" name="remove" class="btn btn-sm btn-danger">Remover</button> </td></tr>';

            $('tbody').append(html);

            adicionadaConta(contaid);

            row_inc++;
        }

        function adicionarMascaraValor(){
            $('#fech_'+(row_inc-1)).find("input[name*='receitas']").inputmask('valor');
            $('#fech_'+(row_inc-1)).find("input[name*='despesas']").inputmask('valor');
            $('#fech_'+(row_inc-1)).find("input[name*='resultados']").inputmask('valor');
        }

        function adicionadaConta(idConta){
            $("select option[value*='"+idConta+"']").prop('disabled', true);
            $("select option[value*='"+idConta+"']").addClass('text-success');
        }

        function removidaConta(idConta){
            $("select option[value*='"+idConta+"']").prop('disabled', false);
            $("select option[value*='"+idConta+"']").removeClass('text-success');

            atualizarTotalFechamento();
        }

        function atualizarTotalFechamento(){
            let total = 0;
            $("input[name*='resultados']").each(
                function(index){
                    let valor = $(this).val();
                    if(!valor)
                        valor = 0;
                    total += parseFloat(valor);
                }
            );
            $('#saldo_mes').val(total);
        }

        $('#add').click(function(){
            let contaid   = $('#conta_fechamento_id').children("option:selected").val();
            let contanome = $('#conta_fechamento_id').children("option:selected").text();
            if(contaid != ''){
                dynamic_field(contaid, contanome, null, null, null, null)
                $('#conta_fechamento_id').val("");
                adicionarMascaraValor();
            }
        });

        $(document).on('click', "button[name*='remove']", function(elemet){
            let trparent = elemet.target.parentElement.parentElement;
            trparent.parentNode.removeChild(trparent);
            let idconta = $(trparent).find("input[name*='contasid']").val();
            removidaConta(idconta);
        });

        $(document).on('change', "input[name*='receitas']", function(elemet){
            let rec = elemet.target.value;
            let trparent = elemet.target.parentElement.parentElement;
            let res = $(trparent).find("input[name*='resultados']");
            let desp = $(trparent).find("input[name*='despesas']");

            if(!rec)
                rec = 0;
            if(desp.val() && desp.val() != 0)
                res.val((rec - parseFloat(desp.val())));
            else
                if(res.val() && res.val() != 0)
                    desp.val((rec - parseFloat(res.val())));
                else
                    res.val(rec);

            atualizarTotalFechamento();
        });

        $(document).on('change', "input[name*='despesas']", function(elemet){
            let desp = elemet.target.value;
            let trparent = elemet.target.parentElement.parentElement;
            let res = $(trparent).find("input[name*='resultados']");
            let rec = $(trparent).find("input[name*='receitas']");

            if(!desp)
                desp = 0;

            if(rec.val() && rec.val() != 0)
                res.val((parseFloat(rec.val()) - desp));
            else
                if(res.val() && res.val() != 0)
                    rec.val((desp + parseFloat(res.val())));
                else
                    res.val(-desp);

            atualizarTotalFechamento();
        });

        $(document).on('change', "input[name*='resultados']", function(elemet){
            let res = parseFloat(elemet.target.value);
            let trparent = elemet.target.parentElement.parentElement;
            let desp = $(trparent).find("input[name*='despesas']");
            let rec = $(trparent).find("input[name*='receitas']");

            if(!res)
                res = 0;

            if(rec.val() && rec.val() != 0)
                desp.val((parseFloat(rec.val()) - res));
            else
                if(desp.val() && desp.val() != 0)
                    rec.val((res + parseFloat(desp.val())));
                else
                    rec.val(res);

            atualizarTotalFechamento();
        });

    });
</script>
@endsection
