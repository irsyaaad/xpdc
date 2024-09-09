<?php

namespace Modules\Keuangan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\BayarPacking;
use Modules\Operasional\Entities\PackingBarang;
use Session;
use App\Models\Perusahaan;
use Modules\Keuangan\Entities\ACPerush;
use DB;
use Auth;
use Validator;

class BayarPackingController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {   
        $page = 50;
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = PackingBarang::getListPacking($page, $id_perush);
        $data["perusahaan"] = Perusahaan::getRoleUser();
        $data["ac"] = ACPerush::getACDebit($id_perush);
        
        return view('keuangan::bayarpacking.bayarpacking', $data);
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        abort(404);
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'ac4_d' => 'bail|numeric|required|exists:'.env('DB_CONNECTION').'.'.env('DB_DATABASE').'.m_ac_perush,id_ac',
            'n_bayar' => 'bail|numeric|required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }  

        if(!isset($request->c_pro) && $request->c_pro==null){
            return redirect()->back()->with('error', 'Komponen Biaya Tidak dipilih');
        }
        
        DB::beginTransaction();
        try {
            $a_packing = [];
            $a_bayar = [];
            
            if(count($request->c_pro) > 1){
                $total = 0;
                
                foreach($request->c_pro as $key => $value){
                    $packing = PackingBarang::findOrFail($value);
                    $sisa = $packing->n_total - $packing->n_bayar;
                    
                    $total += $sisa;
                    
                    $a_bayar[$key]["id_packing"] = $value; 
                    $a_bayar[$key]["id_stt"] = $packing->id_stt;
                    $a_bayar[$key]["id_perush"] = $packing->id_perush; 
                    $a_bayar[$key]["id_perush_kirim"] = $packing->id_perush_kirim;
                    $a_bayar[$key]["tgl_bayar"] = date("Y-m-d");
                    $a_bayar[$key]["ac4_d"] = $request->ac4_d;
                    $a_bayar[$key]["ac4_k"] = $packing->ac4_d;
                    $a_bayar[$key]["n_bayar"] = $sisa;
                    $a_bayar[$key]["id_user"] = Auth::user()->id_user;
                    $a_bayar[$key]["created_at"] = date("Y-m-d H:i:s");
                    $a_bayar[$key]["updated_at"] = date("Y-m-d H:i:s");
                    $time = time();
                    $a_bayar[$key]["kode_bayar"] = $value.substr($time, 3,10);
                    $a_bayar[$key]["keterangan"] = "Bayar Packing No. STT".$packing->kode_stt;

                    if($request->keterangan != null){
                        $a_bayar[$key]["keterangan"] = $request->keterangan;
                    }
                    
                    $a_packing[$key]["id_packing"] = $value;
                    $a_packing[$key]["n_bayar"] = $packing->n_total;
                    $a_packing[$key]["is_lunas"] = true;
                }
                
                if($request->n_bayar != $total){
                    return redirect()->back()->with('error', 'Nominal Bayar Tidak Sama');
                }

            }else{
                foreach($request->c_pro as $key => $value){
                    $packing = PackingBarang::findOrFail($value);
                    $sisa = $packing->n_total - $packing->n_bayar;
                    
                    if($request->n_bayar > $sisa){
                        return redirect()->back()->with('error', 'Nominal Batar Terlalu Besar ');
                    }
                    
                    $total = $packing->n_bayar + $request->n_bayar;
                    
                    $a_bayar[$key]["id_packing"] = $value; 
                    $a_bayar[$key]["id_stt"] = $packing->id_stt;
                    $a_bayar[$key]["id_perush"] = $packing->id_perush; 
                    $a_bayar[$key]["id_perush_kirim"] = $packing->id_perush_kirim;
                    $a_bayar[$key]["tgl_bayar"] = date("Y-m-d");
                    $a_bayar[$key]["ac4_d"] = $request->ac4_d;
                    $a_bayar[$key]["ac4_k"] = $packing->ac4_d;
                    $a_bayar[$key]["n_bayar"] = $request->n_bayar;
                    $a_bayar[$key]["id_user"] = Auth::user()->id_user;
                    $a_bayar[$key]["created_at"] = date("Y-m-d H:i:s");
                    $a_bayar[$key]["updated_at"] = date("Y-m-d H:i:s");
                    $time = time();
                    $a_bayar[$key]["kode_bayar"] = $value.substr($time, 3,10);
                    $a_bayar[$key]["keterangan"] = "Bayar Packing No. STT".$packing->kode_stt;
                    
                    if($request->keterangan != null){
                        $a_bayar[$key]["keterangan"] = $request->keterangan;
                    }

                    $a_packing[$key]["id_packing"] = $value;
                    $a_packing[$key]["n_bayar"] = $total;
                    $a_packing[$key]["is_lunas"] = false;

                    if($packing->n_total == $total){
                        $a_packing[$key]["is_lunas"] = true;
                    }
                    
                }
            }
            // insert bayar packing
            BayarPacking::insert($a_bayar);
            foreach($a_packing as $key => $value){
                $data = [];
                $data["id_packing"] = $value["id_packing"];
                $data["n_bayar"] = $value["n_bayar"];
                $data["is_lunas"] = $value["is_lunas"];
                PackingBarang::where("id_packing", $data["id_packing"])->update($data);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pembayaran Biaya Gagal Disimpan'.$e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Data Pembayaran Biaya Sukses Disimpan');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        return view('keuangan::show');
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        abort(404);
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, $id)
    {
        abort(404);
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        abort(404);
    }
}
