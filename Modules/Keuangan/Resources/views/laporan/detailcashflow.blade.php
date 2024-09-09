@extends('template.document')

@section('data')
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>
<div class="col-md-12 text-right">
    <a href="{{ $filter["back"] }}" class="btn btn-sm btn-warning">
        <i class="fa fa-reply"></i>	Kembali
    </a>
</div> 

<div class="col text-center mb-3">
    <h4>Detail Cashflow</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
</div>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <th>No</th>
            <th>Tanggal</th>
            <th>No. Detail</th>
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Total</th>
        </thead>
        <tbody>
            @php 
                $total = 0; 
                $nomer = 1;
            @endphp
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $nomer++ }}</td>
                    <td>{{ daydate($value->tgl).", ".dateindo($value->tgl) }}</td>
                    <td>{{ $value->id_detail }}</td>
                    <td>{{ $value->keterangan }}</td>
                    <td class="text-right">@if ($value->id_debet < 2000)
                        {{ number_format($value->nominal, 0, ',', '.') }}
                        @php
                            $total+=$value->nominal;
                        @endphp
                        @else
                        0
                        @endif
                    </td>
                    <td class="text-right">@if ($value->id_debet > 2000)
                        {{ number_format($value->nominal, 0, ',', '.') }}
                        @php
                            $total+=$value->nominal;
                        @endphp
                        @else
                            0
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background-color: grey; color : #ffff">
                <td colspan="6" class="text-center">Total</td>
                <td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection