@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem das Contas em Corretoras</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="#" data-url-moeda-list="{{route('moeda.selectBoxList')}}"
                data-url-corretoras-list="{{route('corretora.selectBoxList')}}" data-toggle="modal" data-target="#addModal">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>

    <hr class="bg-warning">

    <table class="table table-primary table-sm">
        <thead>
          <tr>
            <th scope="col">Identificador</th>
            <th scope="col"></th>
            <th scope="col">Tipo Conta</th>
            <th scope="col">Corretora</th>
            <th scope="col">Abertura</th>
            <th scope="col">Status</th>
            <th scope="col">Tipo Corretora</th>
            <th scope="col">Moeda</th>
            <th scope="col">Depositos</th>
            <th scope="col">Saques</th>
            <th scope="col">Saldo</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($contas as $indexKey => $conta)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">

                        <th scope="row">{{ $conta->identificador }}</th>
                        <td><label class="badge badge-success fs13 {{ $conta->padrao ? '' : 'hidde-me' }}">P</label></td>
                        <td><label class='{{$conta->real_demo ? $conta->real_demo == 'D'  ? 'badge badge-warning fs13' : 'badge badge-info fs13' :''}}'>
                            {{ $conta->real_demo ? $conta->real_demo == 'D' ? 'Demo' : 'Real' : '' }}
                        </label></td>
                        <td>{{ $conta->corretora ? $conta->corretora->nome : '' }}</td>
                        <td>{{ $conta->dtabertura }}</td>
                        <td class='{{ $conta->ativa ? 'text-info' : 'text-warning'}}'>{{ $conta->ativa ? 'Ativa' : 'Inativa' }}</td>
                        <td>{{ $conta->tipo == 'C' ? 'Nacional' : 'Internacional' }}</td>
                        <td>{{ $conta->moeda ? $conta->moeda->full_name : '' }}</td>
                        <td class="text-success">{{ $conta->entradas_formatado }}</td>
                        <td class="text-warning">{{ $conta->saidas_formatado }}</td>
                        <td class="{{ ($conta->saldo > 0) ? 'text-success' : (($conta->saldo < 0) ? 'text-warning' : '') }}">
                            {{ $conta->saldo_formatado }}
                        </td>
                        <td>
                            <a href="#" data-toggle="modal"  data-target="#editModal"
                                data-url-edit="{{route("conta.corretora.edit", $conta->id)}}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da conta corretora?',
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
                                        document.getElementById('delete-contacorretora-form-{{ $conta->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-contacorretora-form-{{ $conta->id }}" action="{{ route('conta.corretora.delete', $conta->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Conta Corretora Cadastrada</th>
                @endforelse
        </tbody>
      </table>
      @include('modulos.trade.modais.contaCorretora')
@endsection
