<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingBiayaVendorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ac_hutang' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'ac_biaya' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'id_biaya_grup' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_biaya_grup,id_biaya_grup',
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
