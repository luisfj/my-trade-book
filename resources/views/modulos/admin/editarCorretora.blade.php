@extends('layouts.app')

@section('content')
    <h1 class="text-active">Corretora</h1>
    <hr class="bg-warning">

    {!! Form::model($corretora, ['route' => ['corretora.update', $corretora->id], 'method' => 'put']) !!}
        @include('modulos.admin.templates.formCorretora')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
