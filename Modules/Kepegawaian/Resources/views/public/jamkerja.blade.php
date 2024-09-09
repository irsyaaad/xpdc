@extends('kepegawaian::layout')
@section('content')
@php
$total_jam_kerja = ($jmla * 8);
$jam_kerja =  ($jmla-$jml) * 8;
$jam_libur = ($jml * 8);
@endphp
@include('kepegawaian::filter')
<hr>
<div class="row">
    <div class="col-md-3">
        <label>Tanggal Awal : <b>@if(isset($filter["f_dr_tgl"])){{ dateindo($filter["f_dr_tgl"]) }}@endif</b></label><br>
        <label>Tanggal Akhir : <b>@if(isset($filter["f_sp_tgl"])){{ dateindo($filter["f_sp_tgl"]) }}@endif</b></label><br>
    </div>
    <div class="col-md-3">
        <label>Jumlah Hari : <b>{{ $jmla }}</b></label><br>
        <label>Jumlah Jam : <b>{{ $total_jam_kerja }}</b></label><br>
    </div>
    <div class="col-md-3">
        <label>Jumlah Hari Kerja : <b>{{ ($jmla-$jml) }}</b></label><br>
        <label>Jumlah Jam Kerja  : <b>{{ $jam_kerja }}</b></label><br>
    </div>
    <div class="col-md-3">
        <label>Jumlah Hari Libur : <b>{{ $jml }}</b></label><br>
        <label>Jumlah Jam Libur : <b>{{ $jam_libur }}</b></label>
    </div>
    
    <div class="col-md-12">
        <hr>
        <nav>
            <div class="nav nav-tabs nav-pills nav-fill" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                    <b>Data Absensi</b>
                </a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                    <b>Data Jam Kerja</b>
                </a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <b>Data Statistik</b>
                </a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                @include("kepegawaian::absensi")
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
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
                                {{-- <td style="border: 1px solid white;">Alpha</td> --}}
                                
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
                            @endphp
                            @foreach($karyawan as $key => $value)
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
                                    @if(isset($kehadiran[$value->id_karyawan]))
                                    {{ $kehadiran[$value->id_karyawan]["total"] }}
                                    @php
                                    $s_jam_kerja += $kehadiran[$value->id_karyawan]["total"];
                                    @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($hijin[$value->id_karyawan]["dd"]))
                                    @php
                                    $td = $hijin[$value->id_karyawan]["dd"]["total"]*8;
                                    $s_jam_kerja += $td;
                                    @endphp
                                    @endif
                                    
                                    @if(isset($jizin[$value->id_karyawan]["id"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["id"]["total"])/60,2);
                                    $s_jam_kerja += $tt;
                                    @endphp
                                    {{ ($tt+$td) }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($hijin[$value->id_karyawan]["dk"]))
                                    {{ $hijin[$value->id_karyawan]["dk"]["total"]*8 }}
                                    @php
                                    $s_jam_kerja += $hijin[$value->id_karyawan]["dk"]["total"]*8;
                                    @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($hijin[$value->id_karyawan]["c"]))
                                    {{ $hijin[$value->id_karyawan]["c"]["total"]*8 }}
                                    @php
                                    $s_jam_kerja += $hijin[$value->id_karyawan]["c"]["total"]*8;
                                    @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($hijin[$value->id_karyawan]["s"]))
                                    {{ $hijin[$value->id_karyawan]["s"]["total"]*8 }}
                                    @php
                                    $s_jam_kerja += $hijin[$value->id_karyawan]["s"]["total"]*8;
                                    @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($hijin[$value->id_karyawan]["bd"]))
                                    {{ $hijin[$value->id_karyawan]["bd"]["total"]*8 }}
                                    @php
                                    $s_jam_kerja += $hijin[$value->id_karyawan]["bd"]["total"]*8;
                                    @endphp
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($jizin[$value->id_karyawan]["ps"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["ps"]["total"])/60,2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif 
                                </td>
                                <td class="td-garis">
                                    @if(isset($jizin[$value->id_karyawan]["tm"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["tm"]["total"])/60,2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif 
                                </td>
                                <td class="td-garis">
                                    @if(isset($jizin[$value->id_karyawan]["it"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["it"]["total"])/60,2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif 
                                </td>
                                <td class="td-garis">
                                    @if(isset($jizin[$value->id_karyawan]["ip"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["ip"]["total"])/60,2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($jizin[$value->id_karyawan]["k"]))
                                    @php
                                    $tt = round(toMinutes($jizin[$value->id_karyawan]["k"]["total"])/60,2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($terlambat[$value->id_karyawan]))
                                    @php
                                    $tt = round(toMinutes($terlambat[$value->id_karyawan])/60, 2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($istirahat[$value->id_karyawan]) and $value->id_perush != "14")
                                    @php
                                    $tt = round(toMinutes($istirahat[$value->id_karyawan])/60, 2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    {{ $tt }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if(isset($pulang[$value->id_karyawan]))
                                    @php
                                    $tt = round(toMinutes($pulang[$value->id_karyawan])/60, 2);
                                    $s_jam_kosong += $tt;
                                    @endphp
                                    @endif
                                </td>
                                @php
                                    $alpha = $jam_kerja-$s_jam_kerja-$s_jam_kosong;
                                    if($alpha < 0){
                                        $alpha = 0;
                                    }
                                    $s_jam_kosong += $alpha;
                                    @endphp
                                <td class="td-garis">
                                    @php
                                    $s_jam_kerja = $jam_kerja - $s_jam_kosong; 
                                    if($s_jam_kerja>$jam_kerja){
                                        $s_jam_kerja = $jam_kerja;
                                    }
                                    if($s_jam_kerja<0){
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
                                    if($jk>$jam_kerja){
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
                                    @endphp
                                    {{ $tt." %" }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="td-garis text-right" colspan="15">
                                    RATA - RATA :
                                </td>
                                <td class="td-garis">
                                    @if($total_jam > 0)
                                    {{ round(($total_jam/$total_karyawan), 3) }}
                                    @else
                                    {{ 0 }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if($total_jam_kosong > 0)
                                    {{ round(($total_jam_kosong/$total_karyawan), 3) }}
                                    @else
                                    {{ 0 }}
                                    @endif
                                </td>
                                <td class="td-garis">
                                    @if($total_prosentase > 0)
                                    {{ round(($total_prosentase/$total_karyawan), 3) }} %
                                    @elseif($total_prosentase > 100)
                                    {{ 100 }} %
                                    @else
                                    {{ 0 }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                @include("kepegawaian::statistik")
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#f_perush").val('{{ $filter["f_perush"] }}');
    $("#f_id_karyawan").val('{{ $filter["f_id_karyawan"] }}');
</script>
@endsection