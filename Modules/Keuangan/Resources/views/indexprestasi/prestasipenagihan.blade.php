@extends('template.document2')

@section('data')
@if(Request::segment(1)=="prestasipenagihanomset" and Request::segment(2)==null)
@include("template.filter")
<table class="table table-responsive table-bordered table-sm" id="html_table" width="100%">
    <thead style="background-color: grey; color : #ffff">
       <tr>
        <th rowspan="2">BL</th>
        <th rowspan="2">TH</th>
        <th rowspan="2">Omset</th>
        <th colspan="2">1-{{$tahun}}</th>
        <th colspan="2">2-{{$tahun}}</th>
        <th colspan="2">3-{{$tahun}}</th>
        <th colspan="2">4-{{$tahun}}</th>
        <th colspan="2">5-{{$tahun}}</th>
        <th colspan="2">6-{{$tahun}}</th>
        <th colspan="2">7-{{$tahun}}</th>
        <th colspan="2">8-{{$tahun}}</th>
        <th colspan="2">9-{{$tahun}}</th>
        <th colspan="2">10-{{$tahun}}</th>
        <th colspan="2">11-{{$tahun}}</th>
        <th colspan="2">12-{{$tahun}}</th>
       </tr>
       <tr>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
        <th>Bayar</th>
        <th>%</th>
       </tr>
    </thead>
    <tbody>
        @foreach($omset as $key => $value)
        <tr>
            <td>{{$value->month}}</td>
            <td>{{$tahun}}</td>
            <td>@if(isset($value->totalamount))Rp. {{ number_format($value->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>@if(isset($bayar[$value->month][1]) and $bayar[$value->month][1]->month == $value->month)Rp. {{ number_format($bayar[$value->month][1]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][2]) and $bayar[$value->month][2]->month == $value->month)Rp. {{ number_format($bayar[$value->month][2]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][3]) and $bayar[$value->month][3]->month == $value->month)Rp. {{ number_format($bayar[$value->month][3]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][4]) and $bayar[$value->month][4]->month == $value->month)Rp. {{ number_format($bayar[$value->month][4]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][5]) and $bayar[$value->month][5]->month == $value->month)Rp. {{ number_format($bayar[$value->month][5]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][6]) and $bayar[$value->month][6]->month == $value->month)Rp. {{ number_format($bayar[$value->month][6]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][7]) and $bayar[$value->month][7]->month == $value->month)Rp. {{ number_format($bayar[$value->month][7]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][8]) and $bayar[$value->month][8]->month == $value->month)Rp. {{ number_format($bayar[$value->month][8]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][9]) and $bayar[$value->month][9]->month == $value->month)Rp. {{ number_format($bayar[$value->month][9]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][10]) and $bayar[$value->month][10]->month == $value->month)Rp. {{ number_format($bayar[$value->month][10]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][11]) and $bayar[$value->month][11]->month == $value->month)Rp. {{ number_format($bayar[$value->month][11]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
            <td>@if(isset($bayar[$value->month][12]) and $bayar[$value->month][12]->month == $value->month)Rp. {{ number_format($bayar[$value->month][12]->totalamount, 0, ',', '.') }}@else @endif</td>
            <td>%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif


@if(Request::segment(1)=="lamaharisttbygroup" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-responsive table-sm table-bordered" id="html_table" width="100%">
    <thead style="background-color: grey; color : #ffff" >
       <tr>
            <th class="text-center" rowspan=2>BL</th>
            <th class="text-center" rowspan=2>TH</th>
            <th class="text-center" colspan=3 class="text-center">Jumlah</th>
            <th class="text-center" colspan=3 class="text-center">Rata - Rata Hari</th>
            <th class="text-center" rowspan=2>Omset</th>
            <th class="text-center" rowspan=2>Bayar</th>
            <th class="text-center" rowspan=2>Piutang</th>
            <th class="text-center" rowspan=2>Bayar %</th>
       </tr>
       <tr>
            <th class="text-center">DM</th>
            <th class="text-center">STT</th>
            <th class="text-center">Blm Bayar</th>
            <th class="text-center">Kemb</th>
            <th class="text-center">Inv</th>
            <th class="text-center">Bayar</th>
       </tr>
    </thead>
    <tbody>
        @php
            $total_dm = 0;
            $total_stt = 0;
            $total_omset = 0;
            $total_bayar = 0;
            $blm_bayar = 0;
            $total_piutang = 0;
        @endphp
        @foreach($data as $key => $value)
            <tr class="text-center">
                <td>@if(isset($value->month)){{$value->month}}@endif</td>
                <td></td>
                <td>@if(isset($value->total_dm)){{$value->total_dm}}@endif</td>
                <td>@if(isset($value->total_stt)){{$value->total_stt}}@endif</td>
                <td>@if(isset($value->stt_bayar)){{$value->total_stt-$value->stt_bayar}}@endif</td>
                <td></td>
                <td></td>
                <td></td>
                <td>@if(isset($value->total_pend))Rp. {{ number_format($value->total_pend, 0, ',', '.') }}@endif</td>                
                <td>@if(isset($value->total_bayar))Rp. {{ number_format($value->total_bayar, 0, ',', '.') }}@endif</td>                
                <td>@if(isset($value->total_bayar))Rp. {{ number_format($value->total_pend-$value->total_bayar, 0, ',', '.') }}@endif</td>
                <td>@if(isset($value->total_bayar)){{round($value->total_bayar/$value->total_pend*100,2)}}@endif</td>
            </tr>
            @php
            $total_dm += $value->total_dm;
            $total_stt += $value->total_stt;
            $total_omset += $value->total_pend;
            $total_bayar += $value->total_bayar;
            $blm_bayar += ($value->total_stt-$value->stt_bayar);
            $total_piutang += ($value->total_pend-$value->total_bayar);
        @endphp
        @endforeach
            <tr class="text-center" style="background-color: grey; color : #ffff">
                <td colspan=2 >Total</td>
                <td>{{$total_dm}}</td>
                <td>{{$total_stt}}</td>
                <td>{{$blm_bayar}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_piutang, 0, ',', '.') }}</td>
                <td></td>
            </tr>
    </tbody>
</table>
@endif
@endsection