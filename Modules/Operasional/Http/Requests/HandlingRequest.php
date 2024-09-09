<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandlingRequest extends FormRequest
{
    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'id_sopir' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.users,id_user',
            'id_armada' => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'region_dr'  => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'region_tuju'  => 'bail|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
        ];
    }
    
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'id_sopir' => 'sopir',
            'id_armada' => 'armada',
            'region_dr'  => 'wilayah asal',
            'region_tuju'  => 'wilayah tujuan',
        ];
    }
}
