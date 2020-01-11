@extends('layouts.app')

@section('content')
    <h1 class="text-active">Conta Fechamento</h1>
    <hr class="bg-warning">

    {!! Form::model($conta, ['route' => ['conta.fechamento.update', $conta->id], 'method' => 'put']) !!}
        @include('modulos.balancoMensal.templates.formContaFechamento')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection

