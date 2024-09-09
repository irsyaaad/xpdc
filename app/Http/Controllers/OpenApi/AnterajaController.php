<?php

namespace App\Http\Controllers\OpenApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tarif;
use App\Models\Wilayah;

class AnterajaController extends Controller
{
    protected $userId;
    protected $perushId;

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->userId   = $request->attributes->get('user_id');
            $this->perushId = $request->attributes->get('id_perush');

            if (!$this->userId || !$this->perushId) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }
    
    public function index(Request $request)
    {
        // $GetTarif = Tarif::with('asal','tujuan','layanan')->where('id_perush',$this->perushId)->get();
        // return response()->json($GetTarif);
    }

    public function store(Request $request){
        $data = $request->json()->all();
        
        $rules = [
            'no_resi'           => 'required|string|max:255',
            'nama_pengirim'     => 'required|string',
            'telp_pengirim'     => 'required|numeric',
            'alamat_pengirim'   => 'required|string',
            'kota_pengirim'     => 'required|string',
            'nama_penerima'     => 'required|string',
            'telp_penerima'     => 'required|numeric',
            'alamat_penerima'   => 'required|string',
            'kota_penerima'     => 'required|string',
            'berat'             => 'required|numeric',
            'volume'            => 'required|numeric',
            'koli'              => 'required|numeric',
            'kubik'             => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total'             => 'required|numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $CekKotaPengirim = Wilayah::where('nama_wil',$data['kota_pengirim'])->first();
        $CekKotaPenerima = Wilayah::where('nama_wil',$data['kota_penerima'])->first();
        if(!$CekKotaPengirim){
            return response()->json([
                'errors' => "Kota Pengirim Tidak Ditemukan"
            ], 422);
        }
        if(!$CekKotaPenerima){
            return response()->json([
                'errors' => "Kota Penerima Tidak Ditemukan"
            ], 422);
        }

        $DataInsert = [
            'no_resi'               => $data['no_resi'],
            'pengirim_nm'           => $data['nama_pengirim'],
            'pengirim_telp'         => $data['telp_pengirim'],
            'pengirim_alm'          => $data['alamat_pengirim'],
            'pengirim_id_region'    => $CekKotaPengirim->id_wil,
            'pengirim_kodepos'      => $data['kodepos_pengirim'],
            'id_tipe_kirim'         => 2,
            'penerima_nm'           => $data['nama_penerima'],
            'penerima_telp'         => $data['telp_penerima'],
            'penerima_alm'          => $data['alamat_penerima'],
            'penerima_id_region'    => $CekKotaPenerima->id_wil,
            'n_berat'               => $data['berat'],
            'n_volume'              => $data['volume'],
            'n_koli'                => $data['koli'],
            'n_kubik'               => $data['kubik'],
            'c_total'               => $data['total'],
        ];
        dd($DataInsert);

    }
}
