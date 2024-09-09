@extends('template.document2')

@section('data')
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }

        td {
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
    </style>
    <div class="row">
        <div class="col-md-4">
            <h4><i class="fa fa-thumb-tack"></i>
                <b></b>
            </h4>
        </div>
        <div class="col-md-4">
            <h5 class="text-center"><b>Daftar Pelanggan Aktif</b></h5>
            <h5 class="text-center"><b>{{ $marketing->nm_marketing }}</b></h5>
            <h5 class="text-center"><b>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }} </b>
            </h5>
        </div>

        <div class="col-md-4 text-right">
            <a href="{{ $filter['back'] }}" class="btn btn-sm btn-warning mr-1">
                <i class="fa fa-reply"></i> Kembali
            </a>
            <a href="{{ route('cetak-pelanggan-aktif', [
                'id_marketing' => $filter['id_marketing'],
                'dr_tgl' => $filter['dr_tgl'],
                'sp_tgl' => $filter['sp_tgl'],
            ]) }}"
                target="_blank" class="btn btn-sm btn-success">
                <i class="fa fa-print"></i>
            </a>
        </div>
    </div>
    <br>
    <div class="table-responsive" style="display: block; overflow-x: auto;white-space: nowrap;">
        <table class="table table-responsive table-sm" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th style="text-align: center; vertical-align: middle;">No</th>
                    <th style="text-align: center; vertical-align: middle;">ID Pelanggan</th>
                    <th style="text-align: center; vertical-align: middle;">Nama Pelanggan</th>
                    <th style="text-align: center; vertical-align: middle;">Awal</th>
                    <th style="text-align: center; vertical-align: middle;">Terakhir</th>
                    <th style="text-align: center; vertical-align: middle;">STT</th>
                    <th style="text-align: center; vertical-align: middle;">Telpn</th>
                    <th style="text-align: center; vertical-align: middle;">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_stt = 0;
                    $total_koli = 0;
                    $total_omset = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr style="background-color: {{ $value->baru == 1 ? '#9af5ac' : '' }}">
                        <td class="text-center">{{ $key += 1 }}</td>
                        <td class="text-center">{{ $value->id_plgn }}</td>
                        <td class="">{{ strtoupper($value->nm_pelanggan) }}</td>
                        <td class="text-center">{{ dateindo($value->awal) }}</td>
                        <td class="text-center">{{ dateindo($value->akhir) }}</td>
                        <td class="text-center">{{ $value->stt }}</td>
                        <td class="text-center">{{ $value->telp }}</td>
                        <td class="">{{ $value->alamat }}</td>
                    </tr>
                    @php
                        $total_stt += $value->stt;
                    @endphp
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
