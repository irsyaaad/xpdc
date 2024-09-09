@extends('template.document')

@section('data')
@if(Request::segment(1)=="omsetvscashin")
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        text-align: center;
        vertical-align: middle;
    }
</style>
<div class="col text-center mb-3">
    <h4>SLA DM Trucking</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>
<div class="table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
<table class="table table-sm table-bordered table-hover table-striped" id="data-table">
    <thead style="background-color: grey; color : #ffff">
        <tr>
            <th rowspan="2" >No</th>
            <th rowspan="2" >No Bukti (Kode STT)</th>
            <th rowspan="2" >Tanggal</th>
            <th rowspan="2" >Nama Pelanggan</th>
            <th rowspan="2" >Omset</th>
            <th rowspan="2" >Bayar</th>
            <th colspan="2">Hingga Sekarang</th>
            <th rowspan="2" >Marketing</th>
        </tr>
        <tr>
            <th>Bayar</th>
            <th>Piutang</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_omset            = 0;
            $total_bayar            = 0;
            $total_piutang          = 0;
            $total_bayar_sekarang   = 0;
            $total_piutang_sekarang = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $value->kode }}</td>
            <td>{{ dateindo($value->tgl_masuk) }}</td>
            <td>{{ $value->nm_pelanggan }}</td>
            <td class="text-right">
                {{number_format($value->omset, 0, ',', '.')}}
                @php $total_omset += $value->omset; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->bayar, 0, ',', '.')}}
                @php $total_bayar += $value->bayar; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->bayar_sekarang, 0, ',', '.')}}
                @php $total_bayar_sekarang += $value->bayar_sekarang; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->omset - $value->bayar_sekarang, 0, ',', '.')}}
                @php $total_piutang_sekarang += $value->omset - $value->bayar_sekarang; @endphp
            </td>
            <td>{{ $value->nm_marketing }}</td>
        </tr>
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td class="text-center" colspan="4">Total</td>
            <td class="text-right">{{number_format($total_omset, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_bayar, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_bayar_sekarang, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_piutang_sekarang, 0, ',', '.')}}</td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
@endif
@endsection
