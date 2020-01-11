@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Corretora</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['corretora.create'], 'method' => 'post']) !!}
        @include('modulos.admin.templates.formCorretora')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
