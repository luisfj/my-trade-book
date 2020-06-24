@extends('layouts.app')

@section('content')
<h1 class="text-active"><h1>
    <span class="material-icons text-info icon-v-bottom" style="font-size: 50px !important;">
        show_chart
    </span>
    <span>Evolução Capital</span>
</h1>

<hr class="bg-warning">
    <div class="row justify-content-center">

        <div class="col-12 col-md-12 col-xl-12 marb-10 noPadding-with-lr-5 fs13">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @include('modulos.dashboards.evolucaoPercentual.resumoGeral')
                </div>
            </div>
        </div>
<!--
        <div class="col-12 col-md-4 col-xl-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body d-flex flex-column">
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-7 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-5 marb-10 noPadding-with-lr-5">
            <div class="card marb-5 altura-min-100p">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                </div>
            </div>
        </div>
    -->
    </div>

@endsection

@section('page-script')
@parent
<script>
</script>
@stop
