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
                <th>ID Vendor</th>
                <th>Nama Vendor</th>
                <th>Saldo Awal</th>
                <th>Biaya Stt</th>
                <th>Biaya Vendor</th>
                <th>Hutang</th>
                <th>Bayar</th>
                <th>Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td><a href="{{ route('detailhutangvendor', [
                        'id_ven' => $value->id_ven, 
                        'dr_tgl' => $filter['dr_tgl'], 
                        'sp_tgl' => $filter['sp_tgl'],
                        ]) }}" style="color:black;">{{ $value->id_ven }}</a></td>
                    <td><a href="{{ route('detailhutangvendor', [
                        'id_ven' => $value->id_ven, 
                        'dr_tgl' => $filter['dr_tgl'], 
                        'sp_tgl' => $filter['sp_tgl'],
                        ]) }}" style="color:black;">{{ strtoupper($value->nm_ven) }}</a></td>
                    <td class="text-right">{{ number_format($value->saldo_awal, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->stt, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->vendor, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->bayar, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($value->saldo_awal + $value->total - $value->bayar, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection