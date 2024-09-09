<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SttDmHandlingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'c_stt'  => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
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
