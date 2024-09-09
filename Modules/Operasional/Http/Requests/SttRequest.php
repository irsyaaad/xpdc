<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SttRequest extends FormRequest
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
                'tgl_masuk' => 'date|bail|nullable|min:10|max:11',
                'tgl_keluar' => 'date|bail|nullable|min:10|max:11|after_or_equal:tgl_masuk',
                'no_awb' => 'bail|nullable|min:5|max:32',
                // for pengirim
                'cara_kemas'  => 'bail|nullable|max:150',
                'pengirim_perush'  => 'bail|nullable|min:4|max:64',
                'id_pelanggan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_plgn,id_pelanggan',
                'pengirim_nm'  => 'bail|required|min:3|max:64',
                'pengirim_telp'  => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
                'pengirim_id_region'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
                'pengirim_alm'  => 'bail|required|min:4|max:255',
                'pengirim_kodepos'  => 'bail|nullable|numeric|digits_between:3,8',
                // for penerima
                'penerima_perush'  => 'bail|nullable|min:4|max:64',
                'id_pelanggan_penerima'  => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_pelanggan,id_pelanggan',
                'penerima_nm'  => 'bail|required|min:4|max:64',
                'penerima_telp'  => 'bail|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7',
                'penerima_id_region'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_wilayah,id_wil',
                'penerima_alm'  => 'bail|required|min:4|max:255',
                'penerima_kodepos'  => 'bail|nullable|numeric|digits_between:3,8',
                
                // for detail
                'id_tipe_kirim'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_tipe_kirim,id_tipe_kirim',
                'id_cr_byr_o'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_cr_bayar_order,id_cr_byr_o',
                'id_layanan'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_layanan,id_layanan',
                // 'id_tarif'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_tarif,id_tarif',
                'id_ven'  => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_vendor,id_ven',
                'id_packing'  => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_packing,id_packing',
                'id_marketing' =>'required',
                
                // 'info_kirim'  => 'bail|required',

                'n_berat'  => 'bail|required|numeric',
                'n_volume'  => 'bail|required|numeric',
                'n_koli'  => 'bail|required|numeric',
                'n_kubik'  => 'bail|required|numeric',

                'n_tarif_brt'  => 'bail|required|numeric',
                'n_tarif_vol'  => 'bail|required|numeric',
                'n_tarif_borongan'  => 'bail|nullable|numeric',

                'n_hrg_bruto'  => 'bail|required',
                // 'n_terusan'  => 'bail|nullable|numeric',
                // 'n_hrg_terusan'  => 'bail|nullable|numeric',

                'n_diskon'  => 'bail|nullable',
                'n_materai' => 'bail|nullable',
                'n_ppn'  => 'bail|nullable',
                'is_ppn' => 'bail|nullable|numeric',

                'id_asuransi'  => 'bail|nullable|numeric',
                'n_asuransi'  => 'bail|nullable',

                'c_total'  => 'bail|required',
                'secret_code'  => 'bail|nullable|numeric|digits_between:6,6',

                // for chargemin
                // 'cm_brt'  => 'bail|required|numeric',
                // 'cm_brt'  => 'bail|required|numeric',
                'c_hitung' => 'bail|required|numeric',
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
            'tgl_masuk' => 'Tgl masuk',
            'tgl_keluar' => 'Tgl Rencana Keluar',
            'no_awb' => 'No Airway Bil',

            'id_plgn' => 'Pelanggan',
            'pengirim_nm' => 'Nama pengirim',
            'pengirim_telp' => 'Telp. pengirim',
            'pengirim_id_region' => 'Region pengirim',
            'pengirim_alm' => 'Alamat pengirim',
            'pengirim_kodepos' => 'Kode pos pengirim',
            'pengirim_perush'  => 'Perusahaan pengirim',

            'penerima_perush'  => 'Perusahaan penerima',
            'penerima_nm' => 'Nama penerima',
            'penerima_telp' => 'Telp. penerima',
            'penerima_id_region' => 'Region penerima',
            'penerima_alm' => 'Alamat penerima',
            'penerima_kodepos' => 'Kode pos penerima',

            'id_tipe_kirim'  => 'Type kiriman',
            'id_cr_byr_o'  => 'Cara Bayar',
            'id_layanan'  => 'Layanan',
            'id_ven'  => 'Vendor',
            'id_packing'  => 'Cara Packing',

            'id_marketing' =>'Marketing',
            'info_kirim'  => 'Info Kirim',

            'n_berat'  => 'jumlah kg',
            'n_volume'  => 'jumlah Kgv',
            'n_koli'  => 'jumlah koli',
            'n_kubik'  => 'jumlah m3',

            'n_tarif_brt'  => 'harga kg',
            'n_tarif_vol'  => 'harga kgv',
            'n_tarif_borongan'  => 'harga borongan',
            'n_hrg_bruto'  => 'harga bruto',

            'n_diskon'  => 'diskon',
            'n_materai' => 'materai',
            'n_ppn'  => 'ppn',
            'is_ppn' => 'ppn',

            'id_asuransi'  => 'asuransi',
            'n_asuransi'  => 'nilai asuransi',

            'c_total'  => 'total',
            'secret_code'  => 'Secreet code',
            'c_hitung' => 'cara hitung',
        ];
    }
}
