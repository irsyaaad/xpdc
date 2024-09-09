<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DaftarMuatRequest extends FormRequest
{
    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'id_perush_tj' => 'bail|required|alpha_num|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.s_perusahaan,id_perush',
            'id_kapal' => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_kapal,id_kapal',
            'id_armada' => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
            'id_sopir' => 'bail|nullable|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_sopir,id_sopir',
            'nm_dari' => 'bail|nullable|min:4|max:64',
            'nm_tuju' => 'bail|nullable|min:4|max:64',
            'nm_pj_dr' => 'bail|required|min:4|max:64',
            'nm_pj_tuju' => 'bail|nullable|min:4|max:64',
            'tgl_berangkat' => 'bail|required|date',
            'tgl_sampai' => 'bail|required|date|after_or_equal:tgl_berangkat',
            'no_container' => 'bail|nullable|min:6|max:16',
            'no_seal' => 'bail|nullable|min:6|max:16',
            'atd' => 'bail|nullable|date',
            'ata' => 'bail|nullable|date'
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
            'id_perush_tj' => 'Perusahaan Tujuan',
            'id_kapal' => 'Kapal',
            'id_sopir' => 'Sopir',
            'nm_dari' => 'Pelabuah asal',
            'nm_tuju' => 'Pelabuhan Tujuan',
            'nm_pj_dr' => 'PJ Asal',
            'nm_pj_tuju' => 'PJ Tujuan',
            'tgl_berangkat' => 'Tgl Berangkat',
            'tgl_sampai' => 'Tgl Estimasi Sampai',
            'no_container' => 'Nomor Contaier',
            'no_seal' => 'Nomor Seal',
            'atd' => 'Actual Time Departure',
            'ata' => 'Actual Time Arrive',
        ];
    }
    
}
