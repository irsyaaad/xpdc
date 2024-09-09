@extends('template.document2')

@section('data')
<div class="row">
    <div class="col-md-4">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>{{$marketing->nm_marketing}}</b>
        </h4>
    </div>
    <div class="col-md-4">
        <h5><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}} </b></h5>
    </div>

    <div class="col-md-4 text-right">
        <a href="{{ $filter["back"] }}" class="btn btn-sm btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>
</div>
<br>
<table class="table table-responsive table-sm" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th style="text-align: center; vertical-align: middle;">No</th>
            <th style="text-align: center; vertical-align: middle;">Nama Pelanggan</th>
            <th style="text-align: center; vertical-align: middle;">Total STT</th>
            <th style="text-align: center; vertical-align: middle;">Total Koli</th>
            <th class="text-center">Total Omset</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_stt = 0;
            $total_koli = 0;
            $total_omset = 0;
        @endphp
        @foreach($data as $key => $value)
            <tr>
                <td class="text-center">{{$key+=1}}</td>
                <td class="text-center">{{ strtoupper($value->nm_pelanggan) }}</td>
                <td class="text-center">{{ $value->total_stt }}</td>
                <td class="text-center">{{ $value->total_koli }}</td>
                <td class="text-center">Rp. {{number_format($value->total_omset, 0, ',', '.')}}</td>
            </tr>
            @php 
                $total_stt      += $value->total_stt;
                $total_koli     += $value->total_koli;
                $total_omset    += $value->total_omset;
            @endphp
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td colspan = "2" class="text-center">Total</td>
            <td class="text-center">{{ $total_stt }}</td>
            <td class="text-center">{{ $total_koli }}</td>
            <td class="text-center">Rp. {{number_format($total_omset, 0, ',', '.')}}</td>
        </tr>
    </tbody>
</table>
@endsection
