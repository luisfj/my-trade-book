@extends('layouts.app')

@section('content')

    <h1 class="text-active">Fechamento Mês</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['fechamento.mes.select'], 'method' => 'post']) !!}
    <div class="form-group">
        {!! Form::label('mes_ano', 'Mês - Ano') !!}
        <input type="month" name="mes_ano" class="form-control">
    </div>
    {!! Form::submit('Adicionar Fechamento', ['class' => 'btn btn-success']) !!}
    <a class="btn btn-warning" href="javascript:history.back()">
        Votar
    </a>
    {!! Form::close() !!}
    <div class="mb-5"></div>
@endsection
