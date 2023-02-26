<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')){
            return[
                'nome' => 'required|string|max:258',
                'descricao' => 'required|string',
                'preco' => 'required|integer',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }else{
            return[
                'nome' => 'required|string|max:258',
                'descricao' => 'required|string',
                'preco' => 'required|integer',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }
    }

    public function mensagem(){
        if(request()->isMethod('post')){
            return[
                'nome' => 'Nome eh requerido',
                'descricao' => 'Descricao eh requerida',
                'preco' => 'Preco eh requerido',
                'imagem' => 'Imagem eh requerida',
            ];
        }else{
            return[
                'nome' => 'Nome eh requerido',
                'descricao' => 'Descricao eh requerida',
                'preco' => 'Preco eh requerido',
                'imagem' => 'Imagem eh requerida',
            ];
        }
    }
}
