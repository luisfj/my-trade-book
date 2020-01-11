@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Instrumento</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['instrumento.create'], 'method' => 'post']) !!}
        @include('modulos.trade.templates.formInstrumento')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
