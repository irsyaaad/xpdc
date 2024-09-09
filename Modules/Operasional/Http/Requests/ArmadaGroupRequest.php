<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArmadaGroupRequest extends FormRequest
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
                'nm_armd_grup' => 'bail|required|alpha|min:4|max:128|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_armada_grup,nm_armd_grup',
                'gr_armada'  => 'bail|required|digits_between:1,3',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
            ];
        }else{
            return [                
                'nm_armd_grup' => 'bail|required|alpha|min:4|max:128',
                'gr_armada'  => 'bail|required|digits_between:1,3',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
            ];
        }
    }
    
    public function authorize()
    {
        return true;
    }
}
