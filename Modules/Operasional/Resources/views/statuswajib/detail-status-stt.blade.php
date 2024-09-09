@extends('template.document')
@section('style')
    <style>
        .text-bold {
            font-weight: bold;
        }
    </style>
@endsection
@section('data')
    <div class="row">
        <div class="form-group col-md-12 text-right" style="margin-top: 25px">
            <a href="{{ $url_back }}" class="btn btn-md btn-warning"><i class="fa fa-arrow-left"> Kembali</i></a>
            <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
        </div>

        <div class="col-md-12" id="print-js">
            <center>
                <b style="font-weight:bold">Rekap Detail Status Stt</b><br>
            </center>
            <table class="table-sm table-bordered mt-2 table">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Tgl Update</th>
                        <th>User Update</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr
                            style="color : {{ in_array($value->id_status, array_merge($status_pengirim, $status_penerima)) ? 'green' : '' }}">
                            <td>{{ $key + 1 }}</td>
                            <td>({{ $value->id_status }}) {{ $value->keterangan }}</td>
                            <td>{{ date('d-m-Y', strtotime($value->tgl_update)) }}</td>
                            <td>{{ $value->nm_user }}</td>
                            @php
                                if (in_array($value->id_status, $status_pengirim)) {
                                    $status = 'Wajib (Sebagai Pengirim)';
                                } elseif (in_array($value->id_status, $status_penerima)) {
                                    $status = 'Wajib (Sebagai Penerima)';
                                } else {
                                    $status = 'Tidak Wajib';
                                }
                            @endphp
                            <td>{{ $status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 portrait;
            color:black;
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 20px;
        font-size: 15px;
    }
    table {
        margin: auto;
        font-family: "Arial";
        font-size: 12px;
        border-collapse: collapse;
    }
    table th, 
    table td {
        border-top: 1px solid black;
        border-bottom: 1px solid  black;
        border-left: 1px solid  black;
        padding: 5px 14px;
    }
    table th, 
    table td:last-child {
        border-right: 1px solid  black;
    }
    table td:first-child {
        border-top: 1px solid  black;
    }
    
    table thead th {
        color: black;
    }

    .text-bold{
        font-weight: bold;
    }
    
    table tbody td {
        color: black;
    }
</textarea>
    <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
@endsection

@section('script')
    <script>
        @if (isset($data['dr_tgl']))
            $("#dr_tgl").val("{{ $data['dr_tgl'] }}");
        @endif

        @if (isset($data['sp_tgl']))
            $("#sp_tgl").val("{{ $data['sp_tgl'] }}");
        @endif

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
