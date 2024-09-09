@extends('template.document2')

@section('data')
@if(Request::segment(1)=="neracadetail" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-sm table-bordered">
    <thead style="background-color: grey; color : #ffff">
        <th class="text-center">No</th>
        <th class="text-center">Tanggal</th>
        <th class="text-center">No Detail</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Debet</th>
        <th class="text-center">Kredet</th>
        <th class="text-center">Total</th>
    </thead>
    @foreach($data1 as $key => $value)
        @if($value->id_ac < 3)
           @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            @if(isset($data4[$value3->id_ac]))
                                @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                    @php $temp=$saldo_awal["$value4->ac_perush"]; @endphp
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr style="background-color: grey; color : #ffff">
                                    <td colspan=7><b>{{$value4->ac_perush}} - {{$value4->nama_ac_perush}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">SALDO AWAL</td>
                                        <td colspan="2">Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]))
                                        @foreach($data5[$value4->ac_perush] as $key5 => $value5)
                                        @php
                                        if(isset($value5->n_materai)){
                                            $temp+=$value5->n_materai;
                                        }elseif(isset($value5->total)){
                                            $temp-=$value5->total;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{$key5+1}}</td>
                                            <td>@if(isset($value5->created_at)){{date('d-M-Y', strtotime($value5->created_at))}} @endif</td>
                                            <td>@if(isset($value5->id_stt)){{$value5->id_stt}}@else{{$value5->id_detail}}@endif</td>
                                            <td>@if(isset($value5->info)){{$value5->info}}@endif</td>
                                            <td>@if(isset($value5->n_materai))Rp. {{ number_format($value5->n_materai, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>@if(isset($value5->total))Rp. {{ number_format($value5->total, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr>
                                        <td class="text-center" colspan="6">Sub total {{$value4->nama_ac_perush}}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif                                    
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
           @endif
        @endif
    @endforeach
</table>
@endif
@if(Request::segment(1)=="rugilabadetail" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-sm table-bordered">
    <thead style="background-color: grey; color : #ffff">
        <th class="text-center">No</th>
        <th class="text-center">Tanggal</th>
        <th class="text-center">No Detail</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Debet</th>
        <th class="text-center">Kredet</th>
        <th class="text-center">Total</th>
    </thead>
    @foreach($data1 as $key => $value)
        @if($value->id_ac > 3)
           @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            @if(isset($data4[$value3->id_ac]))
                                @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                @php $temp=0; @endphp
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr style="background-color: grey; color : #ffff">
                                    <td colspan=7><b>{{$value4->ac_perush}} - {{$value4->nama_ac_perush}}</b></td>
                                    </tr>
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]))                                        
                                        @foreach($data5[$value4->ac_perush] as $key5 => $value5)
                                        @php
                                        if(isset($value5->n_materai)){
                                            $temp+=$value5->n_materai;
                                        }elseif(isset($value5->total)){
                                            $temp-=$value5->total;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{$key5+1}}</td>
                                            <td>@if(isset($value5->created_at)){{date('d-M-Y', strtotime($value5->created_at))}} @endif</td>
                                            <td>@if(isset($value5->id_stt)){{$value5->id_stt}}@else{{$value5->id_detail}}@endif</td>
                                            <td>@if(isset($value5->info_kirim)){{$value5->info_kirim}}@else{{$value5->info}}@endif</td>
                                            <td>@if(isset($value5->n_materai))Rp. {{ number_format($value5->n_materai, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>@if(isset($value5->total))Rp. {{ number_format($value5->total, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr>
                                        <td class="text-center" colspan="6">Sub total {{$value4->nama_ac_perush}}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
           @endif
        @endif
    @endforeach
</table>
@endif
@endsection()