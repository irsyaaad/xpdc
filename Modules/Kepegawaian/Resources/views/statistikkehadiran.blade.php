@extends('template.document2')
@section('data')

@if(Request::segment(1)=="statistikkehadiran" or Request::segment(2)=="filter")
@include('kepegawaian::filter.statistikkehadiran')
<hr>
<div class="row">
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
</div>

<div style="overflow-x:auto;">
    <table class="table table-responsive table-striped table-bordered">
        <thead style="background-color: grey; color : #ffff; border: 1px solid white;">
            <tr style="border: 1px solid #fff;">
                <th rowspan="2">No</th>
                <th rowspan="2" style="border: 1px solid #fff;">Nama Karyawan</th>
                <th rowspan="2" style="border: 1px solid #fff;">Kehadiran</th>
                <th colspan="{{ count($jenis) }}" style="border: 1px solid #fff; text-align:center">Perizinan</th>
                <th colspan="5" style="border: 1px solid #fff; text-align:center">Ket Hadir</th>
            </tr>
            <tr style="border: 1px solid #fff;">
                @foreach($jenis as $key => $value)
                <th style="border: 1px solid #fff;">{{  $value->nm_jenis }}</th>
                @endforeach
                <th style="border: 1px solid #fff;">Terlambat</th>
                <th style="border: 1px solid #fff;">Tdk Absen Pulang</th>
                <th style="border: 1px solid #fff;">Tdk Absen Masuk</th>
                <th style="border: 1px solid #fff;">Pulang awal</th>
                <th style="border: 1px solid #fff;">Alpha</th>
            </tr>
        </thead>
        <tbody>
            @php
                $hadir = 0;
                $it = 0;
                $pulcep = 0;
                $tidakmasuk = 0;
                $keluar = 0;
                $sakit = 0;
                $terlambat = 0;
                $tp = 0;
                $ta = 0;
                $pa = 0;
                $calpha = 0;
            @endphp
            @foreach($data as $key => $value)
            <tr>
                @php
                $alpha = 0;
                @endphp
                <td>
                    {{ $key+1 }}
                </td>
                <td>
                    {{ strtoupper($value->nm_karyawan) }}
                </td>
                <td>
                    {{ $value->absen }}
                    @php
                        $hadir += $value->absen;
                    @endphp
                </td>
                @foreach($jenis as $key1 => $value1)
                @if(isset($izin[$value->id_karyawan][$value1->id_jenis]))
                <td>{{ $izin[$value->id_karyawan][$value1->id_jenis]->ijin }}</td>
                @php
                $ij = $izin[$value->id_karyawan][$value1->id_jenis];
                
                if($ij->format == "2"){
                    $alpha += $ij->ijin;
                }
                if($value1->id_jenis == "it"){
                    $it += $izin[$value->id_karyawan][$value1->id_jenis]->ijin;
                }
                if($value1->id_jenis == "tm"){
                    $tidakmasuk += $izin[$value->id_karyawan][$value1->id_jenis]->ijin;
                }
                if($value1->id_jenis == "ip"){
                    $pulcep += $izin[$value->id_karyawan][$value1->id_jenis]->ijin;
                }
                if($value1->id_jenis == "k"){
                    $keluar += $izin[$value->id_karyawan][$value1->id_jenis]->ijin;
                }
                if($value1->id_jenis == "s"){
                    $sakit += $izin[$value->id_karyawan][$value1->id_jenis]->ijin;
                }
                @endphp
                @else
                <td></td>
                @endif
                @endforeach

                @for($i = 2; $i<=3; $i++)
                @if(isset($status_datang[$value->id_karyawan][$i]))
                <td>
                    {{ $status_datang[$value->id_karyawan][$i]->jumlah }}
                    @if($i == 2)
                        @php
                            $terlambat += $status_datang[$value->id_karyawan][$i]->jumlah;
                        @endphp
                    @endif
                    @if($i == 3)
                        @php
                            $tp += $status_datang[$value->id_karyawan][$i]->jumlah;
                        @endphp
                    @endif
                </td>
                @else
                <td>
                </td>
                @endif
                @endfor
                
                @for($i = 4; $i<=5; $i++)
                @if(isset($status_pulang[$value->id_karyawan][$i]))
                <td>
                    {{ $status_pulang[$value->id_karyawan][$i]->jumlah }}
                    @if($i == 4)
                        @php
                            $ta += $status_pulang[$value->id_karyawan][$i]->jumlah;
                        @endphp
                    @endif
                    @if($i == 5)
                        @php
                            $pa += $status_pulang[$value->id_karyawan][$i]->jumlah;
                        @endphp
                    @endif
                </td>
                @else
                <td></td>
                @endif
                @endfor
                <td>
                    @php
                    $alpha2 = $jmla - ($jml + $value->absen + $alpha);
                    
                    if($alpha2 < 0){
                        $alpha2 = 0;
                    }
                    @endphp
                    {{ $alpha2 }}
                    @php
                        $calpha += $alpha2;
                    @endphp
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2">Total : </td>
                <td>{{ $hadir }}</td>
                <td colspan="3"> </td>
                <td>{{ $it }}</td>
                <td>{{ $pulcep }}</td>
                <td>{{ $tidakmasuk }}</td>
                <td>{{ $keluar }}</td>
                <td>{{ $sakit }}</td>
                <td>{{ $terlambat }}</td>
                <td>{{ $tp }}</td>
                <td>{{ $ta }}</td>
                <td>{{ $pa }}</td>
                <td>{{ $calpha }}</td>
            </tr>
        </tbody>
    </table>
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
    
    $("#f_perush").select2();
    $("#f_status").select2();

    $("#f_perush").val('{{ Session("perusahaan")["id_perush"] }}');
    
    @if(isset($filter["f_perush"]))  
    $("#f_perush").val('{{ $filter["f_perush"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_status"]))   
    $("#f_status").val('{{ $filter["f_status"] }}').trigger("change");
    @endif
    
</script>
@endsection