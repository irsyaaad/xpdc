<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupBiayaRequest extends FormRequest
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
                'nm_biaya_grup'  => 'bail|required|max:128|min:4',
                'klp'  => 'bail|required',
            ];
        }else{
            return [
                'nm_biaya_grup'  => 'bail|required|max:128|min:4',
                'klp'  => 'bail|required',
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
