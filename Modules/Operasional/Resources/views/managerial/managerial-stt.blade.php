@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>

<div class="col text-center mb-3">
    <h4>Managerial Stt</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kode STT</th>
                <th>Kode Pelanggan</th>
                <th>Nama Pengirim</th>
                <th>Kgv</th>
                <th>Kg</th>
                <th>M3</th>
                <th>Koli</th>
                <th>Omset</th>
                <th>Harga Satuan</th>
                <th>Bayar</th>
                <th>Piutang</th>
                <th>Tipe Tarif</th>
                <th>Nama Marketing</th>
                <th>Kode DM</th>
                <th>Tgl DM Berangkat</th>
                <th>Tgl DM Tiba</th>
                <th>Tgl STT Sampai</th>
                <th>Nama Status</th>
                <th>Cara Bayar</th>
                <th>Harga Bruto</th>
                <th>Diskon</th>
                <th>PPN</th>
                <th>Materai</th>
                <th>Asuransi</th>
                <th>No AWB</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td colspan="26">{{ dateindo($key) }}</td>
                </tr>
                @foreach ($data[$key] as $index => $item)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $item->kode_stt }}</td>
                        <td>{{ $item->kode_plgn_group }}</td>
                        <td>{{ $item->pengirim_nm }}</td>
                        <td>{{ $item->n_volume }}</td>
                        <td>{{ $item->n_berat }}</td>
                        <td>{{ $item->n_kubik }}</td>
                        <td>{{ $item->n_koli }}</td>
                        <td class="text-right" >{{ toNumber($item->c_total) }}</td>
                        <td class="text-right">{{ toNumber($item->n_tarif_koli) }}</td>
                        <td class="text-right">{{ toNumber($item->bayar) }}</td>
                        <td class="text-right">{{ toNumber($item->c_total-$item->bayar) }}</td>
                        <td>{{ $item->tarif }}</td>
                        <td>{{ $item->nm_marketing }}</td>
                        <td>{{ $item->kode_dm }}</td>
                        <td>{{ !empty($item->tgl_berangkat) ? dateindo($item->tgl_berangkat) : ''}}</td>
                        <td>{{ !empty($item->tgl_sampai) ? dateindo($item->tgl_sampai) : '' }}</td>
                        <td>{{ !empty($item->tgl_tiba) ? dateindo($item->tgl_tiba) : '' }}</td>
                        <td>{{ strtoupper($item->nama_status) }}</td>
                        <td>{{ strtoupper($item->nm_cr_byr_o) }}</td>
                        <td class="text-right">{{ toNumber($item->n_hrg_bruto) }}</td>
                        <td class="text-right">{{ toNumber($item->n_diskon) }}</td>
                        <td class="text-right">{{ toNumber($item->n_ppn) }}</td>
                        <td class="text-right">{{ toNumber($item->n_materai) }}</td>
                        <td class="text-right">{{ toNumber($item->n_asuransi) }}</td>
                        <td>{{ $item->no_awb }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection