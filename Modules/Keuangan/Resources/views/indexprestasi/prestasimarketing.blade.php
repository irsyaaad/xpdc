@extends('template.document2')
@section('data')
<style>
    .tr-bold{
        font-weight:bold !important;
        font-size: 10pt;
    }
    .td-right{
        text-align: right;
    }
    .font-dark{
        color: blue;
    }
</style>
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">

    <div class="row">

        <div class="form-group col-md-3">
            <label for="dr_tgl">
                <b>Tanggal Awal</b>
            </label>
            <input class="form-control m-input m-input--square" name="dr_tgl" id="dr_tgl" type="date" />
        </div>
        <div class="form-group col-md-3">
            <label for="sp_tgl">
                <b>Tanggal Akhir</b>
            </label>
            <input class="form-control m-input m-input--square" name="sp_tgl" id="sp_tgl" type="date" />
        </div>

        <div class="form-group col-md-3" style="margin-top: 25px">
            <button class="btn btn-md btn-info" type="submit"><i class="fa fa-filter"></i> Filter</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning"><i class="fa fa-times"> Reset</i></a>
            <a href="javascript:printDiv('print-js');" class="btn btn-md btn-primary"><i class="fa fa-print"> Cetak</i></a>
        </div>

        <div class="col-md-12" id="print-js">
            <center>
                <b style="font-weight:bold">Rekap Index Prestasi Marketing</b><br>
                <b style="font-weight:bold">Periode : {{ $filter["dr_tgl"] }} s/d {{ $filter["sp_tgl"] }}</b>
            </center>
            <br>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No</th>
                            <th>ID Group</th>
                            <th>Nama Group</th>
                            <th>Jumlah STT</th>
                            <th>Jumlah Koli</th>
                            <th>Total Omset</th>
                            <th>Total Bayar</th>
                            <th>%</th>
                            <th>Total Piutang</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalall = 0;
                        $stotalstt = 0;
                        $stotalkoli =0;
                        $stotalomzet = 0;
                        $stotalpiutang = 0;
                        $stotalbayar = 0;
                        $stotalpersen = 0;
                        $stotalperpiut = 0;
                        @endphp
                        @foreach($marketing as $key => $value)
                        <tr>
                            <td colspan="10" style="background-color: rgb(221, 218, 218)">
                                @php
                                    $urls = url("detailprestasimarketing?id_marketing=".$key."&_token=".$filter["_token"]."&dr_tgl=".$filter["dr_tgl"]."&sp_tgl=".$filter["sp_tgl"])
                                @endphp
                                <a href="{{ $urls }}" class="font-dark" >{{ strtoupper($value) }}</a>
                            </td>
                        </tr>
                        @if(isset($data[$value]))
                        @php
                        $no = 1;
                        $total_stt          = 0;
                        $total_koli         = 0;
                        $total_omset        = 0;
                        $total_bayar        = 0;
                        $total_piut         = 0;
                        $prosentase_bayar   = 0;
                        $prosentase_piutang = 0;
                        @endphp
                        @foreach($data[$value] as $key2 => $value2)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>@if(isset($value2->id_plgn_group)){{$value2->id_plgn_group}}@endif</td>
                            <td>@if(isset($value2->nm_group)){{$value2->nm_group}}@endif</td>
                            <td class="td-right">@if(isset($value2->stt))
                                {{$value2->stt}}
                                @php
                                $total_stt += $value2->stt;
                                @endphp
                                @endif
                            </td>
                            <td class="td-right">@if(isset($value2->koli))
                                {{$value2->koli}}
                                @php
                                $total_koli += $value2->koli;
                                @endphp
                                @endif
                            </td>
                            <td class="td-right">@if(isset($value2->omset))
                                {{ number_format($value2->omset, 0, ',', '.') }}
                                @php
                                $total_omset += $value2->omset;
                                if($value2->omset > 0){
                                    $prosentase_bayar = round(($value2->bayar/($value2->omset)) * 100,2);
                                }
                                @endphp
                                @endif
                            </td>
                            <td class="td-right">@if(isset($value2->bayar))
                                {{ number_format($value2->bayar, 0, ',', '.') }}
                                @php
                                $total_bayar += $value2->bayar;
                                @endphp
                                @endif
                            </td>
                            <td class="td-right">{{$prosentase_bayar}} %</td>
                            <td class="td-right">@if(isset($value2->omset) and isset($value2->bayar))
                                {{ number_format($value2->omset-$value2->bayar, 0, ',', '.') }}
                                @php
                                $total_piut += $value2->omset-$value2->bayar;
                                if($value2->omset > 0){
                                    $prosentase_piutang = round((($value2->omset-$value2->bayar)/($value2->omset)) * 100,2);
                                }
                                @endphp
                                @endif
                            </td>
                            <td class="td-right">{{$prosentase_piutang}} %</td>
                        </tr>
                        @endforeach
                        <tr class="tr-bold">
                            <td colspan="3" class="text-center">TOTAL</td>
                            <td class="td-right">{{$total_stt}}</td>
                            <td class="td-right">{{$total_koli}}</td>
                            <td class="td-right"> {{ number_format($total_omset, 0, ',', '.') }}</td>
                            <td class="td-right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
                            @php
                            if ($total_bayar > 0 and $total_omset > 0) {
                                $prosentase_total_bayar = round(($total_bayar/($total_omset)) * 100,2);
                            } else {
                                $prosentase_total_bayar = 0;
                            }                        
                            @endphp
                            <td class="td-right">{{$prosentase_total_bayar}} %</td>
                            <td class="td-right">
                                <b>{{ number_format($total_piut, 0, ',', '.') }} </b>
                            </td>
                            @php
                            if ($total_bayar > 0 and $total_omset > 0) {
                                $prosentase_total_piutang = round(($total_piut/($total_omset)) * 100,2);
                            } else {
                                $prosentase_total_piutang = 0;
                            }                            
                            @endphp
                            <td class="td-right">{{$prosentase_total_piutang}} %</td>
                        </tr>
                        @php
                        $stotalstt +=$total_stt;
                        $stotalkoli +=$total_koli;
                        $stotalomzet +=$total_omset;
                        $stotalbayar +=$total_bayar;
                        $stotalpiutang +=$total_piut;
                        @endphp
                        @endif
                        @endforeach
                        @php
                        $divpersen = divnum($stotalbayar, $stotalomzet)*100;
                        $divperpiut = divnum($stotalpiutang, $stotalomzet)*100;
                        $stotalpersen = number_format($divpersen, 2, ',', '.');
                        $stotalperpiut = number_format($divperpiut, 2, ',', '.');
                        @endphp
                        <tr class="tr-bold " style="background-color: grey; color : #ffff">
                            <td colspan="3" class="text-center">GRAND TOTAL : </td>
                            <td class="text-right">{{ toNumber($stotalstt) }}</td>
                            <td class="text-right">{{ toNumber($stotalkoli) }}</td>
                            <td class="text-right">{{ toNumber($stotalomzet) }}</td>
                            <td class="text-right">{{ toNumber($stotalbayar) }}</td>
                            <td class="text-right">{{ $stotalpersen." %" }}</td>
                            <td class="text-right">{{ toNumber($stotalpiutang) }}</td>
                            <td class="text-right">{{ $stotalperpiut." %" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
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
    .font-dark{
        color: black;
    }
</textarea>
@endsection

@section('script')
<script>
    $("#dr_tgl").val("{{ $filter["dr_tgl"] }}");
    $("#sp_tgl").val("{{ $filter["sp_tgl"] }}");

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
