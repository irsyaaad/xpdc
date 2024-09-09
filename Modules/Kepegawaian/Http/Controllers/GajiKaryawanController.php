<?php

namespace Modules\Kepegawaian\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Perusahaan;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kepegawaian\Entities\Absensi;
use Modules\Kepegawaian\Entities\DetailPiutang;
use Modules\Kepegawaian\Entities\GajiKaryawan;
use Modules\Kepegawaian\Entities\Perijinan;
use Modules\Kepegawaian\Entities\PiutangKaryawan;
use Modules\Kepegawaian\Entities\SettingDenda;
use Modules\Kepegawaian\Entities\SettingHariLibur;
use Modules\Keuangan\Entities\ACPerush;

class GajiKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date("m");
        $tahun = date("Y");

        if (isset($request->f_perush) and $request->f_perush != null) {
            $id_perush = $request->f_perush;
        }

        if (isset($request->f_bulan) and $request->f_bulan != null) {
            $bulan = $request->f_bulan;
        }

        if (isset($request->f_tahun) and $request->f_tahun != null) {
            $tahun = $request->f_tahun;
        }

        $gaji = GajiKaryawan::getGaji($bulan, $tahun, $id_perush);

        if (get_admin()) {
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        } else {
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }

        $data["ac"] = ACPerush::getACDebit();
        $data["piutang"] = ACPerush::getPiutang("piutang karyawan", $id_perush);
        $data["ac_gaji"] = ACPerush::getPiutang("gaji", $id_perush);
        $data["data"] = $gaji;
        $data["filter"] = array("f_perush" => $id_perush, "f_bulan" => $bulan, "f_tahun" => $tahun);
        // dd($data);
        return view('kepegawaian::gajikaryawan.index', $data);
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
        $diff = SettingHariLibur::getDateDiff($request->dr_tgl, $request->sp_tgl);

        if ($diff < 28) {
            return redirect()->back()->with('error', 'Generate Gaji Tidak Boleh Kurang Dari 28 Hari');
        }

        if ($diff > 31) {
            return redirect()->back()->with('error', 'Generate Gaji Tidak Boleh Lebih Dari 31 Hari');
        }

        $cek = GajiKaryawan::where("id_perush", $request->id_perush)
            ->where("bulan", $request->bulan)->where("tahun", $request->tahun)->get()->first();

        // dd($cek);
        if (isset($cek->is_approve) and $cek->is_approve == 1) {
            return redirect()->back()->with('error', 'Gaji Bulan ' . $request->bulan . ' tahun ' . $request->tahun . ' sudah di approve  ');
        }

        $gaji = GajiKaryawan::getGaji($request->bulan, $request->tahun, $request->id_perush);
        //dd($gaji);
        if ($gaji != null) {
            GajiKaryawan::where("bulan", $request->bulan)
                ->where("tahun", $request->tahun)
                ->where("id_perush", $request->id_perush)->delete();
        }
        $this->generate($request);
        return redirect()->back()->with('success', 'Berhasil Generate');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $gaji = GajiKaryawan::getDetail($id)->get()->first();
        $data["data"] = $gaji;
        return view('kepegawaian::gajikaryawan.detail', $data);
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
        DB::beginTransaction();
        try {
            $gaji = GajiKaryawan::findOrFail($id);
            $gaji->n_tunjangan_jabatan = $request->n_tunjangan_jabatan;
            $gaji->n_tunjangan_kinerja = $request->n_tunjangan_kinerja;
            $gaji->n_tunjangan_kpi = $request->n_tunjangan_kpi;
            $gaji->n_tunjangan_kesehatan = $request->n_tunjangan_kesehatan;
            $gaji->n_tunjangan_jht = $request->n_tunjangan_jht;
            $gaji->n_tunjangan_jkk = $request->n_tunjangan_jkk;
            $gaji->n_tunjangan_jkm = $request->n_tunjangan_jkm;
            $gaji->n_tunjangan_jp = $request->n_tunjangan_jp;

            $gaji->n_piutang = $request->n_piutang;
            // $gaji->n_kehadiran = $request->n_kehadiran;
            // $gaji->n_potongan_kehadiran = $request->n_potongan_kehadiran;
            // $gaji->n_potongan_kasbon = $request->n_potongan_kasbon;
            // $gaji->n_potongan_pph = $request->n_potongan_pph;
            // $gaji->n_potongan_kesehatan = $request->n_potongan_kesehatan;
            // $gaji->n_potongan_jht = $request->n_potongan_jht;
            // $gaji->n_potongan_jp = $request->n_potongan_jp;
            // dd($gaji);
            $gaji->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data gaji karyawan Gagal di update, ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data gaji karyawan berhasil di update');
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

    public function getDenda($dr_tgl, $sp_tgl, $id_perush, $id_karyawan = null)
    {
        // get diff hari
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $red_sun = $red + $sun;
        $hari_masuk = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);

        if (get_admin()) {
            $data = Absensi::getStatistik($dr_tgl, $sp_tgl, null, $id_karyawan, true);
        } else {
            $data = Absensi::getStatistik($dr_tgl, $sp_tgl, $id_perush, $id_karyawan, true);
        }

        $d_denda = SettingDenda::getSettingDenda($id_perush);
        $perijinan = Perijinan::getDenda($dr_tgl, $sp_tgl, $id_perush, $id_karyawan, $d_denda);
        $s_absen = SettingDenda::where("id_jenis", ">", "1")->where("id_jenis", "<", "6")->where("id_perush", $id_perush)->get();
        if (count($s_absen) < 4) {
            return redirect()->back()->with('error', 'Setting Denda Absensi belum di buat');
        }

        $a_absen = [];
        foreach ($s_absen as $key => $value) {
            $a_absen[$value->id_jenis] = $value;
        }

        // get setting alpha
        $s_alpha = SettingDenda::getAlpha($id_perush);
        // data status datang dan pulang
        $s_datang = Absensi::getStatusDatang($dr_tgl, $sp_tgl, $id_perush);
        $s_pulang = Absensi::getStatusPulang($dr_tgl, $sp_tgl, $id_perush);
        // dd($data);

        $absen = [];
        foreach ($data as $key => $value) {
            $id_karyawan = $value->id_karyawan;
            $alpha = 0;
            $datang = 0;
            $pulang = 0;
            $denda = 0;
            $cuti = 0;
            $ijin = 0;
            if (isset($perijinan[$id_karyawan])) {
                $kurang = 0;
                foreach ($perijinan[$id_karyawan] as $key1 => $value1) {
                    $nominal = $value1["nominal"];
                    $frekuensi = $value1["frekuensi"];
                    $hasil = ($value1["jumlah"] - $frekuensi) * $nominal;
                    $kurang += $hasil;
                    $ijin += $value1["jumlah"];
                }

                if ($kurang > 0) {
                    $denda += $kurang;
                }
                // echo '('.$id_karyawan . ') denda = ' . $denda . '<br>';
            }
            // cek denda datang atau pulang
            for ($i = 2; $i <= 5; $i++) {
                $setting = $a_absen[$i];
                if ($setting->id_jenis >= "2" and $setting->id_jenis <= "3") {
                    if (isset($s_datang[$id_karyawan][$setting->id_jenis])) {
                        $jumlah = $s_datang[$id_karyawan][$setting->id_jenis]->jumlah;
                        if ($jumlah > $setting->frekuensi) {
                            $jml = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $datang += $jml;
                            // echo '('.$id_karyawan . ') => ' . $jumlah . ' - ' . $setting->frekuensi . ' => ' . $jml. ' / ' . $datang. '<br>';
                        }
                    }
                } elseif ($setting->id_jenis >= "4" and $setting->id_jenis <= "5") {
                    if (isset($s_pulang[$id_karyawan][$setting->id_jenis])) {
                        $jumlah = $s_pulang[$id_karyawan][$setting->id_jenis]->jumlah;
                        if ($jumlah > $setting->frekuensi) {
                            $jml = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $pulang += $jml;
                        }
                    }
                }
            }

            $hadir = $hari_masuk - $value->absen;
            $hadir = $hadir - $cuti - $ijin;
            $alpha = $hadir - $red_sun;
            $alpha = ($alpha - $s_alpha->frekuensi) * $s_alpha->nominal;

            if ($alpha < 0) {
                $alpha = 0;
            }

            $total = $alpha + $denda + $datang + $pulang;
            // echo '('.$id_karyawan . ') total = ' . $total . ' denda = ' . $denda . ' datang = ' . $datang. ' pulang = ' . $pulang. '<br>';

            if ($total < 0) {
                $total = 0;
            }

            $absen[$id_karyawan] = $total;
        }
        // dd($absen);
        return $absen;
    }

    public function getPersentase($dr_tgl, $sp_tgl, $id_perush)
    {
        $red = SettingHariLibur::getSum($id_perush, $dr_tgl, $sp_tgl);
        $sun = SettingHariLibur::getSunday($dr_tgl, $sp_tgl);
        $jml = $red + $sun;
        $total_hari = SettingHariLibur::getDateDiff($dr_tgl, $sp_tgl);
        $jam_kerja = ($total_hari - $jml) * 8;
        $karyawan = Karyawan::select("id_karyawan", "nm_karyawan", "n_gaji")->where("id_perush", $id_perush)->where("is_aktif", true)->orderby("nm_karyawan", "asc")->get();
        $kehadiran = Absensi::newlaporan($dr_tgl, $sp_tgl, $id_perush);

        // kurangi menit
        $istirahat = Absensi::getIstirahat($dr_tgl, $sp_tgl, $id_perush);
        $terlambat = Absensi::getTerlambat($dr_tgl, $sp_tgl, $id_perush);
        $pulang = Absensi::getPulang($dr_tgl, $sp_tgl, $id_perush);
        /// kurangi hari ijin
        $ijin = Perijinan::getIjinHari($dr_tgl, $sp_tgl, $id_perush);
        // kurangi menit ijin
        $jizin = Perijinan::getIjinJam($dr_tgl, $sp_tgl, $id_perush);

        $data = [];
        foreach ($karyawan as $key => $value) {
            $hadir = 0;
            $minus = 0;
            $izin = 0;
            $jijin = 0;
            $total = 0;
            if (isset($kehadiran[$value->id_karyawan])) {
                $hadir += $kehadiran[$value->id_karyawan]["total"];
            }

            if (isset($id[$value->id_karyawan])) {
                $hadir += $id[$value->id_karyawan] * 8;
            }

            if (isset($dk[$value->id_karyawan])) {
                $hadir += $dk[$value->id_karyawan] * 8;
            }

            if (isset($istirahat[$value->id_karyawan])) {
                $minus += toMinutes($istirahat[$value->id_karyawan]);
            }

            if (isset($terlambat[$value->id_karyawan])) {
                $minus += toMinutes($terlambat[$value->id_karyawan]);
            }

            if (isset($pulang[$value->id_karyawan])) {
                $minus += toMinutes($pulang[$value->id_karyawan]);
            }

            $minus = $minus / 60;
            $minus = round($minus, 2);
            if (isset($ijin[$value->id_karyawan]["c"])) {
                $hadir += $ijin[$value->id_karyawan]["c"]["total"] * 8;
            }

            if (isset($ijin[$value->id_karyawan]["bd"])) {
                $hadir += $ijin[$value->id_karyawan]["bd"]["total"] * 8;
            }

            if (isset($ijin[$value->id_karyawan]["dd"])) {
                $hadir += $ijin[$value->id_karyawan]["dd"]["total"] * 8;
            }

            if (isset($ijin[$value->id_karyawan]["dk"])) {
                $hadir += $ijin[$value->id_karyawan]["dk"]["total"] * 8;
            }

            if (isset($ijin[$value->id_karyawan]["s"])) {
                $hadir += $ijin[$value->id_karyawan]["s"]["total"] * 8;
            }

            if (isset($ijin[$value->id_karyawan]["tm"])) {
                $jijin += $ijin[$value->id_karyawan]["tm"]["total"] * 8;
            }

            if (isset($jizin[$value->id_karyawan]["it"])) {
                $jijin += round(toMinutes($jizin[$value->id_karyawan]["it"]["total"]) / 60, 2);
            }

            if (isset($jizin[$value->id_karyawan]["ip"])) {
                $jijin += round(toMinutes($jizin[$value->id_karyawan]["ip"]["total"]) / 60, 2);
            }

            if (isset($jizin[$value->id_karyawan]["k"])) {
                $jijin += round(toMinutes($jizin[$value->id_karyawan]["k"]["total"]) / 60, 2);
            }

            if (isset($jizin[$value->id_karyawan]["ps"])) {
                $hadir += round(toMinutes($jizin[$value->id_karyawan]["ps"]["total"]) / 60, 2);
            }

            if (isset($jizin[$value->id_karyawan]["id"])) {
                $hadir += round(toMinutes($jizin[$value->id_karyawan]["id"]["total"]) / 60, 2);
            }

            $jijin = round($jijin, 2);
            $alpha = $jam_kerja - ($hadir + $minus + $jijin);
            $total = $jam_kerja - ($minus + $jijin + $alpha);
            $persen = ($total / $jam_kerja) * 100;
            $denda = 0;
            if ($persen < 90) {
                $denda = $value->n_gaji * 10 / 100;
            }

            $data[$value->id_karyawan] = $denda;
        }

        return $data;
    }

    public function generate(Request $request)
    {
        $id_perush = $request->id_perush;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $a_denda = [];
        if ($request->type == "1") {
            $a_denda = $this->getPersentase($dr_tgl, $sp_tgl, $id_perush);
        } else {
            $a_denda = $this->getDenda($dr_tgl, $sp_tgl, $id_perush);
        }

        $piutang = PiutangKaryawan::getPiutang($id_perush);
        $newdata = GajiKaryawan::getData($id_perush)->get();
        DB::beginTransaction();
        try {

            $datanya = [];
            foreach ($newdata as $key => $value) {
                $tahun = substr($request->tahun, 2, 4);
                $bulan = $request->bulan;

                $datanya[$key]["id_gk"] = $value->karyawan_id . $tahun . $bulan . $id_perush;
                $datanya[$key]["kode_gk"] = "GK" . $value->karyawan_id . $tahun . $bulan . $id_perush;
                $datanya[$key]["id_karyawan"] = $value->karyawan_id;
                $datanya[$key]["n_gaji"] = isset($value->n_gaji) ? $value->n_gaji : 0;
                $datanya[$key]["n_bpjs"] = isset($value->n_tunjangan_kesehatan) ? $value->n_tunjangan_kesehatan : 0;
                $datanya[$key]["n_tunjangan_jabatan"] = isset($value->n_tunjangan_jabatan) ? $value->n_tunjangan_jabatan : 0;
                $datanya[$key]["n_tunjangan_kinerja"] = isset($value->n_tunjangan_kinerja) ? $value->n_tunjangan_kinerja : 0;
                $datanya[$key]["n_tunjangan_kpi"] = isset($value->n_tunjangan_kpi) ? $value->n_tunjangan_kpi : 0;
                $datanya[$key]["n_tunjangan_kesehatan"] = isset($value->n_tunjangan_kesehatan) ? $value->n_tunjangan_kesehatan : 0;
                $datanya[$key]["n_tunjangan_jht"] = isset($value->n_tunjangan_jht) ? $value->n_tunjangan_jht : 0;
                $datanya[$key]["n_tunjangan_jkk"] = isset($value->n_tunjangan_jkk) ? $value->n_tunjangan_jkk : 0;
                $datanya[$key]["n_tunjangan_jkm"] = isset($value->n_tunjangan_jkm) ? $value->n_tunjangan_jkm : 0;
                $datanya[$key]["n_tunjangan_jp"] = isset($value->n_tunjangan_jp) ? $value->n_tunjangan_jp : 0;
                $datanya[$key]["n_potongan_pph"] = isset($value->n_potongan_pph) ? $value->n_potongan_pph : 0;
                $datanya[$key]["n_potongan_kesehatan"] = isset($value->n_potongan_kesehatan) ? $value->n_potongan_kesehatan : 0;
                $datanya[$key]["n_potongan_jht"] = isset($value->n_potongan_jht) ? $value->n_potongan_jht : 0;
                $datanya[$key]["n_potongan_jp"] = isset($value->n_potongan_jp) ? $value->n_potongan_jp : 0;
                $datanya[$key]["id_perush"] = $id_perush;
                $datanya[$key]["dr_tgl"] = $request->dr_tgl;
                $datanya[$key]["sp_tgl"] = $request->sp_tgl;
                $datanya[$key]["bulan"] = $request->bulan;
                $datanya[$key]["tahun"] = $request->tahun;
                $datanya[$key]["created_at"] = date("Y-m-d H:i:s");
                $datanya[$key]["updated_at"] = date("Y-m-d H:i:s");
                $n_denda = 0;

                if (isset($a_denda[$value->id_karyawan])) {
                    $n_denda += $a_denda[$value->id_karyawan];
                }

                $datanya[$key]["n_piutang"] = 0;
                if (isset($piutang[$value->id_karyawan])) {
                    $datanya[$key]["n_piutang"] = $piutang[$value->id_karyawan];
                }

                if ($n_denda < 0) {
                    $n_denda = 0;
                }

                $datanya[$key]["n_denda"] = (Int) $n_denda;
            }

            GajiKaryawan::insert($datanya);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Generate ' . $e->getMessage());
        }
    }

    public function generate_denda(Request $request)
    {
        // dd($request->all());
        $id_perush = $request->id_perush;
        $dr_tgl = $request->dr_tgl;
        $sp_tgl = $request->sp_tgl;
        $a_denda = [];
        if ($request->type == "1") {
            $a_denda = $this->getPersentase($dr_tgl, $sp_tgl, $id_perush);
        } else {
            $a_denda = $this->getDenda($dr_tgl, $sp_tgl, $id_perush);
        }

        $newdata = GajiKaryawan::getData($id_perush)->get();
        DB::beginTransaction();
        try {

            $datanya = [];
            foreach ($newdata as $key => $value) {
                $tahun = substr($request->tahun, 2, 4);
                $bulan = $request->bulan;
                $n_denda = 0;

                if (isset($a_denda[$value->id_karyawan])) {
                    $n_denda += $a_denda[$value->id_karyawan];
                }

                if ($n_denda < 0) {
                    $n_denda = 0;
                }

                $datanya[$key]["n_denda"] = (Int) $n_denda;
                $gaji = GajiKaryawan::where('id_karyawan', $value->id_karyawan)
                    ->where('bulan', $request->bulan)
                    ->where('tahun', $request->tahun)
                    ->first();
                // dd($gaji, $value->id_karyawan, $request->dr_tgl, $request->sp_tgl, $request->bulan, $request->tahun);
                if (!empty($gaji) && count($gaji) > 0) {
                    $gaji->n_denda = $n_denda;
                    $gaji->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Generate ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Berhasil Generate Denda');
    }

    public function getPiutang($id_perush, $dr_tgl, $sp_tgl)
    {
        $data = PiutangKaryawan::getPiutang($id_perush, $dr_tgl, $sp_tgl);
    }

    public function cetak(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date("m");
        $tahun = date("Y");

        if (isset($request->id_perush) and $request->id_perush != null) {
            $id_perush = $request->id_perush;
        }

        if (isset($request->bulan)) {
            $bulan = $request->bulan;
        }

        if (isset($request->bulan)) {
            $tahun = $request->tahun;
        }

        $gaji = GajiKaryawan::getGaji($bulan, $tahun, $id_perush);
        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;
        $data["tahun"] = $tahun;
        $data["data"] = $gaji;
        $data["tunjangan"] = isset($request->tunjangan) ? $request->tunjangan : [];
        $data["tunj_nonthp"] = isset($request->tunj_nonthp) ? $request->tunj_nonthp : [];
        $data["potongan"] = isset($request->potongan) ? $request->potongan : [];
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);

        // return view('kepegawaian::gajikaryawan.cetak', $data);

        $pdf = \PDF::loadview("kepegawaian::gajikaryawan.cetak-gaji", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    public function cetakall(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date("m");
        $tahun = date("Y");

        if (isset($request->bulan)) {
            $bulan = $request->bulan;
        }

        if (isset($request->bulan)) {
            $tahun = $request->tahun;
        }
        $gaji = GajiKaryawan::getDetailGaji($bulan, $tahun);
        $a_gaji = [];

        foreach ($gaji as $key => $value) {
            $a_gaji[$value->id_perush][$value->id_karyawan] = $value;
        }

        $perusahaan = Perusahaan::getRoleUser();

        $data["perusahaan"] = $perusahaan;
        $data["gaji"] = $a_gaji;
        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;

        return view('kepegawaian::gajikaryawan.cetakall', $data);
    }

    public function excel(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date("m");
        $tahun = date("Y");

        if (isset($request->id_perush) and $request->id_perush != null) {
            $id_perush = $request->id_perush;
        }

        if (isset($request->bulan) and $request->bulan != null) {
            $bulan = $request->bulan;
        }

        if (isset($request->tahun) and $request->tahun != null) {
            $tahun = $request->tahun;
        }
        $gaji = GajiKaryawan::getGaji($bulan, $tahun, $id_perush);

        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;
        $data["data"] = $gaji;
        $data["tunjangan"] = isset($request->tunjangan) ? $request->tunjangan : [];
        $data["tunj_nonthp"] = isset($request->tunj_nonthp) ? $request->tunj_nonthp : [];
        $data["potongan"] = isset($request->potongan) ? $request->potongan : [];
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);

        return view('kepegawaian::gajikaryawan.excel', $data);
    }

    public function excelall(Request $request)
    {
        $id_perush = Session("perusahaan")["id_perush"];
        $bulan = date("m");
        $tahun = date("Y");

        if (isset($request->bulan) and $request->bulan != null) {
            $bulan = $request->bulan;
        }

        if (isset($request->tahun) and $request->tahun != null) {
            $tahun = $request->tahun;
        }

        $gaji = GajiKaryawan::getDetailGaji($bulan, $tahun);
        $a_gaji = [];

        foreach ($gaji as $key => $value) {
            $a_gaji[$value->id_perush][$value->id_karyawan] = $value;
        }
        $perusahaan = Perusahaan::getRoleUser();

        $data["perusahaan"] = $perusahaan;
        $data["gaji"] = $a_gaji;
        $data["bulan"] = $bulan;
        $data["tahun"] = $tahun;

        return view('kepegawaian::gajikaryawan.excelall', $data);
    }

    public function slipgaji($id)
    {
        $gaji = GajiKaryawan::findOrFail($id);
        $karyawan = Karyawan::findOrFail($gaji->id_karyawan);
        $data["gaji"] = $gaji;
        $data["karyawan"] = $karyawan;
        $data["perusahaan"] = Perusahaan::findOrFail($gaji->id_perush);
        $data["dr_tgl"] = $gaji->dr_tgl;
        $data["sp_tgl"] = $gaji->sp_tgl;
        $data["bulan"] = $gaji->bulan . " / " . $gaji->tahun;

        return view('kepegawaian::gajikaryawan.slipgaji', $data);
    }

    public function approve(Request $request)
    {
        $date = (Int) date("d");
        $bulan = $request->a_bulan;
        $tahun = $request->a_tahun;

        $id_perush = Session("perusahaan")["id_perush"];
        $gaji = GajiKaryawan::where("id_perush", $id_perush)
            ->where("bulan", $bulan)->where("tahun", $tahun)->get()->first();

        if ($gaji == null) {
            return redirect()->back()->with('error', 'Gaji Bulan ' . $bulan . ' tahun ' . $tahun . ' belum digenerate  ');
        }

        if ($gaji->is_approve == 1) {
            return redirect()->back()->with('error', 'Gaji Bulan ' . $bulan . ' tahun ' . $tahun . ' sudah di approve  ');
        }

        $piutang = PiutangKaryawan::where("id_perush", $id_perush)->where("approve", true)->where("is_lunas", "!=", true)->get();
        $gaji = GajiKaryawan::where("id_perush", $id_perush)
            ->where("bulan", $bulan)->where("tahun", $tahun)->get();

        DB::beginTransaction();
        try {
            $n_piutang = [];
            foreach ($gaji as $key => $value) {
                $a_gaji["ac4_kredit"] = $request->ac_kredit;
                $a_gaji["ac4_debit"] = $request->ac_debit_gaji;
                $a_gaji["is_approve"] = true;
                GajiKaryawan::where("id_gk", $value->id_gk)->update($a_gaji);
                if ($value->n_piutang > 0) {
                    $n_piutang[$value->id_karyawan] = $value->n_piutang;
                }
            }

            foreach ($piutang as $key => $value) {
                $a_piutang["bayar"] = 0;
                $a_detail["n_bayar"] = 0;
                if (isset($n_piutang[$value->id_karyawan]) and $n_piutang[$value->id_karyawan] > 0) {
                    $a_piutang["bayar"] = $value->bayar + $n_piutang[$value->id_karyawan];
                    $a_detail["n_bayar"] = $n_piutang[$value->id_karyawan];
                }
                $a_piutang["angsuran_ke"] = $value->angsuran_ke + 1;
                $a_piutang["sisa"] = $value->nominal - $a_piutang["bayar"];
                if ($a_piutang["bayar"] == $value->nominal) {
                    $a_piutang["is_lunas"] = true;
                    $a_piutang["tgl_selesai"] = date("Y-m-d");
                }
                $a_detail["ac4_debit"] = $request->ac_kredit;
                $a_detail["ac4_kredit"] = $request->ac_kredit_piutang;
                $a_detail["tgl_bayar"] = date("Y-m-d");
                $a_detail["id_perush"] = $id_perush;
                $a_detail["id_karyawan"] = $value->id_karyawan;
                $a_detail["id_piutang"] = $value->id_piutang;
                $a_detail["id_user"] = Auth::user()->id_user;
                $a_detail["created_at"] = date("Y-m-d H:i:s");
                $a_detail["updated_at"] = date("Y-m-d H:i:s");
                DetailPiutang::insert($a_detail);
                PiutangKaryawan::where("id_piutang", $value->id_piutang)->update($a_piutang);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Aprove Gaji Karyawan ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Gaji Karyawan Berhasil Di Approve');
    }

    public function rekapGaji(Request $request)
    {
        $id_perush = isset($request->id_perush) ? $request->id_perush : Session("perusahaan")["id_perush"];
        $from_year = isset($request->from_year) ? $request->from_year : date('Y');
        $to_year = isset($request->to_year) ? $request->to_year : date('Y');
        $rekap_gaji = GajiKaryawan::getRekapGaji($id_perush, $from_year, $to_year);

        $rekap = [];
        $bulan = [];
        $tahun = [];

        foreach ($rekap_gaji as $key => $value) {
            $tahun[$value->tahun] = $value->tahun;
            $bulan[$value->tahun][] = $value->bulan;
            $rekap[$value->tahun][$value->bulan] = $value;
        }

        // dd($tahun, $bulan, $rekap);
        if (get_admin()) {
            $data["perusahaan"] = Perusahaan::select("id_perush", "nm_perush")->get();
        } else {
            $data["perusahaan"] = Perusahaan::getRoleUser();
        }
        $data["tahun"] = $tahun;
        $data["bulan"] = $bulan;
        $data["data"] = $rekap;
        $data["filter"] = [
            'from_year' => $from_year,
            'to_year' => $to_year,
            'id_perush' => $id_perush,
        ];

        return view('kepegawaian::gajikaryawan.rekap-gaji', $data);
    }

    public function cetakRekapGaji(Request $request)
    {
        $id_perush = isset($request->id_perush) ? $request->id_perush : Session("perusahaan")["id_perush"];
        $from_year = isset($request->from_year) ? $request->from_year : date('Y');
        $to_year = isset($request->to_year) ? $request->to_year : date('Y');
        $rekap_gaji = GajiKaryawan::getRekapGaji($id_perush, $from_year, $to_year);

        $rekap = [];
        $bulan = [];
        $tahun = [];

        foreach ($rekap_gaji as $key => $value) {
            $tahun[$value->tahun] = $value->tahun;
            $bulan[$value->tahun][] = $value->bulan;
            $rekap[$value->tahun][$value->bulan] = $value;
        }

        // dd($tahun, $bulan, $rekap);
        $data["tahun"] = $tahun;
        $data["bulan"] = $bulan;
        $data["data"] = $rekap;
        $data["filter"] = [
            'from_year' => $from_year,
            'to_year' => $to_year,
            'id_perush' => $id_perush,
        ];        
        $data["perusahaan"] = Perusahaan::findOrFail($id_perush);
        

        // return view('kepegawaian::gajikaryawan.cetak', $data);

        $pdf = \PDF::loadview("kepegawaian::gajikaryawan.cetak-rekap-gaji", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
}
