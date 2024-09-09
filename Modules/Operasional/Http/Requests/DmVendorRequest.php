<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DmVendorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_ven' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
            'id_lsj_ven' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.s_perusahaan,id_perush',
            'id_wil_asal' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'id_wil' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
            'id_layanan' => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_layanan,id_layanan',
            'nm_dari' => 'bail|nullable|max:64',
            'jenis1' => 'bail|nullable|numeric',
            'jenis2' => 'bail|nullable|numeric',
            'nm_tuju' => 'bail|nullable|max:64',
            'nm_pj_dr' => 'bail|required|min:4|max:64',
            'nm_pj_tuju' => 'bail|nullable|min:4|max:64',
            'tgl_berangkat' => 'bail|required|date',
            'no_seal'  => 'bail|nullable|max:25|min:3',
            'no_container'  => 'bail|nullable|max:25|min:3',
            'cara'  => 'bail|required|in:1,2,3,4',
            'n_harga'  => 'bail|required|numeric',
            'tgl_sampai' => 'bail|required|date|after_or_equal:tgl_berangkat',
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
            'id_ven' => 'vendor luar',
            'id_lsj_ven' => 'vendor cabang',
            'id_wil' => 'wilayah tujuan',
            'id_layanan' => 'layanan',
            'nm_dari' => 'pelabuhan asal',
            'jenis1' => 'type',
            'jenis2' => 'type2',
            'nm_tuju' => 'pelabuhan tujuan',
            'nm_pj_dr' => 'penanggung jawab asal',
            'nm_pj_tuju' => 'penanggung jawab tujuan',
            'tgl_berangkat' => 'tgl berangkat',
            'no_seal'  => 'no seal',
            'no_container'  => 'no container',
            'tgl_sampai' => 'tgl estimasi sampai',
        ];
    }
}
