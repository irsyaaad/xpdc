<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaraBayarRequest extends FormRequest
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
                'id_cr_byr_o' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:1|max:4|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_cr_bayar_order,kode_cr_byr_o',
                'nm_cr_byr_o' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2|max:16|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_cr_bayar_order,nm_cr_byr_o',
                'is_aktif'  => 'bail|nullable|numeric|max:1',
            ];
        }else{
            return [
                'id_cr_byr_o' => 'bail|required|alpha|min:1|max:4',
                'nm_cr_byr_o' => 'bail|required|min:2|max:16',
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
