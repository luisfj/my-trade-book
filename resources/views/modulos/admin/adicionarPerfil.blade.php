@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Perfil de Investidor</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['perfil.create'], 'method' => 'post']) !!}
        @include('modulos.admin.templates.formPerfil')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
