<?php

namespace Modules\Kepegawaian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\MarketingActivity;
use Modules\Kepegawaian\Entities\Marketing;
use Modules\Kepegawaian\Entities\Activity;
use App\Models\Pelanggan;
use DB;

class MarketingActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data["marketing"] = Marketing::where('id_perush', Session('perusahaan')['id_perush'])->get();
        $data['data'] = MarketingActivity::with('perush', 'pelanggan', 'activity')->where('id_perush', Session('perusahaan')['id_perush'])->paginate(25);
        // dd($data);
        return view('kepegawaian::activity-marketing.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["marketing"] = Marketing::where('id_perush', Session('perusahaan')['id_perush'])->get();
        $data["activity"] = Activity::all();
        $data["pelanggan"] = Pelanggan::where('id_perush', Session('perusahaan')['id_perush'])->get();
        return view('kepegawaian::activity-marketing.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl' => 'required',
            'marketing' => 'bail|required',
            'activity' => 'bail|required',
            'pelanggan' => 'bail|required',
            'nama' => 'bail|required',
            'alamat' => 'bail|nullable|max:256',
            'keterangan' => 'bail|nullable|max:256',
        ]);

        DB::beginTransaction();
        try {
            $activity = New MarketingActivity();
            $activity->tgl = $request->tgl;
            $activity->id_marketing = $request->marketing;
            $activity->id_pelanggan = $request->pelanggan;
            $activity->id_activity = $request->activity;
            $activity->nama = $request->nama;
            $activity->id_perush = Session('perusahaan')['id_perush'];
            $activity->alamat = $request->alamat;
            $activity->keterangan = $request->keterangan;
            $activity->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Activity Gagal Disimpan' .$e->getMessage());
        }

        return redirect(url("activity-marketing"))->with('success', 'Data Activity Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data["data"] = MarketingActivity::findOrFail($id);
        // dd($data);
        return view('kepegawaian::activity-marketing.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = MarketingActivity::findOrFail($id);
        $data["marketing"] = Marketing::where('id_perush', Session('perusahaan')['id_perush'])->get();
        $data["activity"] = Activity::all();
        $data["pelanggan"] = Pelanggan::where('id_perush', Session('perusahaan')['id_perush'])->get();
        // dd($data);
        return view('kepegawaian::activity-marketing.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl' => 'required',
            'marketing' => 'bail|required',
            'activity' => 'bail|required',
            'pelanggan' => 'bail|required',
            'nama' => 'bail|required',
            'alamat' => 'bail|nullable|max:256',
            'keterangan' => 'bail|nullable|max:256',
        ]);

        DB::beginTransaction();
        try {
            $activity = MarketingActivity::findOrFail($id);
            $activity->tgl = $request->tgl;
            $activity->id_marketing = $request->marketing;
            $activity->id_pelanggan = $request->pelanggan;
            $activity->id_activity = $request->activity;
            $activity->nama = $request->nama;
            $activity->id_perush = Session('perusahaan')['id_perush'];
            $activity->alamat = $request->alamat;
            $activity->keterangan = $request->keterangan;
            $activity->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Activity Gagal Disimpan' .$e->getMessage());
        }

        return redirect(url("activity-marketing"))->with('success', 'Data Activity Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $dok = MarketingActivity::findOrFail($id);
            $dok->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Activity Marketing Gagal Dihapus');
        }
        return redirect()->back()->with('success', 'Data Activity Marketing Dihapus');
    }
}
