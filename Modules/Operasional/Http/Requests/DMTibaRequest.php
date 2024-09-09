<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DMTibaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_dm' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_dm,id_dm',
            'nm_terima' => 'bail|required|min:4|max:64',
            'info' => 'bail|required|min:6|max:254',
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

    public function attributes()
    {
        return [
            'id_dm' => 'nomor daftar muat',
            'nm_terima' => 'penanggung jawab',
            'info' => 'info',
        ];
    }
}
