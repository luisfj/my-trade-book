@extends('layouts.app')

@section('content')
    @if(session('success'))
    <div class="alert alert-success">
        <b>{{ session('success')['messages'] }}</b>
    </div>
    @endif

    <h1 class="text-active">Profile</h1>
    <hr class="bg-warning">

    {!! Form::model($profile, ['route' => ['profile.update'], 'method' => 'put']) !!}
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 card border-dark mb-3" style="padding-top:15px;">
                <h4>Dados</h4>
                <hr>
                <div class="form-group">
                    {!! Form::label('perfil_investidor_id', 'Perfil') !!}
                    {!! Form::select('perfil_investidor_id', $select_list_perfil, null, ['placeholder' => '-- Selecione o perfil --', 'class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('sexo', 'Genero') !!}
                    {!! Form::select('sexo', ['M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro', 'N' => 'Prefiro não informar'], null, ['placeholder' => '-- Genero --', 'class' => 'form-control'])  !!}
                </div>

                <div class="form-group">
                    {!! Form::label('nome_completo', 'Nome completo') !!}
                    {!! Form::text('nome_completo', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('nascimento', 'Data Nascimento') !!}
                    {!! Form::date('nascimento', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('cpf', 'CPF') !!}
                    {!! Form::text('cpf', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('inicio_mercado', 'Data de Inicio no Mercado') !!}
                    {!! Form::date('inicio_mercado', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 card border-dark mb-3" style="padding-top:15px;">
                <h4>Endereço/Contato</h4>
                <hr>

                <div class="form-group">
                    {!! Form::label('telefone', 'Telefone') !!}
                    {!! Form::text('telefone', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('cep', 'Cep') !!}
                    {!! Form::text('cep', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('pais', 'País') !!}
                    {!! Form::text('pais', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('estado', 'Estado') !!}
                    {!! Form::text('estado', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('cidade', 'Cidade') !!}
                    {!! Form::text('cidade', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 card border-dark mb-3" style="padding-top:15px;">
                <h4>Social</h4>
                <hr>

                <div class="form-group">
                    {!! Form::label('site', 'Site') !!}
                    {!! Form::text('site', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('instagram', 'Instagram') !!}
                    {!! Form::text('instagram', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('facebook', 'Facebook') !!}
                    {!! Form::text('facebook', null, ['class' => 'form-control']) !!}
                </div>

            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 card border-dark mb-3" style="padding-top:15px;">
                <h4>Sobre</h4>
                <hr>

                <div class="form-group">
                    {!! Form::label('sobre_mim', 'Quem sou...') !!}
                    {!! Form::text('sobre_mim', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
    {!! Form::close() !!}
        <div class="mb-5"></div>
@endsection
