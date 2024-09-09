<?php

namespace App\Http\Controllers\OpenApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarif;

class CekTarifController extends Controller
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
        $GetTarif = Tarif::with('asal','tujuan','layanan')->where('id_perush',$this->perushId)->get();
        return response()->json($GetTarif);
    }

    public function store(Request $request){
        $kota_asal       = $request->input('kota_asal');
        $kota_tujuan     = $request->input('kota_tujuan');
        $GetTarif = Tarif::with('asal', 'tujuan', 'layanan')
            ->where('id_perush', $this->perushId)
            ->whereHas('asal', function ($query) use ($kota_asal) {
                $query->where('nama_wil', $kota_asal);
            })
            ->whereHas('tujuan', function ($query) use ($kota_tujuan)  {
                $query->where('nama_wil', $kota_tujuan);
            })
            ->get();
        return response()->json($GetTarif);
    }
}
