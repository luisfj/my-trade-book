@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">

        <div class="col-12 col-md-4 col-xl-2 marb-10 noPadding-with-lr-5 fs13">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.resumos.resumoGeral')
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 col-xl-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body d-flex flex-column">
                    @include('modulos.dashboards.resumos.estatisticasAvancadas')
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                <div class="card-body">
                    @include('modulos.dashboards.operacoes.evolucaoAnualDoSaldo')
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-7 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.operacoes.tradeATradeMensal')
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-5 marb-10 noPadding-with-lr-5">
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

@section('page-script')
@parent
<script>
Chart.plugins.register({
    afterLayout: function(chart, options) {
        if(chart.canvas.id === 'chartEvolucaoSaldoAnual'){
            if(chart.width < 300){
                chart.aspectRatio = 1.2;
            } else if(chart.width > 700){
                chart.aspectRatio = 2.2;
            } else if(chart.width > 500){
                chart.aspectRatio = 1.7;
            } else {
                chart.aspectRatio = 1.5;
            }
            chart.resize();
        } else if(chart.canvas.id === 'myChart'){
            if(chart.width < 300){
                chart.aspectRatio = 1.2;
            } else {
                chart.aspectRatio = 1.8;
            }
            chart.resize();
        } else if(chart.canvas.id === 'chartEvoMes'){
            if(chart.width < 600){
                chart.aspectRatio = 1.2;
            } else {
                chart.aspectRatio = 1.2;
            }
            chart.resize();
        } else if(chart.canvas.id === 'chartResultadosHDD' || chart.canvas.id === 'chartPontosHDD'){
            chart.aspectRatio = 1.7;
            chart.resize();
        }
    },
});
</script>
@stop
