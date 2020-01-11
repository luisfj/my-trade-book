@extends('layouts.app')

@section('content')
    <h1 class="text-active">Operação</h1>
    <hr class="bg-warning">

    {!! Form::model($operacao, ['route' => ['operacao.update', $operacao->id], 'method' => 'put']) !!}
        @include('modulos.trade.templates.formOperacao')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
