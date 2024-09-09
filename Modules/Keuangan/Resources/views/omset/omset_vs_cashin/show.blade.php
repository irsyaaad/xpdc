@extends('template.document')

@section('data')
@if(Request::segment(1)=="omsetvscashin")
@include("template.filter2")
<div class="table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
<table class="table table-sm table-bordered table-hover table-striped" id="data-table">
    <thead style="background-color: grey; color : #ffff">
        <tr>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">ID Plgn</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Nama Pelanggan</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Tlpn</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Omset</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Bayar</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Piutang</th>
            <th colspan="2">Hingga Sekarang</th>
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
            <td>{{ $value->id_pelanggan }}</td>
            <td>{{ $value->nm_pelanggan }}</td>
            <td>{{ $value->telp }}</td>
            <td class="text-right">
                {{number_format($value->omset, 0, ',', '.')}}
                @php $total_omset += $value->omset; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->bayar, 0, ',', '.')}}
                @php $total_bayar += $value->bayar; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->piutang, 0, ',', '.')}}
                @php $total_piutang += $value->piutang; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->bayar_hingga_sekarang, 0, ',', '.')}}
                @php $total_bayar_sekarang += $value->bayar_hingga_sekarang; @endphp
            </td>
            <td class="text-right">
                {{number_format($value->omset - $value->bayar_hingga_sekarang, 0, ',', '.')}}
                @php $total_piutang_sekarang += $value->omset - $value->bayar_hingga_sekarang; @endphp
            </td>
        </tr>
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td class="text-center" colspan="3">Total</td>
            <td class="text-right">{{number_format($total_omset, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_bayar, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_piutang, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_bayar_sekarang, 0, ',', '.')}}</td>
            <td class="text-right">{{number_format($total_piutang_sekarang, 0, ',', '.')}}</td>
        </tr>
    </tbody>
</table>
</div>
@endif
@endsection
