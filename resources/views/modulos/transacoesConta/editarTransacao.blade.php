@extends('layouts.app')

@section('content')
    <h1 class="text-active">Transação</h1>
    <hr class="bg-warning">

    {!! Form::model($transacao, ['route' => ['transacao.update', $transacao->id], 'method' => 'put']) !!}
        @include('modulos.transacoesConta.templates.formTransacao')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection

