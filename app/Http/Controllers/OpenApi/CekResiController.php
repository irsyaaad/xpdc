<?php

namespace App\Http\Controllers\OpenApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MTOrder;

class CekResiController extends Controller
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
    
    public function store(Request $request){
        $awb        = $request->input('no_resi');
        $GetResi    = MTOrder::with('history')->where('no_awb',$awb)->first();
        return response()->json($GetResi);
    }
}
