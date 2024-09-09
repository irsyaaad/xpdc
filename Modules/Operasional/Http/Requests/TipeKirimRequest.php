<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class TipeKirimRequest extends FormRequest
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
               
                'nm_tipe_kirim'  => 'bail|required|min:2|max:32|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_tipe_kirim,nm_tipe_kirim',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
            ];
        }else{
            return [
                'nm_tipe_kirim'  => 'bail|required|min:2|max:32',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
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
