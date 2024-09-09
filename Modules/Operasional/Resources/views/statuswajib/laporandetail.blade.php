@extends('template.document')
@section('style')
    <style>
        .text-bold {
            font-weight: bold;
        }
    </style>
@endsection
@section('data')
    @php
        $url =
            url('laporanstatuswajib') .
            '/' .
            $perush->id_perush .
            '/detailpengirim' .
            '?id_perush=' .
            $perush->id_perush .
            '&dr_tgl=' .
            $dr_tgl .
            '&sp_tgl=' .
            $sp_tgl;
        $back =
            url('laporanstatuswajib') .
            '?id_perush=' .
            $perush->id_perush .
            '&dr_tgl=' .
            $dr_tgl .
            '&sp_tgl=' .
            $sp_tgl;
    @endphp
    <form method="GET" action="{{ $url }}" enctype="multipart/form-data" id="form-select">
        @csrf
        <input type="hidden" name="_method" value="GET">
        <div class="row">
            <div class="form-group col-md-3">
                <label for="level">
                    <b>Dari Tanggal</b>
                </label>
                <input type="date" id="dr_tgl" name="dr_tgl" value="{{ $dr_tgl }}" class="form-control" />
            </div>

            <div class="form-group col-md-3">
                <label for="level">
                    <b>Sampai Tanggal</b>
                </label>
                <input type="date" id="sp_tgl" name="sp_tgl" value="{{ $sp_tgl }}" class="form-control" />
            </div>

            <div class="form-group col-md-3" style="margin-top: 25px">
                <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-filter"> Filter</i></button>
                <a href="{{ $back }}" class="btn btn-md btn-warning"><i class="fa fa-arrow-left"> Kembali</i></a>
                <a href="javascript:printDiv('print-js');" class="btn btn-md btn-info"><i class="fa fa-print"> Cetak</i></a>
            </div>

            <div class="col-md-12" id="print-js">
                <center>
                    <b style="font-weight:bold">Rekap Detail Status Wajib</b><br>
                    <b style="font-weight:bold">Periode : {{ $dr_tgl }} s/d {{ $sp_tgl }}</b>
                </center>
                <table class="table-responsive table-bordered mt-2 table">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">STT</th>
                            <th rowspan="2">Tgl Masuk</th>
                            <th rowspan="2">Pengirim</th>
                            <th rowspan="2">Alm. Tujuan</th>
                            <th rowspan="2">Jumlah Status</th>
                            <th colspan="2" class="text-center">Pengirim</th>
                            <th colspan="2" class="text-center">Penerima</th>
                        </tr>
                        <tr>
                            <th>Wajib</th>
                            <th>Stat Ok</th>

                            <th>Wajib</th>
                            <th>Stat Ok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: rgba(163, 163, 163, 0.789); color : #ffff">
                            <td class="text-bold" colspan="11">1. SEBAGAI PENGIRIM</td>
                        </tr>
                        @php
                            $total_status = 0;
                            $total_status_wajib_pengirim = 0;
                            $total_status_wajib_penerima = 0;
                        @endphp
                        @foreach ($data as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a
                                        href="{{ url('laporanstatuswajib') . '/' . $value->id_stt . '/detailstatusstt?url_back=' . url()->current() . "&id_perush=29&dr_tgl={$dr_tgl}&sp_tgl={$sp_tgl}" }}">{{ $value->kode_stt }}</a>
                                </td>
                                <td>{{ date('d-m-Y', strtotime($value->tgl_masuk)) }}</td>
                                <td>{{ $value->pengirim_nm }}</td>
                                <td>
                                    {{ $value->penerima_alm }}
                                </td>
                                <td>{{ $value->total }}</td>
                                @php
                                    $total_status += $value->total;
                                @endphp
                                <td>{{ $value->status_wajib_pengirim }}</td>
                                <td>{{ $value->status_ok_pengirim }}</td>
                                @php
                                    $total_status_wajib_pengirim += $value->status_ok_pengirim;
                                @endphp
                                <td>{{ $value->status_wajib_penerima }}</td>
                                <td>{{ $value->status_ok_penerima }}</td>
                                @php
                                    $total_status_wajib_penerima += $value->status_ok_penerima;
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td colspan="4" class="text-right">Total</td>
                            <td>{{ $total_status }}</td>
                            <td></td>
                            <td>{{ $total_status_wajib_pengirim }}</td>
                            <td></td>
                            <td>{{ $total_status_wajib_penerima }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </form>
    <textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 landscape;
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
        font-size: 13px;
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
