<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>Dados</h4>
        <hr>

        <div class="form-group">
            {!! Form::label('nome', 'Nome') !!}
            {!! Form::text('nome', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('descricao', 'Descricao') !!}
            {!! Form::text('descricao', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a class="btn btn-warning" href="{{ route('perfil.index') }}">
    Votar para Listagem
</a>