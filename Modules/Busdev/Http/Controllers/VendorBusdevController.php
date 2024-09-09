<?php

namespace Modules\Busdev\Http\Controllers;

use Illuminate\Http\Request;
// use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Busdev\Entities\VendorBusdev;
use Modules\Busdev\Entities\RelationModel;
use App\Models\GroupVendor;
use App\Models\Wilayah;
use App\Models\Module;
use Validator;
use DB;
use Auth;
use App\Models\HargaVendor;


class VendorBusdevController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // // Inisialisasi variabel dari input request
        // $page = $request->page != null ? $request->page : 1;
        // $perpage = $request->shareselect != null ? $request->shareselect : 50;
        // $id_ven = $request->id_ven != null ? $request->id_ven : null;
        // $id_grup_ven = $request->id_grup_ven != null ? $request->id_grup_ven : null;
        // $id_wil = $request->id_wil != null ? $request->id_wil : null;
        $page = 100;
        // $vendorBusdev = new VendorBusdev;
        $data["data"] = VendorBusdev::orderBy('updated_at', 'desc')->paginate($page);

        $data["vendor"] = VendorBusdev::select("nm_ven", "id_ven")->get();
        $data["vendor"] = GroupVendor::select("nm_grup_ven", "id_grup_ven")->get();
        // Menerapkan fungsi getdatafilter dengan kondisi filter
        // $data["data"] = $vendorBusdev->getdatafilter($page, $perpage, $id_ven, $id_grup_ven, $id_wil);

        if (strtolower(Session("role")["nm_role"]) == "busdev") {
            // return view('kepegawaian::hargavendor.index', $data);
            $data["module"] = Module::getSessionModul();
            $data["content"] = 'busdev::contents.adminbusdev.daftarvendorr.daftarvendorbusdev';
            return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
        } else {
            // return view('kepegawaian::hargavendor.card', $data);
            $data["module"] = Module::getSessionModul();
            $data["content"] = 'busdev::contents.marketing.daftarvendor.daftarvend';
            return view('busdev::metroniclapan-template.mainview-busdevs', $data);
            //   return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
        }
    }
    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $search = $request->filled('search') ? $request->search : null;

    //         $data = DB::table('vendor_busdev')
    //             ->select('id_ven', 'nm_ven', 'telp_ven', 'id_grup_ven', 'id_wil', 'alamat_ven', 'is_aktif')
    //             ->where('nm_ven', 'like', '%' . $search . '%')
    //             ->orderBy('updated_at', 'desc')
    //             ->paginate(100);

    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 $btn = '<div class="dropdown">
    //                 <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
    //                     Action
    //                 </button>
    //                 <div class="dropdown-menu">';
    //                 $btn .= '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detvendorbackdrop' . $row->id_ven . '"><i class="bx bx-detail me-1"></i> Rincian</a>';
    //                 // Tambahkan opsi aksi lainnya sesuai kebutuhan
    //                 $btn .= '</div></div>';

    //                 return $btn;
    //             })
    //             ->addColumn('Nama Vendor', function ($row) {
    //                 return strtoupper($row->nm_ven) . '<br>' . $row->telp_ven;
    //             })
    //             ->addColumn('Group', function ($row) {
    //                 return isset($row->id_grup_ven) ? strtoupper($row->id_grup_ven) : '-';
    //             })
    //             ->addColumn('nama_wil', function ($row) {
    //                 return isset($row->wilayah) ? strtoupper($row->wilayah) : '-';
    //             })
    //             ->addColumn('Is Aktif', function ($row) {
    //                 return $row->is_aktif == 1 ? '<i class="fa fa-check" style="color: green"></i>' : '<i class="fa fa-times" style="color: red"></i>';
    //             })
    //             ->rawColumns(['action', 'Nama Vendor', 'Group', 'Wilayah', 'Is Aktif'])
    //             ->make(true);
    //     }

    //     $data["module"] = Module::getSessionModul();

    //     if (strtolower(Session("role")["nm_role"]) == "busdev") {
    //         $data["content"] = 'busdev::contents.adminbusdev.daftarvendorr.daftarvendorbusdev';
    //         return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    //     } else {
    //         $data["content"] = 'busdev::contents.marketing.daftarvendor.daftarvend';
    //         return view('busdev::metroniclapan-template.mainview-busdevs', $data);
    //     }
    // }



    public function getvendor($id)
    {
        $data = VendorBusdev::findOrFail($id);

        return Response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data["data"] = [];
        $data["group"] = GroupVendor::get();
        // return view('busdev::vendor.create', $data);
        $data["content"] = 'busdev::contents.adminbusdev.daftarvendorr.tambahvendorbusdev';
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);
    }

    public function getharga(string $id, Request $request)
    {
        $data = HargaVendor::getOnes($request->asal, $request->tujuan);
        $a_data = [];

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $parts = '<tr>
                <td>' . ($key + 1) . '</td>';
                if ($value->rekomendasi == "1") {
                    $parts .= '<td><label style="cursor: pointer" data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan">
                ' . $value->nm_ven . ' <br>
                    <i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i>
                </label></td>';
                } else {
                    $parts .= '<td>' . $value->nm_ven . '</td>';
                }
                $parts .= '<td>
                    ' . strtoupper($value->wil_asal) . '
                </td>
                <td>
                    ' . strtoupper($value->wil_tujuan) . '
                </td>
                <td>
                    ' . toRupiah($value->harga) . '" / Kg"<br> Min : ' . $value->min_kg . '
                </td>
                <td>
                ' . toRupiah($value->hrg_kubik) . '" / M3"<br> Min : ' . $value->min_kubik . '
                </td>
                <td>' . $value->time . ' Hari </td>
                <td>
                <input type="checkbox" class="checks" value="' . $value->id_harga . '" id="idcheck" name="idcheck[]" />
                </td>
            </tr>';

                $a_data[$key] = $parts;
            }
        } else {
            $a_data[0] = '<tr style="font-weight:bold;"><td colspan="8" class="text-center"><b>Harga Tidak Ditemukan</b></td></tr>';
        }

        return Response()->json($a_data);
    }

    public function saveimport($id, Request $request)
    {
        // // dd($request);
        // if(count($request->idcheck)<=0){
        //     return redirect()->back()->withErrors($validator)->with('error', 'Tidak Ada data yang dipilih');
        // }
        // Pengecekan apakah $request->idcheck terdefinisi dan bukan null
        if (!isset($request->idcheck) || !is_array($request->idcheck)) {
            return redirect()->back()->with('error', 'Tidak Ada data yang dipilih Periksa Kembali Pilihan Anda!');
        }

        // Pengecekan jumlah elemen dalam $request->idcheck
        if (count($request->idcheck) <= 0) {
            return redirect()->back()->with('error', 'Tidak Ada data yang dipilih Periksa Kembali Pilihan Anda!');
        }

        try {
            DB::beginTransaction();

            // check data sudah di import atau belum
            $existingRelations = RelationModel::where('parent', $id)
                ->whereIn('child', $request->idcheck)
                ->get();

            if ($existingRelations->count() > 0) {
                // Jika ada relasi yang sudah ada, munculkan pesan kesalahan
                return redirect()->back()->with('error', 'Data sudah di-import sebelumnya Mohon Periksa Kembali Data anda!');
            }

          
            foreach ($request->idcheck as $key => $value) {
                $harga = new RelationModel();
                $harga->parent = $id;
                $harga->child = $value;
                $harga->id_user = Auth::user()->id_user;
                $harga->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Harga Vendor Gagal di import ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data Harga Vendor Berhasil di import');
    }

//     fungsi-baru
//     public function saveimport($id, Request $request)
// {
//     // Validasi masukan
//     if (!isset($request->idcheck) || !is_array($request->idcheck) || count($request->idcheck) <= 0) {
//         return response()->json(['error' => 'Tidak Ada data yang dipilih. Periksa kembali pilihan Anda!']);
//     }

//     // Inisialisasi array untuk menyimpan data yang di checklist
//     $dataToBeSaved = [];

//     try {
//         DB::beginTransaction();

//         // Periksa data sudah diimport atau belum
//         $existingRelations = RelationModel::where('parent', $id)
//             ->whereIn('child', $request->idcheck)
//             ->get();

//         if ($existingRelations->count() > 0) {
//             return response()->json(['error' => 'Data sudah di-import sebelumnya. Mohon periksa kembali data Anda!']);
//         }

//         // Iterasi melalui idcheck untuk menyimpan relasi dan membangun array dataToBeSaved
//         foreach ($request->idcheck as $key => $value) {
//             $harga = new RelationModel();
//             $harga->parent = $id;
//             $harga->child = $value;
//             $harga->id_user = Auth::user()->id_user;
//             $harga->save();

//             // Menambahkan data yang baru saja disimpan ke dalam array
//             $dataToBeSaved[] = [
//                 'parent' => $harga->parent,
//                 'child' => $harga->child,
//                 'id_user' => $harga->id_user,
//             ];
//         }

//         DB::commit();
//     } catch (\Exception $e) {
//         DB::rollback();
//         return response()->json(['error' => 'Data Harga Vendor Gagal di import ' . $e->getMessage()]);
//     }

//     // ... (kode setelahnya, jika ada)

//     return response()->json(['success' => 'Data Harga Vendor Berhasil di import']);
// }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    // fungsi lama
    public function store(Request $request)
    {
        $rules = array(
            'id_grup_ven' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor_grup,id_grup_ven',
            'id_wil' => 'bail|required|min:1|max:11|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'nm_ven' => 'bail|required|min:4|max:64|',
            'alm_ven' => 'bail|required|min:6|max:128',
            'telp_ven' => 'bail|required|digits_between:1,32',
            'is_aktif' => 'bail|nullable|digits:1',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)
                ->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan');
        } else {
            try {
                // save to group
                DB::beginTransaction();

                $vendor = new VendorBusdev();
                $vendor->nm_ven = $request->nm_ven;
                $vendor->id_grup_ven = $request->id_grup_ven;
                $vendor->id_wil = $request->id_wil;
                $vendor->alm_ven = $request->alm_ven;
                $vendor->telp_ven = $request->telp_ven;
                $vendor->is_aktif = $request->is_aktif;
                $vendor->id_user = Auth::user()->id_user;
                $vendor->save();

                DB::commit();
            } catch (Exception $e) {
                return redirect()->back()->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan' . $e->getMessage());
            }

            return redirect(route_redirect())->with('success', 'Data Vendor Disimpan');
        }
    }

    // fungsi baru
    // public function store(Request $request)
    // {
    //     $rules = array(
    //         'id_grup_ven' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor_grup,id_grup_ven',
    //         'id_wil' => 'bail|required|min:1|max:11|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
    //         'nm_ven' => 'bail|required|min:4|max:64',
    //         'alm_ven' => 'bail|required|min:6|max:128',
    //         'telp_ven' => 'bail|required|digits_between:1,32|unique:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor,telp_ven,NULL,id_ven,id_wil,' . $request->id_wil,
    //         'is_aktif' => 'bail|nullable|digits:1',
    //     );

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)
    //             ->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan');
    //     } else {
    //         try {
    //             // save to group
    //             DB::beginTransaction();

    //             $vendor = new VendorBusdev();
    //             $vendor->nm_ven = $request->nm_ven;
    //             $vendor->id_grup_ven = $request->id_grup_ven;
    //             $vendor->id_wil = $request->id_wil;
    //             $vendor->alm_ven = $request->alm_ven;
    //             $vendor->telp_ven = $request->telp_ven;
    //             $vendor->is_aktif = $request->is_aktif;
    //             $vendor->id_user = Auth::user()->id_user;
    //             $vendor->save();

    //             DB::commit();
    //         } catch (Exception $e) {
    //             return redirect()->back()->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan' . $e->getMessage());
    //         }

    //         return redirect(route_redirect())->with('success', 'Data Vendor Disimpan');
    //     }
    // }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

        return view('busdev::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $vendor = VendorBusdev::findOrFail($id);
        $data["group"] = GroupVendor::get();
        $data["wilayah"] = Wilayah::findOrFail($vendor->id_wil);
        $data["data"] = $vendor;

        // return view('busdev::vendor.create', $data);
        $data["content"] = 'busdev::contents.adminbusdev.daftarvendorr.tambahvendorbusdev';
        return view('busdev::metronictigelapan-template.mainview-adminbusdev', $data);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'id_grup_ven' => 'bail|required|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_vendor_grup,id_grup_ven',
            'id_wil' => 'bail|required|min:1|max:11|exists:' . env('DB_CONNECTION') . '.' . env('DB_DATABASE') . '.m_wilayah,id_wil',
            'nm_ven' => 'bail|required|min:4|max:64|',
            'alm_ven' => 'bail|required|min:6|max:128',
            'telp_ven' => 'bail|required|digits_between:1,32',
            'is_aktif' => 'bail|nullable|digits:1',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)
                ->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan');
        } else {
            try {
                // save to group
                DB::beginTransaction();

                $vendor = VendorBusdev::findOrFail($id);
                $vendor->nm_ven = $request->nm_ven;
                $vendor->id_grup_ven = $request->id_grup_ven;
                $vendor->id_wil = $request->id_wil;
                $vendor->alm_ven = $request->alm_ven;
                $vendor->telp_ven = $request->telp_ven;
                $vendor->is_aktif = $request->is_aktif;
                $vendor->id_user = Auth::user()->id_user;
                $vendor->save();

                DB::commit();
            } catch (Exception $e) {
                return redirect()->back()->withInput($request->all())->with('error', 'Data Vendor Gagal Disimpan' . $e->getMessage());
            }

            return redirect(route_redirect("vendorbusdev/" . $id . '/edit'))->with('success', 'Data Vendor Disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    // public function destroy($id)
    // {
    //     try {
    //         // save to group
    //         DB::beginTransaction();

    //         $vendor = VendorBusdev::findOrFail($id);
    //         $vendor->delete();

    //         DB::commit();
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', 'Data Vendor Gagal dihapus' . $e->getMessage());
    //     }

    //     return redirect(route_redirect())->with('success', 'Data Vendor dihapus');
    // }

    public function deletevendor($id)
    {
        try {
            // save to group
            DB::beginTransaction();

            $vendor = VendorBusdev::findOrFail($id);

            // Check if data aktif
            if ($vendor->is_aktif == 1) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data Vendor tidak dapat di Hapus karena data masih Aktif!');
            }
            // cek datavendor sudah ada data harga maka,tidak bisa hapus
            $hasPrices = HargaVendor::where('id_ven', $id)->exists();

            if ($hasPrices) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data Vendor tidak dapat di Hapus karena sudah ada Data Harga! silahkan cek kembali!');
            }

            // proses jika data tidak aktif
            $vendor->delete();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data Vendor Gagal dihapus' . $e->getMessage());
        }

        return redirect(route_redirect())->with('success', 'Data Vendor dihapus');
    }




}
