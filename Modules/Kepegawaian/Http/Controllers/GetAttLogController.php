<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\SettingJam;
use Modules\Kepegawaian\Entities\JenisPerijinan;
use Modules\Kepegawaian\Entities\Perijinan;
use App\Libraries\Excel_reader;
use App\Libraries\SpreadsheetReader;
use App\Libraries\SimpleXLSX;
use DB;
use Auth;
use Exception;
use File;
use Illuminate\Support\Facades\Storage;
use App\User;
use Hash;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use DateTime;
use Carbon\Carbon;
use Session;
use Modules\Kepegawaian\Entities\MesinFinger;
use Validator;
use Illuminate\Support\Facades\Log;

class GetAttLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('kepegawaian::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('kepegawaian::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('kepegawaian::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('kepegawaian::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function save_log(Request $request)
    {
        $original_data  = file_get_contents('php://input');
        $decoded_data   = json_decode($original_data, true);
        $encoded_data   = json_encode($decoded_data);

        if (isset($decoded_data['type']) AND isset($decoded_data['cloud_id']))
        {

            $type       = $decoded_data['type'];
            $cloud_id   = $decoded_data['cloud_id'];
            $created_at = date('Y-m-d H:i:s');

            // $sql    = "INSERT INTO t_log (cloud_id,type,created_at,original_data) VALUES ('".$cloud_id."', '".$type."', '".$created_at."', '".$encoded_data."')";
            // $result = mysqli_query($conn, $sql);

        }

        if (isset($request)) {
            $datanya = 'tester';
            $file_name = "AttLog.txt";
            Storage::put($file_name, json_encode($request->all()));
            Log::info('Create File AttLog');
        }
        
    }

    public function show_image(Request $request)
    {
        $imagePath = $request->url; // Bisa juga URL seperti 'http://example.com/image.jpg'
        
        // Mendapatkan konten gambar
        $imageData = file_get_contents($imagePath);

        // Mendapatkan tipe mime gambar
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);

        // Mengatur header untuk tipe konten gambar
        header("Content-Type: $mimeType");

        // Menampilkan gambar
        echo $imageData;
    }
    
}
