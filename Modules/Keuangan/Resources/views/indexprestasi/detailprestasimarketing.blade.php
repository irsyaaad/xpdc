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

    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
    td{
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <input type="hidden" value="{{ $id_marketing }}" id="id_marketing" name="id_marketing">
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
            <a href="javascript:printDiv('print-js');" class="btn btn-md btn-primary"><i class="fa fa-print"> Cetak</i></a>
            <a href="{{ $filter["back"] }}" class="btn btn-md btn-warning"><i class="fa fa-reply"> Kembali</i></a>
        </div>
        
        <div class="col-md-12" id="print-js">
            <center>
                <b style="font-weight:bold">Rekap Detail Index Prestasi Marketing {{ isset($marketing->nm_marketing) ? $marketing->nm_marketing : 'Tanpa Marketing' }}</b><br>
                <b style="font-weight:bold">Periode : {{ $filter["dr_tgl"] }} s/d {{ $filter["sp_tgl"] }}</b>
            </center>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead style="background-color: grey; color : #ffff">
                        <tr class="tr-bold">
                            <th rowspan="2" class="text-center">No</th>
                            <th rowspan="2" class="text-center">No. STT</th>
                            <th rowspan="2" width="100" class="text-center">Tgl Masuk</th>
                            <th rowspan="2" class="text-center">Pengirim</th>
                            <th colspan="3" class="text-center">Tujuan</th>
                            <th rowspan="2" class="text-center">Tipe Kirim</th>
                            <th colspan="2" class="text-center">Total</th>
                        </tr>
                        <tr class="tr-bold">
                            <th class="text-center">Cabang</th>
                            <th class="text-center">Penerima</th>
                            <th width="200" class="text-center">Alamat Penerima</th>
                            <th width="50" class="text-center">Koli</th>
                            <th width="100" class="text-center">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $gkoli = 0;
                            $gomset = 0;
                        @endphp
                        @foreach($group as $key => $value)
                        <tr class="tr-bold">
                            <td colspan="10" style="background-color: rgb(221, 218, 218);">
                                <b style="margin-left: 10px">{{ strtoupper($value) }}</b>
                            </td>
                        </tr>
                        @php
                        $tkoli = 0;
                        $tomset =0;
                        @endphp
                        @if(isset($data[$key]))
                        @php
                            $i = 1;
                        @endphp
                        @foreach($data[$key] as $key2 => $value2)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $value2->kode_stt }}</td>
                            <td>{{ $value2->tgl_masuk }}</td>
                            <td>{{ $value2->pengirim_nm }}</td>
                            <td>{{ $value2->cabang }}</td>
                            <td>{{ $value2->penerima_nm }}</td>
                            <td>{{ $value2->penerima_alm }}</td>
                            <td>{{ $value2->nm_tipe_kirim }}</td>
                            <td class="text-right">{{ toNumber($value2->n_koli) }}</td>
                            <td class="text-right">{{ toNumber($value2->omset) }}</td>
                        </tr>
                        @php
                            $i++;
                            $tkoli += $value2->n_koli;
                            $tomset += $value2->omset;
                        @endphp  
                        @endforeach
                        @endif
                        <tr class="tr-bold" style="background-color: rgb(221, 218, 218); page-break-inside:avoid; page-break-after:auto">
                            <td colspan="8" class="text-center">TOTAL : </td>
                            <td class="text-right">{{ toNumber($tkoli) }}</td>
                            <td class="text-right">{{ toNumber($tomset) }}</td>
                        </tr>
                        @php
                            $gkoli += $tkoli;
                            $gomset += $tomset;
                        @endphp
                        @endforeach
                        <tr class="tr-bold" style="background-color: grey; color : #ffff">
                            <td colspan="8" class="text-center">GRAND TOTAL : </td>
                            <td class="text-right">{{ toNumber($gkoli) }}</td>
                            <td class="text-right">{{ toNumber($gomset) }}</td>
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
    .text-right{
        text-align: right;
    }

    .text-center{
        text-align: center;
    }
    
    table tbody td {
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
