@extends('template.document2')

@section('data')
    <form method="GET" action="{{ url('omsetbyregion') }}" enctype="multipart/form-data" id="form-select">
        @csrf
        <div class="row align-items-center">
            <div class="col-xl-12">
                <div class="form-group row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight: bold;">
                                Region
                            </label>
                            <div class="form-check">
                                <input type="radio" name="f_region" value="asal"
                                    {{ $request->f_region == 'asal' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Asal
                                </label>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="f_region" value="tujuan"
                                    {{ $request->f_region == 'tujuan' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Tujuan
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label style="font-weight: bold;">
                            Dari Tanggal
                        </label>
                        <input type="date" class="form-control" name="f_start" id="f_start"
                            value="{{ isset($filter['f_start']) ? $filter['f_start'] : '' }}">
                    </div>
                    <div class="col-md-3">
                        <label style="font-weight: bold;">
                            Sampai Tanggal
                        </label>
                        <input type="date" class="form-control" name="f_end" id="f_end"
                            value="{{ isset($filter['f_end']) ? $filter['f_end'] : '' }}">
                    </div>
                    <div class="col-md-3" style="padding-top:28px;">
                        <button class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom"
                            title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span>
                            Cari</button>
                        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip"
                            data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span>
                            Refresh</a>
                        <a href="javascript:printDiv('print-js');" class="btn btn-sm btn-info"><i class="fa fa-print">
                                Cetak</i></a>
                    </div>

                    <div class="col-md-12 mt-4" id="print-js">
                        <center>
                            <b style="font-weight:bold">Rekap Omzet By Region
                                {{ isset($request->f_region) ? ucfirst($request->f_region) : 'Tujuan' }}</b><br>
                            <b style="font-weight:bold">Periode : {{ $filter['f_start'] }} s/d {{ $filter['f_end'] }}</b>
                        </center>
                        <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
                            <table class="table-responsive table-sm table" width="100%">
                                <thead style="background-color: grey; color : #ffff">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Region</th>
                                        <th>Provinsi - Kab/Kota</th>
                                        <th>STT</th>
                                        <th class="text-right">Kg</th>
                                        <th class="text-right">Kgv</th>
                                        <th class="text-right">M3</th>
                                        <th class="text-right">Koli</th>
                                        <th class="text-right">Omset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t_stt = 0;
                                        $t_kg = 0;
                                        $t_kgv = 0;
                                        $t_m3 = 0;
                                        $t_koli = 0;
                                        $t_omset = 0;
                                        $i = 0;
                                        $parent = '';
                                        $t_substt = 0;
                                        $t_subkg = 0;
                                        $t_subkgv = 0;
                                        $t_subm3 = 0;
                                        $t_subkoli = 0;
                                        $t_subomset = 0;
                                    @endphp
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('detail-omset-by-region', [
                                                    'id_wil' => $value->id_region,
                                                    'dr_tgl' => isset($_GET['f_start']) ? $_GET['f_start'] : date('Y-m-01'),
                                                    'sp_tgl' => isset($_GET['f_end']) ? $_GET['f_end'] : date('Y-m-t'),
                                                    'tipe' => isset($_GET['f_region']) ? $_GET['f_region'] : 'tujuan',
                                                ]) }}"
                                                    style="color:black;">{{ $value->nama_wil }}
                                                </a>
                                            </td>
                                            <td>{{ $value->kab }}</td>
                                            <td class="text-right">{{ $value->jml_stt }}</td>
                                            <td class="text-right">{{ $value->jml_berat }}</td>
                                            <td class="text-right">{{ $value->jml_volume }}</td>
                                            <td class="text-right">{{ $value->jml_kubik }}</td>
                                            <td class="text-right">{{ $value->jml_koli }}</td>
                                            <td class="text-right">{{ toNumber($value->jml_omset) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: grey; color : #ffff">
                                        <td colspan="2" class="text-center"><b>Total : </b></td>
                                        <td class="text-right">
                                            {{ toNumber($t_stt) }}
                                        </td>
                                        <td class="text-right">
                                            {{ toNumber($t_kg) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($t_kgv, 2, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($t_m3, 2, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            {{ toNumber($t_koli) }}
                                        </td>
                                        <td class="text-right">
                                            {{ toNumber($t_omset) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
    
    table tbody td {
        color: black;
    }
</textarea>
    <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
@endsection

@section('script')
    <script>
        function printDiv(elementId) {
            var a = document.getElementById('printing-css').value;
            var b = document.getElementById(elementId).innerHTML;
            window.frames["print_frame"].document.title = document.title;
            window.frames["print_frame"].document.body.innerHTML = '<style>' + a + '</style>' + b;
            window.frames["print_frame"].window.focus();
            window.frames["print_frame"].window.print();
        }

        @if (isset($filter['f_layanan']))
            $("#f_layanan").val('{{ $filter['f_layanan'] }}');
        @endif
    </script>
@endsection
