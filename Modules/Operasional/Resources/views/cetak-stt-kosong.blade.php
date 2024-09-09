<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
    <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Cetak STT Kosong</title>
    <style>
        @media print{
            @page
            {
                /* size: 5.5in 9.4in ; */
                /* size: portrait; */
                size: landscape;
                margin-right: 1cm;
                /* margin-top: -0.1cm; */
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
            line-height: 16px;
            font-size: 15px;
            /* font-weight: bold; */
            color: #000;
        }
        td{
            padding: 2px;
        }
        .heading {
            text-align: center;
            padding-bottom: 3px;
        }
        .almt {
            vertical-align: text-top;
        }
    </style>
</head>
<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:20px">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
</div>
    <div class="container" style=" margin-top:16px;">
        <div class="row">
            <div class="col-3">
                <center style="margin-top: 15px">
                    @php

                        if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                        }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 64px">
                </center>
            </div>
            <div class="col-9">
                <div class="heading" style="font-size: 16px; margin-top: 15px"><strong>{{ strtoupper($perusahaan->nm_perush) }}</strong></div>
                <div class="heading">
                    @php
                        echo ($perusahaan->header);
                    @endphp
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:10px">
        <table width="100%" >
            <tr>
                <td colspan="2" width="35%"><b>No AWB</b></td>
                <td></td>
                <td width="25%" colspan="2" style="border: 1px solid black;">TANGGAL IN</td>
                <td width="25%" colspan="2" style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td colspan="2"><b>SURAT TANDA TERIMA TITIPAN No.</b></td>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">TANGGAL OUT</td>
                <td colspan="2" style="border: 1px solid black;"></td>
            </tr>
            <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                <td width="10%" >Kepada</td>
                <td colspan="2">: </td>
                <td colspan="4" class="text-center" style="border: 1px solid black;">NAMA BARANG</td>
            </tr>
            <tr style="border-left: 1px solid black;" height="50px">
                <td class="almt">Alamat</td>
                <td colspan="2" class="almt">: </td>
                <td colspan="4" rowspan="2" class="text-center" style="border: 1px solid black;padding-top:10px; font-size:20px;"><b></b></td>
            </tr>
            <tr style="border-left: 1px solid black;">
                <td >No Telpn</td>
                <td colspan="2">: </td>
            </tr>
            <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                <td width="10%">Pengirim</td>
                <td colspan="2">: </td>
                <td class="text-center" colspan="2" style="border: 1px solid black;">CARA KEMAS</td>
                <td class="text-center" colspan="2" style="border: 1px solid black;">KETARANGAN</td>
            </tr>
            <tr style="border-left: 1px solid black;" height="50px">
                <td class="almt">Alamat</td>
                <td colspan="2" class="almt">: </td>
                <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b></b></td>
                <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b></b></td>
            </tr>
            <tr style="border-left: 1px solid black;">
                <td >No Telpn</td>
                <td colspan="2">: </td>
            </tr>
            <tr height="30px">
                <td width="10%" class="text-center" style="border: 1px solid black;">Collie</td>
                <td colspan="2" class="text-center" style="border: 1px solid black;">Perincian Koli</td>
                <td class="text-center" style="border: 1px solid black;">ANGKA</td>
                <td class="text-center" style="border: 1px solid black;">SATUAN</td>
                <td class="text-center" style="border: 1px solid black;">Tarif</td>
                <td class="text-center" style="border: 1px solid black;">Total</td>
            </tr>
            <tr height="50px">
                <td width="10%" class="text-center" style="border: 1px solid black;">
                   
                </td>
                <td colspan="2" style="border: 1px solid black;">
                    
                </td>
                <td class="text-center" style="border: 1px solid black;">
                
                </td>
                <td class="text-center" style="border: 1px solid black;"></td>
                <td class="text-center" style="border: 1px solid black;"> </td>
                <td class="text-center" style="border: 1px solid black;"></td>
            </tr>
        </table>
    </div>
    <br>
    <div class=container style="height:18px">
        <div class="row">
            <div class="col">
            <canvas id="qr-code" style="width:50%"></canvas>
            </div>
            <div class="col">
                Penerima
            </div>
            <div class="col">
                Pengirim
            </div>
            <div class="col">
                {{ $perusahaan->nm_perush }}
            </div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col">
            
            </div>
            <div class="col">
                @if (isset($penerima))
                <img src="data:image/png;base64,{!! $penerima !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(3)"><i class="fa fa-money"></i>  TTD</button>
                @endif

            </div>
            <div class="col">
                @if (isset($pengirim))
                <img src="data:image/png;base64,{!! $pengirim !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(2)"><i class="fa fa-money"></i>  TTD</button>
                @endif
            </div>
            <div class="col">
                @if (isset($admin))
                <img src="data:image/png;base64,{!! $admin !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"><i class="fa fa-money"></i>  TTD</button>
                @endif
            </div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col">

            </div>
            <div class="col">
                
            </div>
            <div class="col">
                
            </div>
            <div class="col" style="color: transparent">
                @isset($data->user->nm_user)
                    {{$data->user->nm_user}}
                @endisset
                <br><br>
            </div>
        </div>
    </div>
    <br><br>
    <table>
        <tr>
            <td width="15%" style="border-top: 1px solid black;border-left: 1px solid black;">Customer Service</td>
            <td width="5%" style="border-top: 1px solid black;">:</td>
            <td width="10%" style="border-top: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->nm_cs)){{$perusahaan->nm_cs}}@endif</td>
            <td rowspan=2><p style="margin-left : 10px">
                @if (isset($data->is_asuransi))
                    * Barang muatan yang dimuat pada halaman ini, <b>DIASURANSIKAN</b></p>
                @else
                    * Barang muatan yang dimuat pada halaman ini, <b>TIDAK DIASURANSIKAN</b> dan <b>ISI TIDAK DIPERIKSA</b></p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black;border-left: 1px solid black;">Contact Person</td>
            <td style="border-bottom: 1px solid black;">:</td>
            <td style="border-bottom: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->telp_cs)){{$perusahaan->telp_cs}}@else - @endif</td>
        </tr>
    </table>
    <hr style="border: 1px solid black; margin-top:-1px;">
    <div class="row" style="margin-top: -14px">
        <div class="col">Putih : Pengirim Lunas</div>
        <div class="col">Merah : Arsip</div>
        <div class="col">Kuning : Pengirim</div>
        <div class="col">Hijau : Penerima</div>
    </div>
    <div style="page-break-before: always;"></div>
    <div class="container" style=" margin-top:16px;">
        <div class="row">
            <div class="col-3">
                <center style="margin-top: 15px">
                    @php

                        if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                        }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 64px">
                </center>
            </div>
            <div class="col-9">
                <div class="heading" style="font-size: 16px; margin-top: 15px"><strong>{{ strtoupper($perusahaan->nm_perush) }}</strong></div>
                <div class="heading">
                    @php
                        echo ($perusahaan->header);
                    @endphp
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:10px">
        <table width="100%" >
            <tr>
                <td colspan="2" width="35%"><b>No AWB</b></td>
                <td></td>
                <td width="25%" colspan="2" style="border: 1px solid black;">TANGGAL IN</td>
                <td width="25%" colspan="2" style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td colspan="2"><b>SURAT TANDA TERIMA TITIPAN No.</b></td>
                <td></td>
                <td colspan="2" style="border: 1px solid black;">TANGGAL OUT</td>
                <td colspan="2" style="border: 1px solid black;"></td>
            </tr>
            <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                <td width="10%" >Kepada</td>
                <td colspan="2">: </td>
                <td colspan="4" class="text-center" style="border: 1px solid black;">NAMA BARANG</td>
            </tr>
            <tr style="border-left: 1px solid black;" height="50px">
                <td class="almt">Alamat</td>
                <td colspan="2" class="almt">: </td>
                <td colspan="4" rowspan="2" class="text-center" style="border: 1px solid black;padding-top:10px; font-size:20px;"><b></b></td>
            </tr>
            <tr style="border-left: 1px solid black;">
                <td >No Telpn</td>
                <td colspan="2">: </td>
            </tr>
            <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                <td width="10%">Pengirim</td>
                <td colspan="2">: </td>
                <td class="text-center" colspan="2" style="border: 1px solid black;">CARA KEMAS</td>
                <td class="text-center" colspan="2" style="border: 1px solid black;">KETARANGAN</td>
            </tr>
            <tr style="border-left: 1px solid black;" height="50px">
                <td class="almt">Alamat</td>
                <td colspan="2" class="almt">: </td>
                <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b></b></td>
                <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b></b></td>
            </tr>
            <tr style="border-left: 1px solid black;">
                <td >No Telpn</td>
                <td colspan="2">: </td>
            </tr>
            <tr height="30px">
                <td width="10%" class="text-center" style="border: 1px solid black;">Collie</td>
                <td colspan="2" class="text-center" style="border: 1px solid black;">Perincian Koli</td>
                <td class="text-center" style="border: 1px solid black;">ANGKA</td>
                <td class="text-center" style="border: 1px solid black;">SATUAN</td>
                <td class="text-center" style="border: 1px solid black;">Tarif</td>
                <td class="text-center" style="border: 1px solid black;">Total</td>
            </tr>
            <tr height="50px">
                <td width="10%" class="text-center" style="border: 1px solid black;">
                   
                </td>
                <td colspan="2" style="border: 1px solid black;">
                    
                </td>
                <td class="text-center" style="border: 1px solid black;">
                
                </td>
                <td class="text-center" style="border: 1px solid black;"></td>
                <td class="text-center" style="border: 1px solid black;"> </td>
                <td class="text-center" style="border: 1px solid black;"></td>
            </tr>
        </table>
    </div>
    <br>
    <div class=container style="height:18px">
        <div class="row">
            <div class="col">
            <canvas id="qr-code" style="width:50%"></canvas>
            </div>
            <div class="col">
                Penerima
            </div>
            <div class="col">
                Pengirim
            </div>
            <div class="col">
                {{ $perusahaan->nm_perush }}
            </div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col">
            
            </div>
            <div class="col">
                @if (isset($penerima))
                <img src="data:image/png;base64,{!! $penerima !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(3)"><i class="fa fa-money"></i>  TTD</button>
                @endif

            </div>
            <div class="col">
                @if (isset($pengirim))
                <img src="data:image/png;base64,{!! $pengirim !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(2)"><i class="fa fa-money"></i>  TTD</button>
                @endif
            </div>
            <div class="col">
                @if (isset($admin))
                <img src="data:image/png;base64,{!! $admin !!}"  height="50px"> <br>
                @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"><i class="fa fa-money"></i>  TTD</button>
                @endif
            </div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col">

            </div>
            <div class="col">
                
            </div>
            <div class="col">
                
            </div>
            <div class="col" style="color: transparent">
                @isset($data->user->nm_user)
                    {{$data->user->nm_user}}
                @endisset
                <br><br>
            </div>
        </div>
    </div>
    <br><br>
    <table>
        <tr>
            <td width="15%" style="border-top: 1px solid black;border-left: 1px solid black;">Customer Service</td>
            <td width="5%" style="border-top: 1px solid black;">:</td>
            <td width="10%" style="border-top: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->nm_cs)){{$perusahaan->nm_cs}}@endif</td>
            <td rowspan=2><p style="margin-left : 10px">
                @if (isset($data->is_asuransi))
                    * Barang muatan yang dimuat pada halaman ini, <b>DIASURANSIKAN</b></p>
                @else
                    * Barang muatan yang dimuat pada halaman ini, <b>TIDAK DIASURANSIKAN</b> dan <b>ISI TIDAK DIPERIKSA</b></p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black;border-left: 1px solid black;">Contact Person</td>
            <td style="border-bottom: 1px solid black;">:</td>
            <td style="border-bottom: 1px solid black;border-right: 1px solid black;">@if(isset($perusahaan->telp_cs)){{$perusahaan->telp_cs}}@else - @endif</td>
        </tr>
    </table>
    <hr style="border: 1px solid black; margin-top:-1px;">
    <div class="row" style="margin-top: -14px">
        <div class="col">Putih : Pengirim Lunas</div>
        <div class="col">Merah : Arsip</div>
        <div class="col">Kuning : Pengirim</div>
        <div class="col">Hijau : Penerima</div>
    </div>
    <div class="modal fade" id="modal-asuransi" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body text-center">
              <h5><b id="text-auth"> Masukkan TTD </b></h5>
            </div>
            <div class="modal-body" style="margin-top: -7%">
                <center>
                    <div id="signatureArea" >
                        <div style="height: 100px; width:300px; border: 1px solid black">
                            <canvas id="signaturePad" class="ttd" width="300" height="100" ></canvas>
                        </div>
                    </div>
                </center>
                <input type="hidden" name="level" id="level">
                <input type="hidden" name="url" id="url">
               <div class="text-right">
                <button type="button" id="btnSaveSignature" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Submit TTD</button>
                <button type="button" id="clear" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Clear</button>
               </div>
            </div>
          </div>
        </div>
      </div>
</body>
</html>
<script>
    $("#cetak").click(function(){
        // $("#tombol").hide();
        // $("#ttd").hide();
        window.print();
    });

    function showttd(params) {
        var q = "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3))}}";
        console.log(q);
        console.log("masuk ",params);
        $("#modal-asuransi").modal('show');
        $("#level").val(params);
        $("url").val(q);
    }
    var qr;
    (function() {
            var id_stt = "http://lsj-express.id/cekresi/<?php echo $id; ?>";
            console.log(id_stt);
            qr = new QRious({
            element: document.getElementById('qr-code'),
            size: 100,
            value: id_stt
        });
    })();

    $("#clear").click(function(e){
            const canvas = document.getElementById('signaturePad');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            reload();
        });

        var id_stt = "{{$data->id_stt}}";
        console.log(id_stt);
        $(document).ready(function() {
            $('#signatureArea').signaturePad({
                drawOnly: true,
                defaultAction: 'drawIt',
                validateFields: false,
                lineWidth: 0,
                output: null,
                sigNav: null,
                name: null,
                typed: null,
                penColour:'#000000',
                clear: 'input[type=reset]',
                typeIt: null,
                drawIt: null,
                typeItDesc: null,
                drawItDesc: null
            });
        });

        $("#btnSaveSignature").click(function(e){
            html2canvas([document.getElementById('signaturePad')], {
                onrendered: function (canvas) {
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    // console.log(img_data);
                    var token = "{{ csrf_token() }}";
                    var level = $("#level").val();
                    var url = $("#url").val();
                    $.ajax({
                        type: "POST",
                        url: "{{ url('savettd') }}",
                        dataType: "json",
                        data: {_token: token, img: img_data, id_ref : id_stt, url :  url, level : level, type : 'stt'},
                        beforeSend: function(e) {
                            if(e && e.overrideMimeType) {
                                e.overrideMimeType("application/json;charset=UTF-8");
                            }
                        },
                        success: function(response){
                            console.log(response);
                            // location.href = response.url;
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(thrownError);
                        }
                    });
                    // document.getElementById("canvasImage").src="data:image/gif;base64,"+img_data;
                    $("#modal-asuransi").modal('hide');
                    location.reload();
                }
            });

        });
        window.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
</script>
