<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifHandlingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {
        return [
            'id_tujuan' => 'bail|digits_between:1,100|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'id_asal' => 'bail|digits_between:1,100|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'hrg_brt' => 'bail|required|numeric',
            'hrg_borongan' => 'bail|nullable|numeric',
            'info' => 'bail|max:100',
            'is_aktif' => 'bail|nullable|digits:1|numeric',
            'is_standart' => 'bail|digits:1|nullable|numeric',
        ];
    }

    public function messages()
    {   
        return [
            'max'     => ':attribute Terlalu Panjang',
            'min'  => ':attribute Terlalu Pendek',
            'required' => ':attribute harus di isi',
            'digits_between' => ':attribute antara 1 dan 100 karakter',
            'digits' => ':attribute harus valid 1 karakter',
            'numeric' => ':attribute harus valid number',
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
}
