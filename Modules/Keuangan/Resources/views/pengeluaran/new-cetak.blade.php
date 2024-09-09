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
    .atas{
        font-size: 16px;
    }
    
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">

    <div class="container" style="margin-top:20px">
        <table width="100%">
            <tr>
                <td width="65%"><b>{{ strtoupper($perusahaan->nm_perush) }}</b></td>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $data->kode_pengeluaran }}</td>
            </tr>
            <tr>
                <td><b>Surabaya Utama</b></td>
                <td>Tanggal</td>
                <td>:</td>
                <td>@if(isset($data->tgl_keluar)){{ daydate($data->tgl_keluar).", ".dateindo($data->tgl_keluar) }}@endif</td>
            </tr>
            <tr>
                <td colspan="4">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td>
            </tr>

        </table>

        <div>
            <p class="text-center" style="text-decoration: underline;"><b>BUKTI KAS / BANK KELUAR</b></p>
            <p class="text-center"><b>Kode Perk. Kredit : @if(isset($data->debet->nama))
                {{ strtoupper("( ".$data->debet->id_ac." )   ".$data->debet->nama) }}
                @endif</b></p>
        </div>
        <table class="atas" width="100%">
            <tr>
                <td width="15%">No. Transaksi</td>
                <td width="2%"> : </td>
                <td>{{ $data->kode_pengeluaran }}</td>
            </tr>
            <tr>
                <td>Tgl. Masuk</td>
                <td> : </td>
                <td>@if(isset($data->tgl_keluar)){{ daydate($data->tgl_keluar).", ".dateindo($data->tgl_keluar) }}@endif</td>
            </tr>
            <tr>
                <td>Akun Debet</td>
                <td> : </td>
                <td>@if(isset($data->debet->nama))
                    {{ strtoupper("( ".$data->debet->id_ac." )   ".$data->debet->nama) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Penerima</td>
                <td> : </td>
                <td>{{strtoupper($data->terima_dr)}}</td>
            </tr>
        </table>
        <br>
        
        <table width="100%" class="t">
            <thead>
                <th>No</th>
                <th width="30%">Info</th>
                <th width="10%">Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($detail as $key => $value)
                <tr>
                    <td class="t">{{ $key+1 }}</td>
                    <td class="t text-left">
                        @if(isset($value->akun->id_ac)) {{ strtoupper($value->akun->nama) }}@endif
                        <br><br>{{ $value->info }}
                    </td>
                    <td class="t">{{ $value->jumlah }}</td>
                    <td class="t">{{ number_format($value->harga, 0, ',', '.') }}</td>
                    <td class="t">{{ number_format($value->total, 0, ',', '.') }}</td>
                </tr>
                @php
                $total+=$value->total;
                @endphp
                @endforeach
                <tr>
                    <td colspan="4" class="t text-center">Total</td>
                    <td colspan="1" class="t text-center">Rp. {{ number_format($total, 0, ',', '.') }}</td>
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
            <td width="25%">Penerima</td>
        </tr>
        <tr class="text-center">
            <td width="25%" height="50px">
                @if (isset($direktur))
                <img height="50px" src="{{url('generatettd/').'/'.$direktur}}" alt="ttd">
                @else
                <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(4)"> TTD</button>
                @endif
            </td>
            <td width="25%">
                @if (isset($manager))
                <img height="50px" src="{{url('generatettd/').'/'.$manager}}" alt="ttd">
                @else
                <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(3)"> TTD</button>
                @endif
            </td>
            <td width="25%">
                @if (isset($admin))
                <img height="50px" src="{{url('generatettd/').'/'.$admin}}" alt="ttd">
                @else
                <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"> TTD</button>
                @endif
            </td>
            <td width="25%">
                @if (isset($penyetor))
                <img height="50px" src="{{url('generatettd/').'/'.$penyetor}}" alt="ttd">
                @else
                <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(2)"> TTD</button>
                @endif
            </td>
        </tr>
        <tr class="text-center">
            <td width="25%">{{ $perusahaan->nm_dir }}</td>
            <td width="25%">{{ $perusahaan->nm_keu }}</td>
            <td width="25%">@if(isset($data->user->nm_user)){{ $data->user->nm_user }}@endif</td>
            <td width="25%">@if(isset($data->terima_dr)){{ $data->terima_dr }}@endif</td>
        </tr>
    </table>
    
    <textarea id="printing-css" style="display:none;">
        @media print{
            @page
            {
                size: A4 portrait;
                /* size: landscape; */
            }
            #tombol{
                display: none !important;
            }
            #ttd{
                display: none !important;
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
            font-size: 14px;
            height : 70px;
            vertical-align: text-top;
            text-align : center;
        }
        .atas{
            font-size: 12px;
        }
    </textarea>
    <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
</div>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    function showttd(params) {
        var q = "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3))}}";
        console.log(q);
        var url = "{{url('/createdttd')}}";
        console.log(url);
        var ref = "{{Request::segment(2)}}";
        const data = {level : params, url : q, type : 'buktipendapatan', id_ref : ref};
        
        var urlParam = []
        
        for (var i in data){
            urlParam.push(encodeURI(i) + "=" + encodeURI(data[i]));
        }
        console.log(urlParam);
        location.href = "{{url('/createttd')}}?"+ urlParam.join("&");
    }
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
