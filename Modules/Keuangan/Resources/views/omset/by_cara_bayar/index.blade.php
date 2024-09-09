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
                <th rowspan="2">Bulan</th>
                <th rowspan="2">Tahun</th>
                <th rowspan="2">Omset</th>
                <th rowspan="2">Bayar</th>
                <th rowspan="2">Piutang</th>
                <th colspan="2">Tunai Cash</th>
                <th colspan="2">Transfer</th>
            </tr>
            <tr>
                <th>Bayar</th>
                <th>%</th>
                <th>Bayar</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $value->bulan }}</td>
                    <td>{{ $value->tahun }}</td>
                    <td class="text-right">{{ toNumber($value->omset) }}</td>
                    <td class="text-right">{{ toNumber($value->bayar) }}</td>
                    <td class="text-right">{{ toNumber($value->piutang) }}</td>
                    <td class="text-right">{{ toNumber($value->cash) }}</td>
                    <td class="text-center">{{ ($value->bayar > 0 && $value->cash > 0)? round(($value->cash/$value->bayar)*100, 2) : 0}} %</td>
                    <td class="text-right">{{ toNumber($value->transfer) }}</td>
                    <td class="text-center">{{ ($value->bayar > 0 && $value->transfer > 0)? round(($value->transfer/$value->bayar)*100, 2) : 0}} %</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection