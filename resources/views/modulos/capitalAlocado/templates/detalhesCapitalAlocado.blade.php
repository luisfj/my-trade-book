<div class="panel card marb-15">
    <div class="card-header">
        Contas que compõe o capital alocado
    </div>
    <div class="card-body">

        <form id="addContaCapAlocado" action="{{ route('capital.alocado.add.conta') }}" method="POST" >
            @csrf
            <input type="hidden" name="capitalAlocado_id" value="{{ $capitalAlocadoSelecionado->id }}">

            <div class="form-group row col-sm-12" style="text-align: right;">
                <div class="form-group row col-sm-4">
                    {!! Form::label('conta_id', 'Conta', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
                    <div class="col-sm-10">

                        {!! Form::select('conta_id', $conta_lista, null,
                            ['placeholder' => '-- Selecione a conta --', 'class' => 'form-control form-control-sm']) !!}
                    </div>
                </div>

                <div class="form-group col-sm-1" style="text-align: left !important;">
                    <button type="submit" class="btn btn-success btn-sm md-18" style="width: 50px">
                        <i class="material-icons md-18">add</i>
                    </button>
                </div>
            </div>
        </form>
        <table class="table table-sm table-primary">
            <thead>
              <tr>
                <th scope="col">Identificador</th>
                <th scope="col">Conta</th>
                <th scope="col">Saldo</th>
                <th scope="col">Ações</th>
              </tr>
            </thead>
            <tbody>
                @forelse ($contasComposicao as $indexKey => $conta)
                    <tr class="table-primary">
                        <th scope="row">
                            {{ $conta->identificador }}
                        </th>
                        <td >{{ Str::limit($conta->pluck_name, 200) }}</td>
                        <td >{{ $conta->saldoFormatado }}</td>

                        <td>
                            <a href="#" onclick="
                            swal({
                                    title: 'Confirma remover a conta da composição do capital alocado?',
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
                                        document.getElementById('delete-transacao-form-{{ $conta->id }}').submit();
                                    }
                                })
                                ">
                                <i class="material-icons md-18 text-danger">delete_outline</i>
                            </a>

                            <form id="delete-transacao-form-{{ $conta->id }}" action="{{ route('capital.alocado.delete.conta', $conta->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="conta_id" value="{{ $conta->id }}">
                                <input type="hidden" name="capitalAlocado_id" value="{{ $capitalAlocadoSelecionado->id }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma conta compondo o capital alocado!</th>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel card">
    <div class="card-header">
        <div class="col-lg-6 col-md-6 col-sm-12" style="width:100% !important;">
            Transações
            <a href="#" class="btn btn-success btn-sm md-18 marl-20" style="width: 50px"
                data-capitalAlocado_id="{{$capitalAlocadoSelecionado->id}}" data-toggle="modal" data-target="#addModal">
                <i class="material-icons md-light md-18">add</i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-primary table-sm">
            <thead>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Data</th>
                <th scope="col">Valor</th>
                <th scope="col">Ações</th>
              </tr>
            </thead>
            <tbody>
                @forelse ($transferencias as $indexKey => $transacao)
                    <tr class="table-primary {{ ($transacao->newForCapitalAlocado->tipo == 'D') ? 'text-success' : (($transacao->newForCapitalAlocado->tipo == 'S') ? 'text-warning' : '') }}">
                        <th scope="row">
                            @if($transacao->newForCapitalAlocado->tipo == 'D')
                                <i class="material-icons md-18">save_alt</i>
                                @if($transacao->conta != null)
                                    Transferência para a conta: ({{ $transacao->conta->identificador }})
                                @else
                                    Depósito
                                @endif
                            @elseif($transacao->newForCapitalAlocado->tipo == 'S')
                                <i class="material-icons md-18 text-warning">reply_all</i>
                                @if($transacao->conta != null)
                                    Transferência da conta: ({{ $transacao->conta->identificador }})
                                @else
                                    Saque
                                @endif
                            @endif
                        </th>
                        <td>{{ date('d/m/Y H:i:s', strtotime($transacao->data)) }}</td>
                        <td>
                            {{ $transacao->newForCapitalAlocado->valorFormatado }}
                        </td>
                        <td>
                            <a href="#" data-toggle="modal"  data-target="#editModal" class="{{ $transacao->conta_id != null ? 'hidde-me' : '' }}"
                                data-url-edit="{{ route('capital.alocado.edit', $transacao->id) }}">
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

                            <form id="delete-transacao-form-{{ $transacao->id }}" action="{{ route('capital.alocado.delete', $transacao->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="transacao_id" value="{{ $transacao->id }}">
                                <input type="hidden" name="capitalAlocado_id" value="{{ $capitalAlocadoSelecionado->id }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Transação Cadastrada</th>
                @endforelse
            </tbody>
          </table>
    </div>
</div>
@include('modulos.capitalAlocado.modais.saquesERetiradasAlocado')
