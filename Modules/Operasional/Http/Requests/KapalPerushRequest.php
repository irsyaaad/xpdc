<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KapalPerushRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'nm_kapal_perush' => 'bail|required|min:4|max:32',
                'alamat'  => 'bail|required|min:8|max:128',
                'telp'  => 'bail|required|numeric|digits_between:4,16',
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
