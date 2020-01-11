@extends('layouts.app')

@section('content')
    <h1 class="text-active">Moeda</h1>
    <hr class="bg-warning">

    {!! Form::model($moeda, ['route' => ['moeda.update', $moeda->id], 'method' => 'put']) !!}
        @include('modulos.admin.templates.formMoeda')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
