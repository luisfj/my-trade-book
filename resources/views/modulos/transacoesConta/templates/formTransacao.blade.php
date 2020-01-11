<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>
            <i class="material-icons text-success" id="iconDeposito">save_alt</i>
            <i class="material-icons text-warning" id="iconSaque">reply_all</i>
            Dados
        </h4>
        <hr>

        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', ['D' => 'Depósito', 'S' => 'Saque/Retirada', 'T' => 'Importação'], $transacao->tipo ?? 'D',
                    ['placeholder' => '-- Tipo --', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('ticket', 'Ticket') !!}
            {!! Form::text('ticket', null, ['placeholder' => 'Ticket', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('data', 'Data') !!}
            {!! Form::datetime('data', null, ['placeholder' => 'Data','class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('codigo_transacao', 'Cod. Transação') !!}
            {!! Form::text('codigo_transacao', null, ['placeholder' => 'Cod. Transação', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('valor', 'Valor') !!}
            {!! Form::text('valor', null, ['placeholder' => 'Valor', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('conta_id', 'Conta') !!}
            {!! Form::select('conta_id', $conta_lista, null, ['placeholder' => '-- Selecione a Conta --', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('contraparte_id', 'Contraparte') !!}
            {!! Form::select('contraparte_id', $contraparte_lista, null, ['placeholder' => '-- Selecione a Contraparte --', 'class' => 'form-control']) !!}
        </div>
    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a class="btn btn-warning" href="javascript:history.back()">
    Votar para Listagem
</a>

@section('page-script')
<script>
    $(document).ready(function(){
        loadPage();

        function loadPage(){
            $("select option[value*='T']").prop('disabled',true);
            $("select option[value*='D']").addClass('text-success');
            $("select option[value*='S']").addClass('text-warning');
            changeTipo();
        }

        function changeTipo(){
            if($('#tipo').val() == 'D') {//se Deposito
                $('#iconDeposito').removeClass('hidde-me');
                $('#iconSaque').addClass('hidde-me');
                $('#tipo').addClass('text-success');
                $('#tipo').removeClass('text-warning');
            } else if($('#tipo').val() == 'S'){ //se Saque
                $('#iconDeposito').addClass('hidde-me');
                $('#iconSaque').removeClass('hidde-me');
                $('#tipo').removeClass('text-success');
                $('#tipo').addClass('text-warning');
            } else { // se transação, eu desabilito todos os campos que não devem ser editados
                $('#iconDeposito').removeClass('hidde-me');
                $('#iconSaque').removeClass('hidde-me');
                $('#tipo').prop('disabled', true);
                $('#ticket').prop('disabled', true);
                $('#data').prop('disabled', true);
                $('#codigo_transacao').prop('disabled', true);
                $('#valor').prop('disabled', true);
                $('#conta_id').prop('disabled', true);

                if($('#valor').val() < 0){
                    $('#iconDeposito').addClass('hidde-me');
                    $('#tipo').addClass('text-warning');
                }else {
                    $('#iconSaque').addClass('hidde-me');
                    $('#tipo').addClass('text-success');
                }
            }
        }

        $('#tipo').on('change', changeTipo);


    });
</script>
@endsection
