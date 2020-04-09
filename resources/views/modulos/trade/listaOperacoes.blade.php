@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem das Operações</h1></div>
    </div>

    <hr class="bg-warning">
    <form method="POST" class="form-horizontal" action="{{ route('operacao.filter') }}">
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

    <table class="table table-primary table-sm">
        <thead>
          <tr>
            <th scope="col">Abertura</th>
            <th scope="col">Ticket</th>
            <th scope="col">Tipo</th>
            <th scope="col">Instrumento</th>
            <th scope="col">Lotes</th>
            <th scope="col">Conta Corretora</th>
            <th scope="col">Fechamento</th>
            <th scope="col">Tempo</th>
            <th scope="col">Resultado</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($operacoes as $indexKey => $operacao)
                    <tr class="table-primary {{ $operacao->resultado > 0 ? 'text-success' : ($operacao->resultado < 0 ? 'text-danger' : '') }}">
                        <th scope="row">
                            @if($operacao->resultado > 0)
                                <i class="material-icons md-18">arrow_upward</i>
                            @elseif($operacao->resultado < 0)
                                <i class="material-icons md-18">arrow_downward</i>
                            @endif
                            {{ $operacao->abertura_formatado }}
                        </th>
                        <td>{{ $operacao->ticket }}</td>
                    <td class="{{ $operacao->tipo == 'buy' ? 'text-info' : 'text-warning' }}">{{ $operacao->tipo }}</td>
                        <td>{{ $operacao->instrumento->nome }}</td>
                        <td>{{ $operacao->lotes }}</td>
                        <td>{{ $operacao->contaCorretora->corretora->nome }}</td>
                        <td>{{ $operacao->fechamento_formatado }}</td>
                        <td>{{ $operacao->duracao_trade_formatado }}</td>
                        <td class="fbold">
                            {{ $operacao->resultado }}
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Operação Cadastrada</th>
                @endforelse
        </tbody>
      </table>
@endsection
