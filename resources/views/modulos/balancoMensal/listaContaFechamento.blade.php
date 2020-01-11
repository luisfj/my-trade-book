@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem das Contas de Fechamento</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="{{ route('conta.fechamento.add') }}">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>

    <hr class="bg-warning">

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col"></th>
            <th scope="col">Tipo</th>
            <th scope="col">Nome</th>
            <th scope="col">Abertura</th>
            <th scope="col">Saldo Inicial</th>
            <th scope="col">Saldo Atual</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($contas as $indexKey => $conta)
                    <tr class="table-primary {{ ($conta->saldo_atual > 0) ? 'text-success' : (($conta->saldo_atual < 0) ? 'text-warning' : '') }}">
                        <th scope="row">
                            @if($conta->saldo_atual > 0)
                                <i class="material-icons md-18">save_alt</i>
                            @elseif($conta->saldo_atual < 0)
                                <i class="material-icons md-18 text-warning">reply_all</i>
                            @endif
                        </th>
                        <td>{{ $conta->tipo }}</td>
                        <td>{{ $conta->nome }}</td>
                        <td>{{ date('d/m/Y', strtotime($conta->abertura)) }}</td>
                        <td>{{ $conta->saldo_inicial }}</td>
                        <td class="{{ ($conta->saldo_atual > 0) ? 'text-success' : (($conta->saldo_atual < 0) ? 'text-warning' : '') }}">
                            {{ $conta->saldo_atual }}
                        </td>
                        <td>
                            <a href="{{ route('conta.fechamento.edit', $conta->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da conta de fechamento?',
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
                                        document.getElementById('delete-conta-form-{{ $conta->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-conta-form-{{ $conta->id }}" action="{{ route('conta.fechamento.delete', $conta->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Conta de Fechamento Cadastrada</th>
                @endforelse
        </tbody>
      </table>
@endsection
