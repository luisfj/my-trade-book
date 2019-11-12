<?php

namespace App\Http\Requests\Bugs;

use Illuminate\Foundation\Http\FormRequest;

class BugStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//\Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo' => 'required|min:3|max:100',
            'descricao' => 'required|min:3|max:100',
            //'autor' => 'required|exists:users,id'
        ];
    }
}
