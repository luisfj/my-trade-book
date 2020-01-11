@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Moeda</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['moeda.create'], 'method' => 'post']) !!}
        @include('modulos.admin.templates.formMoeda')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
