@extends('template.document2')
@section('data')
@section('style')
<style>
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
            margin-left: 2cm;
            margin-right: 2cm;
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15pt;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : ptpx;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12pt;
    }

    .head{
        font-size: 18px;
        font-weight: bold;
        /* height: 50px; */
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
        margin-top: 20px;
        text-align:right
    }
    .kepada p{
        line-height: 10px;
        font-size: 12pt;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .nomor{
        margin-top: 20px;
        text-align: center;
    }
    .isipesan{
        text-align: justify;
        text-justify: inter-word;
        font-size: 12pt;
    }
    .penutup{
        font-size: 12pt;
    }

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url("laporanjamkerja") }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
                <td class="head text-center" >SURAT PERINGATAN PERTAMA (SP-1)</td>
            </tr>
            <tr>
                @php
                    $tahun = date('Y');
                    $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
                    $bln = $array_bln[date('n')];
                @endphp
                <td class="nomor">Nomor : {{$perusahaan->kode_perush}}/{{$tahun}}/{{$bln}}/{{$karyawan->id_karyawan}}</td>
            </tr>
        </table>
        <br>
        <div class="kepada">
            <p>Kepada, Yth</p>
            <p>{{$karyawan->nm_karyawan}}</p>
            <p>Di Tempat</p>
            <br>
        </div>
        <br>
        <div class="isipesan">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Dengan ini perusahaan menyampaikan Surat Peringatan Pertama (SP-1) ini untuk menindak lanjuti terkait kedisiplinan karyawan yang pernah perusahaan sampaikan sebelumnya
                kepada saudara
                {{$karyawan->nm_karyawan}}. Agar saudara merubah sikap dan bertindak secara professional lagi, dengan ini perusahaan memberikan sanksi sesuai dengan aturan yang berlaku yakni <b>Pemotongan Gaji sebesar 15% (Lima Belas Persen)</b> dari gaji pokok</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Dan apabila teguran Surat Peringatan Pertama (SP-1) ini tidak direspon dengan baik maka kami akan mengeluarkan Surat Peringatan Ketiga (SP-2) yang berarti dengan sanksi yang lebih berat yang bersifat pemotongan Gaji sebesar 30% (Tiga Puluh Persen).
                </p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Demikian Surat Peringatan Pertama (SP-1) ini kami buat agar dapat diperhatikan dan yang bersangkutan bisa merubah dan menunjukan sikap profesionalisme dalam bekerja.
            </p>
        </div>
    </div>

    <table width="100%" style="margin-top:50px" class="penutup">
        <tr>
            @php
                $tgl = date("Y-m-d");
            @endphp
            <td colspan="2" class="text-right">{{$perusahaan->kotakab}}, {{dateindo($tgl)}}</td>
        </tr>
        <tr>
            <td class="text-left">Hormat Kami,</td>
            <td class="text-right">Menyetujui,</td>
        </tr> <tr>
            <td class="text-left">HRD</td>
            <td class="text-right">Kepala Cabang,</td>
        </tr>
        <tr height="70px">
            <td class="text-left">
                @if (isset($ttd))
                    {{-- <img id="canvasImage" width="150px"/> --}}
                    <img width="100px" src="{{url('generatettd/'.$ttd->id)}}" alt="ttd">
                @else
                    @if (Request::segment(1) == "invoicehandling")
                        <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"> TTD</button>
                    @endif
                @endif
            </td>
            <td class="text-right">
                @if (isset($ttd))
                    {{-- <img id="canvasImage" width="150px"/> --}}
                    <img width="100px" src="{{url('generatettd/'.$ttd->id)}}" alt="ttd">
                @else
                    @if (Request::segment(1) == "invoicehandling")
                        <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"> TTD</button>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td class="text-left">MAYLINDA YOVA AURELIA</td>
            @if($kacab!=null)
            <td class="text-right">{{ strtoupper($kacab) }}</td>
            @else
            <td class="text-right">___________</td>
            @endif
        </tr>
    </table>
</div>

<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
            margin-left: 2cm;
            margin-right: 2cm;
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15pt;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : ptpx;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12pt;
    }

    .head{
        font-size: 18px;
        font-weight: bold;
        /* height: 50px; */
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
        margin-top: 20px;
        text-align:right
    }
    .kepada p{
        line-height: 10px;
        font-size: 12pt;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .nomor{
        margin-top: 20px;
        text-align: center;
    }
    .isipesan{
        text-align: justify;
        text-justify: inter-word;
        font-size: 12pt;
    }
    .penutup{
        font-size: 12pt;
    }

</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
<script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
<script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
<script>
    function showttd(params) {
        var q = "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3))}}";
        console.log(q);
        var url = "{{url('/createdttd')}}";
        console.log(url);
        var ref = "{{Request::segment(2)}}";
        const data = {level : params, url : q, type : 'invoicehandling', id_ref : ref};

        var urlParam = []

        for (var i in data){
            urlParam.push(encodeURI(i) + "=" + encodeURI(data[i]));
        }
        console.log(urlParam);
        location.href = "{{url('/createttd')}}?"+ urlParam.join("&");
    }
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    @isset($ttd)
    var ttd = "{{$ttd->ttd_admin}}";
    document.getElementById("canvasImage").src="data:image/gif;base64,"+ttd;
    @endisset

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
