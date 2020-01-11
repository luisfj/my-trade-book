@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Conta Corretora</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['conta.corretora.create'], 'method' => 'post']) !!}
        @include('modulos.trade.templates.formContaCorretora')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
