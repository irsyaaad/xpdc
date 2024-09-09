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
    <title>Cetak STT KEMBALI | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
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
            line-height: 15px;
            font-size: 12px;
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
            line-height : 15px;
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
        .penutup{
            font-size: 14px;
        }
        .tr-bold{
            font-weight: bold !important;
        }
        .atas{
            line-height : 5px;
        }

        .table-detail td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }

    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td style="text-align: center;">
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
                        <label style="font-size:12px;">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>   
                </td>                
            </tr>            
        </table>
        <hr>
    </div>
    <div class="atas">
        <p class="text-center" style="font-size:15px;"><b>SURAT PENGANTAR</b></p>
    </div>
    <div class="container">
        <table>
            <tr>
                <td width="100px">No Agenda</td>
                <td width="10px"> : </td>
                <td>{{ $data->kode_stt_kembali }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td> : </td>
                <td>@if(isset($data->tgl)){{ daydate($data->tgl).", ".dateindo($data->tgl) }}@endif</td>
            </tr>
            <tr>
                <td>Status</td>
                <td> : </td>
                <td>@if(isset($data->status)){{ $data->status }}@endif</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td> : </td>
                <td>{{ $data->keterangan }}</td>
            </tr>
        </table>
        <br>
        <table class="table-detail" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Kode STT (AWB)</th>
                    <th>Tgl Order</th>
                    <th>Pengirim</th>
                    <th>Telpon Pengirim</th>
                    <th>Penerima</th>
                    <th>Alamat Tujuan</th>
                    <th>Telpon Penerima</th>
                    <th>Tipe Kiriman</th>
                    <th>Koli</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($detail as $key => $value)
                <tr>
                    <td>{{ $key+1 }} </td>
                    <td>{{ strtoupper($value->kode_stt) }}<br> ({{ strtoupper($value->no_awb) }})</td>
                    <td>{{ dateindo($value->tgl_masuk) }}</td>
                    <td>{{ strtoupper($value->pengirim_nm)}}</td>
                    <td>{{ isset($value->pengirim_telp) ? $value->pengirim_telp : '-' }}</td>
                    <td>{{ strtoupper($value->penerima_nm)}}</td>
                    <td>{{ isset($value->penerima_alm) ? $value->penerima_alm : '-' }}</td>
                    <td>{{ isset($value->penerima_telp) ? $value->penerima_telp : '-' }}</td>
                    <td>{{ isset($value->tipekirim->nm_tipe_kirim) ? $value->tipekirim->nm_tipe_kirim : '-' }}</td>
                    <td>{{ $value->n_koli }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
