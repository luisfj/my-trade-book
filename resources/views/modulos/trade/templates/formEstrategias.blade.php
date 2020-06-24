<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12" style="padding-top:15px;">

        <div class="form-group">
            {!! Form::label('nome', 'Nome') !!}
            {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'form-control', 'style' => 'text-transform: uppercase']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('descricao', 'Descrição') !!}
            {!! Form::text('descricao', null, ['placeholder' => 'Descrição', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('ativa', 'Ativa') !!}
            {!! Form::checkbox('ativa', null, ['class' => 'form-control']) !!}
        </div>

    </div>
</div>
