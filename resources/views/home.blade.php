@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">

        <div class="col-md-2 marb-10 noPadding-with-lr-5 fs13">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.resumos.resumoGeral')
                </div>
            </div>
        </div>

        <div class="col-md-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.resumos.estatisticasAvancadas')
                </div>
            </div>
        </div>

        <div class="col-md-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                <div class="card-body">
                    @include('modulos.dashboards.operacoes.evolucaoAnualDoSaldo')
                </div>
            </div>
        </div>

        <div class="col-md-7 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.operacoes.tradeATradeMensal')
                </div>
            </div>
        </div>

        <div class="col-md-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.operacoes.evolucaoMensalDoSaldo')
                </div>
            </div>
        </div>

        @include('modulos.dashboards.operacoes.resultadosDiasDaSemana')
        @include('modulos.dashboards.operacoes.resultadosPorSemanaDoMes')
        @include('modulos.dashboards.operacoes.resultadosPorHoraDoDia')

    </div>

@endsection
