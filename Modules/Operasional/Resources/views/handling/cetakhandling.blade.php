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
        font-size : 12px;
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
    }
    
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container" >
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
            <tr><td class="heading">{{ $perusahaan->alamat }},{{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</td></tr>
            <tr><td class="heading">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td></tr>
        </table>
    </div>
    <hr class="hrhead">
    <div class="container" style="margin-top:20px">
        <table width="100%" class="datanya">
            <tr>
                <td width="20%">No. Handling</td>
                <td width="2%">:</td>
                <td>{{ strtoupper($data->kode_handling) }}</td>
            </tr>
            <tr>
                <td>Nama Sopir / Armada</td>
                <td>:</td>
                <td>@if($data->sopir->nm_sopir){{ strtoupper($data->sopir->nm_sopir) }}@endif / @if($data->armada->nm_armada){{ strtoupper($data->armada->nm_armada) }}@endif</td>
            </tr>
            <tr>
                <td>Berangkat (Tgl / Jam)</td>
                <td>:</td>
                <td>@if(isset($data->tgl_berangkat)){{ daydate($data->tgl_berangkat).", ".dateindo($data->tgl_berangkat) }}@endif / @if($data->waktu_berangkat)
                    {{ date("H:i:s", strtotime($data->waktu_berangkat))." WIB" }}
                    @endif</td>
                </tr>
                <tr>
                    <td>Selesai (Tgl / Jam)</td>
                    <td>:</td>
                    <td>@if(isset($data->tgl_selesai)){{ daydate($data->tgl_selesai).", ".dateindo($data->tgl_selesai) }}@endif / @if($data->waktu_selesai)
                        {{ date("H:i:s", strtotime($data->waktu_selesai))." WIB" }}
                        @endif</td>
                    </tr>
                    <tr>
                        <td>Asal / Tujuan</td>
                        <td>:</td>
                        <td>@if($data->asal->nama_wil){{ strtoupper($data->asal->nama_wil) }}@endif / @if($data->region_tuju){{ strtoupper($data->tujuan->nama_wil) }}@endif</td>
                    </tr>
                    <tr>
                        <td>Penanggung Jawab</td>
                        <td>:</td>
                        <td>@if($data->user->nm_user){{ strtoupper($data->user->nm_user) }}@endif</td>
                    </tr>
                    <tr>
                        <td>Ketarangan</td>
                        <td>:</td>
                        <td>{{ $data->keterangan }}</td>
                    </tr>
                </table>             
                <hr style="border-top: 1px solid red;">
                <p>Berikut Data STT pada Handling : </p>
                <table width="100%" class="t">
                    <thead>
                        <tr>
                            <th rowspan="2">No. </th>
                            <th rowspan="2">No. STT</th>
                            <th rowspan="2">No. DM</th>
                            <th rowspan="2">Penerima</th>
                            <th rowspan="2">Alamat</th>
                            <th colspan="4"  class="text-center">Jumlah</th>
                        </tr>
                        <tr>
                            <th>Koli</th>
                            <th>Kg</th>
                            <th>Kgv</th>
                            <th>M3</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail as $key => $value)
                        <tr>
                            <td class="t">{{ $key+1 }}</td>
                            <td class="t">{{ strtoupper($value->kode_stt) }}<br>{{ dateindo($value->tgl_masuk) }}</td>
                            <td class="t">{{ strtoupper($value->kode_dm) }}</td>
                            <td class="t">{{ strtoupper($value->penerima_nm)}}<br>{{ $value->penerima_telp }}</td>
                            <td class="t">
                                <label class="txt-style">{{ $value->penerima_alm}}<br>{{ $value->nama_wil." - ".$value->prov." ".$value->kab }}</label>
                            </td>
                            <td class="t">
                                {{ $value->n_koli }}
                            </td>
                            <td class="t">
                                {{ $value->n_berat }}
                            </td>
                            <td class="t">
                                {{ $value->n_volume }}
                            </td>
                            <td class="t">
                                {{ $value->n_kubik }}
                            </td>
                        </tr>
                        @endforeach
                        @if($detail == null)
                        <tr>
                            <td colspan="9" class="text-center">
                                <b>Data Kosong</b>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <br>
                <table width="100%">
                    <tr class="penutup">
                        <td width="50%">Sopir</td>
                        <td class="text-right">Penanggung Jawab</td>
                    </tr>
                    <tr>
                        <td >@if($data->sopir->nm_sopir){{ strtoupper($data->sopir->nm_sopir) }}@endif</td>
                        <td class="text-right">@if($data->user->nm_user){{ strtoupper($data->user->nm_user) }}@endif</td>
                    </tr>
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
                font-size : 12px;
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