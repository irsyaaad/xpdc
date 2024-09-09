<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\Vendor;
use Modules\Operasional\Entities\CaraBayar;
use DB;
use Auth;
use App\Http\Requests\VendorRequest;
use App\Models\RoleUser;
use App\Models\GroupVendor;
use Modules\Keuangan\Entities\GroupBiaya;
use Modules\Keuangan\Entities\ACPerush;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $page = 50;
        $f_id_grup_ven = $request->f_id_grup_ven;
        $f_id_ven = $request->f_id_ven;
        $id_perus = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data = [];
        $data["data"] = Vendor::getListVendor($page, $f_id_grup_ven, $f_id_ven);
        $data["group"] = GroupVendor::select("id_grup_ven", "nm_grup_ven")->get();
        
        if($f_id_ven != null){
            $f_id_ven = Vendor::select("id_ven", "nm_ven")->where("id_ven", $f_id_ven)->get()->first();
        }

        $data["filter"] = array("f_id_ven" => $f_id_ven, "f_id_grup_ven" => $f_id_grup_ven, "page"=> $page);

        return view("vendor", $data);
    }

    public function filter(Request $request)
    {
        $page = 50;
        $f_id_grup_ven = $request->f_id_grup_ven;
        $f_id_ven = $request->f_id_ven;
        $id_perus = Session("perusahaan")["id_perush"];
        
        if(isset($request->shareselect)){
            $page = $request->shareselect;
        }

        $data = [];
        $data["data"] = Vendor::getListVendor($page, $f_id_grup_ven, $f_id_ven);
        $data["group"] = GroupVendor::select("id_grup_ven", "nm_grup_ven")->get();
        
        if($f_id_ven != null){
            $f_id_ven = Vendor::select("id_ven", "nm_ven")->where("id_ven", $f_id_ven)->get()->first();
        }

        $data["filter"] = array("f_id_ven" => $f_id_ven, "f_id_grup_ven" => $f_id_grup_ven, "page"=> $page);
        
        return view("vendor", $data);
    }
    
    public function create()
    {
        $data["carabayar"] = CaraBayar::all();
        $data["group"] = GroupVendor::all();
        $data["biaya"] = GroupBiaya::getList();
        $data["kredit"] = ACPerush::getList(Session("perusahaan")["id_perush"]);

        return view("vendor", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {
        try {
            // save to group
            DB::beginTransaction();
            
            $vendor                 = new Vendor();
            $vendor->nm_ven     = $request->nm_ven;
            $vendor->id_grup_ven     = $request->id_grup_ven;
            $vendor->id_wil     = $request->id_wil;
            $vendor->alm_ven     = $request->alm_ven;
            $vendor->telp_ven     = $request->telp_ven;
            $vendor->npwp     = $request->npwp;
            $vendor->nm_pemilik     = $request->nm_pemilik;
            $vendor->email_ven     = $request->email_ven;
            $vendor->kontak_ven     = $request->kontak_ven;
            $vendor->kontak_hp     = $request->kontak_hp;
            $vendor->cara_bayar     = $request->cara_bayar;
            $vendor->hari_inv     = $request->hari_inv;
            $vendor->is_aktif        = $request->is_aktif;
            $vendor->id_user         = Auth::user()->id_user;
            $vendor->id_perush           = Session("perusahaan")["id_perush"];
            $vendor->cara_bayar     = $request->cara_bayar;
            $vendor->id_biaya_grup        = $request->id_biaya_grup;
            $vendor->ac4_kredit        = $request->ac4_kredit;
            $vendor->ac4_debet        = $request->ac4_debet;
            $vendor->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Vendor Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Vendor Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data["data"] = Vendor::with("wilayah", "perusahaan", "group", "cara")->findOrFail($id);
        $data["carabayar"] = CaraBayar::all();
        $data["group"] = GroupVendor::all();
        $data["biaya"] = GroupBiaya::getList();
        $data["kredit"] = ACPerush::getList(Session("perusahaan")["id_perush"]);
        return view("vendor", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["data"] = Vendor::with("wilayah", "perusahaan", "group", "cara")->findOrFail($id);
        $data["carabayar"] = CaraBayar::all();
        $data["group"] = GroupVendor::all();
        $data["biaya"] = GroupBiaya::getList();
        $data["kredit"] = ACPerush::getList(Session("perusahaan")["id_perush"]);
        //dd($data);
        return view("vendor", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorRequest $request, $id)
    {
        try {
            // save to group
            DB::beginTransaction();

            $vendor                 = Vendor::findOrFail($id);
            $vendor->nm_ven     = $request->nm_ven;
            $vendor->id_grup_ven     = $request->id_grup_ven;
            $vendor->id_wil     = $request->id_wil;
            $vendor->alm_ven     = $request->alm_ven;
            $vendor->telp_ven     = $request->telp_ven;
            $vendor->npwp     = $request->npwp;
            $vendor->nm_pemilik     = $request->nm_pemilik;
            $vendor->email_ven     = $request->email_ven;
            $vendor->kontak_ven     = $request->kontak_ven;
            $vendor->kontak_hp     = $request->kontak_hp;
            $vendor->cara_bayar     = $request->cara_bayar;
            $vendor->hari_inv     = $request->hari_inv;
            $vendor->is_aktif        = $request->is_aktif;
            $vendor->id_user         = Auth::user()->id_user;
            $vendor->id_perush           = Session("perusahaan")["id_perush"];
            $vendor->cara_bayar     = $request->cara_bayar;
            $vendor->id_biaya_grup        = $request->id_biaya_grup;
            $vendor->ac4_kredit        = $request->ac4_kredit;
            $vendor->ac4_debet        = $request->ac4_debet;
            $vendor->save();
            
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Vendor Gagal Disimpan'.$e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Vendor Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect(route_redirect())->with('success', 'Data Vendor Dihapus');
    }
}
