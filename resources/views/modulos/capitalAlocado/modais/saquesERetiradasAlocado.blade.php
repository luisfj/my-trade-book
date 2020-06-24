<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editarCorretora"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Depósito/Saque</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessageEdit" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formEdit" method="PUT" action="{{ route('capital.alocado.update', -1) }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.capitalAlocado.templates.formTransacaoAlocado')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button id="btnSalvarEditando" type="button" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="adicionarCorretora"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Depósito/Saque</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessage" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formAdd" method="POST" action="{{ route('capital.alocado.create') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.capitalAlocado.templates.formTransacaoAlocado')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button id="btnSalvarNovo" type="button" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function(){
        let cId = null;

        function changeTipo(tipo, modal){
            if(tipo.val() == 'D') {//se Deposito
                modal.find('#iconDeposito').removeClass('hidde-me');
                modal.find('#iconSaque').addClass('hidde-me');
                tipo.addClass('text-success');
                tipo.removeClass('text-warning');
            } else if(tipo.val() == 'S'){ //se Saque
                modal.find('#iconDeposito').addClass('hidde-me');
                modal.find('#iconSaque').removeClass('hidde-me');
                tipo.removeClass('text-success');
                tipo.addClass('text-warning');
            }
        }

        $('#btnSalvarNovo').on('click', function (event) {
            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            let erros = validarFormulario($('#formAdd'));
            if(erros){ console.log("deu erro");
                $('#errorMessage').removeClass('hidde-me');
                $('#errorMessage b').html(erros);
                return;
            }

            $.post( $('#formAdd').attr('action'), $('#formAdd').serialize(), function(data) {
                if(data.success){
                    location.reload(true);
                } else {
                    console.log(data);
                    $('#errorMessage').removeClass('hidde-me');
                    $('#errorMessage b').html(data.error);
                }
            },
            'json' // I expect a JSON response
            )
        });

        $('#btnSalvarEditando').on('click', function (event) {
            if(!$('#errorMessageEdit').hasClass('hidde-me'))
                $('#errorMessageEdit').addClass('hidde-me');

            let erros = validarFormulario($('#formEdit'), true);
            if(erros){
                $('#errorMessageEdit').removeClass('hidde-me');
                $('#errorMessageEdit b').html(erros);
                return;
            }

            $.ajax({
                url: $('#formEdit').attr('action'),
                type: 'PUT',
                data: $('#formEdit').serialize(),
                success: function(data) {
                    if(data.success){
                        location.reload(true);
                    } else {
                        $('#errorMessageEdit').removeClass('hidde-me');
                        $('#errorMessageEdit b').html(data.error);
                    }
                }
                });

        });

        function validarFormulario(formulario, isUpdate = false) {
            let erros = '';
            if(!$(formulario[0].data).val())
                erros += '<li>Data deve ser informada!</li>';
            if(!$(formulario[0].valor).val())
                erros += '<li>Valor deve ser informado!</li>';

            if(!erros){
                let valor = $(formulario[0].valor).val().replace(',', '.') * 1;
                let tipo  = $(formulario[0].tipo).val();
                if(tipo == 'D' && valor < 0 || tipo == 'S' && valor > 0){
                    $(formulario[0].valor).val((valor * -1));
                }
            }
            return erros ? '<ul>'+erros+'</ul>' : null;
        }

        $('#editModal').on('show.bs.modal', function (event) {

            if(!$('#errorMessageEdit').hasClass('hidde-me'))
                $('#errorMessageEdit').addClass('hidde-me');

            var button = $(event.relatedTarget) // Button that triggered the modal
            var urlEdit = button.data('url-edit') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)

            $.getJSON(urlEdit , function(data){
                //modal.find("#corretora_nm").html(' - (' + data.transacao.conta.identificador +') ' + data.transacao.conta.corretora.nome);

                modal.find('#data').val($.format.date(data.transacao.data, "yyyy-MM-ddTHH:mm:ss"));//.toJSON().slice(0,19)
                modal.find('#valor').val(data.transacao.valor);
                modal.find('#tipo').val(data.transacao.tipo);

                cId = data.transacao.id;

                var actUrl = modal.find('#formEdit').attr('action');
                actUrl = actUrl.replace('-1', cId);
                modal.find('#formEdit').attr('action', actUrl);

                changeTipo(modal.find("#tipo"), modal);
            });

            modal.find("#tipo").on('change', function name(event) {
                changeTipo(modal.find("#tipo"), modal);
            });
        });

        $('#addModal').on('show.bs.modal', function (event) {

            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            var button = $(event.relatedTarget) // Button that triggered the modal
            var conta_id = button.data('capitalalocado_id') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)

            modal.find('#capitalAlocado_id').val(conta_id);

            changeTipo(modal.find("#tipo"), modal);

            modal.find("#tipo").on('change', function name(event) {
                changeTipo(modal.find("#tipo"), modal);
            });

        });
    });
    </script>
@endsection
