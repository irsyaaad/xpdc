@extends('template.document')
@section('style')
<style>
    .text-bold{
        font-weight: bold;
    }
</style>
@endsection
@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="level">
                <b>Dari Tanggal</b>
            </label>
            <input type="date" id="dr_tgl" name="dr_tgl" value="{{ $dr_tgl }}" class="form-control"/>
        </div>
        
        <div class="form-group col-md-3">
            <label for="level">
                <b>Sampai Tanggal</b>
            </label>
            <input type="date" id="sp_tgl" name="sp_tgl" value="{{ $sp_tgl }}" class="form-control"/>
        </div>
        
        <div class="form-group col-md-3" style="margin-top: 25px">
            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-filter"> Filter</i></button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning"><i class="fa fa-retweet"> Refresh</i></a>
            <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
        </div>
        
        <div class="col-md-12" id="print-js">
            <center>
                <b style="font-weight:bold">Rekap Status Wajib</b><br>
                <b style="font-weight:bold">Periode : {{ $dr_tgl }} s/d {{ $sp_tgl }}</b>
            </center>
            <table class="table table-responsive table-bordered mt-2">
                <thead style="background-color: grey; color : #ffff">
                    <tr >
                        <th rowspan="2" class="text-center">No</th>
                        <th rowspan="2" class="text-center">Perusahaan Asal</th>
                        <th colspan="3" class="text-center">Jumlah</th>
                        <th colspan="3" class="text-center">Pengirim</th>
                        <th colspan="3" class="text-center">Penerima</th>
                    </tr>
                    <tr >
                        <th class="text-center">STT</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Rata-Rata</th>
                        
                        <th class="text-center">Wajib</th>
                        <th class="text-center">Stat Ok</th>
                        <th class="text-center">%</th>
                        
                        <th class="text-center">Wajib</th>
                        <th class="text-center">Stat Ok</th>
                        <th class="text-center">%</th>
                    </tr>
                    <tr >
                        <th class="text-center">A</th>
                        <th class="text-center">B</th>
                        <th class="text-center">C</th>
                        <th class="text-center"> D</th>
                        <th class="text-center">E=D/C</th>
                        <th class="text-center">F</th>
                        <th class="text-center">G</th>
                        <th class="text-center">H=G/C</th>
                        <th class="text-center">I</th>
                        <th class="text-center">J</th>
                        <th class="text-center">K=J/C</th>
                    </tr>
                </thead>
                <tbody>
                    <tr >
                        <td class="text-bold" colspan="11">1. SEBAGAI PENGIRIM</td>
                    </tr>
                    <tr>
                        <td class="text-right">1.</td>
                        <td>
                            @php
                            $url =url("laporanstatuswajib").'/'.$perush->id_perush.'/detailpengirim'.'?id_perush='.$perush->id_perush.'&dr_tgl='.$dr_tgl.'&sp_tgl='.$sp_tgl;
                            @endphp
                            <a href="{{ $url }}">
                                {{ $perush->nm_perush }}
                            </a>
                        </td>
                        <td>{{ $status->total_stt }}</td>
                        <td>{{ $status->total_status }}</td>
                        <td>{{ ($status->total_stt && $status->total_status) > 0 ? round(($status->total_status/$status->total_stt), 2) : 0 }}</td>
                        <td>{{ $wajib_pengirim }}</td>
                        <td>{{ $status->total_status_ok_pengirim }}</td>
                        <td>{{ ($status->total_stt && $status->total_status_ok_pengirim) > 0 ? round(($status->total_status_ok_pengirim/$status->total_stt) * 100,2) : 0 }}</td>
                        <td>{{ $wajib_penerima }}</td>
                        <td>{{ $status->total_status_ok_penerima }}</td>
                        <td>{{ ($status->total_stt && $status->total_status_ok_penerima) > 0 ? round(($status->total_status_ok_penerima/$status->total_stt) * 100,2) : 0 }}</td>
                    </tr>
                    <tr >
                        <td class="text-bold" colspan="11">2. SEBAGAI PENERIMA</td>
                    </tr>
                </tbody>
            </table>
            <br>
        </div>
    </div>
</form>
<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 landscape;
            color:black;
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15px;
    }
    table {
        margin: auto;
        font-family: "Arial";
        font-size: 12px;
        border-collapse: collapse;
        font-size: 13px;
    }
    table th, 
    table td {
        border-top: 1px solid black;
        border-bottom: 1px solid  black;
        border-left: 1px solid  black;
        padding: 5px 14px;
    }
    table th, 
    table td:last-child {
        border-right: 1px solid  black;
    }
    table td:first-child {
        border-top: 1px solid  black;
    }
    
    table thead th {
        color: black;
    }
    
    .text-bold{
        font-weight: bold;
    }
    
    table tbody td {
        color: black;
    }
</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
@endsection

@section('script')
<script>
    
    @if(isset($data["dr_tgl"]))
    $("#dr_tgl").val("{{ $data['dr_tgl'] }}");
    @endif
    
    @if(isset($data["sp_tgl"]))
    $("#sp_tgl").val("{{ $data['sp_tgl'] }}");
    @endif
    
    function printDiv(elementId) {
        var a = document.getElementById('printing-css').value;
        var b = document.getElementById(elementId).innerHTML;
        window.frames["print_frame"].document.title = document.title;
        window.frames["print_frame"].document.body.innerHTML = '<style>' + a + '</style>' + b;
        window.frames["print_frame"].window.focus();
        window.frames["print_frame"].window.print();
    }
</script>
@endsection
