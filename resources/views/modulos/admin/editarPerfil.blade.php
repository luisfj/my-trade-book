@extends('layouts.app')

@section('content')
    <h1 class="text-active">Perfil de Investidor</h1>
    <hr class="bg-warning">

    {!! Form::model($perfil, ['route' => ['perfil.update', $perfil->id], 'method' => 'put']) !!}
        @include('modulos.admin.templates.formPerfil')
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
