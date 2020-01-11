@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Conta Fechamento</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['conta.fechamento.create'], 'method' => 'post']) !!}
        @include('modulos.balancoMensal.templates.formContaFechamento')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
