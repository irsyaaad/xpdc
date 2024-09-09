@extends('template.document2')

@section('data')

    <style>
        table thead {
            background-color: gray;
            color: #fff;
        }
    </style>

    @if (isset($data))
        <div class="row">
            <div class="col-md-12">
                <div class="text-right">
                    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i>
                        Kembali</a>
                </div>
                @php
                    $total_tunjnonthp = 0;
                    $total_tunjangan = 0;
                    $total_potongan = 0;
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

                    foreach ($tunj_nonthp as $key => $value) {
                        $total_tunjnonthp += isset($data->$key) ? $data->$key : 0;
                    }
                    foreach ($tunjangan as $key => $value) {
                        $total_tunjangan += isset($data->$key) ? $data->$key : 0;
                    }

                    foreach ($potongan as $key => $value) {
                        $total_potongan += isset($data->$key) ? $data->$key : 0;
                    }
                @endphp
                <table class="table" style="margin-top: 1%">
                    <tr>
                        <td>Nama Karyawan : <b>{{ strtoupper($data->nm_karyawan) }}</b></td>
                        <td>Jabatan : <b>{{ strtoupper($data->nm_jabatan) }}</b></td>
                        <td>Gaji Pokok : <b>{{ isset($data->n_gaji) ? toRupiah($data->n_gaji) : 0 }}</b></td>
                    </tr>
                    <tr>
                        <td>Perusahaan / Devisi : <b>{{ $data->nm_perush }}</b></td>
                        <td>Pangkat / Golongan : <b>{{ strtoupper($data->golongan . ' ' . $data->pangkat) }}</b></td>
                        <td>Tunjangan : <b>{{ toRupiah($total_tunjangan) }}</b></td>
                    </tr>
                    <tr>
                        <td>Jenis Karyawan / Bagian : <b>{{ strtoupper($data->nm_jenis) }}</b></td>
                        <td>Bulan / Tahun : <b>{{ $data->bulan . ' / ' . $data->tahun }}</b></td>
                        <td>Potongan : <b>{{ toRupiah($total_potongan) }}</b></td>
                    </tr>
                    <tr>
                        <td>Tanggal Awal : <b>{{ dateindo($data->dr_tgl) }}</b></td>
                        <td>Tanggal Akhir : <b>{{ dateindo($data->sp_tgl) }}</b></td>
                        <td>Gaji THP :
                            @if (isset($data->n_gaji))
                                {{ torupiah($data->n_gaji + $total_tunjangan - $total_potongan) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <div class="col-md-12" style="margin-top: 1%">
                <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                    action="{{ route('gajikaryawan.update', $data->id_gk) }}" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="container-fluid row">
                        <div class="col-md-12">
                            <h4 style="margin-left: 2%"><i class="fa fa-thumb-tack"></i>
                                <b>Detail Tunjangan Non THP</b>
                            </h4>
                            <table class="table-responsive table-bordered table">
                                <thead>
                                    <tr>
                                        <th><b>NO. </b></th>
                                        <th><b>TUNJANGAN</b></th>
                                        <th><b>NOMINAL TUNJANGAN NON THP</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 0;
                                    @endphp
                                    @foreach ($tunj_nonthp as $key => $value)
                                        <tr>
                                            <td>{{ $no += 1 }}</td>
                                            <td>{{ strtoupper($value) }}</td>
                                            <td><input type="number" class="form-control" name="{{ $key }}"
                                                    id="{{ $key }}"
                                                    placeholder="Masukkan nominal {{ $value }}"
                                                    value="{{ isset($data->$key) ? $data->$key : 0 }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" style="text-align: right">TOTAL</td>
                                        <td><b>{{ toRupiah($total_tunjnonthp) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <hr>
            <div class="col-md-12" style="margin-top: 1%">
                <div class="container-fluid row">
                    <div class="col-md-12">
                        <h4 style="margin-left: 2%"><i class="fa fa-thumb-tack"></i>
                            <b>Detail Tunjangan</b>
                        </h4>
                        <table class="table-responsive table-bordered table">
                            <thead>
                                <tr>
                                    <th><b>NO. </b></th>
                                    <th><b>TUNJANGAN</b></th>
                                    <th><b>NOMINAL TUNJANGAN</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 0;
                                @endphp
                                @foreach ($tunjangan as $key => $value)
                                    <tr>
                                        <td>{{ $no += 1 }}</td>
                                        <td>{{ strtoupper($value) }}</td>
                                        <td><input type="number" class="form-control" name="{{ $key }}"
                                                id="{{ $key }}"
                                                placeholder="Masukkan nominal {{ $value }}"
                                                value="{{ isset($data->$key) ? $data->$key : 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" style="text-align: right">TOTAL</td>
                                    <td><b>{{ toRupiah($total_tunjangan) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-12" style="margin-top: 1%">
                <div class="container-fluid row">
                    <div class="col-md-12">
                        <h4 style="margin-left: 2%"><i class="fa fa-thumb-tack"></i>
                            <b>Detail Potongan</b>
                        </h4>
                        <table class="table-responsive table-bordered table">
                            <thead>
                                <tr>
                                    <th><b>NO. </b></th>
                                    <th><b>POTONGAN</b></th>
                                    <th><b>NOMINAL POTONGAN</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 0;
                                @endphp
                                @foreach ($potongan as $key => $value)
                                    <tr>
                                        <td>{{ $no += 1 }}</td>
                                        <td>{{ strtoupper($value) }}</td>
                                        <td>
                                            @if ($key != 'n_piutang')
                                                {{ isset($data->$key) ? $data->$key : 0 }}
                                            @else
                                                <input type="number" class="form-control" name="{{ $key }}"
                                                    id="{{ $key }}"
                                                    placeholder="Masukkan nominal {{ $value }}"
                                                    value="{{ isset($data->$key) ? $data->$key : 0 }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" style="text-align: right">TOTAL</td>
                                    <td><b>{{ toRupiah($total_potongan) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if (!$data->is_approve)
                        <div class="col-md-12 text-right">
                            <button class="btn btn-sm btn-success">
                                <i class="fa fa-save"> </i> Update Gaji
                            </button>
                            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i
                                    class="fa fa-reply"></i> Kembali</a>
                        </div>
                    @else
                    @endif

                </div>
                </form>
            </div>

        </div>
    @endif

@endsection

@section('script')
@endsection
