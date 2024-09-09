<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackingRequest extends FormRequest
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
                'id_packing'  => 'bail|required|alpha_num|min:2|max:32|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.d_packing,id_packing',
                'nm_packing'  => 'bail|required|',
            ];
            
        }else{
            return [
                'id_packing'  => 'bail|required|alpha_num',
                'nm_packing'  => 'bail|required|',
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
