<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <title>Cetak INVOICE | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style type="text/css">
        html { margin: 15px}
        body{
            font-family: sans-serif !important;
        }
        .page-break {
            page-break-after: always;
        }
        
        .text-center{
            text-align: center;
        }
        .text-title{
            margin-left: 10pt;
        }
        
        .text-body{
            font-size: 7pt !important;
        }
        
        .stt{
            width: 100%;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: -40px;
            text-decoration: underline;
        }
        
        .isi{
            padding : 20px;
        }
        
        .table-isi{
            font-size: 12px;
        }
        
        .isi-content{
            border-bottom : 1px solid black;
        }
        
        .footer{
            padding : 20px;
        }
        
        .table-footer{
            font-size: 12px;
            text-align:center;
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
            font-size : 11px;
        }
        
        .head{
            font-size: 12px;
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
            line-height: 10px;
        }
        .kepada td{
            font-size : 12px;
        }
        .hr{
            border-top: 1px solid red;
            margin-top : 10px;
        }
        .col-xd-3{
            margin-left: 10px;
            margin-right: 10px;
            font-size: 12px;
            float: left;
            position: relative;
        }
    </style>
</head>
<body>
    <div style="width: 100%">
        <div style="width: 30%; float: left; position: relative;" class="text-center">
            @php
            
            if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                
                $full_path = Storage::path($path);
                $base64 = base64_encode(Storage::get($path));
                $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                $perusahaan->logo = $image;
            }
            @endphp
            <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:5%">
        </div>
        <div style="width: 70%;">
            <center>
                <b style="font-size:16px">{{ strtoupper($perusahaan->nm_perush) }}</b>
                <br>
                <label style="font-size:12px">
                    {!! $perusahaan->header !!}
                </label>
            </center>
        </div>
    </div>
    <hr>
    <div class="stt">
        <p>Surat Tanda Terima</p>
    </div>
    
    <div class="isi">
        <table width="100%" class="table-isi">
            <tr>
                <td width="100px">Diterima Oleh</td>
                <td>:</td>
                <td class="isi-content">{{$invoice->pelanggan->nm_pelanggan}}</td>
            </tr>
            
            <tr>
                <td style="vertical-align: text-top;" rowspan="3">Berita</td>
                <td style="vertical-align: text-top;" rowspan="3">:</td>
                <td class="isi-content">INVOICE TAGIHAN "{{$invoice->kode_invoice}}"
                </td>
            </tr>
            <tr>
                <td class="isi-content">
                    Pada Tanggal : 
                    @if(isset($invoice->tgl))
                    {{ daydate($invoice->tgl).", ".dateindo($invoice->tgl) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="isi-content">
                    Terbilang : {{ strtoupper(terbilang($invoice->total)) }}
                </td>
            </tr>
            <tr>
                <td>Kembali Tanggal</td>
                <td> : </td>
                <td class="isi-content"></td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <table class="table-footer" width="100%">
            <tr>
                <td width="50%" style="text-align:center">
                    {{ strtoupper($perusahaan->nm_perush) }}
                </td>
                <td width="50%" style="text-align:center">
                    Yang Menerima
                </td>
            </tr>
            <tr>
                <td height="70px"></td>
            </tr>
            <tr>
                <td width="50%" style="text-align:center">
                    @if(isset($invoice->user->nm_user))
                    {{ strtoupper($invoice->user->nm_user)}}
                    @endif
                </td>
                <td width="50%" style="text-align:center">
                    (.....................................)<br>
                    Cap Nama Jelas
                </td>
            </tr>
        </table>
    </div>
    
    <div class="page-break"></div>
    
    <div style="width: 100%">
        <div style="width: 30%; float: left; position: relative;" class="text-center">
            @php
            
            if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                
                $full_path = Storage::path($path);
                $base64 = base64_encode(Storage::get($path));
                $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                $perusahaan->logo = $image;
            }
            @endphp
            <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:5%">
        </div>
        <div style="width: 70%;">
            <center>
                <b style="font-size:16px">{{ strtoupper($perusahaan->nm_perush) }}</b>
                <br>
                <label style="font-size:12px">
                    {!! $perusahaan->header !!}
                </label>
            </center>
        </div>
    </div>
    <hr>
    <div style="margin-top: -10%">
        <table width="100%" class="kepada">
            <tr>
                <td colspan="2" class="text-center"><br>
                    <b style="font-size: 11pt;">INVOICE TAGIHAN</b>
                    <hr style="width: 130px; margin-top:0px">
                    {{$invoice->kode_invoice}}
                </td>
            </tr>
        </table>
    </div>
    <table width="100%" class="kepada" style="padding: 5px; line-height:12px">
        <tr>
            <td>Tanggal Invoice : {{dateindo($invoice->tgl)}}</td>
            
            <td class="text-right" colspan="2">Kepada : </td>
        </tr>
        <tr>
            <td>Tgl Jatuh Tempo : {{dateindo($invoice->inv_j_tempo)}}</td>
            <td colspan="2" class="text-right">{{$invoice->pelanggan->nm_pelanggan}}</td> 
        </tr>
        <tr>
            <td>NPWP : @if(isset($invoice->pelanggan->npwp)){{ $invoice->pelanggan->npwp }}@endif</td>
            <td colspan="2" class="text-right">{{$invoice->pelanggan->alamat}}</td>  
        </tr>
        <tr>
            <td></td>
            <td colspan="2" class="text-right">{{$invoice->pelanggan->telp}}</td>                
        </tr>
    </table>
    <div class="container" style="padding: 5px">
        <table width="100%" style="border-collapse: collapse;">
            <thead class="head">
                <th>No.</th>
                <th>No. STT / Tgl Masuk</th>
                <th>No AWB</th>
                <th>Asal / Tujuan</th>
                <th>Koli</th>
                <th>Info Barang</th>
                <th>Info Tarif</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kurang</th>
                <th>Keterangan</th>
            </thead>
            <tbody>
                @php
                $total = 0;
                $tot_bayar = 0;
                @endphp
                @foreach($stt as $key => $value)}
                <tr>
                    <td class="t">{{ $key+1 }}</td>
                    <td class="t">
                        @isset($value->kode_stt){{$value->kode_stt}}@endisset
                        <br>
                        @isset($value->tgl_masuk){{ dateindo($value->tgl_masuk) }}@endisset
                        <br>
                        @isset($value->penerima_nm)( {{ $value->penerima_nm }} )@endisset
                    </td>
                    <td class="t">@isset($value->no_awb){{$value->no_awb}}@endisset</td>
                    <td class="t">
                        @isset($value->asal){{$value->asal}}@endisset
                        <br> ->
                        @isset($value->tujuan){{$value->tujuan}}@endisset
                    </td>
                    <td class="t">{{$value->n_koli}}</td>
                    <td class="t" style="text-align:left; padding-left : 5px;">
                        {{$value->n_berat}} ( Kg )
                        <br>
                        {{$value->n_volume}} ( Kgv )
                        <br>
                        {{$value->n_kubik}}  ( M3 )
                    </td>
                    <td class="t" style="text-align:left; padding-left : 5px;">
                        Tarif : Rp. {{number_format($value->n_tarif_brt, 0, ',', '.')}}
                        <br>                        
                        Diskon : Rp. {{number_format($value->n_diskon, 0, ',', '.')}}
                        <br>                        
                        Asuransi : Rp. {{number_format($value->n_asuransi, 0, ',', '.')}}
                        <br>                        
                        PPN : Rp. {{number_format($value->n_ppn, 0, ',', '.')}}
                    </td>
                    <td class="t">Rp. {{number_format($value->c_total, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->bayar, 0, ',', '.')}}</td>
                    <td class="t">Rp. {{number_format($value->c_total-$value->bayar, 0, ',', '.')}}</td>
                    <td class="t">{{ ucwords(strtolower($value->info_kirim)) }}</td>
                </tr>
                @php
                $total+=$value->c_total;
                $tot_bayar += $value->bayar;
                @endphp
                @endforeach
                <tr>
                    <td class="t text-center" colspan="7">Total</td>
                    <td class="t text-center">{{ torupiah($total) }}</td>
                    <td class="t text-center">{{ torupiah($tot_bayar) }}</td>
                    <td class="t text-center">{{ torupiah($total-$tot_bayar) }}</td>
                    <td class="t text-center"></td>
                </tr>
                <tr>
                    <td class="t" colspan="11"><b>Terbilang : {{ terbilang($total-$tot_bayar) }} Rupiah</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div style="width: 100%">
        
        <div class="col-xd-3" style="width: 35%;border: solid 1px; padding: 10px">
            <label>Pembayaran bisa dilakukan via transfer  :</label>
            <p style="color: red; font-weight: bold">
                @if (isset($perusahaan->info_invoice))
                {!! $perusahaan->info_invoice !!}
                @else
                -
                @endif
            </p>
        </div>
        <div class="col-xd-3" style="width: 30%">
            <label>Dicetak Pada : </label><br>
            <label>{{ date("d-m-Y H:i:s") }}</label>
        </div>
        <div class="col-xd-3" style="width: 40%">
            <label>{{ $perusahaan->nm_perush }},</label>
            <p>
                <br>
                <br>
                <br>
                {{strtoupper($invoice->user->nm_user)}}
            </p>
        </div>
    </div>
</body>

</html>
