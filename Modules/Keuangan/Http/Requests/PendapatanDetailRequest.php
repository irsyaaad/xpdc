<?php

namespace Modules\Keuangan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendapatanDetailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_ac' => 'bail|required|min:2|max:16|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac,id_ac',
            'id_pendapatan' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.keu_pendapatan,id_pendapatan',
            'info'  => 'bail|required|max:256|min:4',
            'harga'  => 'bail|required|numeric',
            'jumlah'  => 'bail|nullable|numeric',
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
