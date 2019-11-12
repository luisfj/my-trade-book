<?php

namespace App\Http\Requests\Configuracoes;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracoesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descricao_verificar_mensagem',
            //'autor' => 'required|exists:users,id'
        ];
    }
}
