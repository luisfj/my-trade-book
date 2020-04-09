<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editarCorretora"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Corretora</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" method="POST" action="{{ route('corretora.update', -1) }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.admin.templates.formCorretora')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" form="formEdit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="adicionarCorretora"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Corretora</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAdd" method="POST" action="{{ route('corretora.create') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.admin.templates.formCorretora')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" form="formAdd">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function(){
        $('#editModal').on('show.bs.modal', function (event) {

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

            $.getJSON(urlEdit , function(data){
                $.each(data.moedas_list, function (i, item) {
                    modal.find('#moeda_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });

                modal.find('#nome').val(data.corretora.nome);
                modal.find('#uf').val(data.corretora.uf);
                modal.find('#moeda_id').val(data.corretora.moeda_id);

                var actUrl = modal.find('#formEdit').attr('action');
                actUrl = actUrl.replace('-1', data.corretora.id);
                modal.find('#formEdit').attr('action', actUrl);
            });
        });

        $('#addModal').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget) // Button that triggered the modal
            var urlMoedaList = button.data('url-moeda-list') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)
            modal.find('#moeda_id')
                .find('option')
                .remove()
                .end()
                .append('<option selected="selected" value="">-- Selecione a moeda --</option>');

            $.getJSON(urlMoedaList , function(data){
                $.each(data.moedas_list, function (i, item) {
                    modal.find('#moeda_id').append($('<option>', {
                        value: i,
                        text : item
                    }));
                });
            });

            modal.find('#nome').val('');
            modal.find('#uf').val('');

        });
    });
    </script>
@endsection
