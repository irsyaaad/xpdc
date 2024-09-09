@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th {
        text-align: center;
    }
</style>
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>No Stt</th>
                <th>Tanggal Masuk</th>
                <th>Nama Pelanggan</th>
                <th>No AWB</th>
                <th>Tipe Kirim</th>
                <th>Cara Bayar</th>
                <th>Berat</th>
                <th>Volume</th>
                <th>Koli</th>
                <th>Total</th>
                <th>Piutang</th>
                <th>No DM</th>
                <th>Perusahaan Tujuan / Nama Vendor</th>
                <th>Tgl Berangkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ isset($value->tgl_masuk)? daydate($value->tgl_masuk).", ".dateindo($value->tgl_masuk) : '-' }}</td>
                    <td>{{ strtoupper($value->nm_pelanggan) }}</td>
                    <td>{{ $value->no_awb }}</td>
                    <td>{{ $value->nm_tipe_kirim }}</td>
                    <td>{{ $value->nm_cr_byr_o }}</td>
                    <td>{{ $value->n_berat }}</td>
                    <td>{{ $value->n_volume }}</td>
                    <td>{{ $value->n_koli }}</td>
                    <td>{{ $value->c_total }}</td>
                    <td>{{ $value->piutang }}</td>
                    <td>{{ $value->kode_dm }}</td>
                    <td>{{ !empty($value->nm_perush)? $value->nm_perush : $value->nm_ven }}</td>
                    <td>{{ isset($value->tgl_berangkat)? daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection