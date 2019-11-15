@extends('layouts.app')

@section('content')
    @if(session('success'))
    <div class="alert alert-success">
        <b>{{ session('success')['messages'] }}</b>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        <b>{{ session('error')['messages'] }}</b>
    </div>
    @endif

    <h1 class="text-active">Perfil de Investidor</h1>
    <hr class="bg-warning">

    {!! Form::model($perfil, ['route' => ['perfil.update', $perfil->id], 'method' => 'put']) !!}
        @include('modulos.admin.templates.formPerfil')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
