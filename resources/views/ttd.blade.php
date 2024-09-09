<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Tangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeric/1.2.6/numeric.min.js"></script>
    <script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
    <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
</head>
<body>
    <p>Harap Isi Tanda tangan didalam Kotak dibawah ini :</p>
    <div id="signatureArea" >
        <div style="height: 110px; width:310px; border: 1px solid black">
            <canvas id="signaturePad" class="ttd" width="300" height="100" ></canvas>
        </div>
    </div>
    <button class="btn btn-success" id="btnSaveSignature">Simpan TTD</button>
    <button class="btn btn-danger" id="clear">Clear</button>

    <script>
        $("#clear").click(function(e){
            const canvas = document.getElementById('signaturePad');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            reload();
        });

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
                clear: 'input[type=reset]',
                typeIt: null,
                drawIt: null,
                typeItDesc: null,
                drawItDesc: null
            });
        });

        var id_ref = "{{$data->id_ref}}";
        var type = "{{$data->type}}";
        var url = "{{$data->url}}";
        var level = "{{$data->level}}";
        console.log(id_ref);

        $("#btnSaveSignature").click(function(e){
            html2canvas([document.getElementById('signaturePad')], {
                onrendered: function (canvas) {
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    // console.log(img_data);
                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        type: "POST",
                        url: "{{ url('savettd') }}",
                        dataType: "json",
                        data: {_token: token, img: img_data, id_ref : id_ref, type:type, url:url, level:level},
                        beforeSend: function(e) {
                            if(e && e.overrideMimeType) {
                                e.overrideMimeType("application/json;charset=UTF-8");
                            }
                        },
                        success: function(response){
                            console.log(response);
                            location.href = response.url;
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(thrownError);
                        }
                    });
                    // document.getElementById("canvasImage").src="data:image/gif;base64,"+img_data;
                }
            });

        });
    </script>
    {{-- <p>Signature Image</p>
        <img id="canvasImage" /> --}}
    </body>
    </html>
