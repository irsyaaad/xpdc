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
    <title>Cetak STT | Lsj Express Group</title>
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
            font-size: 11pt;
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
    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td width="45%">
                    @php
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 56px">
                </td>
                <td class="heading">
                    <center>
                        <b>{{ strtoupper($perusahaan->nm_perush) }}</b><br>
                        <label style="font-size:10pt">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <table class="table1">
        <tr>
            <td colspan="3"  style="font-size:8.3pt"><b>No AWB</b></td>
            <td>@if(isset($data->no_awb)){{ $data->no_awb }}@endif</td>
            <td colspan="2" style="border: 1px solid black;">TANGGAL IN</td>
            <td  colspan="2" style="border: 1px solid black;">
                @if(isset($data->tgl_masuk))
                {{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="4"  style="font-size:8pt">
                <b>
                    SURAT TANDA TERIMA TITIPAN No.    
                </b>
                @if(isset($data->kode_stt))
                {{ $data->kode_stt }}
                @endif
            </td>
            <td colspan="2" style="border: 1px solid black;">TANGGAL OUT</td>
            <td colspan="2" style="border: 1px solid black;">
                @if(isset($data->tgl_keluar))
                {{ daydate($data->tgl_keluar).", ".dateindo($data->tgl_keluar) }}
                @endif
            </td>
        </tr>
        <tr style="border-top: 1px solid black; border-left: 1px solid black;">
            <td>Kepada</td>
            <td colspan="3" class="text-body">: {{ strtoupper($data->penerima_nm) }}</td>
            <td colspan="4" class="text-center" style="border: 1px solid black;">NAMA BARANG</td>
        </tr>
        <tr style="border-left: 1px solid black;" height="50px">
            <td class="text-body">Alamat</td>
            <td colspan="3" class="text-body">: 
                {{ strtoupper($data->penerima_alm) }} - {{ $data->penerima_kodepos }}, 
                @if(isset($data->tujuan->nama_wil))
                {{ $data->tujuan->nama_wil }}
                @endif
            </td>
            <td colspan="4" rowspan="2" class="text-center" style="border: 1px solid black;padding-top:10px; font-size:18px;">
                <b>@if(isset($data->tipekirim->nm_tipe_kirim))
                    {{ $data->tipekirim->nm_tipe_kirim }}
                    @endif
                </b>
            </td>
        </tr>
        <tr style="border-left: 1px solid black;">
            <td class="text-body">No Telpn</td>
            <td colspan="2">: {{ $data->penerima_telp }}</td>
        </tr>
        <tr style="border-top: 1px solid black; border-left: 1px solid black;">
            <td class="text-body">Pengirim</td>
            <td colspan="3" class="text-body">: {{ strtoupper($data->pengirim_nm) }}</td>
            <td class="text-center" colspan="2" style="border: 1px solid black;">CARA KEMAS</td>
            <td class="text-center" colspan="2" style="border: 1px solid black;">KETARANGAN</td>
        </tr>
        <tr style="border-left: 1px solid black;" height="50px">
            <td class="text-body">Alamat</td>
            <td colspan="3" class="text-body">: 
                {{ strtoupper($data->pengirim_alm) }} - {{ $data->pengirim_kodepos }}, 
                @if(isset($data->asal->nama_wil)){{ $data->asal->nama_wil }} 
                @endif
            </td>
            <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px">
                <b>
                    @if(isset($data->packing->nm_packing)){{ $data->packing->nm_packing }}@endif
                </b>
            </td>
            <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px">
                <b>@if(isset($data->info_kirim)){{ $data->info_kirim }}@endif</b>
            </td>
        </tr>
        <tr style="border-left: 1px solid black;">
            <td class="text-body">No Telpn</td>
            <td colspan="3">: {{ $data->pengirim_telp }}</td>
        </tr>
        <tr >
            <td class="text-center" style="border: 1px solid black;">Collie</td>
            <td colspan="3" class="text-center" style="border: 1px solid black;">Perincian Koli</td>
            <td class="text-center" style="border: 1px solid black;">ANGKA</td>
            <td class="text-center" style="border: 1px solid black;">SATUAN</td>
            <td class="text-center" style="border: 1px solid black;">Tarif</td>
            <td class="text-center" col style="border: 1px solid black;">Total</td>
        </tr>
        <tr>
            <td width="10%" class="text-center" style="border: 1px solid black;">
                @isset($data->n_koli)
                {{$data->n_koli}}
                @endisset
            </td>
            <td colspan="3" style="border: 1px solid black;">
                @foreach($detail as $key => $value)
                ({{ $value->ket_koli }} {{ $value->keterangan }}) ,
                @endforeach
            </td>
            <td class="text-center" style="border: 1px solid black;">
                @if(isset($data->c_tarif))
                @if($data->c_tarif == 1)
                {{ $data->n_berat }}
                @elseif($data->c_tarif == 2)
                {{ $data->n_volume }}
                @elseif($data->c_tarif == 4)
                {{ $data->n_kubik }}
                @endif
                @endif
            </td>
            <td class="text-center" style="border: 1px solid black;">@if(isset($data->c_tarif))
                @if($data->c_tarif == 1)
                Kg
                @elseif($data->c_tarif == 2)
                KgV
                @elseif($data->c_tarif == 4)
                M3
                @endif
                @endif
            </td>
            <td class="text-center" style="border: 1px solid black;">
                @if (Request::segment(3) == "cetak_pdf")
                @if(isset($data->c_tarif))
                @if($data->c_tarif == 1)
                {{ $data->n_tarif_brt }}
                @elseif($data->c_tarif == 2)
                {{ $data->n_tarif_vol }}
                @elseif($data->c_tarif == 4)
                {{ $data->n_tarif_kubik }}
                @endif
                @endif
                @else
                0
                @endif
            </td>
            <td class="text-center" style="border: 1px solid black;">
                <p class="text-center">
                    @if (Request::segment(3) == "cetak_pdf")
                    @if(isset($data->c_total)){{ "Rp. ".number_format($data->c_total, 2, ',', '.') }}@endif
                    @else
                    0
                    @endif
                    
                </p>
            </td>
        </tr>
    </table>
    <br>
    <table class="table2">
        <tr>
            <td width="25%">
                <img src="data:image/png;base64,{!! $qrcode !!}">
            </td>
            <td width="25%">
                <center>
                    Penerima, <br>
                    <img src="data:image/png;base64,{!! $penerima !!}"  height="50px"> <br>
                    {{ $data->penerima_nm }}
                </center>
            </td>
            <td width="25%">
                <center>
                    Pengirim, <br>
                    <img src="data:image/png;base64,{!! $pengirim !!}"  height="50px"> <br>
                    {{ $data->pengirim_nm }}
                </center>
            </td>
            <td width="25%">
                <center>
                    {{ $perusahaan->nm_perush }}, <br>
                    <img src="data:image/png;base64,{!! $admin !!}"  height="50px"> <br>
                    {{ Auth::user()->nm_user }}
                </center>
            </td>
        </tr>
    </table>
    <br>
    <table class="table2">
        <tr>
            <td style="border-top: 1px solid black;border-left: 1px solid black;">Customer Service</td>
            <td style="border-top: 1px solid black;">:</td>
            <td  style="border-top: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->nm_cs)){{$perusahaan->nm_cs}}@endif</td>
            <td rowspan=2>
                <p style="margin-left : 10px; font-size:8.5pt">
                    @if (isset($data->is_asuransi))
                    * Barang muatan yang dimuat pada halaman ini, <b>DIASURANSIKAN</b></p>
                    @else
                    * Barang muatan yang dimuat pada halaman ini, <b>TIDAK DIASURANSIKAN</b> dan <b>ISI TIDAK DIPERIKSA</b>
                </p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black;border-left: 1px solid black;">Contact Person</td>
            <td style="border-bottom: 1px solid black;">:</td>
            <td style="border-bottom: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->telp_cs)){{$perusahaan->telp_cs}}@else - @endif</td>
        </tr>
    </table>
    <hr>
    <table class="table2">
        <tr>
            <td> Putih : Pengirim Lunas </td>
            <td> Merah : Arsip </td>
            <td> Kuning : Pengirim </td>
            <td> Hijau : Penerima </td>
        </tr>
    </table>
</body>
</html>
