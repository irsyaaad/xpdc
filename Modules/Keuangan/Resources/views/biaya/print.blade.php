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
    
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
            <tr><td class="heading">{{ $perusahaan->alamat }},{{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</td></tr>
            <tr><td class="heading">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td></tr>
        </table>
    </div>
    <hr class="hrhead">
    <div class="container" style="margin-top:20px">
        <table width="100%" class="datanya">
            <tr>
                <td>No. DM</td>
                <td> : </td>
                <td>{{$dm->kode_dm}}</td>
            </tr>
            @if ($dm->is_vendor)
            <tr>
                <td>Nama Vendor</td>
                <td> : </td>
                <td>@if (isset($dm->vendor->nm_ven))
                    {{$dm->vendor->nm_ven}}
                    @else
                    @isset($dm->perush_tujuan->nm_perush)
                    {{$dm->perush_tujuan->nm_perush}}
                    @endisset
                    @endif</td>
                </tr>
                @else
                <tr>
                    <td>Nama Kapal</td>
                    <td> : </td>
                    <td>@isset($dm->kapal->nm_kapal)
                        {{$dm->kapal->nm_kapal}}
                        @endisset</td>
                </tr>
                <tr>
                    <td>Sopir</td>
                    <td> : </td>
                    <td>
                        @isset($dm->sopir->nm_sopir)
                        {{$dm->sopir->nm_sopir}}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td>No Plat</td>
                    <td> : </td>
                    <td>
                        @isset($dm->armada->no_plat)
                        {{$dm->armada->no_plat}}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <td>Berangkat / Tujuan</td>
                    <td> : </td>
                    <td>
                        {{$dm->nm_dari}} / {{$dm->nm_tuju}}                   
                    </td>
                </tr>
                @endif
                <tr>
                    <td>Tgl Berangkat / Est Tiba</td>
                    <td> : </td>
                    <td>
                        ({{dateindo($dm->tgl_berangkat)}}) / ({{dateindo($dm->tgl_sampai)}})                    
                    </td>
                </tr>
                @if ($dm->id_layanan == 2)
                <tr>
                    <td>No Container / No Seal</td>
                    <td> : </td>
                    <td>
                        {{$dm->no_container}} / {{$dm->no_seal}}               
                    </td>
                </tr>
                @endif
            </table>
            <br>
            <table width="100%" class="t">
                <thead>
                    <th id="n">No</th>
                    <th id="n">Biaya</th>
                    <th id="n">Kelompok</th>
                    <th id="n">Keterangan</th>
                    <th id="n">Nominal</th>
                    <th id="n">Dibayar</th>
                    <th id="n">Kurang</th>
                    <th id="n">Status</th>
                </thead>
                <tbody>
                    @php
                    $t_nominal = 0;
                    $t_bayar = 0;
                    $t_kurang = 0;
                    $total = 0;
                    @endphp
                    @foreach($biaya as $key => $value)
                    @php
                    $id = $value->id_dm.$value->last_id;
                    @endphp
                    <tr>
                        <td class="t">{{ ($key+1) }}</td>
                        <td class="t">@if(isset($value->group->nm_biaya_grup)){{strtoupper($value->group->nm_biaya_grup)}}@endif</td>
                        <td class="t">
                            @if(isset($value->group->klp))
                            @if($value->group->klp==1)
                            HPP
                            @else
                            Operasional
                            @endif
                            @endif
                        </td>
                        <td class="t">
                            {{ $value->keterangan }}
                        </td>
                        <td class="t">
                            @php 
                            $t_nominal += $value->nominal;
                            @endphp
                            {{ strtoupper(number_format($value->nominal, 0, ',', '.')) }}
                            <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $value->nominal }}" />
                        </td>
                        <td class="t">
                            @php 
                            $t_bayar += $value->n_bayar;
                            @endphp
                            {{ torupiah($value->n_bayar) }}
                        </td>
                        <td class="t">
                            @php 
                            $n_kurang = $value->nominal-$value->n_bayar;
                            $t_kurang += $n_kurang;
                            @endphp
                            {{ torupiah($n_kurang) }}
                        </td>
                        <td class="t">
                        @if($n_kurang=="0")
                            Lunas
                            @else
                            Belum Lunas
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right t">Perhitungan : </td>
                        <td class="t">
                            {{ torupiah($t_nominal) }}
                        </td>
                        <td class="t">
                            {{ torupiah($t_bayar) }}
                        </td>
                        <td class="t">
                            {{ torupiah($t_kurang) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <table width="100%">
            <tr class="text-center">
                <td width="25%">Direktur</td>
                <td width="25%">Manager Keuangan</td>
                <td width="25%">Kasir</td>
                <td width="25%">Penyetor</td>
            </tr>
            <br><br>
            <tr class="text-center">
                <td width="25%"><br><br>{{ $perusahaan->nm_dir }}</td>
                <td width="25%"><br><br>{{ $perusahaan->nm_keu }}</td>
                <td width="25%"><br><br>{{ Auth::user()->nm_user }}</td>
                <td width="25%"><br><br>
                    @if ($dm->is_vendor)
                    @if (isset($dm->vendor->nm_ven))
                    {{$dm->vendor->nm_ven}}
                    @else
                    @isset($dm->perush_tujuan->nm_perush)
                    {{$dm->perush_tujuan->nm_perush}}
                    @endisset
                    @endif              
                    @else
                    @isset($dm->perush_tujuan->nm_perush)
                    {{$dm->perush_tujuan->nm_perush}}
                    @endisset
                    @endif
                </td>
            </tr>
        </table>       
        <br>
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
            font-size : 10px;
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
            font-size: 12px;
            height : 70px;
            vertical-align: text-top;
            text-align : center;
        }
    </textarea>
    <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
</div>
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