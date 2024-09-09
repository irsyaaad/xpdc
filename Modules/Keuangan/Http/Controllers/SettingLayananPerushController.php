<?php

namespace Modules\Keuangan\Http\Controllers;

use App\Models\Layanan;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Keuangan\Entities\ACPerush;
use Modules\Keuangan\Entities\SettingGroupLayanan;
use Modules\Keuangan\Entities\SettingLayananPerush;
use Modules\Keuangan\Http\Requests\SettingLayananRequest;
use Session;

class SettingLayananPerushController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $data["data"] = SettingLayananPerush::with("layanan", "diskon", "piutang", "pendapatan", "ppn", "materai", "asuransi", "user")
            ->where("id_perush", $id_perush)->get();

        return view('keuangan::settinglayanan', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["layanan"] = Layanan::all();
        $data["akun"] = ACPerush::select("id_ac", "nama")->get();

        return view('keuangan::settinglayanan', $data);
    }

    public function generate()
    {
        DB::beginTransaction();
        try {
            SettingLayananPerush::where('id_perush', Session("perusahaan")["id_perush"])->delete();

            $data = SettingGroupLayanan::all();
            $setting = [];
            foreach ($data as $key => $value) {
                $setting[$key]["id_user"] = Auth::user()->id_user;
                $setting[$key]["ac_pendapatan"] = $value->ac_pendapatan;
                $setting[$key]["ac_diskon"] = $value->ac_diskon;
                $setting[$key]["ac_ppn"] = $value->ac_ppn;
                $setting[$key]["ac_materai"] = $value->ac_materai;
                $setting[$key]["ac_piutang"] = $value->ac_piutang;
                $setting[$key]["ac_asuransi"] = $value->ac_asuransi;
                $setting[$key]["ac_packing"] = $value->ac_packing;
                $setting[$key]["id_layanan"] = $value->id_layanan;
                $setting[$key]["id_perush"] = Session("perusahaan")["id_perush"];
                $setting[$key]["created_at"] = date("Y-m-d h:i:s");
                $setting[$key]["updated_at"] = date("Y-m-d h:i:s");
            }
            SettingLayananPerush::insert($setting);

            DB::commit();
        } catch (Exception $e) {
            DB::commit();
            return redirect()->back()->with('error', 'Data Setting Group Layanan Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Setting Group Layanan Disimpan');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SettingLayananRequest $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingLayananPerush::where("id_layanan", $request->id_layanan)
            ->where("ac_pendapatan", $request->ac_pendapatan)
            ->where("ac_diskon", $request->ac_diskon)
            ->where("ac_ppn", $request->ac_ppn)
            ->where("ac_materai", $request->ac_materai)
            ->where("ac_piutang", $request->ac_piutang)
            ->where("ac_asuransi", $request->ac_asuransi)
            ->where("id_perush", $id_perush)
            ->get()->first();

        if ($cek != null) {
            return redirect()->back()->with('error', 'Data Setting Group Layanan Sudah Ada ! ');
        }

        try {

            // save to user
            DB::beginTransaction();

            $setting = new SettingLayananPerush();
            $setting->id_user = Auth::user()->id_user;
            $setting->ac_pendapatan = $request->ac_pendapatan;
            $setting->ac_diskon = $request->ac_diskon;
            $setting->ac_ppn = $request->ac_ppn;
            $setting->ac_materai = $request->ac_materai;
            $setting->ac_piutang = $request->ac_piutang;
            $setting->ac_asuransi = $request->ac_asuransi;
            $setting->ac_packing = $request->ac_packing;
            $setting->id_layanan = $request->id_layanan;
            $setting->id_perush = $id_perush;
            $setting->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Layanan Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Setting Group Layanan  Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["layanan"] = Layanan::all();
        $data["akun"] = ACPerush::select("id_ac", "nama")->get();
        $data["data"] = SettingLayananPerush::findOrFail($id);

        return view('keuangan::settinglayanan', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SettingLayananRequest $request, $id)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $cek = SettingLayananPerush::where("id_layanan", $request->id_layanan)
            ->where("ac_pendapatan", $request->ac_pendapatan)
            ->where("ac_diskon", $request->ac_diskon)
            ->where("ac_ppn", $request->ac_ppn)
            ->where("ac_materai", $request->ac_materai)
            ->where("ac_piutang", $request->ac_piutang)
            ->where("ac_asuransi", $request->ac_asuransi)
            ->where("id_perush", $id_perush)
            ->get()->first();

        // if ($cek != null) {
        //     return redirect()->back()->with('error', 'Data Setting Group Layanan Sudah Ada ! ');
        // }

        try {

            // save to user
            DB::beginTransaction();

            $setting = SettingLayananPerush::findOrFail($id);
            $setting->id_user = Auth::user()->id_user;
            $setting->ac_pendapatan = $request->ac_pendapatan;
            $setting->ac_diskon = $request->ac_diskon;
            $setting->ac_ppn = $request->ac_ppn;
            $setting->ac_materai = $request->ac_materai;
            $setting->ac_piutang = $request->ac_piutang;
            $setting->ac_asuransi = $request->ac_asuransi;
            $setting->ac_packing = $request->ac_packing;
            $setting->id_layanan = $request->id_layanan;
            $setting->id_perush = $id_perush;
            $setting->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Layanan Gagal Disimpan ' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Setting Group Layanan  Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */

    public function destroy($id)
    {
        try {

            // save to user
            DB::beginTransaction();

            $setting = SettingLayananPerush::findOrFail($id);
            $setting->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Setting Group Layanan Gagal Dihapus ' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Setting Group Layanan  Dihapus');
    }
}
