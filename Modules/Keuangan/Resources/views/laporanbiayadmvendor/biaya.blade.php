@extends('template.document2')

@section('data')
<form method="GET" action="{{ url("biayabydmvendor") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <div class="row align-items-center">
        <div class="col-xl-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Dari Tanggal
                    </label>
                    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
                </div>
                <div class="col-md-4">
                    <label style="font-weight: bold;">
                        Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
                </div>
                <div class="col-md-4" style="padding-top:28px;">
                    <button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span> Cari</button>
                    <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span> Refresh</a>
                    <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
                </div>
                
                <div class="col-md-12 mt-4" id="print-js">
                    <center>
                        <b style="font-weight:bold">Rekap Omzet VS Biaya DM VENDOR</b><br>
                        <b style="font-weight:bold">Periode : {{ $filter['dr_tgl'] }} s/d {{ $filter['sp_tgl'] }}</b>
                    </center>
                    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
                        <table class="table table-bordered table-striped" id="biaya-table">
                            <thead style="background-color: grey; color : #ffff">
                                <th>No</th>
                                <th>Kode DM</th>
                                <th>Tgl Berangkat</th>
                                <th>Cabang Tujuan</th>
                                <th>Nama Kapal</th>
                                <th>Nama Sopir</th>
                                <th>No PLAT</th>
                                <th>Biaya</th>
                                <th>Bayar</th>
                                <th>Sisa</th>
                                <th>Tgl DM Dibuat</th>
                                <th>ID User</th>
                            </thead>
                            <tbody>
                                @php
                                $total_biaya = 0;
                                $total_bayar = 0;
                                $total_sisa  = 0;
                                @endphp
                                @foreach($data as $key => $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>@if(isset($value->kode_dm)) <a href="{{ route('showbiayabydmvendor', [
                                        'id' => $value->id_dm, 
                                        'dr_tgl' => $filter['dr_tgl'], 
                                        'sp_tgl' => $filter['sp_tgl'],
                                        ]) }}">{{$value->kode_dm}}</a> @endif</td>
                                        <td>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
                                        <td>@if(isset($value->nm_perush)){{strtoupper($value->nm_perush)}}@else {{strtoupper($value->nm_ven)}} @endif</td>
                                        <td>@if(isset($value->nm_kapal_perush)){{$value->nm_kapal_perush}} @else - @endif</td>
                                        <td>@if(isset($value->nm_sopir)){{$value->nm_sopir}} @else - @endif</td>
                                        <td>@if(isset($value->no_plat)){{$value->no_plat}} @else - @endif</td>
                                        <td class="text-right">@if(isset($value->biaya)){{ number_format($value->biaya, 0, ',', '.') }} @else 0 @endif</td>
                                        <td class="text-right">@if(isset($value->bayar)){{ number_format($value->bayar, 0, ',', '.') }} @else 0 @endif</td>
                                        <td class="text-right">{{ isset($value->sisa) ? number_format($value->sisa, 0, ',', '.') : '0' }}</td>
                                        <td>@if(isset($value->created_at)){{$value->created_at}}@endif</td>
                                        <td>@if(isset($value->nm_user)){{$value->nm_user}}@endif</td>
                                    </tr>
                                    @php
                                    $total_biaya += $value->biaya;
                                    $total_bayar += $value->bayar;
                                    $total_sisa  += $value->sisa;
                                    @endphp
                                    @endforeach
                                    <tr style="background-color: grey; color : #ffff">
                                        <td colspan=7 class="text-center">Total</td>
                                        <td class="text-right">{{ number_format($total_biaya, 0, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($total_sisa, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
    
    table tbody td {
        color: black;
    }
</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
@endsection

@section('script')
<script>
    
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