@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12">
        <h1>
            <span class="material-icons text-success icon-v-bottom" style="font-size: 50px !important;">
                gps_fixed
            </span>
            <span>Estratégias</span>
        </h1>
    </div>
    <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
        <a class="btn btn-success form-control" href="#" data-url-moeda-list="{{route('moeda.selectBoxList')}}"
            data-url-corretoras-list="{{route('corretora.selectBoxList')}}" data-toggle="modal" data-target="#addModal">
            <i class="material-icons md-light md-24">add_circle_outline</i>
        </a>
    </div>
</div>

<hr class="bg-warning">

    <table class="table table-primary table-sm table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">Descricao</th>
            <th scope="col">Estado</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($estrategias as $estrategia)
                    <tr class="{{ $estrategia->ativa ? '' : 'text-warning'}}">
                        <td>{{ $estrategia->nome }}</td>
                        <td>{{ $estrategia->descricao }}</td>
                        <td>@if($estrategia->ativa)
                                <i class="material-icons md-light md-18">check</i> Ativa
                            @else
                                <i class="material-icons md-light md-18">close</i> Inativa
                            @endif
                        </td>
                        <td>
                            <a href="#" data-toggle="modal"  data-target="#editModal"
                                data-url-edit="{{ route('estrategias.edit', $estrategia->id) }}">
                                <i class="material-icons text-info md-18">edit</i>
                            </a>

                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma a exclusão da estratégia?',
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
                                        document.getElementById('delete-estrategia-form-{{ $estrategia->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-estrategia-form-{{ $estrategia->id }}" action="{{ route('estrategias.delete', $estrategia->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma estratégia cadastrada</th>
                @endforelse
        </tbody>
      </table>
      {{ $estrategias->links() }}
      @include('modulos.trade.modais.estrategias')
@endsection
