<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12" style="padding-top:15px;">
        <h4>
            <i class="material-icons text-success" id="iconDeposito">save_alt</i>
            <i class="material-icons text-warning" id="iconSaque">reply_all</i>
            <input type="hidden" id="conta_id" name="conta_id" >
            <label id="corretora_nm" class="text-info">-</label>
        </h4>
        <hr>

        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            <select class="custom-select" id="tipo" name="tipo">
                <option selected="selected" value="D" class="text-success">Depósito</option>
                <option value="S" class="text-warning">Saque/Retirada</option>
            </select>
        </div>

        <div class="form-group">
            {!! Form::label('data', 'Data') !!}
            <input type="datetime-local" class="form-control" id="data" name="data">
        </div>

        <div class="form-group">
            {!! Form::label('ticket', 'Ticket') !!}
            {!! Form::text('ticket', null, ['placeholder' => 'Ticket', 'class' => 'form-control']) !!}
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
            {!! Form::label('capExt', 'Origem ou destino é da conta de capital alocado externo?') !!}
            {!! Form::checkbox('capExt', null, ['class' => 'form-control']) !!}
        </div>

    </div>
</div>
