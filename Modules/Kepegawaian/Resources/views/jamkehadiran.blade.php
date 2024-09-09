@extends('template.document2')
@section('data')
@php
$total_jam_kerja = ($jmla * 8);
$jam_kerja =  ($jmla-$jml) * 8;
$jam_libur = ($jml * 8);
@endphp
@include('kepegawaian::filter.statistikkehadiran')
<hr>
<div class="row">
    <div class="col-md-3">
        <label>Tanggal Awal : <b>@if(isset($dr_tgl)){{ dateindo($dr_tgl) }}@endif</b></label><br>
        <label>Tanggal Akhir : <b>@if(isset($sp_tgl)){{ dateindo($sp_tgl) }}@endif</b></label><br>
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
        <table class="table table-responsive table-striped">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Karyawan</th>
                    <th colspan="2" class="text-center">Jumlah Jam</th>
                    <th rowspan="2">Prosentase (%)</th>
                    <th rowspan="2">Action</th>
                </tr>
                <tr>
                    <th>Jam Bekerja</th>
                    <th>Jam Tidak Bekerja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawan as $key => $value)
                @php
                    $jk = 0;
                @endphp
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->nm_karyawan }}</td>
                    <td>
                        @if(isset($data[$value->id_karyawan]))
                        @php
                            $jk = $data[$value->id_karyawan]*8;
                        @endphp
                        @endif
                        {{ $jk  }}
                    </td>
                    <td>
                        {{ ($jam_kerja-$jk) }}
                    </td>
                    <td>
                        @php
                            $prosentase = $jk * 100 / $jam_kerja;
                            $prosentase = round($prosentase,2);
                        @endphp
                        {{ $prosentase }}
                    </td>
                    <td>
                        @if($prosentase>=80  and $prosentase<90)
                        <a href="{{url('suratperingatan/'.$value->id_karyawan.'/cetak')}}" target="blank">
                            <i class="fa fa-file"></i> <span>SP 1</span>
                        </a>
                        @elseif($prosentase>=70 and $prosentase<80)
                        <a href="{{url('suratperingatan/'.$value->id_karyawan.'/cetak')}}" target="blank">
                            <i class="fa fa-file"></i> <span>SP 2</span>
                        </a>
                        @else
                        <a href="{{url('suratperingatan/'.$value->id_karyawan.'/cetak')}}" target="blank">
                            <i class="fa fa-file"></i> <span>SP 3</span>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
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
    
    $("#f_perush").val('{{ Session("perusahaan")["id_perush"] }}');
    
    @if(isset($filter["f_perush"]))
    $("#f_perush").val('{{ $filter["f_perush"] }}');
    @endif
    
    @if(isset($filter["f_status"]))
    $("#f_status").val(1);
    @endif
    
</script>
@endsection
