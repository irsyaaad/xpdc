<?php

namespace App\Http\Requests;
use Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $id = Request::segment(2)!=null?Request::segment(2):null;
        if ($this->getMethod() == 'POST') {
            return [
                'id_karyawan'  => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
                'id_role'  => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.role,id_role',
                'id_perush'  => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.s_perusahaan,id_perush',
                'username'  => 'bail|required|alpha_num|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.users,username',
                'password' => 'required|alpha_num|min:4|max:40',
            ];
        }else{
            return [
                'id_karyawan'  => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_karyawan,id_karyawan',
                'username'  => 'bail|required|alpha_num|unique:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.users,username,'.$id.',id_user',
                'password' => 'nullable|alpha_num|max:40',
            ];
        }
    }
}
