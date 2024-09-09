@extends('template.document')

@section('data')
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>{{$akun->nama}}</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        @if(Request::segment(1) == "bukubesar" or Request::segment(1) == "cashflow")
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
        @else       
            <a href="{{ url(Request::segment(1))."/$akun->parent"."/show" }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
        @endif
    </div>    
</div>
<br>
<table class="table table-striped table-responsive table-sm" id="html_table" width="100%">
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
        @php $temp = $saldo_awal[$id]; @endphp
        <tr>
            <td colspan="4" class="text-center">SALDO AWAL</td>
            <td colspan="2">Rp. {{ number_format($temp, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
        </tr>
        @foreach($data as $key => $value)
        <tr>
            @php
            if($key < $batas)
            $temp += $value->n_materai;
            else
            $temp -= $value->total;
            @endphp
            <td>{{$key+1}}</td>
            <td>@if(isset($value->created_at)){{date('d-M-Y', strtotime($value->created_at))}} @endif</td>
            <td>@if(isset($value->id_stt)){{$value->id_stt}}@else{{$value->id_detail}}@endif</td>
            <td>@if(isset($value->info)){{$value->info}}@endif</td>
            <td>@if($key < $batas)Rp. {{ number_format($value->n_materai, 0, ',', '.') }}@else 0 @endif</td>
            <td>@if($key >= $batas)Rp. {{ number_format($value->total, 0, ',', '.') }}@else 0 @endif</td>
            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td colspan="6" class="text-center">TOTAL</td>
            <td><b>Rp. {{ number_format($temp, 0, ',', '.') }}</b></td>
        </tr>
    </tbody>
</table>
@endsection