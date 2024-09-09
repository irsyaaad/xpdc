@extends('template.document2')

@section('data')
    @if (Request::segment(1) == 'gajikaryawan' &&
            (Request::segment(2) == null or Request::segment(2) == 'page' or Request::segment(2) == 'filter'))
        <div class="row">
            <div class="col-md-12">
                @include('kepegawaian::filter.gajikaryawan')
                <hr>
            </div>
            <div class="col-md-12" style="margin-top: 10px">
                @php
                    $tunjangan = [
                        'n_tunjangan_jabatan' => 'Tunjangan Jabatan',
                        'n_tunjangan_kinerja' => 'Tunjangan Kinerja',
                        'n_tunjangan_kpi' => 'KPI',
                    ];

                    $tunj_nonthp = [
                        'n_tunjangan_kesehatan' => 'Tunj. BPJS Kesehatan',
                        'n_tunjangan_jht' => 'JHT',
                        'n_tunjangan_jkk' => 'JKK',
                        'n_tunjangan_jkm' => 'JKM',
                        'n_tunjangan_jp' => 'JP',
                    ];

                    $potongan = [
                        'n_potongan_pph' => 'PPH 21',
                        'n_potongan_kesehatan' => 'Potongan Kesehatan',
                        'n_potongan_jht' => 'Potngan JHT',
                        'n_potongan_jp' => 'Potngan JP',
                        'n_denda' => 'Absensi Kehadiran',
                        'n_piutang' => 'Piutang Karyawan',
                    ];
                @endphp
                <div class="table-responsive mt-3" style="display: block; overflow-x: auto;white-space: nowrap;">
                    <table class="table-lg table-striped table-responsive table" width="100%">
                        <thead style="background-color: grey; color : #ffff">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Karyawan </th>
                                <th rowspan="2">Gaji Pokok</th>
                                <th colspan="5">
                                    <center>Tunjangan Non THP</center>
                                </th>
                                <th colspan="3">
                                    <center>Tunjangan</center>
                                </th>
                                <th colspan="6">
                                    <center>Potongan</center>
                                </th>
                                <th rowspan="2">Gaji Diterima (THP)</th>
                                <th rowspan="2">Approve</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                @foreach ($tunj_nonthp as $key => $item)
                                    <td>{{ $item }}</td>
                                @endforeach
                                @foreach ($tunjangan as $key => $item)
                                    <td>{{ $item }}</td>
                                @endforeach
                                @foreach ($potongan as $key => $item)
                                    <td>{{ $item }}</td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_gaji = 0;
                                $total_thp = 0;
                                foreach ($tunj_nonthp as $key => $item) {
                                    $total[$key] = 0;
                                }

                                foreach ($tunjangan as $key => $item) {
                                    $total[$key] = 0;
                                }
                                foreach ($potongan as $key => $item) {
                                    $total[$key] = 0;
                                }
                            @endphp
                            @foreach ($data as $key => $value)
                                @php
                                    $total_gaji += $value->n_gaji;
                                    $total_tunjnonthp = 0;
                                    $total_tunjangan = 0;
                                    $total_potongan = 0;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if (isset($value->id_karyawan))
                                            {{ strtoupper($value->nm_karyawan) }}
                                        @endif
                                    </td>
                                    @php
                                    @endphp
                                    <td>
                                        @if (isset($value->n_gaji))
                                            {{ toRupiah($value->n_gaji) }}
                                        @endif
                                    </td>
                                    @foreach ($tunj_nonthp as $key => $item)
                                        <td>{{ isset($value->$key) ? toRupiah($value->$key) : 0 }}</td>
                                        @php
                                            $total_tunjnonthp += $value->$key;
                                            $total[$key] += isset($value->$key) ? $value->$key : 0;
                                        @endphp
                                    @endforeach
                                    @foreach ($tunjangan as $key => $item)
                                        <td>{{ isset($value->$key) ? toRupiah($value->$key) : 0 }}</td>
                                        @php
                                            $total_tunjangan += $value->$key;
                                            $total[$key] += isset($value->$key) ? $value->$key : 0;
                                        @endphp
                                    @endforeach
                                    @foreach ($potongan as $key => $item)
                                        <td>{{ isset($value->$key) ? toRupiah($value->$key) : 0 }}</td>
                                        @php
                                            $total_potongan += isset($value->$key) ? $value->$key : 0;
                                            $total[$key] += isset($value->$key) ? $value->$key : 0;
                                        @endphp
                                    @endforeach
                                    <td>
                                        {{ toRupiah($value->n_gaji + $total_tunjangan - $total_potongan) }}
                                        @php
                                            $total_thp += $value->n_gaji + $total_tunjangan - $total_potongan;
                                        @endphp
                                    </td>
                                    <td>
                                        @if ($value->is_approve == true)
                                            <i class="text-success fa fa-check"> </i>
                                        @else
                                            <i class="text-danger fa fa-times"> </i>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-accent" target="_blank"
                                            href="{{ url(Request::segment(1) . '/' . $value->id_gk . '/detail') }}"><i
                                                class="fa fa-eye"></i></a>
                                        @if (isset($value->n_gaji))
                                            <a class="btn btn-sm btn-success" target="_blank"
                                                href="{{ url(Request::segment(1) . '/' . $value->id_gk . '/slipgaji') }}"><i
                                                    class="fa fa-print"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                <td colspan="2">TOTAL</td>
                                <td>{{ toRupiah($total_gaji) }}</td>
                                @php
                                    $grandtotal_tunjnonthp = 0;
                                    $grandtotal_tunjangan = 0;
                                    $grandtotal_potongan = 0;
                                @endphp
                                @foreach ($tunj_nonthp as $key => $item)
                                    <td>{{ isset($total[$key]) ? toRupiah($total[$key]) : 0 }}</td>
                                    @php
                                        $grandtotal_tunjnonthp += $total[$key];
                                    @endphp
                                @endforeach
                                @foreach ($tunjangan as $key => $item)
                                    <td>{{ isset($total[$key]) ? toRupiah($total[$key]) : 0 }}</td>
                                    @php
                                        $grandtotal_tunjangan += $total[$key];
                                    @endphp
                                @endforeach
                                @foreach ($potongan as $key => $item)
                                    <td>{{ isset($total[$key]) ? toRupiah($total[$key]) : 0 }}</td>
                                    @php
                                        $grandtotal_potongan += $total[$key];
                                    @endphp
                                @endforeach
                                <td>{{ toRupiah($total_thp) }}</td>
                                <td colspan="2"></td>
                            </tr>
                            @if (count($data) < 1)
                                <tr>
                                    <td colspan="14">
                                        <center><b>Gaji Belum Di Generate</b></center>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12" style="margin-top: 20px">
                <div class="row">
                    <h5 class="col-3">Total Gaji Pokok:</h5>
                    <h5 class="col-6">{{ toRupiah($total_gaji) }}</h5>
                </div>
                <div class="row">
                    <h5 class="col-3">Total Tunj. Non THP:</h5>
                    <h5 class="col-6">{{ toRupiah($grandtotal_tunjnonthp) }}</h5>
                </div>
                <div class="row">
                    <h5 class="col-3">Total Tunjangan:</h5>
                    <h5 class="col-6">{{ toRupiah($grandtotal_tunjangan) }}</h5>
                </div>
                <div class="row">
                    <h5 class="col-3">Total Potongan:</h5>
                    <h5 class="col-6">{{ toRupiah($grandtotal_potongan) }}</h5>
                </div>
                <div class="row">
                    <h5 class="col-3">Total Gaji Diterima (THP):</h5>
                    <h5 class="col-6">{{ toRupiah($total_thp) }}</h5>
                </div>
            </div>
        </div>

        @include('kepegawaian::gajikaryawan.modal')
    @endif
@endsection

@section('script')
    <script>
        $("#f_perush").val('{{ Session('perusahaan')['id_perush'] }}');
        $("#f_bulan").val('{{ date('m') }}');
        $("#f_tahun").val('{{ date('Y') }}');

        function CheckStatus() {
            $("#modal-create").modal('show');
        }

        function CheckDenda() {
            $("#modal-create-denda").modal('show');
        }

        function CheckApprove() {
            $("#modal-approve").modal('show');
        }

        @if (isset($filter['f_perush']))
            $("#f_perush").val('{{ $filter['f_perush'] }}');
        @endif

        @if (isset($filter['f_bulan']))
            $("#f_bulan").val('{{ $filter['f_bulan'] }}');
        @endif

        @if (isset($filter['f_tahun']))
            $("#f_tahun").val('{{ $filter['f_tahun'] }}');
        @endif
    </script>
@endsection
