@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem das Corretoras</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="#" data-url-moeda-list="{{route('moeda.selectBoxList')}}"  data-toggle="modal" data-target="#addModal">
                <i class="material-icons md-light md-24">add_circle_outline</i>
            </a>
        </div>
    </div>

    <hr class="bg-warning">

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Nome</th>
            <th scope="col">Uf</th>
            <th scope="col">Moeda</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($corretoras as $indexKey => $corretora)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                        <th scope="row">{{ $corretora->id }}</th>
                        <td>{{ $corretora->nome }}</td>
                        <td>{{ $corretora->uf }}</td>
                        <td>{{ $corretora->moeda->full_name }}</td>
                        <td>
                            <a href="#"  data-toggle="modal"  data-target="#editModal"
                            data-url-edit="{{route("corretora.edit", $corretora->id)}}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da corretora?',
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
                                        document.getElementById('delete-corretora-form-{{ $corretora->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-corretora-form-{{ $corretora->id }}" action="{{ route('corretora.delete', $corretora->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Corretora Cadastrada</th>
                @endforelse
        </tbody>
      </table>
      {{ $corretoras->links() }}
      @include('modulos.admin.modais.corretora')
@endsection
