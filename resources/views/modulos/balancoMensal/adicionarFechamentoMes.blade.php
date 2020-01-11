@extends('layouts.app')

@section('content')

    <h1 class="text-active">Fechamento Mês</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['fechamento.mes.create'], 'method' => 'put']) !!}
        @include('modulos.balancoMensal.templates.formFechamentoMes')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
