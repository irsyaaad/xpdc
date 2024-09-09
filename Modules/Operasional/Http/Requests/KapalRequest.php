<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KapalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        return [
                'id_kapal_perush' => 'bail|required|numeric|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_kapal_perush,id_kapal_perush',
                'nm_kapal'  => 'bail|required|min:4|max:128',
                'dr_rute'  => 'bail|nullable|max:64',
                'ke_rute'  => 'bail|nullable|max:64',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
                'def_tarif'  => 'bail|nullable|numeric',
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
