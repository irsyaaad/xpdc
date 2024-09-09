<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Tangan</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeric/1.2.6/numeric.min.js"></script>
    <script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
    <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
</head>
<body>
    <div id="signatureArea" >
        <div style="height: 150px; width:150px; border: 1px solid black">
            <canvas id="signaturePad" class="ttd" width="150" height="150" ></canvas>
        </div>
    </div>
    <button id="btnSaveSignature">Simpan TTD</button>
    <button id="clear">Clear TTD</button>

    <script>
        $("#clear").click(function(e){
            const canvas = document.getElementById('signaturePad');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            reload();
        });

        var kode_invoice = "{{$data->id_invoice}}";
        console.log(kode_invoice);
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

        $("#btnSaveSignature").click(function(e){
            html2canvas([document.getElementById('signaturePad')], {
                onrendered: function (canvas) {
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    // console.log(img_data);
                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        type: "POST",
                        url: "{{ url('invoicehandling/savettd') }}",
                        dataType: "json",
                        data: {_token: token, img: img_data, id_ref : kode_invoice},
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
