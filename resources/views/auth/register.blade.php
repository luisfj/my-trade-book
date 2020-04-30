@extends('layouts.app')

@section('content')
<div class="container">
    <div id="registro" class="row justify-content-center hidde-me">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registrar</div>

                <div class="card-body">
                    <div class="form-group row mb-0" style="padding-bottom: 15px !important">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('login.google') }}" class="btn btn-light" style="padding:10px 20px !important; color: #272B30 !important;">
                                <img src="{{ asset('img/google.png') }}" alt="..." class="img-fluid rounded" width="30px">
                                Registrar com Google
                            </a>
                            <a href="{{ route('login.facebook') }}" class="btn" style="background-color: #2d4486; color: white; padding:8px 20px !important">
                                <img src="{{ asset('img/facebookw.png') }}" alt="..." class="img-fluid rounded" width="30px">
                                Registrar com Facebook
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar Senha</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="termos" class="row justify-content-center" style="">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <div class="form-group row mb-0" style="padding-bottom: 15px !important">
                        <div class="col-md-12" style="overflow:auto; display: block; height:550px;">
                            @include('termosTemplate')
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-info" id="btnAceitarTermos">
                                Aceito os Termos de Uso e Políticas de Privacidade
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('page-script')
<script>
    var tTimeout;
    var rTimeout;
    var elmen;

    $(document).ready(function(){
        $('#btnAceitarTermos').on('click', function () {
            $('#termos').addClass('hidde-me');
            $('#registro').removeClass('hidde-me');
        });
    });

</script>
@endsection
