@extends('template.document2')

@section('data')
<form method="GET" action="{{ url("omsetbylayanan") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <div class="row align-items-center">
        <div class="col-xl-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <label style="font-weight: bold;">
                        Layanan
                    </label>
                    <select class="form-control" name="f_layanan" id="f_layanan">
                        <option value="">-- Pilih Layanan -- </option>
                        @foreach($layanan as $key => $value)
                        <option value="{{ $value->id_layanan }}">{{ $value->nm_layanan }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="font-weight: bold;">
                        Dari Tanggal
                    </label>
                    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
                </div>
                <div class="col-md-3">
                    <label style="font-weight: bold;">
                        Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
                </div>
                <div class="col-md-3" style="padding-top:28px;">
                    <button class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span> Cari</button>
                    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span> Refresh</a>
                    <a href="javascript:printDiv('print-js');" class="btn btn-sm btn-info"><i class="fa fa-print"> Cetak</i></a>
                </div>
                
                <div class="col-md-12 mt-4" id="print-js">
                    <center>
                        <b style="font-weight:bold">Rekap Omzet By Layanan</b><br>
                        <b style="font-weight:bold">Periode : {{ $filter['dr_tgl'] }} s/d {{ $filter['sp_tgl'] }}</b>
                    </center>
                    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
                        <table class="table table-responsive table-striped table-sm" width="100%">
                            <thead style="background-color: grey; color : #ffff">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Layanan</th>
                                    <th>STT</th>
                                    <th>Berat</th>
                                    <th>Volume</th>
                                    <th>Koli</th>
                                    <th>Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $tstt = 0;
                                    $tberat = 0;
                                    $tvolume = 0;
                                    $tkoli = 0;
                                    $tomset= 0;
                                @endphp
                                @foreach($data as $key => $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>@if(isset($value->nm_layanan))<a href="{{ url(Request::segment(1)."/".$value->id_layanan."/show") }}" style="color:black;">{{$value->nm_layanan}}</a>@endif</td>
                                    <td>@if(isset($value->stt)){{$value->stt}}@endif</td>
                                    <td>@if(isset($value->berat)){{$value->berat}}@endif</td>
                                    <td>@if(isset($value->volume)){{$value->volume}}@endif</td>
                                    <td>@if(isset($value->koli)){{$value->koli}}@endif</td>
                                    <td>@if(isset($value->omset)){{ toNumber($value->omset) }}@endif</td>
                                </tr>
                                @php
                                    $tstt += $value->stt;
                                    $tberat += $value->berat;
                                    $tvolume += $value->volume;
                                    $tkoli += $value->koli;
                                    $tomset += $value->omset;
                                @endphp
                                @endforeach
                                <tr style="background-color: grey; color : #ffff">
                                    <td colspan="2" class="text-right"><b>Total : </b></td>
                                    <td>
                                        {{  toNumber($tstt)  }}
                                    </td>
                                    <td>
                                        {{  toNumber($tberat)  }}
                                    </td>
                                    <td>
                                        {{  toNumber($tvolume)  }}
                                    </td>
                                    <td>
                                        {{  toNumber($tkoli)  }}
                                    </td>
                                    <td>
                                        {{  toNumber($tomset)  }}
                                    </td>
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
    
    @if(isset($filter["f_layanan"]))
    $("#f_layanan").val('{{ $filter["f_layanan"] }}');
    @endif
</script>
@endsection