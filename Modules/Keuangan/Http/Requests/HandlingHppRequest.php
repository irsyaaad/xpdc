<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandlingHppRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tgl_bayar'           => 'bail|required|date',
            'ac4_k'            => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac_tujuan'            => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'n_bayar' => 'bail|required|numeric',
            'info' => 'bail|min:6|max:128'
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
