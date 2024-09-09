<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_cr_byr' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_cr_bayar_order,id_cr_byr_o',
            'n_bayar'  => 'bail|required|numeric',
            'tgl_bayar'  => 'bail|required|max:12|min:3',
            'info'  => 'bail|nullable|max:124',
            'ac4_d' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'no_bayar'  => 'bail|nullable|numeric',
            'nm_bayar'  => 'bail|nullable|max:132|min:3',
            'tgl_bg'  => 'bail|nullable|max:12|min:3',
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
