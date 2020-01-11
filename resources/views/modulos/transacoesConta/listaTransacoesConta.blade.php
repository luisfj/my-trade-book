@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem das Transações em Conta</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="{{ route('conta.corretora.add') }}">
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
            <th scope="col">Ticket</th>
            <th scope="col">Código da Transacao</th>
            <th scope="col">Conta</th>
            <th scope="col">Contraparte</th>
            <th scope="col">Valor</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($transacoes as $indexKey => $transacao)
                    <tr class="table-primary {{ ($transacao->valor > 0) ? 'text-success' : (($transacao->valor < 0) ? 'text-warning' : '') }}">
                        <th scope="row">
                            @if($transacao->valor > 0)
                                <i class="material-icons md-18">save_alt</i>
                            @elseif($transacao->valor < 0)
                                <i class="material-icons md-18 text-warning">reply_all</i>
                            @endif
                        </th>
                        <td>{{ date('d/m/Y H:i:s', strtotime($transacao->data)) }}</td>
                        <td>{{ $transacao->ticket }}</td>
                        <td>{{ Str::limit($transacao->codigo_transacao, 16) }}</td>
                        <td>{{ Str::limit($transacao->conta->pluck_name, 20) }}</td>
                        <td>{{ $transacao->contraparte ? Str::limit($transacao->contraparte->pluck_name, 16) : '' }}</td>
                        <td class="{{ ($transacao->valor > 0) ? 'text-success' : (($transacao->valor < 0) ? 'text-warning' : '') }}">
                            {{ $transacao->valor }}
                        </td>
                        <td>
                            <a href="{{ route('transacao.edit', $transacao->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
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
@endsection
