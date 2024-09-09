<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SopirRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'nm_sopir' => 'bail|required|min:2|max:64',
                'alamat'  => 'bail|required|min:6|max:128',
                'alamat_domisili'  => 'bail|required|min:6|max:128',
                'telp'  => 'bail|required|numeric|digits_between:8,16',
                'no_ktp'  => 'bail|required|numeric|digits_between:8,16',
                'telp_keluarga'  => 'bail|nullable|numeric|digits_between:8,16',
                'no_sim'  => 'bail|required|numeric|digits_between:8,16',
                'foto'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'foto_ktp'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'foto_sim'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'foto_kk'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'exp_ktp'  => 'bail|nullable|date',
                'exp_sim'  => 'bail|required|date',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
                'def_armada'  => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,id_armada',
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
