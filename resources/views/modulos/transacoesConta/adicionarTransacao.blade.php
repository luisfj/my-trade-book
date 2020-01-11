@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Transação</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['transacao.create'], 'method' => 'post']) !!}
        @include('modulos.transacoesConta.templates.formTransacao')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
