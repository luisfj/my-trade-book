@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Operação</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['operacao.create'], 'method' => 'post']) !!}
        @include('modulos.trade.templates.formOperacao')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
