@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <h1>
                <span class="material-icons text-info icon-v-bottom" style="font-size: 50px !important;">
                    account_balance
                </span>
                <span>Capital Alocado</span>
            </h1>
        </div>
    </div>

    <hr class="bg-warning">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
            @forelse ($contasCapitalAlocado as $indexKey => $contaCapital)
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel card marb-15">
                        <div class="card-body">
                            <div class="row fs14">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label>Nome:</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 text-right fbold text-info">
                                    <label>{{ $contaCapital->nome }}</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label >Moeda:</label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 text-right ">
                                    <label>{{ $contaCapital->moeda->fullName }}</label>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <span >Em corretoras:</span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 text-right ">
                                    <label>{{ $contaCapital->saldoContasComposicaoCalculadoFormatado }}</label>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <span >Em contas externas:</span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 text-right ">
                                    <label>{{ $contaCapital->saldoTransferenciasCalculadoFormatado }}</label>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <span >TOTAL:</span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 text-right fbold {{ (($contaCapital->saldoTotalCalculado > 0) ? 'text-success' : ($contaCapital->saldoTotalCalculado < 0 ? 'text-warning' : '')) }}">
                                    <label>{{ $contaCapital->saldoTotalCalculadoFormatado }}</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="#" onclick="
                                                event.preventDefault();
                                                document.getElementById('edit-capital-form-{{ $contaCapital->id }}').submit();
                                        ">
                                        <i class="material-icons md-24 text-info">edit</i>
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 text-right">
                                    <a href="#" onclick="
                                    swal({
                                            title: 'Confirma remover o capital alocado?',
                                            buttons: {
                                                cancel: {
                                                    text: 'Cancel',
                                                    value: null,
                                                    visible: true
                                                },
                                                confirm: {
                                                    text: 'Confirmar',
                                                    value: true,
                                                    visible: true,
                                                },
                                            },
                                            icon: 'warning',
                                            closeOnClickOutside: false,
                                        }).then((result) => {
                                            if(result){
                                                event.preventDefault();
                                                document.getElementById('delete-capital-form-{{ $contaCapital->id }}').submit();
                                            }
                                        })
                                        ">
                                        <i class="material-icons md-24 text-danger">delete_outline</i>
                                    </a>
                                </div>

                                <form id="delete-capital-form-{{ $contaCapital->id }}" action="{{ route('capital.alocado.select') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="operacao" value="REMOCAO">
                                    <input type="hidden" name="capitalAlocado_id" value="{{ $contaCapital->id }}">
                                </form>
                                <form id="edit-capital-form-{{ $contaCapital->id }}" action="{{ route('capital.alocado.select') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="capitalAlocado_id" value="{{ $contaCapital->id }}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel card marb-15">
                    <div class="card-body">
                        <span>Nenhum capital alocado cadastrado!</span>
                    </div>
                </div>
            @endforelse
            </div>
        </div>
    </div>

    <div class="panel card marb-15">
        <div class="card-body">
            <form id="addContaCapAlocadoFrm" action="{{ route('capital.alocado.select') }}" method="POST" >
                @csrf
                @if($capitalAlocadoSelecionado)
                    <input type="hidden" name="capitalAlocado_id" value="{{ $capitalAlocadoSelecionado->id }}">
                @endif
                <input type="hidden" name="operacao" value="CADASTRO">

                <div class="form-group row col-sm-12" style="text-align: right;">
                    <div class="form-group row col-sm-4">
                        {!! Form::label('nome', 'Nome', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('nome', $capitalAlocadoSelecionado ? $capitalAlocadoSelecionado->nome : null,
                                    ['placeholder' => 'Nome', 'class' => 'form-control form-control-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group row col-sm-4">
                        {!! Form::label('moeda_id', 'Moeda', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('moeda_id', $moedas_lista, $capitalAlocadoSelecionado ? $capitalAlocadoSelecionado->moeda_id : null,
                                ['placeholder' => '-- Selecione a moeda --', 'class' => 'form-control form-control-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group col-sm-1" style="text-align: left !important;">
                        <button type="submit" class="btn {{ ($capitalAlocadoSelecionado ? 'btn-warning' : 'btn-success')}} btn-sm md-18" >
                            @if($capitalAlocadoSelecionado)
                                <i class="material-icons md-18">check</i>
                                Alterar
                            @else
                                <i class="material-icons md-18">check</i>
                                Adicionar
                            @endif

                        </button>
                    </div>

                    <div class="form-group col-sm-1" style="text-align: left !important;">
                        <a href="{{ route('capital.alocado.index') }}" class="btn btn-info btn-sm md-18 text-dark" >
                            <i class="material-icons md-18">add</i>
                            Novo
                        </a>
                    </div>
                </div>
            </form>

            @if($capitalAlocadoSelecionado)
                @include('modulos.capitalAlocado.templates.detalhesCapitalAlocado')
            @endif
        </div>
    </div>
@endsection
