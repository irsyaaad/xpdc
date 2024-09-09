<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArmadaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->getMethod() == 'POST') {
            return [
                'no_plat' => 'bail|required|min:4|max:32|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada,no_plat',
                'id_armd_grup' => 'bail|required|min:1|max:32|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada_grup,id_armd_grup',
                'id_perush_armd' => 'bail|required|min:1|max:32|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_perush_armada,id_perush_armd',
                'nm_armada'  => 'bail|required|max:64|min:4',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
                'no_bpkb'  => 'bail|nullable|max:64|min:4',
                'no_stnk'  => 'bail|nullable|max:64|min:4',
                'harga' => 'bail|nullable|numeric',
                'volume' => 'bail|nullable|numeric',
                'gambar_bpkb'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'gambar_stnk'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
            ];
        }else{
            return [
                'no_plat' => 'bail|required|min:4|max:32',
                'id_armd_grup' => 'bail|required|min:1|max:32|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada_grup,id_armd_grup',
                'id_perush_armd' => 'bail|required|min:1|max:32|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_perush_armada,id_perush_armd',
                'nm_armada'  => 'bail|required|max:64|min:4',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
                'no_bpkb'  => 'bail|nullable|max:64|min:4',
                'no_stnk'  => 'bail|nullable|max:64|min:4',
                'harga' => 'bail|nullable|numeric',
                'volume' => 'bail|nullable|numeric',
                'gambar_bpkb'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
                'gambar_stnk'  => 'bail|nullable|image|mimes:jpg,png,jpeg,svg,gif|max:2048',
            ];
        }
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
