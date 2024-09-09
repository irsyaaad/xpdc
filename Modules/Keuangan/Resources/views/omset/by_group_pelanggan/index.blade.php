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
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Group</th>
                <th colspan="2">Keseluruhan</th>
                <th colspan="2">Sudah Tiba</th>
                <th colspan="2">Gudang / Perjalanan</th>
            </tr>
            <tr>
                <th>Koli</th>
                <th>Total</th>

                <th>Koli</th>
                <th>Total</th>

                <th>Koli</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->nm_group }}</td>
                    <td>{{ $value->koli_keseluruhan }}</td>
                    <td class="text-right">{{ toNumber($value->keseluruhan) }}</td>
                    <td>{{ $value->koli_sudah_tiba }}</td>
                    <td class="text-right">{{ toNumber($value->sudah_tiba) }}</td>
                    <td>{{ $value->koli_perjalanan }}</td>
                    <td class="text-right">{{ toNumber($value->perjalanan) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection