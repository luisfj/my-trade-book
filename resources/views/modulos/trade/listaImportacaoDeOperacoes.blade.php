@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <h1>
                <span class="material-icons text-info icon-v-bottom" style="font-size: 50px !important;">
                    low_priority
                </span>
                <span>Registros de Importações</span>
            </h1>
        </div>
    </div>

    <hr class="bg-warning">
    <form method="POST" class="form-horizontal" action="{{ route('registros.importacoes.filter') }}">
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

    <table class="table table-primary table-striped table-sm table-hover">
        <thead>
          <tr>
            <th scope="col" class="text-center">Data Importação</th>
            <th scope="col">Arquivo</th>
            <th scope="col" class="text-right">N° Operações</th>
            <th scope="col" class="text-right">$ Operações</th>
            <th scope="col" class="text-right">Nº Depósitos/Saques</th>
            <th scope="col" class="text-right">$ Depósitos/Saques</th>
            <th scope="col" class="text-center">Primeira Operação</th>
            <th scope="col" class="text-center">Ultima Operação</th>
            <th scope="col">Corretora</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($importacoes as $indexKey => $importacao)
                    <tr>
                        <th scope="row" class="text-center">
                            {{ $importacao->data_importacao_formatado }}
                        </th>
                        <td>{{ $importacao->arquivo }}</td>
                        <td class="text-right">{{ $importacao->numero_operacoes }}</td>
                        <td class="text-right">{{ $importacao->valor_operacoes_formatado }}</td>
                        <td class="text-right">{{ $importacao->numero_transferencias }}</td>
                        <td class="text-right">{{ $importacao->valor_transferencias_formatado }}</td>
                        <td class="text-center">{{ $importacao->data_primeiro_registro_formatado }}</td>
                        <td class="text-center">{{ $importacao->data_ultimo_registro_formatado }}</td>
                        <td>{{ $importacao->contaCorretora->corretora->nome }}</td>
                        <td>
                            <a href="#" onclick="
                            swal({
                                    title: 'Ao Excluir TODAS as OPERAÇÕES, DEPÓSITOS e SAQUES importados do arquivo serão excluídos! Confirma a exclusão da importação?',
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
                                        document.getElementById('delete-arq-importacao-form-{{ $importacao->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-arq-importacao-form-{{ $importacao->id }}" action="{{ route('registros.importacoes.delete', $importacao->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Operação Cadastrada</th>
                @endforelse
        </tbody>
      </table>
@endsection
