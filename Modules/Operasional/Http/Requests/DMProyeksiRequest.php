<?php

namespace Modules\Operasional\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DMProyeksiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_biaya_grup' => 'bail|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_biaya_grup,id_biaya_grup',
            'nominal' => 'bail|nullable|numeric',
            'id_stt' => 'bail|nullable|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.t_order,id_stt',
            'tgl_posting' => 'bail|required|date',
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
            'id_biaya_grup' => 'group biaya',
            'nominal' => 'nominal',
            'id_stt' => 'nomor stt',
            'tgl_posting' => 'tgl posting biaya',
        ];
    }
}
