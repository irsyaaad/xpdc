<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifProyeksiReq extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // head stt
            'id_asal'        => 'bail|nullable|numeric|digits_between:1,128|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'id_tujuan'      => 'bail|nullable|numeric|digits_between:1,128|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'id_perushtj'    => 'bail|nullable|min:3|max:64|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.s_perusahaan,id_perush',
            'id_layanan'     => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_layanan,id_layanan',
            'id_tarif'     => 'bail|nullable|numeric|digits_between:1,128|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_tarif,id_tarif',
            'id_ven'     => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven'
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
