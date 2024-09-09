@extends('template.document')

@section('data')
@if(Request::segment(1)=="cashflow")
<table class="table table-borderless table-sm ">
    <thead>
        <th>ID AC</th>
        <th>Nama Account</th>
        <th>Debit</th>
        <th>Kredit</th>
    </thead>
    <tbody>
        @php
        $total_debit = 0;
        $total_kredit = 0;
        @endphp
        @foreach($akun[$id] as $key => $value)
        <tr>
            <td>{{$value->id_ac}}</td>
            <td><a href="{{ url(Request::segment(1)."/".$value->ac_perush."/showdetail") }}" style="color:black;">{{$value->nama_ac_perush}}</a></td>
            <td>Rp. {{ number_format($value->total_pendapatan, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($value->peng_det, 0, ',', '.') }}</td>
            @php
            $total_debit+=$value->total_pendapatan;
            $total_kredit+=$value->peng_det;
            @endphp
        </tr>
        @endforeach
        <tr>
        <td colspan="2"></td>
        <td>Rp. {{ number_format($total_debit, 0, ',', '.') }}</td>
        <td>Rp. {{ number_format($total_kredit, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
@elseif(Request::segment(1)=="neraca")
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>{{$nm_akun->nama}}</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>    
</div>
<br>
<table class="table table-borderless table-sm ">
    <thead>
        <th>ID AC</th>
        <th>Nama Account</th>
        <th>Debit</th>
        <th>Kredit</th>
    </thead>
    <tbody>
        @php
        $total_debit = 0;
        $total_kredit = 0;
        @endphp
        @foreach($akun[$id] as $key => $value)
        <tr>
            <td>{{$value->ac_perush}}</td>
            <td><a href="{{ url(Request::segment(1)."/".$value->ac_perush."/showdetail") }}" style="color:black;">{{$value->nama_ac_perush}}</td>
            <td>Rp. {{ number_format($value->debit, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($value->kredit, 0, ',', '.') }}</td>
            @php
            $total_debit+=$value->debit;
            $total_kredit+=$value->kredit;
            @endphp
        </tr>
        @endforeach
        <tr>
        <td colspan="2"></td>
        <td>Rp. {{ number_format($total_debit, 0, ',', '.') }}</td>
        <td>Rp. {{ number_format($total_kredit, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
@elseif(Request::segment(1)=="rugilaba")
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>{{$nm_akun->nama}}</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>    
</div>
<br>
<table class="table table-borderless table-sm ">
    <thead>
        <th>ID AC</th>
        <th>Nama Account</th>
        <th>Debit</th>
        <th>Kredit</th>
    </thead>
    <tbody>
        @php
        $total_debit = 0;
        $total_kredit = 0;
        @endphp
        @foreach($akun[$id] as $key => $value)
        <tr>
            <td>{{$value->ac_perush}}</td>
            <td><a href="{{ url(Request::segment(1)."/".$value->ac_perush."/showdetail") }}" style="color:black;">{{$value->nama_ac_perush}}</td>
            <td>Rp. {{ number_format($value->debit, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($value->kredit, 0, ',', '.') }}</td>
            @php
            $total_debit+=$value->debit;
            $total_kredit+=$value->kredit;
            @endphp
        </tr>
        @endforeach
        <tr>
        <td colspan="2"></td>
        <td>Rp. {{ number_format($total_debit, 0, ',', '.') }}</td>
        <td>Rp. {{ number_format($total_kredit, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
@endif
@endsection