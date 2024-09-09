<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingBiayaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_biaya_grup' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_biaya_grup,id_biaya_grup',
            'ac4_hutang' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac4_biaya' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
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
