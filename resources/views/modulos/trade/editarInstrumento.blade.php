@extends('layouts.app')

@section('content')
    <h1 class="text-active">Instrumento</h1>
    <hr class="bg-warning">

    {!! Form::model($instrumento, ['route' => ['instrumento.update', $instrumento->id], 'method' => 'put']) !!}
        @include('modulos.trade.templates.formInstrumento')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
