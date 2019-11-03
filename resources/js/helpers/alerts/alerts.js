import swal from 'sweetalert'


export default function messages(tipo, titulo, mensagem, funcao) {
    switch (tipo){
        case 'confirm': {
            swal({
                title: titulo,
                text: mensagem,
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true
                    },
                    confirm: {
                        text: "Confirmar",
                        value: true,
                        visible: true,
                    },
                },
                icon: "warning",
                closeOnClickOutside: false,
            }).then((result) => {
                if(result)
                    funcao();
              })
            break;
        }
        case 'error': {
            swal({
                title: titulo,
                text: mensagem,
                icon: "error",
                closeOnClickOutside: false,
            })
            break;
        }
        case 'warn': {
            swal({
                title: titulo,
                text: mensagem,
                icon: "warning",
                closeOnClickOutside: false,
            })
            break;
        }
        case 'success': {
            swal({
                title: titulo,
                text: mensagem,
                icon: "success",
                closeOnClickOutside: false,
            })
            break;
        }
    }

}
