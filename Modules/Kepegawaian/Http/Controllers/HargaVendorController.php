<?php

namespace Modules\Kepegawaian\Http\Controllers;

use DB;
use Auth;
use Session;
use Exception;
use Validator;
use DataTables;
use App\Models\Module;
use App\Models\Logging;
use App\Models\Wilayah;
use App\Models\Perusahaan;
use App\Models\HargaVendor;
use App\Models\ImportHarga;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Busdev\Entities\VendorBusdev;
use Modules\Busdev\Entities\RelationModel;

class HargaVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // return redirect('busdevmarketing');
        // $page = $request->page != null ? $request->page : 1;
        // $perpage = $request->shareselect != null ? $request->shareselect : 50;
        // $id_asal = $request->id_asal != null ? $request->id_asal : null;
        // $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
        // $id_ven = $request->id_ven != null ? $request->id_ven : null;
        // $updated = $request->updated != null ? $request->updated : null;
        // $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        // $data["data"] = HargaVendor::getData2($page, $perpage, $id_asal, $id_tujuan, $id_ven, $updated);

        $page = $request->page != null ? $request->page : 1;
        $perpage = $request->shareselect != null ? $request->shareselect : 50;
        $id_asal = $request->id_asal != null ? $request->id_asal : null;
        $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
        $type = $request->type != null ? $request->type : null;
        $range = $request->range != null ? $request->range : null;
        $id_ven = $request->id_ven != null ? $request->id_ven : null;
        $updated = null;

        if (Session("role")["nm_role"] != null && strtolower(Session("role")["nm_role"]) == "marketing") {
            if ($id_asal != null || $id_tujuan != null) {
                $ids = null;
                try {
                    // save to user
                    DB::beginTransaction();
                    $loggin = new Logging();
                    $loggin->id_asal = $request->id_asal;
                    $loggin->id_tujuan = $request->id_tujuan;
                    $loggin->id_pengguna = Auth::user()->id_user;
                    $loggin->save();
                    $ids = DB::getPdo()->lastInsertId();

                    DB::commit();
                } catch (Exception $e) {
                    dd($e->getMessage());
                    DB::rollback();
                }
            }
        }

        $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        $term = $request->term;
        $data["wilayahrute"] = Wilayah::getKecamatan2($term);
        // Check user role
        if (strtolower(Session("role")["nm_role"]) == "busdev") {
            // Busdev role
            $data["data"] = HargaVendor::getData($page, $perpage, $id_asal, $id_tujuan, $id_ven, $updated, $range, $type, );
        } else {
            // Other roles
            $data["data"] = HargaVendor::getDataMarketingnew($page, $perpage, $id_asal, $id_tujuan, $id_ven, $updated, $range, $type, );
        }

        $data["filter"] = array(
            'page' => $perpage,
            'tujuan' => Wilayah::getOnes($id_tujuan),
            'asal' => Wilayah::getOnes($id_asal),
            'id_ven' => $id_ven,
            'range' => $range,
            'type' => $type,
            'updated' => $updated,
        );


        if (strtolower(Session("role")["nm_role"]) == "busdev") {
            // return view('kepegawaian::hargavendor.index', $data);
            $data["module"] = Module::getSessionModul();
            $data["content"] = 'busdev::contents.adminbusdev.hargavendor.hargavendorlist';
            return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
        } else {
            // return view('kepegawaian::hargavendor.card', $data);
            $data["module"] = Module::getSessionModul();
            $data["content"] = 'busdev::contents.marketing.hargavendor.hargavendorcard';
            return view('busdev::metroniclapan-template.mainview-busdevs', $data);
            //   return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
        }
    }

    // modul data wilayah busdev


    public function laporanimpor(Request $request)
    {
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        if (isset($request->kab_asal) && $request->kab_asal != "" && $request->kab_asal != null && isset($request->kab_tujuan) && $request->kab_tujuan != "" && $request->kab_tujuan != null) {
            // dd($request);

            $page = $request->page != null ? $request->page : 1;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
            $stts = $request->stts != null ? $request->stts : 1;
            if ($stts != 1) {
                $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            } else {
                $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            }

            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->get_datacetak($id_asal, $id_tujuan, $page, $perpage);

            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
                'stts' => $stts
            );
            // dd($data['filter']);

        } else {
            $page = $request->page != null ? $request->page : 1;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
            $type = $request->type != null ? $request->type : null;
            $range = $request->range != null ? $request->range : null;
            $id_ven = $request->id_ven != null ? $request->id_ven : null;
            $stts = $request->stts != null ? $request->stts : 1;
            if ($stts != 1) {
                $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            } else {
                $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            }
            $updated = null;

            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->get_datacetak($id_asal, $id_tujuan, $page, $perpage);

            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
                'id_ven' => $id_ven,
                'range' => $range,
                'type' => $type,
                'updated' => $updated,
                'stts' => $stts
            );
            // dd($data['filter']);
        }

        $data["content"] = "busdev::contents.laporanbusdev.laporanbusdev";
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }

    public function cetakimport(Request $request)
    {
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        if (isset($request->kab_asal) && $request->kab_asal != "" && $request->kab_asal != null && isset($request->kab_tujuan) && $request->kab_tujuan != "" && $request->kab_tujuan != null) {
            // dd($request);

            $page = $request->page != null ? $request->page : 1;
            $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
            $stts = $request->stts != null ? $request->stts : 1;

            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->get_datacetak($id_asal, $id_tujuan, $page, $perpage);

            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
                'stts' => $stts
            );


        } else {
            $page = $request->page != null ? $request->page : 1;
            $perpage = $request->shareselect != null ? $request->shareselect : 5000;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
            $type = $request->type != null ? $request->type : null;
            $range = $request->range != null ? $request->range : null;
            $id_ven = $request->id_ven != null ? $request->id_ven : null;
            $updated = null;
            $stts = $request->stts != null ? $request->stts : 1;


            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->get_datacetak($id_asal, $id_tujuan, $page, $perpage);


            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
                'id_ven' => $id_ven,
                'range' => $range,
                'type' => $type,
                'updated' => $updated,
                'stts' => $stts
            );
        }
        return view('busdev::contents.laporanbusdev.cetaklaporanimport', $data);
    }

    public function templatedirects(Request $request)
    {
        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() . "/assets-adminbusdev/contohimport/templatedirect.xlsx";

        $headers = array(
            'Content-Type: application/vnd.ms-excel; charset=utf-8',
        );

        return response()->download($file, 'templatedirect.xlsx', $headers);
    }

    public function riwayatcari(Request $request)
    {
        $data["perusahaan"] = Perusahaan::findOrFail(Session("perusahaan")["id_perush"]);

        if (isset($request->kab_asal) && $request->kab_asal != "" && $request->kab_asal != null && isset($request->kab_tujuan) && $request->kab_tujuan != "" && $request->kab_tujuan != null) {
            // dd($request);

            $page = $request->page != null ? $request->page : 1;
            $perpage = $request->shareselect != null ? $request->shareselect : 50;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;

            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->getloggingpencarian($id_asal, $id_tujuan, $page, $perpage);

            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
            );


        } else {
            $page = $request->page != null ? $request->page : 1;
            $perpage = $request->shareselect != null ? $request->shareselect : 50;
            $id_asal = $request->id_asal != null ? $request->id_asal : null;
            $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
            $type = $request->type != null ? $request->type : null;
            $range = $request->range != null ? $request->range : null;
            $id_ven = $request->id_ven != null ? $request->id_ven : null;
            $updated = null;

            // Invoking that non-static method.
            $foo = new HargaVendor();
            $data["data"] = $foo->getloggingpencarian($id_asal, $id_tujuan, $page, $perpage);


            $data["filter"] = array(
                'page' => $perpage,
                'tujuan' => Wilayah::getOnes($id_tujuan),
                'asal' => Wilayah::getOnes($id_asal),
                'id_ven' => $id_ven,
                'range' => $range,
                'type' => $type,
                'updated' => $updated,
            );
        }

        $data["content"] = "busdev::contents.laporanbusdev.riwayatcari";
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);

    }

    public function wilayah(Request $request)
    {
        $data = Wilayah::getKecamatan();

        $response = [
            'message' => 'success',
            'code' => 0,
            'data' => $data,
        ];

        return response($response);
    }

    public function vendor(Request $request)
    {
        $data = VendorBusdev::select("nm_ven as label", "id_ven as value")->get();
        $response = [
            'message' => 'success',
            'code' => 0,
            'data' => $data,
        ];

        return response($response);
    }

    public function import(Request $request)
    {
        if (strtolower(Session("role")["nm_role"]) != "busdev") {
            abort(404);
        }
        try {
            DB::beginTransaction();
            $data = (new ImportHarga)->toArray(request()->file('files'))[0];
            $parent = [];
            $child = [];
            $par = null;
            $a_error = [];
            $i = 1;
            foreach ($data as $row) {
                $cek = HargaVendor::where("wil_asal", $row[2])
                    ->where("wil_tujuan", $row[3])
                    ->where("id_ven", $row[4])
                    ->get()->first();

                if ($cek != null) {
                    $a_error[$i] = [
                        'wil_asal' => $row[2],
                        'wil_tujuan' => $row[3],
                        'id_ven' => $row[4],
                        'harga' => $row[5],
                        'min_kg' => $row[6],
                        'hrg_kubik' => $row[7],
                        'min_kubik' => $row[8],
                        'time' => $row[9],
                        'keterangan' => $row[10],
                        'rekomendasi' => $row[11],
                        'same_balik' => $row[12],
                        'id_user' => Auth::user()->id_user,
                        'parent' => null,
                        'type' => 1,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];

                } else {
                    if ($row[1] == null) {
                        $parent = [
                            'wil_asal' => $row[2],
                            'wil_tujuan' => $row[3],
                            'id_ven' => $row[4],
                            'harga' => $row[5],
                            'min_kg' => $row[6],
                            'hrg_kubik' => $row[7],
                            'min_kubik' => $row[8],
                            'time' => $row[9],
                            'keterangan' => $row[10],
                            'rekomendasi' => $row[11],
                            'same_balik' => $row[12],
                            'id_user' => Auth::user()->id_user,
                            'parent' => null,
                            'type' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s")
                        ];
                        HargaVendor::insert($parent);
                        $par = DB::getPdo()->lastInsertId();
                    }

                    if ($row[1] != null) {
                        $child = [
                            'wil_asal' => $row[2],
                            'wil_tujuan' => $row[3],
                            'id_ven' => $row[4],
                            'harga' => $row[5],
                            'min_kg' => $row[6],
                            'hrg_kubik' => $row[7],
                            'min_kubik' => $row[8],
                            'time' => $row[9],
                            'keterangan' => $row[10],
                            'rekomendasi' => $row[11],
                            'same_balik' => $row[12],
                            'id_user' => Auth::user()->id_user,
                            'parent' => $par,
                            'type' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s")
                        ];

                        HargaVendor::insert($child);
                    }
                }
                $i++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Import Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }

        if (count($a_error) > 0) {
            $a_error = json_encode($a_error);
            // return redirect()->back()->with('success', 'Data Import Harga Vendor Berhasil Disimpan, tapi ada data yang sama '.$a_error);
            return redirect()->back()->with('error', 'Data telah diimport sebelumnya, mohon ulangi dengan data berbeda.');
        } else {
            return redirect()->back()->with('success', 'Data Import Harga Vendor Berhasil Disimpan');
        }

    }


    // replace is_replace new function
    public function importbaru(Request $request)
    {
        if (strtolower(Session("role")["nm_role"]) != "busdev") {
            abort(404);
        }

        try {
            DB::beginTransaction();

            $data = json_decode($request->data);
            $parent = [];
            $child = [];
            $par = null;
            $a_error = [];
            $hargatidakdiinsert = array();
            $i = 1;
            foreach ($data as $row) {
                $hargakg = (double) str_replace(['Rp. ', ','], '', $row[4]);
                $hargakubik = (double) str_replace(['Rp. ', ','], '', $row[6]);
                $leadtime = str_replace(' HARI', '', str_replace('-', ',', $row[8]));
                $leadtime2 = explode(",", $leadtime);
                $leadtimeVariabelkedua = isset($leadtime2[1]) ? $leadtime2[1] : null;
                $wil_asal_text = $row[0];
                $wil_tujuan_text = $row[2];
                $wil_vendor_text = $row[3];

                // Pengecekan kesamaan data
                $cek = HargaVendor::where("wil_asal", $row[11])
                    ->where("wil_tujuan", $row[12])
                    ->where("id_ven", $row[13])
                    ->first();

                if ($cek != null) {
                    $chargmin = $cek->min_kg;
                    if ($chargmin == $row[5]) {
                        if ($row[15] != null && $row[15] == '1') {
                            // Update data eksisting
                            $cek->harga = $hargakg;
                            $cek->min_kg = $row[5];
                            $cek->hrg_kubik = $hargakubik;
                            $cek->min_kubik = $row[7];
                            $cek->time = $leadtimeVariabelkedua;
                            $cek->keterangan = $row[9];
                            $cek->rekomendasi = $row[14];
                            $cek->same_balik = $row[10];
                            $cek->id_user = Auth::user()->id_user;
                            $cek->updated_at = date("Y-m-d H:i:s");
                            $cek->save();
                        } else {
                            array_push($hargatidakdiinsert, $row);
                        }
                    } elseif ($chargmin != $row[5]) {
                        if ($row[15] != null && $row[15] == '1') {
                            // Update data eksisting
                            $cek->harga = $hargakg;
                            $cek->min_kg = $row[5];
                            $cek->hrg_kubik = $hargakubik;
                            $cek->min_kubik = $row[7];
                            $cek->time = $leadtimeVariabelkedua;
                            $cek->keterangan = $row[9];
                            $cek->rekomendasi = $row[14];
                            $cek->same_balik = $row[10];
                            $cek->id_user = Auth::user()->id_user;
                            $cek->updated_at = date("Y-m-d H:i:s");
                            $cek->save();
                        } else {
                            // Insert data baru jika kolom 15 tidak diisi dan min_kg tidak sama
                            $child = [
                                'wil_asal' => $row[11],
                                'wil_tujuan' => $row[12],
                                'id_ven' => $row[13],
                                'harga' => $hargakg,
                                'min_kg' => $row[5],
                                'hrg_kubik' => $hargakubik,
                                'min_kubik' => $row[7],
                                'time' => $leadtimeVariabelkedua,
                                'keterangan' => $row[9],
                                'rekomendasi' => $row[14],
                                'same_balik' => $row[10],
                                'id_user' => Auth::user()->id_user,
                                'parent' => $cek->id,
                                'type' => 1,
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s")
                            ];
                            HargaVendor::insert($child);
                        }
                    } else {
                        if ($row[15] != null && $row[15] == '1') {
                            // Tambahkan pesan error jika kolom 15 diisi dan min_kg tidak sama
                            $a_error[] = [
                                'row' => $i,
                                'error' => 'min_kg tidak sama dan kolom 15 diisi.',
                                'wil_asal' => $row[11],
                                'wil_asal_text' => $row[0],
                                'wil_tujuan' => $row[12],
                                'wil_tujuan_text' => $row[2],
                                'vendor' => $row[13],
                                'vendor_text' => $row[3]
                            ];
                        } else {
                            // Insert data baru jika kolom 15 tidak diisi dan min_kg tidak sama
                            $child = [
                                'wil_asal' => $row[11],
                                'wil_tujuan' => $row[12],
                                'id_ven' => $row[13],
                                'harga' => $hargakg,
                                'min_kg' => $row[5],
                                'hrg_kubik' => $hargakubik,
                                'min_kubik' => $row[7],
                                'time' => $leadtimeVariabelkedua,
                                'keterangan' => $row[9],
                                'rekomendasi' => $row[14],
                                'same_balik' => $row[10],
                                'id_user' => Auth::user()->id_user,
                                'parent' => $cek->id,
                                'type' => 1,
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s")
                            ];
                            HargaVendor::insert($child);
                        }
                    }
                } else {
                    // Jika data tidak ada
                    if ($row[1] == null) {
                        $parent = [
                            'wil_asal' => $row[11],
                            'wil_tujuan' => $row[12],
                            'id_ven' => $row[13],
                            'harga' => $hargakg,
                            'min_kg' => $row[5],
                            'hrg_kubik' => $hargakubik,
                            'min_kubik' => $row[7],
                            'time' => $leadtimeVariabelkedua,
                            'keterangan' => $row[9],
                            'rekomendasi' => $row[14],
                            'same_balik' => $row[10],
                            'id_user' => Auth::user()->id_user,
                            'parent' => null,
                            'type' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ];
                        HargaVendor::insert($parent);
                        $par = DB::getPdo()->lastInsertId();
                    }
                    if ($row[1] != null) {
                        $child = [
                            'wil_asal' => $row[11],
                            'wil_tujuan' => $row[12],
                            'id_ven' => $row[13],
                            'harga' => $hargakg,
                            'min_kg' => $row[5],
                            'hrg_kubik' => $hargakubik,
                            'min_kubik' => $row[7],
                            'time' => $leadtimeVariabelkedua,
                            'keterangan' => $row[9],
                            'rekomendasi' => $row[14],
                            'same_balik' => $row[10],
                            'id_user' => Auth::user()->id_user,
                            'parent' => $par,
                            'type' => 1,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s")
                        ];
                        HargaVendor::insert($child);
                    }
                }
                $i++;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Import Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }
        $data["items"] = json_encode($hargatidakdiinsert);
        Session::put('items', $data["items"]);
        $pesan = "Proses import berhasil!, silahkan periksa kembali lembar sheet anda apabila terdapat data yang tidak berhasil diimport.";
        if (!empty($hargatidakdiinsert)) {
            $pesan = "Proses import berhasil. " . count($hargatidakdiinsert) . " data telah diimport sebelumnya!";
        }
        return redirect()->back()->with('success', $pesan);

    }


    public function datadirect()
    {
        if (strtolower(Session("role")["nm_role"]) != "busdev") {
            abort(404);
        }

        // $data['items'] = array(
        //     "Dummy"
        // );

        $data["content"] = "busdev::contents.adminbusdev.hargavendor.directimpor";
        if (Session::get("items") != null) {
            $data["items"] = json_decode(Session::get("items"));
        }
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }


    public function getdetail(string $id)
    {
        $harga = HargaVendor::findOrFail($id);
        $detail = HargaVendor::getDetail($id);
        $data = [];

        foreach ($detail as $key => $value) {
            // $last =  '<label style="font-size:7pt">' . $value->created_at . "<br>" . $value->insert_user . '</label>';
            // if ($value->update_user != null) {
            //     $last =   '<label style="font-size:7pt">' . $value->updated_at . "<br>" . $value->update_user . '</label>';
            // }

            if ($value->rekomendasi == "1" && $value->same_balik != "1") {
                $data[$key] = '<tr><td><label data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan" style="cursor:pointer"> ' . $value->nm_ven . ' <br><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></label> </td><td>' . $value->wil_asal . '</td><td>' . $value->wil_tujuan . '</td><td>' . toRupiah($value->harga) . ' / Kg <br> Min : ' . $value->min_kg . ' </td><td>'
                    . toRupiah($value->hrg_kubik) . '/ M3 <br> Min : ' . $value->min_kubik . ' </td><td>' . $value->time . ' Hari </td></tr>';
            } else if ($value->rekomendasi != "1" && $value->same_balik == "1") {
                $data[$key] = '<tr><td>' . $value->nm_ven . '<br><span class="badge badge-outline badge-info badge-success pt-1">Berlaku Sebaliknya</span></td><td>' . $value->wil_asal . '</td><td>' . $value->wil_tujuan . '</td><td>' . toRupiah($value->harga) . ' / Kg <br> Min : ' . $value->min_kg . ' </td><td>'
                    . toRupiah($value->hrg_kubik) . '/ M3 <br> Min : ' . $value->min_kubik . ' </td><td>' . $value->time . ' Hari </td></tr>';
            } else if ($value->rekomendasi == "1" && $value->same_balik == "1") {
                $data[$key] = '<tr><td><label data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan" style="cursor:pointer"> ' . $value->nm_ven . ' <br><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></label><br><span class="badge badge-outline badge-info badge-success pt-1">Berlaku Sebaliknya</span></td><td>' . $value->wil_asal . '</td><td>' . $value->wil_tujuan . '</td><td>' . toRupiah($value->harga) . ' / Kg <br> Min : ' . $value->min_kg . ' </td><td>'
                    . toRupiah($value->hrg_kubik) . '/ M3 <br> Min : ' . $value->min_kubik . ' </td><td>' . $value->time . ' Hari </td></tr>';
            } else {
                $data[$key] = '<tr><td>' . $value->nm_ven . '</td><td>' . $value->wil_asal . '</td><td>' . $value->wil_tujuan . '</td><td>' . toRupiah($value->harga) . ' / Kg <br> Min : ' . $value->min_kg . ' </td><td>'
                    . toRupiah($value->hrg_kubik) . '/ M3 <br> Min : ' . $value->min_kubik . ' </td><td>' . $value->time . ' Hari </td></tr>';
            }
            // $data[$key] = [
            //     'id' => $value->id, // or any unique identifier
            //     'vendor' => $value->nm_ven,
            //     'wil_asal' => $value->wil_asal,
            //     'wil_tujuan' => $value->wil_tujuan,
            //     'harga' => toRupiah($value->harga),
            //     'hrg_kubik' => toRupiah($value->hrg_kubik),
            //     'time' => $value->time,
            //     'checked' => false, // Add this property initially set to false
            // ];
        }
        // $data = array_reverse($data);

        return Response()->json($data);
    }

    public function api(Request $request)
    {
        $perpage = isset($request->shareselect) ? $request->shareselect : 50;
        $asal = isset($request->id_asal) ? $request->id_asal : null;
        $tujuan = isset($request->id_tujuan) ? $request->id_tujuan : null;
        $vendor = isset($request->id_ven) ? $request->id_ven : null;
        $page = isset($request->page) ? $request->page : 1;

        $data = HargaVendor::getData2($asal, $tujuan, $vendor);
        $response = [
            'message' => 'success',
            'code' => 0,
            'data' => $data,
        ];

        return response($response);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        $data["data"] = [];

        // return view('kepegawaian::hargavendor.create', $data);
        $data["content"] = 'busdev::contents.adminbusdev.hargavendor.createbusdev';
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_asal' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'id_tujuan' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'keterangan' => 'bail|nullable|max:350',
            'harga' => 'bail|nullable|numeric',
            'hrg_kubik' => 'bail|nullable|numeric',
            'min_kg' => 'bail|nullable|numeric',
            'min_kubik' => 'bail|nullable|numeric',
            'rekomendasi' => 'bail|nullable|numeric',
            'sebaliknya' => 'bail|nullable|numeric',
            'type' => 'bail|required|in:1,2'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        if ($request->type == 1 and ($request->id_ven == null and $request->harga == null and $request->hrg_kubik == null and $request->time == null and $request->min_kg == null and $request->min_kubik == null)) {
            return redirect()->back()->with('error', ' Jika Direct, Data Vendor, Lead Time, Chargemin dan Harga wajib diisi !.')->withInput($request->input());
        }

        // $check = HargaVendor::where("id_ven", $request->id_ven)
        //     ->where("wil_asal", $request->id_asal)
        //     ->where("wil_tujuan", $request->id_tujuan)
        //     ->where("type", $request->type)
        //     ->get()->first();

        // if ($check != null) {
        //     return redirect()->back()->with('error', ' Data Harga sudah ada')->withInput($request->input());
        // }
        if ($request->type == 1) {
            $check = HargaVendor::where("id_ven", $request->id_ven)
                ->where("wil_asal", $request->id_asal)
                ->where("wil_tujuan", $request->id_tujuan)
                ->where("type", 1)
                ->get()
                ->first();

            if ($check != null) {
                return redirect()->back()->with('error', ' Data Harga sudah ada, silahkan Periksa kembali data anda!')->withInput($request->input());
            }
        }

        $type = 1;
        $ids = null;
        try {

            // save to user
            DB::beginTransaction();
            $harga = new HargaVendor();
            $harga->wil_asal = $request->id_asal;
            $harga->wil_tujuan = $request->id_tujuan;
            $harga->id_ven = $request->id_ven ? $request->id_ven : null;
            $harga->harga = $request->harga ? $request->harga : 0;
            $harga->hrg_kubik = $request->hrg_kubik ? $request->hrg_kubik : 0;
            $harga->time = $request->time ? $request->time : 1;
            $harga->type = $request->type;
            $harga->keterangan = $request->keterangan;
            $harga->rekomendasi = $request->rekomendasi;
            $harga->same_balik = $request->sebaliknya;
            $harga->min_kg = $request->min_kg;
            $harga->min_kubik = $request->min_kubik;
            $harga->keterangan = $request->keterangan;
            $harga->id_user = Auth::user()->id_user;
            $harga->updated_user = Auth::user()->id_user;
            $type = $harga->type;

            $harga->save();
            $ids = DB::getPdo()->lastInsertId();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }

        if ($type == 1) {
            return redirect("hargavendor")->with('success', 'Data Harga Vendor Disimpan');
        } else {
            return redirect("hargavendor/" . $ids . '/detail')->with('success', 'Data Harga Vendor Disimpan');
        }
    }

    public function savedetail(Request $request, $id)
    {
        $harga = HargaVendor::findOrfail($id);

        $validator = Validator::make($request->all(), [
            'id_asal' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'id_tujuan' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'keterangan' => 'bail|nullable|max:350',
            'id_ven' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor,id_ven',
            'harga' => 'bail|required|numeric',
            'hrg_kubik' => 'bail|required|numeric',
            'min_kg' => 'bail|required|numeric',
            'min_kubik' => 'bail|required|numeric',
            'rekomendasi' => 'bail|nullable|numeric',
            'sebaliknya' => 'bail|nullable|numeric',
            'time' => 'bail|required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with("errors")->withInput($request->all());
        }

        // $check = HargaVendor::where("id_ven", $request->id_ven)
        //     ->where("wil_asal", $request->id_asal)
        //     ->where("wil_tujuan", $request->id_tujuan)
        //     ->where("type", '1')
        //     ->get()->first();

        // if ($check != null) {
        //     return redirect()->back()->with('error', ' Data Harga sudah ada silahkan cek kembali data anda!')->withInput($request->input());
        // }
        // Cek apakah data sudah ada untuk tipe 1 atau tipe 2
        $existingData = HargaVendor::where(function ($query) use ($request) {
            $query->where("id_ven", $request->id_ven)
                ->where("wil_asal", $request->id_asal)
                ->where("wil_tujuan", $request->id_tujuan)
                ->where("type", '1');
        })->orWhere(function ($query) use ($request) {
            $query->where("id_ven", $request->id_ven)
                ->where("wil_asal", $request->id_asal)
                ->where("wil_tujuan", $request->id_tujuan)
                ->where("type", '2');
        })->first();

        if ($existingData) {
            return redirect()->back()->with('error', 'Data Harga Sudah ada, silahkan Cek kembali Input Data Anda!')->withInput($request->input());
        }


        try {

            DB::beginTransaction();
            $harga = new HargaVendor();
            $harga->wil_asal = $request->id_asal;
            $harga->wil_tujuan = $request->id_tujuan;
            $harga->id_ven = $request->id_ven ? $request->id_ven : null;
            $harga->harga = $request->harga ? $request->harga : 0;
            $harga->hrg_kubik = $request->hrg_kubik ? $request->hrg_kubik : 0;
            $harga->time = $request->time ? $request->time : 0;
            $harga->type = 1;
            $harga->keterangan = $request->keterangan;
            $harga->rekomendasi = $request->rekomendasi;
            $harga->same_balik = $request->sebaliknya;
            $harga->min_kg = $request->min_kg ? $request->min_kg : 0;
            $harga->min_kubik = $request->min_kubik ? $request->min_kubik : 0;
            $harga->id_user = Auth::user()->id_user;
            $harga->updated_user = Auth::user()->id_user;
            $harga->save();

            // insert parent
            $ids = DB::getPdo()->lastInsertId();
            $rel = new RelationModel();
            $rel->parent = $id;
            $rel->child = $ids;
            $rel->id_user = Auth::user()->id_user;
            $rel->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor Disimpan');
    }

    public function updatedetail(Request $request, $id)
    {
        //  dd($request);
        $validator = Validator::make($request->all(), [
            'id_asal' => 'bail|required',
            'id_tujuan' => 'bail|required',
            'keterangan' => 'bail|nullable|max:350',
            'id_ven' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor,id_ven',
            'time' => 'bail|required|numeric',
            'harga' => 'bail|required|numeric',
            'hrg_kubik' => 'bail|required|numeric',
            'min_kg' => 'bail|required|numeric',
            'min_kubik' => 'bail|required|numeric',
            'rekomendasi' => 'bail|nullable|numeric',
            'sebaliknya' => 'bail|nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        if ($request->type == 1 and ($request->id_ven == null and $request->harga == null and $request->hrg_kubik == null and $request->time == null and $request->min_kg == null and $request->min_kubik == null)) {
            return redirect()->back()->with('error', ' Jika Direct, Data Vendor, Lead Time, Chargemin dan Harga wajib diisi !.')->withInput($request->input());
        }

        try {
            // save to user
            DB::beginTransaction();
            $harga = HargaVendor::findOrFail($id);
            $harga->wil_asal = $harga->wil_asal;
            $harga->wil_tujuan = $harga->wil_tujuan;
            $harga->type = $request->type;
            $harga->id_ven = $request->id_ven;
            $harga->harga = $request->harga;
            $harga->hrg_kubik = $request->hrg_kubik;
            $harga->time = $request->time;
            $harga->min_kg = $request->min_kg;
            $harga->min_kubik = $request->min_kubik;
            $harga->rekomendasi = $request->rekomendasi;
            $harga->same_balik = $request->sebaliknya;
            $harga->keterangan = $request->keterangan;
            $harga->updated_user = Auth::user()->id_user;
            $harga->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor Disimpan');
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        // $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        // $harga = HargaVendor::with("vendor")->findOrFail($id);
        // $detail = HargaVendor::getDetail($id);

        // $data["asal"] = Wilayah::findOrFail($harga->wil_asal);
        // $data["tujuan"] = Wilayah::findOrFail($harga->wil_tujuan);
        // $data["data"] = $harga;
        // $data["detail"] = $detail;
        $id_asal = $request->id_asal != null ? $request->id_asal : null;
        $id_tujuan = $request->id_tujuan != null ? $request->id_tujuan : null;
        $id_ven = $request->id_ven != null ? $request->id_ven : null;

        $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        $harga = HargaVendor::getParent($id);
        $detail = HargaVendor::getDetail($id);

        $data["data"] = $harga;
        $data["detail"] = $detail;
        $data["show"] = HargaVendor::getDataShow($id_ven, $id_asal, $id_tujuan);

        // return view('kepegawaian::hargavendor.show', $data);
        $data["content"] = 'busdev::contents.adminbusdev.hargavendor.detailbusdev';
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        $harga = HargaVendor::findOrFail($id);
        $data["asal"] = Wilayah::findOrFail($harga->wil_asal);
        $data["tujuan"] = Wilayah::findOrFail($harga->wil_tujuan);
        $data["data"] = $harga;

        // dd($data['data']);
        // return view('kepegawaian::hargavendor.create', $data);
        $data["content"] = 'busdev::contents.adminbusdev.hargavendor.createbusdev';
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'id_asal' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'id_tujuan' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'keterangan' => 'bail|nullable|max:350',
            'harga' => 'bail|nullable|numeric|',
            'hrg_kubik' => 'bail|nullable|numeric|',
            'min_kg' => 'bail|nullable|numeric|',
            'min_kubik' => 'bail|nullable|numeric|',
            'rekomendasi' => 'bail|nullable|numeric',
            'sebaliknya' => 'bail|nullable|numeric',
            'type' => 'bail|nullable|in:1,2'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors())->withInput($request->input());
        }

        if ($request->type == 1 and ($request->id_ven == null and $request->harga == null and $request->hrg_kubik == null and $request->time == null and $request->min_kg == null and $request->min_kubik == null)) {
            return redirect()->back()->with('error', ' Jika Direct, Data Vendor, Lead Time, Chargemin dan Harga wajib diisi !.')->withInput($request->input());
        }

        try {
            // save to user
            DB::beginTransaction();
            $harga = HargaVendor::findOrFail($id);
            $harga->wil_asal = $request->id_asal;
            $harga->wil_tujuan = $request->id_tujuan;
            $harga->type = $request->type != null ? $request->type : $harga->type;
            $harga->harga = $request->harga;
            $harga->id_ven = $request->id_ven != null ? $request->id_ven : $harga->id_ven;
            $harga->hrg_kubik = $request->hrg_kubik;
            $harga->same_balik = $request->sebaliknya;
            $harga->min_kg = $request->min_kg;
            $harga->min_kubik = $request->min_kubik;
            $harga->time = $request->time;
            $harga->rekomendasi = $request->rekomendasi;
            $harga->keterangan = $request->keterangan;
            $harga->updated_user = Auth::user()->id_user;
            $harga->save();

            if ($harga->parent != null) {
                $sum1 = HargaVendor::where("parent", $harga->parent)->sum("hrg_kubik");
                $sum2 = HargaVendor::where("parent", $harga->parent)->sum("harga");
                $sum3 = HargaVendor::where("parent", $harga->parent)->sum("time");
                $sum4 = HargaVendor::where("parent", $harga->parent)->max("min_kg");
                $sum5 = HargaVendor::where("parent", $harga->parent)->max("min_kubik");

                $total = HargaVendor::findOrfail($harga->parent);
                $total->time = $sum3;
                $total->hrg_kubik = $sum1;
                $total->harga = $sum2;
                $total->min_kg = $sum4;
                $total->min_kubik = $sum5;
                $total->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Disimpan ' . $e->getMessage());
        }

        return redirect("hargavendor/" . $id . "/edit")->with('success', 'Data Harga Vendor Disimpan');
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
            $relation = HargaVendor::getDataRelation($id);
            $harga = HargaVendor::where("id_harga", $relation->child)->first();
            // dd($id);

            // $cek = HargaVendor::where("id_harga", $id)->first();

            // if ($cek != null) {
            //     return redirect()->back()->with('error', 'Gagal Hapus karena masih ada harga detail');
            // }
            $parent = $harga->parent;
            $ids = $harga->id_harga;
            $asal = $harga->wil_asal;
            $tujuan = $harga->wil_tujuan;
            HargaVendor::deleteRelation($id);
            // $harga->delete();

            if ($parent != null) {
                $sum1 = HargaVendor::where("parent", $parent)->sum("hrg_kubik");
                $sum2 = HargaVendor::where("parent", $parent)->sum("harga");
                $sum3 = HargaVendor::where("parent", $parent)->sum("time");
                $sum4 = HargaVendor::where("parent", $parent)->max("min_kg");
                $sum5 = HargaVendor::where("parent", $parent)->max("min_kubik");

                $total["time"] = $sum3;
                $total["hrg_kubik"] = $sum1;
                $total["harga"] = $sum2;
                $total["min_kg"] = $sum4;
                $total["min_kubik"] = $sum5;
                HargaVendor::where("id_harga", $parent)->update($total);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Dihapus ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor Dihapus');
    }

    public function destroydirect($id)
    {
        try {
            // save to user
            DB::beginTransaction();
            // $relation = HargaVendor::getDataRelation($id);
            $harga = HargaVendor::where("id_harga", $id)->first();
            // dd($id);

            // $cek = HargaVendor::where("id_harga", $id)->first();

            // if ($cek != null) {
            //     return redirect()->back()->with('error', 'Gagal Hapus karena masih ada harga detail');
            // }
            // $parent = $harga->parent;
            // $ids = $harga->id_harga;
            // $asal = $harga->wil_asal;
            // $tujuan = $harga->wil_tujuan;
            // HargaVendor::deleteRelation($id);
            $harga->delete();

            // if ($parent != null) {
            //     $sum1 = HargaVendor::where("parent", $parent)->sum("hrg_kubik");
            //     $sum2 = HargaVendor::where("parent", $parent)->sum("harga");
            //     $sum3 = HargaVendor::where("parent", $parent)->sum("time");
            //     $sum4 = HargaVendor::where("parent", $parent)->max("min_kg");
            //     $sum5 = HargaVendor::where("parent", $parent)->max("min_kubik");

            //     $total["time"] = $sum3;
            //     $total["hrg_kubik"] = $sum1;
            //     $total["harga"] = $sum2;
            //     $total["min_kg"] = $sum4;
            //     $total["min_kubik"] = $sum5;
            //     HargaVendor::where("id_harga", $parent)->update($total);
            // }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal Dihapus ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor Dihapus');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        // Validasi bahwa $ids adalah array yang tidak kosong
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        // Validasi bahwa semua elemen dalam $ids adalah integer
        foreach ($ids as $id) {
            if (!is_numeric($id)) {
                return redirect()->back()->with('error', 'ID yang dipilih tidak valid.');
            }
        }

        try {
            DB::beginTransaction();

            // Hapus semua entitas berdasarkan ID yang dipilih dari tabel HargaVendor
            HargaVendor::whereIn('id_harga', $ids)->delete();

            // Hapus relasi dari tabel relationharga jika diperlukan
            // foreach ($ids as $id) {
            //     HargaVendor::deleteRelation($id); // Panggil metode deleteRelation dari model HargaVendor
            // }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            // Gunakan dd() untuk men-debug error SQLSTATE
            dd($e->getMessage()); // Ini akan mencetak pesan error SQLSTATE

            return redirect()->back()->with('error', 'Gagal menghapus data Harga Vendor: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor berhasil dihapus.');
    }


    public function deleteform()
    {
        if (strtolower(Session("role")["nm_role"]) != "busdev") {
            abort(404);
        }

        $data["content"] = "busdev::contents.adminbusdev.hargavendor.batchdelete";
        if (Session::get("items") != null) {
            $data["items"] = json_decode(Session::get("items"));
        }
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }
    public function batchdelete(Request $request)
    {
        $data = json_decode($request->data);
        $deleted = 0;
        $notdeleted = 0;
        $notvalid = 0;
        $hargatidakdiinsert = array();
        $datatidaksesuai = array();

        foreach ($data as $row) {
            if ($row[0] != null && $row[0] != "" && $row[2] != null && $row[2] != "" && $row[3] != null && $row[3] != "" && $row[4] != null && $row[4] != "" && $row[5] != null && $row[5] != "") {
                $cek = HargaVendor::get_data_harga($row[0], $row[2], $row[3], $row[4], $row[5])->first();
                if (!empty($cek)) {
                    // deleting transaction
                    DB::beginTransaction();
                    $harga = HargaVendor::where("id_harga", $cek->id_harga)->first();
                    $harga->delete();
                    DB::commit();
                    $deleted++;
                } else {
                    DB::rollback();
                    array_push($hargatidakdiinsert, $row);
                    $notdeleted++;
                }
            } else {
                array_push($datatidaksesuai, $row);
                $notvalid++;
            }

        }
        $data["items"] = json_encode($hargatidakdiinsert);
        Session::put('items', $data["items"]);
        return redirect()->back()->with('success', 'Transaksi penghapusan data berhasil! Data dihapus : ' . $deleted . ', Data tidak ditemukan : ' . $notdeleted . ', Data Tidak Sesuai : ' . $notvalid);
    }

}
