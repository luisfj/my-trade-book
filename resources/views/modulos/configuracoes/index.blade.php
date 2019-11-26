@extends('layouts.app')

@section('content')
    <h1 class="text-active">Configurações do Usuário</h1>
    <hr class="bg-warning">

    @if(Auth::user()->is_admin())
    {!! Form::model($config, ['route' => ['configuracoes.update'], 'method' => 'put']) !!}
        <div class="form-group">
            {!! Form::label('descricao_verificar_mensagem', 'Resposta quando verificar mensagem:') !!}

            {!! Form::text('descricao_verificar_mensagem', null,['class' => 'form-control']) !!}
        </div>
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}

    @endif
@endsection
