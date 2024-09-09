@extends('template.document2')
@section('data')
@section('style')
<style>
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15px;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 14px;
    }
    .datanya{
        font-family: sans-serif !important;
        line-height: 12px;
        font-size: 12px;
        /* font-weight: bold; */
        color: #000;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 14px;
        margin-top: 10px;
    }
    .text-center{
        text-align : center;
    }
    .text-right{
        text-align : right;
    }
    .heading{
        text-align: center;
        font-size: 14px;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .penutup{
        font-size: 14px;
        height : 70px;
        vertical-align: text-top;
        text-align : center;
    }
    .atas{
        font-size: 16px;
    }
    
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url($filter["backs"]) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>

<div id="print-kan">
    <div class="container" style=" margin-top:10px;">
        <table width="100%">
            <tr>
                <td rowspan="3" width="20%">
                    @php
                    
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                    
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </td>
                <td class="heading">{{ strtoupper($perusahaan->nm_perush) }}</td>
            </tr>
            <tr>
                <td class="heading">
                    @php
                    echo ($perusahaan->header);
                    @endphp
                </td>
            </tr>
        </table>
    </div>
    
    <hr class="hrhead">
    
    <div class="container" style="margin-top:20px">
        <table class="atas">
            <tr>
                <td>Tgl. Awal</td>
                <td> : </td>
                <td>@if(isset($filter["f_start"])){{ daydate($filter["f_start"]).", ".dateindo($filter["f_start"]) }}@endif</td>
                
            </tr>
            <tr>
                <td>Tgl. Akhir</td>
                <td> : </td>
                <td>@if(isset($filter["f_end"])){{ daydate($filter["f_end"]).", ".dateindo($filter["f_end"]) }}@endif</td>
            </tr>
        </table>
        
        <table width="100%" class="t">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kota Pengirim</th>
                    <th>Kota Penerima</th>
                    <th>Jumlah STT</th>
                    <th>Berat</th>
                    <th>Volume</th>
                    <th>Koli</th>
                    <th>Omset</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($data as $key => $value)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>@if(isset($value->asal)){{$value->asal}}@endif</td>
                    <td>@if(isset($value->tujuan)){{$value->tujuan}}@endif</td>
                    <td>@if(isset($value->jumlah_stt)){{$value->jumlah_stt}}@endif</td>
                    <td>@if(isset($value->berat)){{$value->berat}}@endif</td>
                    <td>@if(isset($value->volume)){{$value->volume}}@endif</td>
                    <td>@if(isset($value->jumlah_koli)){{$value->jumlah_koli}}@endif</td>
                    <td class="text-right">@if(isset($value->total)){{ toRupiah($value->total) }}@endif</td>
                    @php
                    $total += $value->total;
                    @endphp
                </tr>           
                @endforeach
                <tr style="border-top: 1px solid">
                    <td class="text-right" colspan="7"><b>Total : </b></td>
                    <td class="text-right"><b>{{ toRupiah($total) }} </b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
        }
    }
    body {
        font-family: sans-serif !important; 
        line-height: 20px;
        font-size: 15px;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 10px;
    }
    
    .head{
        font-size: 18px;
        font-weight: bold;
        height: 50px;
    }
    .text-center{
        text-align : center;
    }
    .text-right{
        text-align : right;
    }
    .heading{
        text-align: center;
        font-size: 14px;
    }
    .kepada{
        line-height: 15px;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .headnote{
        border-top: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-collapse: collapse;
        text-align: left;
        font-size : 10px;
    }
    .note{
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-collapse: collapse;
        text-align: left;
        font-size : 10px;
        height : 50px;
    }
    .penutup{
        font-size: 14px;
    }
</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
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