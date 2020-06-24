<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editarEstrategia"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Estratégia</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessageEdit" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formEdit" method="PUT" action="{{ route('estrategias.update', -1) }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.trade.templates.formEstrategias')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button id="btnSalvarEditando" type="button" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="adicionarEstrategia"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Estratégia</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessage" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formAdd" method="POST" action="{{ route('estrategias.create') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.trade.templates.formEstrategias')
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

        $('#btnSalvarNovo').on('click', function (event) {
            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            let erros = validarFormulario($('#formAdd'));
            if(erros){
                $('#errorMessage').removeClass('hidde-me');
                $('#errorMessage b').html(erros);
                return;
            }

            $.post( $('#formAdd').attr('action'), $('#formAdd').serialize(), function(data) {
                if(data.success){
                    location.reload(true);
                } else {console.log(data);
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
            if(!$(formulario[0].nome).val())
                erros += '<li>Nome deve ser informado!</li>';
            else
                $(formulario[0].nome).val( $(formulario[0].nome).val().toUpperCase() );
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

            $.getJSON(urlEdit , function(data){console.log(data);
                modal.find('#nome').val(data.estrategia.nome);
                modal.find('#descricao').val(data.estrategia.descricao);

                if(data.estrategia.ativa)
                    modal.find('#ativa').attr('checked', 'checked');
                else
                    modal.find('#ativa').removeAttr('checked');

                cId = data.estrategia.id;

                var actUrl = modal.find('#formEdit').attr('action');
                actUrl = actUrl.replace('-1', cId);
                modal.find('#formEdit').attr('action', actUrl);
            });
        });

        $('#addModal').on('show.bs.modal', function (event) {

            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            var button = $(event.relatedTarget) // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)
            modal.find('#ativa').attr('checked', 'checked');
        });
    });

    </script>
@endsection
