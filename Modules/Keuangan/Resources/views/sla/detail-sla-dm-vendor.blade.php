@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th{
        text-align: center;
    }
</style>

<div class="col text-center mb-3">
    <h4>DETAIL SLA DM VENDOR</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>

<table width="100%">
    <tr>
        <td>Nama Vendor</td>
        <td> : </td>
        <td>{{ $vendor->nm_ven }}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td> : </td>
        <td>{{ $vendor->alm_ven }}</td>
    </tr>
    <tr>
        <td>Kontak</td>
        <td> : </td>
        <td>{{ $vendor->kontak_ven }} ( {{ $vendor->kontak_hp }} )</td>
    </tr>
</table>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode STT</th>
                <th rowspan="2">Tgl Masuk</th>
                <th rowspan="2">Nama Pelanggan</th>
                <th rowspan="2">Koli</th>
                <th rowspan="2">Kode DM</th>
                <th rowspan="2">Tgl Berangkat</th>
                <th colspan="3">Muat</th>
                <th colspan="4">Selesai</th>
            </tr>
            <tr>
                {{-- Muat --}}
                <th>Diff</th>
                <th>Def</th>
                <th>Selisih</th>
                {{-- Selesai --}}
                <th>Tgl Selesai</th>
                <th>Diff</th>
                <th>Def</th>
                <th>Selisih</th>
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
                    <td>{{ $value->nm_pelanggan }}</td>      
                    <td>{{ $value->n_koli }}</td>    
                    <td>{{ $value->kode_dm }}</td>
                    <td>{{ dateindo($value->tgl_dm_berangkat) }}</td>   
                    <td>{{ $value->dibuat_ke_berangkat }}</td>    
                    <td>{{ $def }}</td>            
                    <td>{{ $value->dibuat_ke_berangkat-$def }}</td>    
                    <td>{{ dateindo($value->tgl_sampai_tujuan) }}</td> 
                    <td>{{ $value->berangkat_sampai }}</td>    
                    <td>{{ $def }}</td>    
                    <td>{{ $value->dibuat_ke_berangkat-$def }}</td>    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection