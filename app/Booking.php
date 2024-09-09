<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Perusahaan;
use Session;

class Booking extends Model
{   
    protected $table = 'booking';
    protected $primaryKey = 'id';
    
    public static function getBooking($tgl_mulai, $tgl_selesai)
    {
        $user = User::where("id_user", "3")->firstOrFail();
        $token = $user->token;
        $url = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        
        $username = "eye1905";
        $password = "admin123";
        $data = [];
        if($token == null or $token ==""){
            $toks = self::requestToken($username, $password, $url->url_booking);
            $token = $toks->Respon_data->token;
            $user->token = $token;
            $user->save();
            $data = self::reqData($token, $tgl_mulai, $tgl_selesai, $url->url_booking);
        }else{
            $check = self::checkToken($token, $url->url_booking);
            if($check->Respon_status==0){
                $data = self::reqData($token, $tgl_mulai, $tgl_selesai, $url->url_booking);
            }else{
                $toks = self::requestToken($username, $password, $url->url_booking);
                $token = $toks->Respon_data->token;
                $user->token = $token;
                $user->save();
                $data = self::reqData($token, $tgl_mulai, $tgl_selesai, $url->url_booking);
            }
        }
        
        return $data;
    }
    
    public static function donebooking($kode)
    {
        $user = User::where("id_user", "3")->firstOrFail();
        $token = $user->token;
        $url = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        
        $username = "eye1905";
        $password = "admin123";
        $data = [];
        if($token == null or $token ==""){
            $toks = self::requestToken($username, $password, $url->url_booking);
            $token = $toks->Respon_data->token;
            $user->token = $token;
            $user->save();
            $data = self::getdone($token, $kode, $url->url_booking);
        }else{
            $check = self::checkToken($token, $url->url_booking);
            if($check->Respon_status==0){
                $data = self::getdone($token, $kode, $url->url_booking);
            }else{
                $toks = self::requestToken($username, $password, $url->url_booking);
                $token = $toks->Respon_data->token;
                $user->token = $token;
                $user->save();
                $data = self::getdone($token, $kode, $url->url_booking);
            }
        }
        
        return $data;
    }

    public static function getdone($token, $id, $url)
    {
        $req = array(
            'kode_booking' => $id,
            'token' => $token
        );
        
        $url = $url."/booking/doneimport";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($list);
        
        return $response;
    }

    public static function checkToken($token, $url)
    {
        $req = array();
        $url = $url."/booking/checktoken";
        $req = array(
            'token' => $token
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($list);

        return $response;
    }
    
    public static function getDetail($kode)
    {
        $user = User::where("id_user", "3")->firstOrFail();
        $token = $user->token;
        $url = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);
        
        $username = "eye1905";
        $password = "admin123";
        $username = $user->username;
        $password = $user->password;
        $data = [];
        if($token == null or $token ==""){
            $toks = self::requestToken($username, $password, $url->url_booking);
            $token = $toks->Respon_data->token;
            $user->token = $token;
            $user->save();
            $data = self::reqId($token, $kode, $url->url_booking);
        }else{
            $check = self::checkToken($token, $url->url_booking);
            if($check->Respon_status==0){
                $data = self::reqId($token, $kode, $url->url_booking);
            }else{
                $toks = self::requestToken($username, $password, $url->url_booking);
                $token = $toks->Respon_data->token;
                $user->token = $token;
                $user->save();
                $data = self::reqId($token, $kode, $url->url_booking);
            }
        }
        
        return $data;
    }

    public static function reqId($token, $id, $url)
    {
        $req = array(
            'kode_booking' => $id,
            'token' => $token
        );
        
        $url = $url."/booking/getbykode";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($list);
        
        return $response;
    }
    
    public static function reqData($token, $tgl_mulai, $tgl_selesai, $url)
    {
        $req = array(
            'start' => $tgl_mulai,
            'end' => $tgl_selesai,
            'token' => $token
        );
        
        $url = $url."/booking/getbydate";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($list);
        
        return $response;
    }
    
    public static function requestToken($username, $password, $url)
    {
        $req = array();
        $url = $url."/booking/login";
        $req = array(
            'username' => $username,
            'password' => $password
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($list);
        
        return $response;
    }
}
