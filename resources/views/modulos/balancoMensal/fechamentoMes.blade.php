@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Fechamento Mês</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="{{ route('fechamento.mes.add') }}">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>
    <hr class="bg-warning">

    <div class="mb-5"></div>

    <fechamento-grafico-barras></fechamento-grafico-barras>

    <hr style="background-color: gray" />

    <evolucao-saldo-fechamento-grafico></evolucao-saldo-fechamento-grafico>

    <hr style="background-color: gray" />

    <fechamento-mensal-grid :contas="{{ $contas }}"></fechamento-mensal-grid>

@endsection
