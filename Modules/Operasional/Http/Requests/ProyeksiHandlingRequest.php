<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyeksiHandlingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_biaya_grup' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_biaya_grup,id_biaya_grup',
            'nominal' => 'bail|required|numeric|',
        ];
    }
    
    public function authorize()
    {
        return true;
    }
}
