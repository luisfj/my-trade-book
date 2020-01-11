@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem dos Instrumentos</h1></div>
        <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
            <a class="btn btn-success form-control" href="{{ route('instrumento.add') }}">
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
            <th scope="col">Sigla</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($instrumentos as $indexKey => $instrumento)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                        <th scope="row">{{ $instrumento->id }}</th>
                        <td>{{ $instrumento->nome }}</td>
                        <td>{{ $instrumento->sigla }}</td>
                        <td>
                            <a href="{{ route('instrumento.edit', $instrumento->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>
-
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão do instrumento?',
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
                                        document.getElementById('delete-instrumento-form-{{ $instrumento->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-instrumento-form-{{ $instrumento->id }}" action="{{ route('instrumento.delete', $instrumento->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhum Instrumento Cadastrado</th>
                @endforelse
        </tbody>
      </table>
@endsection
