@extends('template.document2')

@section('data')
    @if (Request::segment(1) == 'laporanjamkerja' or Request::segment(2) == 'filter')
        <style>
            table tbody tr td {
                text-align: center;
            }

            table thead tr th {
                text-align: center;
            }

            .td-nama {
                text-align: left;
            }

            .td-garis {
                border: 1px solid rgb(0, 0, 0);
            }
        </style>

        @php
            $total_jam_kerja = $jmla * 8;
            $jam_kerja = ($jmla - $jml) * 8;
            if ($jam_kerja <= 0) {
                $jam_kerja = 8;
            }
            $jam_libur = $jml * 8;
        @endphp

        @include('kepegawaian::filter.filter-jamkerja')

        <hr>
        <div class="row">
            <div class="col-md-3">
                <label>Tanggal Awal : <b>
                        @if (isset($dr_tgl))
                            {{ dateindo($dr_tgl) }}
                        @endif
                    </b></label><br>
                <label>Tanggal Akhir : <b>
                        @if (isset($sp_tgl))
                            {{ dateindo($sp_tgl) }}
                        @endif
                    </b></label><br>
            </div>
            <div class="col-md-3">
                <label>Jumlah Hari : <b>{{ $jmla }}</b></label><br>
                <label>Jumlah Jam : <b>{{ $total_jam_kerja }}</b></label><br>
            </div>
            <div class="col-md-3">
                <label>Jumlah Hari Kerja : <b>{{ $jmla - $jml }}</b></label><br>
                <label>Jumlah Jam Kerja : <b>{{ $jam_kerja }}</b></label><br>
            </div>
            <div class="col-md-3">
                <label>Jumlah Hari Libur : <b>{{ $jml }}</b></label><br>
                <label>Jumlah Jam Libur : <b>{{ $jam_libur }}</b></label>
            </div>
            <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-responsive table-striped">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th rowspan="2" style="border: 1px solid white;">Karyawan</th>
                            <th colspan="3" style="border: 1px solid white; text-align:center">Jam Bekerja</th>
                            <th colspan="4" style="border: 1px solid white; text-align:center">Jam Tidak Bekerja (+)</th>
                            <th colspan="7" style="border: 1px solid white; text-align:center">Jam Tidak Bekerja (-)</th>
                            <td colspan="2" style="text-align: center">Perhitungan</td>
                            <th rowspan="2" style="border: 1px solid white;">Persentase (%)</th>
                            <th rowspan="2" style="border: 1px solid white;">Action</th>
                        </tr>
                        <tr style="border: 1px solid white;">
                            <td style="border: 1px solid white;">Hadir</td>
                            <td style="border: 1px solid white;">Dinas Dalam Kota</td>
                            <td style="border: 1px solid white;">Dinas Luar Kota</td>
                            <td style="border: 1px solid white;">Cuti</td>
                            <td style="border: 1px solid white;">Sakit</td>
                            <td style="border: 1px solid white;">Berduka</td>
                            <td style="border: 1px solid white;">Pulang Cepat (Sakit)</td>

                            <td style="border: 1px solid white;">Tidak Masuk</td>
                            <td style="border: 1px solid white;">Izin Terlambat</td>
                            <td style="border: 1px solid white;">Pulang Cepat</td>
                            <td style="border: 1px solid white;">Keluar</td>
                            <td style="border: 1px solid white;">Terlambat</td>
                            <td style="border: 1px solid white;">Terlambat Istirahat</td>
                            <td style="border: 1px solid white;">Pulang Awal</td>

                            <td style="border: 1px solid white;">Jam Bekerja</td>
                            <td style="border: 1px solid white;">Jam Tidak Bekerja</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_jam = 0;
                            $total_jam_kosong = 0;
                            $total_prosentase = 0;
                            $total_karyawan = 0;
                            $total_hitung = 0;
                        @endphp
                        @foreach ($karyawan as $key => $value)
                            @php
                                $s_jam_kerja = 0;
                                $s_jam_kosong = 0;
                                $s_ijin = 0;
                                $total_karyawan++;
                                $td = 0;
                                $tt = 0;
                            @endphp
                            <tr>
                                <td class="td-garis">{{ $value->nm_karyawan }}</td>
                                <td class="td-garis">
                                    @if (isset($kehadiran[$value->id_karyawan]))
                                        {{ $kehadiran[$value->id_karyawan]['total'] }}
                                        @php
                                            $s_jam_kerja += $kehadiran[$value->id_karyawan]['total'];
                                        @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['dd']))
                                        @php
                                            $td = $hijin[$value->id_karyawan]['dd']['total'] * 8;
                                            $s_jam_kerja += $td;
                                        @endphp
                                    @endif

                                    @if (isset($jizin[$value->id_karyawan]['id']))
                                        @php
                                            $tt = round(toMinutes($jizin[$value->id_karyawan]['id']['total']) / 60, 2);
                                            $s_jam_kerja += $tt;
                                        @endphp
                                        {{ $tt + $td }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['dk']))
                                        {{ $hijin[$value->id_karyawan]['dk']['total'] * 8 }}
                                        @php
                                            $s_jam_kerja += $hijin[$value->id_karyawan]['dk']['total'] * 8;
                                        @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['c']))
                                        {{ $hijin[$value->id_karyawan]['c']['total'] * 8 }}
                                        @php
                                            $s_jam_kerja += $hijin[$value->id_karyawan]['c']['total'] * 8;
                                        @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['s']))
                                        {{ $hijin[$value->id_karyawan]['s']['total'] * 8 }}
                                        @php
                                            $s_jam_kerja += $hijin[$value->id_karyawan]['s']['total'] * 8;
                                        @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['bd']))
                                        {{ $hijin[$value->id_karyawan]['bd']['total'] * 8 }}
                                        @php
                                            $s_jam_kerja += $hijin[$value->id_karyawan]['bd']['total'] * 8;
                                        @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($jizin[$value->id_karyawan]['ps']))
                                        @php
                                            $tt = round(toMinutes($jizin[$value->id_karyawan]['ps']['total']) / 60, 2);
                                            $s_jam_kosong += $tt;
                                            $s_jam_kerja += 8;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($hijin[$value->id_karyawan]['tm']))
                                        @php
                                            $tt = $hijin[$value->id_karyawan]['tm']['total'] * 8;
                                            $s_jam_kosong += $tt;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($jizin[$value->id_karyawan]['it']))
                                        @php
                                            $tt = round(toMinutes($jizin[$value->id_karyawan]['it']['total']) / 60, 2);
                                            $s_jam_kosong += $tt;
                                            $s_jam_kerja += 8;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($jizin[$value->id_karyawan]['ip']))
                                        @php
                                            $tt = round(toMinutes($jizin[$value->id_karyawan]['ip']['total']) / 60, 2);
                                            $s_jam_kosong += $tt;
                                            $s_jam_kerja += 8;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($jizin[$value->id_karyawan]['k']))
                                        @php
                                            $tt = round(toMinutes($jizin[$value->id_karyawan]['k']['total']) / 60, 2);
                                            $s_jam_kosong += $tt;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($terlambat[$value->id_karyawan]))
                                        @php
                                            $tt = round(toMinutes($terlambat[$value->id_karyawan]) / 60, 2);
                                            $s_jam_kosong += $tt;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($istirahat[$value->id_karyawan]) and $value->id_perush != '14')
                                        @php
                                            $tt = round(toMinutes($istirahat[$value->id_karyawan]) / 60, 2);
                                            $s_jam_kosong += $tt;
                                        @endphp
                                        {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if (isset($pulang[$value->id_karyawan]))
                                        @php
                                            $tt = round(toMinutes($pulang[$value->id_karyawan]) / 60, 2);
                                            $s_jam_kosong += $tt;
                                        @endphp
                                    @endif
                                </td>
                                @php
                                    $alpha = $jam_kerja - $s_jam_kerja - $s_jam_kosong;
                                    if ($alpha < 0) {
                                        $alpha = 0;
                                    }
                                    $s_jam_kosong += $alpha;
                                @endphp
                                <td class="td-garis">
                                    @php
                                        $s_jam_kerja = $jam_kerja - $s_jam_kosong;
                                        if ($s_jam_kerja > $jam_kerja) {
                                            $s_jam_kerja = $jam_kerja;
                                        }
                                        if ($s_jam_kerja < 0) {
                                            $s_jam_kerja = 0;
                                        }
                                        $total_jam += $s_jam_kerja;
                                    @endphp
                                    {{ $s_jam_kerja }}
                                </td>
                                <td class="td-garis">
                                    @php
                                        $jk = $s_jam_kosong;
                                        $jk = round($jk, 2);
                                        if ($jk > $jam_kerja) {
                                            $jk = 0;
                                        }

                                        $total_jam_kosong += $jk;
                                    @endphp
                                    {{ $jk }}
                                </td>
                                <td class="td-garis">
                                    @php
                                        $tt = ($s_jam_kerja / $jam_kerja) * 100;
                                        $tt = round($tt, 2);
                                        $total_prosentase += $tt;
                                        if ($tt > 0) {
                                            $total_hitung++;
                                        }
                                    @endphp
                                    {{ $tt }}
                                </td>
                                <td class="td-garis">
                                    @if ($tt < 90)
                                        <a href="{{ url('suratperingatan/' . $value->id_karyawan . '/cetak') }}"
                                            target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-print"> </i> SP
                                            1</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="td-garis text-right" colspan="15">
                                RATA - RATA :
                            </td>
                            <td class="td-garis">
                                @if ($total_jam > 0 && $total_hitung > 0)
                                    @if (!in_array(Session('perusahaan')['id_perush'], [13, 19]))
                                        {{ round($total_jam / $total_hitung, 3) }}
                                    @else
                                        {{ round($total_jam / $total_karyawan, 3) }}
                                    @endif
                                @else
                                    {{ 0 }}
                                @endif
                            </td>
                            <td class="td-garis">
                                @if ($total_jam_kosong > 0 && $total_hitung > 0)
                                    @if (!in_array(Session('perusahaan')['id_perush'], [13, 19]))
                                        {{ round($total_jam_kosong / $total_hitung, 3) }}
                                    @else
                                        {{ round($total_jam_kosong / $total_karyawan, 3) }}
                                    @endif
                                @else
                                    {{ 0 }}
                                @endif
                            </td>
                            <td class="td-garis">
                                @if ($total_prosentase > 0 && $total_hitung > 0)
                                    @if (!in_array(Session('perusahaan')['id_perush'], [13, 19]))
                                        {{ round($total_prosentase / $total_hitung, 3) }} %
                                    @else
                                        {{ round($total_prosentase / $total_karyawan, 3) }} %
                                    @endif
                                @elseif($total_prosentase > 100)
                                    {{ 100 }} %
                                @else
                                    {{ 0 }}
                                @endif
                            </td>
                            <td class="td-garis"> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="container" style="color: red">
            <h5>CATATAN : !!</h5>
            <h6>
                Perhitungan Rata-rata diatas menghiraukan data yang memiliki nilai prosentase 0 %. <br>
                (Data yang nol (0) atau kosong tidak masuk perhitungan)
            </h6>
        </div>
    @endif
@endsection

@section('script')
    <script>
        function html() {
            var url = "{{ url(Request::segment(1) . '/cetak') }}";
            window.open(url);
        }

        function excel() {
            var url = "{{ url(Request::segment(1) . '/excel') }}";
            window.open(url);
        }

        $("#f_perush").val('{{ Session('perusahaan')['id_perush'] }}').trigger("chage");

        @if (isset($filter['f_perush']))
            $("#f_perush").val('{{ $filter['f_perush'] }}').trigger("chage");
        @endif

        $("#f_perush").select2();
        $("#f_status").select2();

        @if (isset($filter['f_status']))
            $("#f_status").val(1).trigger("chage");
        @endif
    </script>
@endsection
