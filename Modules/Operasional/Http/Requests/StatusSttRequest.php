<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusSttRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        return [
            'id_ord_stt_stat' => 'bail|required|digits_between:1,3',
            'nm_ord_stt_stat'  => 'bail|required|min:4|max:64',
            'is_aktif'  => 'bail|nullable|numeric|max:1',
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
