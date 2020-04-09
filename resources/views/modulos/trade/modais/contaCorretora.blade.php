<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editarCorretora"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Conta Corretora</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessageEdit" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formEdit" method="PUT" action="{{ route('conta.corretora.update', -1) }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.trade.templates.formContaCorretora')
                </div>
                <div class="modal-footer">
                    <button id="transfContaPadrao" type="button" class="btn btn-info hidde-me" data-dismiss="modal">Transformar em Conta Padrão</button>
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
                <h4 class="modal-title">Adicionar Conta Corretora</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="errorMessage" class="alert alert-danger hidde-me">
                <b></b>
            </div>

            <form id="formAdd" method="POST" action="{{ route('conta.corretora.create') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.trade.templates.formContaCorretora')
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
                } else {
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

            let erros = validarFormulario($('#formEdit'));
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

        $('#transfContaPadrao').on('click', function (event) {
            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            $.ajax({
                url: '/conta-corretora-padrao/'+cId,
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

        function validarFormulario(formulario) {
            let erros = '';
            if(!$(formulario[0].identificador).val())
                erros += '<li>Identificador deve ser informado</li>';
            if(!$(formulario[0].moeda_id).val())
                erros += '<li>Moeda deve ser informada</li>';
            if(!$(formulario[0].tipo).val())
                erros += '<li>Tipo deve ser informado</li>';
            if(!$(formulario[0].saldo).val())
                erros += '<li>Saldo inicial deve ser informado</li>';
            if(!$(formulario[0].corretora_id).val())
                erros += '<li>Corretora deve ser informada</li>';
            if($(formulario[0].corretora_id).val() && $(formulario[0].corretora_id).val() == -1 && !$(formulario[0].corretora_nm).val())
                erros += '<li>Nome da corretora deve ser informada</li>';
            return erros ? '<ul>'+erros+'</ul>' : null;
        }

        $('#editModal').on('show.bs.modal', function (event) {

            if(!$('#errorMessageEdit').hasClass('hidde-me'))
                $('#errorMessageEdit').addClass('hidde-me');

            if(!$('#transfContaPadrao').hasClass('hidde-me'))
                $('#transfContaPadrao').addClass('hidde-me');

            var button = $(event.relatedTarget) // Button that triggered the modal
            var urlEdit = button.data('url-edit') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)

            modal.find('#moeda_id')
                .find('option')
                .remove()
                .end()
                .append('<option selected="selected" value="">-- Selecione a moeda --</option>');

            modal.find('#corretora_id')
                .find('option')
                .remove()
                .end()
                .append('<option selected="selected" value="">-- Selecione a Corretora --</option>');

            if(!modal.find('#corretora_nome').hasClass('hidde-me'))
                modal.find('#corretora_nome').addClass('hidde-me');

            modal.find('#corretora_id').on('change', function (event) {
                if(event.target.value == -1){
                    modal.find('#corretora_nome').removeClass('hidde-me');
                } else {
                    if(!modal.find('#corretora_nome').hasClass('hidde-me'))
                        modal.find('#corretora_nome').addClass('hidde-me');
                }
            });

            $.getJSON(urlEdit , function(data){
                $.each(data.moedas_list, function (i, item) {
                    modal.find('#moeda_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });
                $.each(data.corretoras_list, function (item, i) {//alterado a ordem para ordenar por nome
                    modal.find('#corretora_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });

                modal.find('#corretora_id').append($('<option>', {
                        value: -1,
                        text : '- OUTRA -'
                    }));

                modal.find('#moeda_id').val(data.conta.moeda_id);
                modal.find('#corretora_id').val(data.conta.corretora_id);
                modal.find('#identificador').val(data.conta.identificador);
                modal.find('#dtabertura').val(data.conta.dtabertura);
                modal.find('#tipo').val(data.conta.tipo);
                modal.find('#ativa').val(data.conta.ativa);
                modal.find('#padrao').val(data.conta.padrao);
                modal.find('#real_demo').val(data.conta.real_demo);
                modal.find('#saldo').val(data.conta.saldo);

                if(!data.conta.padrao)
                    $('#transfContaPadrao').removeClass('hidde-me');

                modal.find('#padrao').attr('disabled', 'true');
                modal.find('#saldo').attr('disabled', 'true');

                cId = data.conta.id;

                var actUrl = modal.find('#formEdit').attr('action');
                actUrl = actUrl.replace('-1', data.conta.id);
                modal.find('#formEdit').attr('action', actUrl);
            });
        });

        $('#addModal').on('show.bs.modal', function (event) {

            if(!$('#errorMessage').hasClass('hidde-me'))
                $('#errorMessage').addClass('hidde-me');

            if(!$('#transfContaPadrao').hasClass('hidde-me'))
                $('#transfContaPadrao').addClass('hidde-me');

            var button = $(event.relatedTarget) // Button that triggered the modal
            var urlMoedaList = button.data('url-moeda-list') // Extract info from data-* attributes
            var urlCorretoraList = button.data('url-corretoras-list');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)
            modal.find('#moeda_id')
                .find('option')
                .remove()
                .end()
                .append('<option selected="selected" value="">-- Selecione a moeda --</option>');

            modal.find('#corretora_id')
                .find('option')
                .remove()
                .end()
                .append('<option selected="selected" value="">-- Selecione a Corretora --</option>');

            if(!modal.find('#corretora_nome').hasClass('hidde-me'))
                modal.find('#corretora_nome').addClass('hidde-me');

            modal.find('#corretora_id').on('change', function (event) {
                if(event.target.value == -1){
                    modal.find('#corretora_nome').removeClass('hidde-me');
                } else {
                    if(!modal.find('#corretora_nome').hasClass('hidde-me'))
                        modal.find('#corretora_nome').addClass('hidde-me');
                }
            });

            $.getJSON(urlMoedaList , function(data){
                $.each(data.moedas_list, function (i, item) {
                    modal.find('#moeda_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });
            });

            $.getJSON(urlCorretoraList , function(data){
                $.each(data.corretoras_list, function (item, i) {
                    modal.find('#corretora_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });
                modal.find('#corretora_id').append($('<option>', {
                    value: -1,
                    text : '- OUTRA -'
                }));
            });

            modal.find('#identificador').val('');
            modal.find('#dtabertura').val('');
            modal.find('#tipo').val('');
            modal.find('#ativa').val('1');
            modal.find('#padrao').val('0');
            modal.find('#real_demo').val('R');
            modal.find('#saldo').val('');

            modal.find('#padrao').removeAttr('disabled');
            modal.find('#saldo').removeAttr('disabled');
        });
    });
    </script>
@endsection
