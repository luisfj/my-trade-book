<div class="modal fade" id="editPerfilModal" tabindex="-1" role="dialog" aria-labelledby="editarPerfil"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Perfil</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" method="POST" action="{{ route('perfil.update', -1) }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.admin.templates.formPerfil')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" form="formEdit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addPerfilModal" tabindex="-1" role="dialog" aria-labelledby="adicionarPerfil"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Perfil</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAdd" method="POST" action="{{ route('perfil.create') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('modulos.admin.templates.formPerfil')
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
        $('#editPerfilModal').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget) // Button that triggered the modal
            var urlEdit = button.data('url-edit') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)

            $.getJSON(urlEdit , function(data){
                modal.find('#nome').val(data.perfil.nome);
                modal.find('#descricao').val(data.perfil.descricao);

                var actUrl = modal.find('#formEdit').attr('action');
                actUrl = actUrl.replace('-1', data.perfil.id);
                modal.find('#formEdit').attr('action', actUrl);
            });
        });

        $('#addPerfilModal').on('show.bs.modal', function (event) {

            //var button = $(event.relatedTarget) // Button that triggered the modal
            //var urlEdit = button.data('url-edit') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            //modal.find('.modal-title').text('Editar url = ' + urlEdit)

            modal.find('#nome').val('');
            modal.find('#descricao').val('');

        });
    });
    </script>
@endsection
