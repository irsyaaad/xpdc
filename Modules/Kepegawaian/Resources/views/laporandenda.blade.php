@extends('template.document2')
@section('data')

@if(Request::segment(1)=="dendakehadiran" or Request::segment(2)=="filter")
<style>
    table tbody tr td{
        text-align: center;
    }
    
    table thead tr th{
        text-align: center;
    }
    
    .td-nama{
        text-align: left;
    }
</style>
@include('kepegawaian::filter.statistikkehadiran')
<hr>
<div class="row" >
    
    <div class="col-md-3">
        <label>Tanggal Awal : <b>@if(isset($dr_tgl)){{ dateindo($dr_tgl) }}@endif</b></label><br>
        <label>Tanggal Akhir : <b>@if(isset($sp_tgl)){{ dateindo($sp_tgl) }}@endif</b></label><br>
    </div>
    <div class="col-md-3">
        <label>Jumlah Hari : <b>@if(isset($jmla)){{ $jmla }}@endif</b></label><br>
        <label>Jumlah Hari Kerja : <b>@if(isset($jmla)){{ $jmla-$jml }}@endif</b></label><br>
        
    </div>
    <div class="col-md-3">
        <label>Jumlah Hari Libur : <b>@if(isset($jml)){{ $jml }}@endif</b></label>
    </div>
    <div class="col-md-12" style="overflow-x:auto;">
        <table class="table table-responsive table-striped" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2" style="border: 1px solid white;">No</th>
                    <th rowspan="2" style="border: 1px solid white;">Karyawan</th>
                    <th rowspan="2" style="border: 1px solid white;">Kehadiran</th>
                    <th colspan="{{ count($jenis)+5 }}" style="border: 1px solid white; text-align:center">Denda</th>
                    <th rowspan="2" style="border: 1px solid white;">Total Denda</th>
                </tr>
                <tr style="border: 1px solid white;">
                    @foreach($jenis as $key => $value)
                    <th style="border: 1px solid white;">{{  $value->nm_jenis }}</th>
                    @endforeach
                    <th style="border: 1px solid white;">Terlambat</th>
                    <th style="border: 1px solid white;">Tdk Absen Masuk</th>
                    
                    <th style="border: 1px solid white;">Tdk Absen Pulang</th>
                    <th style="border: 1px solid white;">Pulang Duluan</th>
                    
                    <th style="border: 1px solid white;">Alpha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawan as $key => $value)
                
                @php
                $total = 0;
                $kurang = 0;
                $tlmb = 0;
                $kehadiran = 0;   
                @endphp
                <tr>
                    <td class="td-nama">
                        {{ $key+1 }}
                        {{-- {{ $value->id_karyawan }} --}}
                    </td>
                    <td class="td-nama">
                        {{ strtoupper($value->nm_karyawan) }}
                    </td>
                    @if(isset($dk[$value->id_karyawan]))
                        @php
                            $kehadiran += $dk[$value->id_karyawan];
                        @endphp
                    @endif
                    @if(isset($id[$value->id_karyawan]))
                    @php
                        $kehadiran += $id[$value->id_karyawan];
                    @endphp
                    @endif
                    
                    @if(isset($absen[$value->id_karyawan]))
                    <td class="td-garis">
                        {{ $absen[$value->id_karyawan]["absen"] }}
                    </td>
                    @php
                    $kurang += $absen[$value->id_karyawan]["absen"];
                    @endphp
                    @else
                    <td class="td-garis"></td>
                    @endif

                    @foreach($jenis as $key1 => $value1)
                    @if(isset($ijin[$value->id_karyawan][$value1->id_jenis]))
                    @php
                    $jumlah = $ijin[$value->id_karyawan][$value1->id_jenis]["jumlah"];
                    $nominal = $ijin[$value->id_karyawan][$value1->id_jenis]["nominal"];
                    $frekuensi = $ijin[$value->id_karyawan][$value1->id_jenis]["frekuensi"];
                    $dd = 0;
                    if($jumlah > $frekuensi){
                        $dd = $jumlah - $frekuensi;
                    }
                    
                    $denda = $dd * $nominal;

                    @endphp
                    <td>{{ str_replace(",", ".", number_format($denda)) }}</td>
                    @php
                    $total += $denda;
                    $kurang += $jumlah;
                    @endphp
                    @else
                    <td> 0 </td>
                    @endif
                    @endforeach
                    
                    @php
                    $n_datang = 0;
                    $n_pulang = 0;
                    @endphp
                    
                    <td>
                        @if(isset($datang[$value->id_karyawan][2]) and $datang[$value->id_karyawan][2]->status_datang=="2")
                        @php
                        $setting = $s_datang[2];
                        $jumlah = $datang[$value->id_karyawan][2]->jumlah;
                        $n_hari = 0;
                        if($jumlah > $setting->frekuensi){
                            $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $n_datang += $n_hari;
                        }
                        
                        echo $n_hari;
                        @endphp
                        @endif
                    </td>

                    <td>
                        @if(isset($datang[$value->id_karyawan][3]) and $datang[$value->id_karyawan][3]->status_datang=="3")
                        @php
                        $setting = $s_datang[3];
                        $jumlah = $datang[$value->id_karyawan][3]->jumlah;
                        $n_hari = 0;
                        if($jumlah > $setting->frekuensi){
                            $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $n_datang += $n_hari;
                        }
                        
                        echo $n_hari;
                        @endphp
                        @endif
                    </td>
                    <td>
                        @if(isset($pulang[$value->id_karyawan][4]->status_pulang) and $pulang[$value->id_karyawan][4]->status_pulang=="4")
                        @php
                        $setting = $s_pulang[4];
                        $jumlah = $pulang[$value->id_karyawan][4]->jumlah;
                        $n_hari = 0;
                        if($jumlah > $setting->frekuensi){
                            $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $n_pulang += $n_hari;
                        }
                        echo $n_hari;
                        @endphp
                        @endif
                    </td>
                    <td>
                        @if(isset($pulang[$value->id_karyawan][5]->status_pulang) and $pulang[$value->id_karyawan][5]->status_pulang=="5")
                        @php
                        $setting = $s_pulang[4];
                        $jumlah = $pulang[$value->id_karyawan][5]->jumlah;
                        $n_hari = 0;
                        if($jumlah > $setting->frekuensi){
                            $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                            $n_pulang += $n_hari;
                        }
                        echo $n_hari;
                        @endphp
                        @endif
                    </td>
                    <td>
                        
                        @if(isset($absen[$value->id_karyawan]))
                        @php
                        $hadir =  $jmla - $jml - $kurang - $kehadiran;
                       // dd($jmla, $jml, $kurang);
                        $denda = ($hadir - $alpha->frekuensi) * $alpha->nominal;

                        if($denda < 0 ){
                            $denda = 0;
                        }
                        
                        @endphp
                        {{ str_replace(",", ".", number_format($denda)) }}
                        @else
                        0
                        @endif
                    </td>
                    
                    <td>
                        @if(isset($absen[$value->id_karyawan]))
                        @php
                        
                        $denda = $total+$denda+$n_datang+$n_pulang;
                        @endphp
                        {{ str_replace(",", ".", number_format($denda)) }}
                        @else
                        0
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif
@endsection

@section('script')
<script>
    function html(){
        var url = "{{ url(Request::segment(1)."/cetak") }}";
        window.open(url);
    }
    function excel(){
        var url = "{{ url(Request::segment(1)."/excel") }}";
        window.open(url);
    }
    
    $("#f_perush").val('{{ Session("perusahaan")["id_perush"] }}').trigger("change");
    @if(isset($filter["f_perush"]))  
    $("#f_perush").val('{{ $filter["f_perush"] }}').trigger("change");
    @endif

    $("#f_perush").select2();
    $("#f_status").select2();

    @if(isset($filter["f_status"]))   
    $("#f_status").val('{{ $filter["f_status"] }}').trigger("change");
    @endif
    
</script>
@endsection