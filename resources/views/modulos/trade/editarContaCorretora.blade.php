@extends('layouts.app')

@section('content')
    <h1 class="text-active">Conta Corretora</h1>
    <hr class="bg-warning">

    {!! Form::model($conta, ['route' => ['conta.corretora.update', $conta->id], 'method' => 'put']) !!}
        @include('modulos.trade.templates.formContaCorretora')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
