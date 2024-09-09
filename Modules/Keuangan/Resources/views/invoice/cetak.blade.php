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
        font-size : 12px;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
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
    .hrhead{
        border: 1px solid black;
    }
    .penutup{
        font-size: 14px;
    }
    
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td rowspan="3">
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
            <tr><td class="heading">{{ $perusahaan->alamat }},{{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</td></tr>
            <tr><td class="heading">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td></tr>
        </table>
        
    </div>
    <hr class="hrhead">
    <div class="container">
        <table width="100%" class="kepada">
            <tr>
                <td colspan="2" class="head text-center" >INVOICE TAGIHAN</td>
            </tr>
            <tr></tr>
            <tr>
                <td>Tanggal : </td>
                <td class="text-right">Kode Invoice : {{$invoice->kode_invoice}}</td>
            </tr>                
            <tr>
                <td>{{dateindo($invoice->tgl)}}</td>
                <td></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td></td>
                <td class="text-right">Kepada : </td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">{{$invoice->pelanggan->nm_pelanggan}}</td>                    
            </tr>
            <tr>
                <td colspan="2" class="text-right">{{$invoice->pelanggan->alamat}}</td>                   
            </tr>
            <tr>
                <td colspan="2" class="text-right">{{$invoice->pelanggan->telp}}</td>                
            </tr>
        </table>  
        <hr class="hr">
        <div style="line-height: 3px">
            <p>Kepada, Yth</p>
            <p>{{$invoice->pelanggan->nm_pelanggan}}</p>
            <p>Di Tempat</p>
            <br>
            <p>Dengan ini kami lampirkan Invoice untuk tagihan biaya Pengiriman Barang anda,</p>
            <p>dengan rincian sebagai berikut :</p>
        </div>
        
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <th>No.</th>
                <th>No. STT</th>
                <th>No AWB</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Koli</th>
                <th>Info</th>
                <th>Harga Kirim</th>
                <th>Diskon</th>
                <th>PPN</th>
                <th>Asuransi</th>
                <th>Total</th>
            </thead>
            
            <tbody>
                @php
                $total = 0;
                $no = 0;
                @endphp
                @foreach($stt as $key => $value)
                <tr>
                    <td class="t">{{$no+1}}</td>
                    <td class="t">@isset($value->kode_stt){{$value->kode_stt}}@endisset</td>
                    <td class="t">@isset($value->no_awb){{$value->no_awb}}@endisset</td>
                    <td class="t">@isset($value->asal->nama_wil){{$value->asal->nama_wil}}@endisset</td>
                    <td class="t">@isset($value->tujuan->nama_wil){{$value->tujuan->nama_wil}}@endisset</td>
                    <td class="t">{{$value->n_koli}}</td>
                    <td class="t">{{$value->n_berat}} Kg
                        <br>{{$value->n_volume}} Kgv
                        <br>{{$value->n_kubik}} M3
                    </td>
                    <td class="t">Rp. {{number_format($value->n_hrg_bruto, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->n_diskon, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->n_ppn, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->n_asuransi, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->c_total, 0, ',', '.')}}</td>
                </tr>
                @php
                    $total+=$value->c_total;
                @endphp
                @endforeach
                <tr>
                    <td class="t text-center" colspan="11">Total</td>
                    <td class="t text-center">{{ torupiah($total) }}</td>
                </tr>
                <tr>
                    <td class="t" colspan="12"><b>Terbilang : {{ terbilang($total) }} Rupiah</b></td>
                </tr>
            </tbody>
        </table>
        
        <br>
        
        <table width="100%" style="margin-top:20px" class="penutup">
            <tr>
                <td class="text-right">{{$perusahaan->kotakab}}, {{dateindo($invoice->tgl)}}</td>
            </tr>
            <tr>
                <td class="text-right">Hormat Kami,</td>
            </tr>
            <tr height="50px">
                
            </tr>
            <tr><td class="text-right">{{strtoupper($perusahaan->nm_perush)}}</td></tr>
        </table>
        
    </div>
    <br>
    <div>
        <table width="100%" style="border-collapse: collapse;">
            <tr class="headnote"><td> Catatan : </td></tr>
            <tr class="note"><td></td></tr>
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
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
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