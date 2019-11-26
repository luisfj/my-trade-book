@extends('layouts.app')

@section('content')

    <h1 class="text-active">Atualizar Mensagem/Enquete</h1>
    <hr class="bg-warning">

    {!! Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'put']) !!}

        @include('modulos.admin.posts.form')

    {!! Form::close() !!}
@endsection


@section('page-script')
<script>
    $(document).ready(function(){
        var count = 0;
        var row_inc = 0;

        changeTipo();

        loadData();

        function loadData(){
            let a = {!! $post->opcoesEnquete !!};
            $.each(a, function (index, value) {
                count++;
                dynamic_field(count, value.nome, value.detalhamento, value.id);
            });
        }

        function dynamic_field(number, nome, detalhamento, opc_id){
            var html = '<tr id="opc_'+row_inc+'">';
            html += '<td> <input type="hidden" name="opcao[]" class="form-control" value="'+ (opc_id != null ? opc_id : '') +'" /> <input type="text" name="nome[]" class="form-control" value="'+ (nome != null ? nome : '') +'" /> </td>';
            html += '<td> <input type="text" name="detalhamento[]" class="form-control" value="'+ (detalhamento != null ? detalhamento : '') +'" /> </td>';
            html += '<td> <button type="button" name="remove" id="remove" class="btn btn-danger">Remover</button> </td></tr>';
            $('tbody').append(html);

            row_inc++;
        }

        function changeTipo(){
            if($('#tipo').val() == 'E') {//se Enquete
                //Mostro a tabela de opções
                $('#opcoesEnquete').removeClass('hidde-me');
                $('#dataFim').removeClass('hidde-me');
                $('#resPublico').removeClass('hidde-me');
                $('#resMultiescolha').removeClass('hidde-me');
            } else { //se Mensagem
                //Não mostro a tabela de opções
                $('#opcoesEnquete').addClass('hidde-me');
                $('#dataFim').addClass('hidde-me');
                $('#resPublico').addClass('hidde-me');
                $('#resMultiescolha').addClass('hidde-me');
            }
        }

        $('#add').click(function(){
            count++;
            dynamic_field(count, null, null, null);
        });

        $(document).on('click', '#remove', function(elemet){
            let trparent = elemet.target.parentElement.parentElement;
            trparent.parentNode.removeChild(trparent);
        });

        $('#tipo').on('change', changeTipo);
    });
</script>
@endsection
