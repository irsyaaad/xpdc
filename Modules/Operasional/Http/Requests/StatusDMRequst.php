<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusDMRequst extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'id_status' => 'bail|required|digits_between:1,3',
                'tipe' => 'bail|required|digits_between:1,3',
                'nm_status'  => 'bail|required|min:3|max:32',
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
