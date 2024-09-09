@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
    }
    td{
        text-align: center;
    }
</style>

<div class="col text-center mb-3">
    <h4>DETAIL SLA DM Trucking</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kode STT</th>
                <th>Tanggal Masuk</th>
                <th>Nama Pelanggan</th>
                <th>Tujuan</th>
                <th>No DM</th>
                {{-- Berangkat --}}
                <th>Tgl Berangkat</th>
                <th>Lama</th>
                <th>Def</th>
                <th>Diff</th>
                {{-- Tiba --}}
                <th>Tgl Tiba</th>
                <th>Lama</th>
                <th>Def</th>
                <th>Diff</th>
                {{-- Dooring --}}
                <th>Tgl Dooring</th>
                <th>Lama</th>
                <th>Def</th>
                <th>Diff</th>
                {{-- Diterima --}}
                <th>Tgl Terima</th>
                <th>Lama</th>
                <th>Def</th>
                <th>Diff</th>
            </tr>
        </thead>
        <tbody>
            @php
                $def = 3;
            @endphp
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ dateindo($value->tgl_masuk) }}</td>
                    <td>{{ strtoupper($value->nm_pelanggan) }}</td>
                    <td style="padding: 0px 10px 0px 10px;">
                        {{ $value->tujuan }}<br>{{ $value->kabupaten }}<br>{{ $value->provinsi }}
                    </td>
                    <td>{{ $value->kode_dm }}</td>

                    {{-- Berangkat --}}
                    <td style="background-color: rgb(243, 243, 243)" class="tr-bold">{{ dateindo($value->tgl_dm_berangkat) }}</td>
                    <td>{{ $value->dibuat_ke_berangkat }}</td>
                    <th>{{ $def }}</th>
                    <th>{{ $value->dibuat_ke_berangkat-$def }}</th>

                    {{-- Tiba --}}
                    <td style="background-color: rgb(243, 243, 243)" class="tr-bold">{{ dateindo($value->tgl_tiba) }}</td>
                    <td>{{ $value->berangkat_ke_tiba }}</td>
                    <th>{{ $def }}</th>
                    <th>{{ $value->berangkat_ke_tiba-$def }}</th>

                    {{-- Dooring --}}
                    <td style="background-color: rgb(243, 243, 243)" class="tr-bold">{{ dateindo($value->tgl_dooring) }}</td>
                    <td>{{ $value->tiba_ke_dooring }}</td>
                    <th>{{ $def }}</th>
                    <th>{{ $value->tiba_ke_dooring-$def }}</th>

                    {{-- Sampai --}}
                    <td style="background-color: rgb(243, 243, 243)" class="tr-bold">{{ dateindo($value->tgl_sampai_tujuan) }}</td>
                    <td>{{ $value->dooring_ke_sampai }}</td>
                    <th>{{ $def }}</th>
                    <th>{{ $value->dooring_ke_sampai-$def }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection