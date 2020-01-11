@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem dos Instrumentos</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="{{ route('operacao.add') }}">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>

    <hr class="bg-warning">

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">Ticket</th>
            <th scope="col">Tipo</th>
            <th scope="col">Instrumento</th>
            <th scope="col">Lotes</th>
            <th scope="col">Conta Corretora</th>
            <th scope="col">Comissao</th>
            <th scope="col">Impostos</th>
            <th scope="col">Swap</th>
            <th scope="col">Resultado</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($operacoes as $indexKey => $operacao)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                        <th scope="row">{{ $operacao->ticket }}</th>
                        <td>{{ $operacao->tipo }}</td>
                        <td>{{ $operacao->instrumento->nome }}</td>
                        <td>{{ $operacao->lotes }}</td>
                        <td>{{ $operacao->contaCorretora->id }}</td>
                        <td>{{ $operacao->comissao }}</td>
                        <td>{{ $operacao->impostos }}</td>
                        <td>{{ $operacao->swap }}</td>
                        <td>{{ $operacao->resultado }}</td>
                        <td>
                            <a href="{{ route('operacao.edit', $operacao->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da operação?',
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
                                        document.getElementById('delete-operacao-form-{{ $operacao->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-operacao-form-{{ $operacao->id }}" action="{{ route('operacao.delete', $operacao->id) }}" method="POST" style="display: none;">
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
