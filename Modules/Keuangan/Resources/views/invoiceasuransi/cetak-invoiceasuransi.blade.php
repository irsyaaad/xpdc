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
    <title>Cetak INVOICE Asuransi| Lsj Express Group</title>
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
        .table1 {
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }
        .table1 tr th{
            background: grey;
            color: #fff;
            font-weight: bold;
        }
        .table1, th, td {
            text-align: left;
        }
        .text-center{
            text-align: center;
        }
        .text-title{
            margin-left: 10pt;
        }
        
        .heading{
            text-align: center;
            padding-top: 10px;
            line-height: 15px;
        }
        
        .table2{
            
            margin-top: -5px;
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }
        
        .text-body{
            font-size: 7pt !important;
        }

        .stt{
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
        margin-top: -40px;
        line-height: 10px;
    }
    .kepada td{
        font-size : 12px;
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
    .headnote td {
        padding-left : 10px;
    }
    .note{
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-collapse: collapse;
        text-align: left;
        font-size : 12px;
        font-weight : bold;
        color : red;
        height : 50px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .penutup td{
        font-size: 12px;
    }
    .setelah-garis p {
        font-size: 12px;
    }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td rowspan="3" style="text-align: center;">
                    @php
                    
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                    
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:-20px">
                </td>
                <td class="heading">
                    <center>
                        <b style="font-size:16px">{{ strtoupper($perusahaan->nm_perush) }}</b><br>
                        <label style="font-size:12px">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>
                    
                    <hr>
                </td>                
            </tr>            
        </table>
    </div>
    {{-- <div class="stt" style="line-height:5px;">
        <p style="margin-top:30px">INVOICE TAGIHAN</p>
        <p style="font-size:12px">No. {{$invoice->kode_invoice}}</p>
    </div> --}}
    
    <table width="100%" class="kepada">
        <tr>
            <td colspan="2" class="head text-center" style="font-size:16px">INVOICE TAGIHAN</td>
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
            <td colspan="2" class="text-right">{{$invoice->pelanggan->nm_perush}}</td>                    
        </tr>
        <tr>
            <td colspan="2" class="text-right">{{$invoice->pelanggan->alamat}}</td>                   
        </tr>
        <tr>
            <td colspan="2" class="text-right">{{$invoice->pelanggan->telp}}</td>                
        </tr>
    </table>  
    <hr class="hr">
    <div style="line-height: 1px" class="setelah-garis">
        <p>Kepada, Yth</p>
        <p>{{$invoice->nm_pelanggan}}</p>
        <p>Di Tempat</p>
        <br>
        <p>Dengan ini kami lampirkan Invoice untuk tagihan biaya <b>Asuransi Barang </b> anda,</p>
        <p>dengan rincian sebagai berikut :</p>
    </div>

    <div class="container">
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <th>No.</th>
                <th>No. STT / Tgl Masuk</th>
                <th>Asal / Tujuan</th>
                <th>Koli</th>
                <th>Harga (Nilai) Barang</th>
                <th>Biaya Asuransi</th>
                <th>Bayar</th>
                <th>Piutang</th>
            </thead>
            
            <tbody>
                @php
                $total = 0;
                $tot_bayar = 0;
                $no = 0;
                @endphp
                @foreach($detail as $key => $value)}
                <tr>
                    <td class="t">{{$no+1}}</td>
                    <td class="t">
                        @isset($value->asuransi->stt->kode_stt) {{ $value->asuransi->stt->kode_stt }}@endisset
                        <br>
                        @isset($value->asuransi->stt->tgl_masuk){{ dateindo($value->asuransi->stt->tgl_masuk) }}@endisset
                        <br>
                        a.n : @isset($value->asuransi->stt->pengirim_nm){{ ($value->asuransi->stt->pengirim_nm) }}@endisset
                    </td>
                    <td class="t">
                        @isset($value->asuransi->stt->asal->nama_wil) {{ $value->asuransi->stt->asal->nama_wil }}@endisset /
                        <br>
                        @isset($value->asuransi->stt->tujuan->nama_wil) {{ $value->asuransi->stt->tujuan->nama_wil }}@endisset
                    </td>
                    <td class="t">{{$value->asuransi->qty}}</td>
                    <td class="t" style="text-align:left; padding-left : 5px;">
                        Rp. {{number_format($value->asuransi->harga_pertanggungan, 0, ',', '.')}}
                    </td>
                    <td class="t" style="text-align:left; padding-left : 5px;">
                        Rp. {{number_format($value->asuransi->nominal, 0, ',', '.')}}
                    </td>
                    <td class="t">
                        @if ($value->asuransi->bayar !== null)
                            Rp. {{number_format($value->asuransi->bayar->sum('n_bayar'), 0, ',', '.')}}
                            @php
                                $tot_bayar += $value->asuransi->bayar->sum('n_bayar');
                            @endphp
                        @else
                            Rp. 0;
                        @endif
                    </td>
                    @if ($value->asuransi->bayar !== null)
                        <td class="t">Rp. {{number_format($value->asuransi->nominal-$value->asuransi->bayar->sum('n_bayar'), 0, ',', '.')}}</td>
                    @else    
                        <td class="t">Rp. {{number_format($value->asuransi->nominal-$value->bayar, 0, ',', '.')}}</td>
                    @endif
                </tr>
                @php
                    $total+=$value->asuransi->nominal;                    
                @endphp
                @endforeach
                <tr>
                    <td class="t text-center" colspan="5">Total</td>
                    <td class="t text-center">{{ torupiah($total) }}</td>
                    <td class="t text-center">{{ torupiah($tot_bayar) }}</td>
                    <td class="t text-center">{{ torupiah($total-$tot_bayar) }}</td>
                </tr>
                <tr>
                    <td class="t" colspan="10"><b>Terbilang : {{ terbilang($total-$tot_bayar) }} Rupiah</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <table width="100%" style="margin-top:20px" class="penutup">
        <tr>
            <td class="text-right">{{$perusahaan->kotakab}}, {{dateindo($invoice->tgl)}}</td>
        </tr>
        <tr>
            <td class="text-right">Hormat Kami,</td>
        </tr>
        <tr>
            <td height="60px"></td>
        </tr>
        <tr><td class="text-right">
            <br><br>
            {{strtoupper($invoice->user->nm_user)}}
        </td></tr>
    </table>
    <br>
    <div>
        <table width="100%" style="border-collapse: collapse;" class="headnote">
            <tr><td> Catatan : </td></tr>
            <tr class="note">
                <td height="50px" style="vertical-align: text-top;">
                    @if (isset($perusahaan->info_invoice))
                        {!! $perusahaan->info_invoice !!}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
