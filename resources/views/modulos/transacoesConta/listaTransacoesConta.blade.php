@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <h1>
                <span class="material-icons text-success icon-v-bottom" style="font-size: 50px !important;">
                    attach_money
                </span>
                <span>Depósitos e Saques</span>
            </h1>
        </div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="#"
                data-conta_id="{{$conta_id}}" data-toggle="modal" data-target="#addModal">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>

    <hr class="bg-warning">
    <form method="POST" class="form-horizontal" action="{{ route('transacoes.filter') }}">
        @csrf
        <div class="form-group row col-sm-12" style="text-align: right;">
            <div class="form-group row col-sm-4">
                {!! Form::label('conta_id', 'Conta', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-10">

                    {!! Form::select('conta_id', $conta_lista, $conta_id,
                        ['placeholder' => '-- Selecione a conta --', 'class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group row col-sm-4">
                {!! Form::label('data_inicial', 'Data Inicial', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    {!! Form::date('data_inicial', $data_inicial, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group row col-sm-4">
                {!! Form::label('data_final', 'Data Final', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    {!! Form::date('data_final', $data_final, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group col-sm-2">
                <button type="submit" class="btn btn-warning btn-sm md-18" style="width: 100%">
                    <i class="material-icons md-18">search</i>
                    Filtrar
                </button>
            </div>
        </div>
    </form>

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">Tipo</th>
            <th scope="col">Data</th>
            <th scope="col" class="show-over-500">Conta</th>
            <th scope="col" class="show-over-500">Ticket</th>
            <th scope="col" class="show-over-500">Código da Transacao</th>
            <th scope="col">Valor</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($transacoes as $indexKey => $transacao)
                    <tr class="table-primary {{ ($transacao->tipo == 'D') ? 'text-success' : (($transacao->tipo == 'S') ? 'text-warning' : '') }}">
                        <th scope="row">
                            @if($transacao->tipo == 'D')
                                <i class="material-icons md-18">save_alt</i>
                                Depósito
                            @elseif($transacao->tipo == 'S')
                                <i class="material-icons md-18 text-warning">reply_all</i>
                                Saque
                            @endif
                        </th>
                        <td>{{ date('d/m/Y H:i:s', strtotime($transacao->data)) }}</td>
                        <td class="show-over-500">{{ Str::limit($transacao->conta->pluck_name, 80) }}</td>
                        <td class="show-over-500">{{ $transacao->ticket }}</td>
                        <td class="show-over-500">{{ Str::limit($transacao->codigo_transacao, 16) }}</td>
                        <td>
                            {{ $transacao->valor }}
                        </td>
                        <td>
                            <a href="#" data-toggle="modal"  data-target="#editModal"
                                data-url-edit="{{ route('transacao.edit', $transacao->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>

                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da transação?',
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
                                        document.getElementById('delete-transacao-form-{{ $transacao->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-transacao-form-{{ $transacao->id }}" action="{{ route('transacao.delete', $transacao->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="conta_id" value="{{ $conta_id }}">
                                <input type="hidden" name="data_inicial" value="{{ $data_inicial }}">
                                <input type="hidden" name="data_final" value="{{ $data_final }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Transação Cadastrada</th>
                @endforelse
        </tbody>
      </table>
      @include('modulos.transacoesConta.modais.saquesERetiradas')
@endsection
