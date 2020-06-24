<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12" style="padding-top:15px;">
        <h4>
            <i class="material-icons text-success" id="iconDeposito">save_alt</i>
            <i class="material-icons text-warning" id="iconSaque">reply_all</i>
            <input type="hidden" id="capitalAlocado_id" name="capitalAlocado_id" >
            <input type="hidden" id="depositoEmConta_id" name="depositoEmConta_id" >
            <label class="text-info">Capital Alocado</label>
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
            {!! Form::label('valor', 'Valor') !!}
            {!! Form::text('valor', null, ['placeholder' => 'Valor', 'class' => 'form-control decimal-mask']) !!}
        </div>

    </div>
</div>
