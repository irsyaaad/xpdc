<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ACPerushRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'parent'           => 'bail|required|min:1|max:10',
                'nama'            => 'bail|required|min:4|max:128',
                'def_pos'            => 'bail|required|alpha|min:1|max:1',
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
