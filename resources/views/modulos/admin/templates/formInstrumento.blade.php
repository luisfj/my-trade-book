<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <div class="form-group">
            {!! Form::label('nome', 'Nome do Instrumento') !!}
            {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('sigla', 'Sigla do Instrumento') !!}
            {!! Form::text('sigla', null, ['placeholder' => 'Sigla', 'class' => 'form-control']) !!}
        </div>

    </div>
</div>
