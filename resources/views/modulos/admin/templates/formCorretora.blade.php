<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">

        <div class="form-group">
            {!! Form::label('nome', 'Nome') !!}
            {!! Form::text('nome', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('uf', 'UF') !!}
            {!! Form::text('uf', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('moeda_id', 'Moeda') !!}
            <select class="custom-select" id="moeda_id" name="moeda_id">
                <option selected="selected" value="">-- Selecione a moeda --</option>
            </select>
        </div>
    </div>
</div>
