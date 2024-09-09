@extends('template.document2')
@section('data')
@include("template.filter2")

@php
    $pembagi_nilai = 1;
    $presisi_desimal = 0;
    $total_rugilaba   = 0;
@endphp
<div class="col text-center mb-3">
    <h4>RUGI LABA KONSOLIDASI</h4>
    <h5>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</h5>
    {{-- dalam ribuan  rupiah --}}
</div>
<table class="table table-borderless table-sm ">
    <thead>
        <th>Nama Account</th>
        @foreach ($perush as $key => $value)
            <th class="text-right">{{ $value->nm_perush }}</th>
        @endforeach
        <th class="text-right">Total</th>
    </thead>
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
            <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            <tr>
                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                @php $sub_total = 0; @endphp
                                @foreach ($perush as $key => $value)
                                    @if (isset($data[$value->id_perush]))
                                        @if(isset($data[$value->id_perush][$value3->id_ac]))
                                            <td class="text-right">{{ ($value3->tipe == "D" && $data[$value->id_perush][$value3->id_ac] != 0) ? "-":"" }}{{ number_format($data[$value->id_perush][$value3->id_ac] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                                            @php
                                                // if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                if ($value3->tipe == "D") {
                                                    $total_pendapatan[$value->id_perush]-=$data[$value->id_perush][$value3->id_ac];
                                                    $sub_total -= $data[$value->id_perush][$value3->id_ac];
                                                }else{
                                                    $total_pendapatan[$value->id_perush]+=$data[$value->id_perush][$value3->id_ac];
                                                    $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                }
                                                
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                                <td class="text-right"> {{ number_format($sub_total / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td class="text-center">Sub Total PENDAPATAN</td>
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($total_pendapatan[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php $totalnya += $total_pendapatan[$value->id_perush] @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                </tr>
            @endif
        @endif
    @endforeach

    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
            <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    @if(isset($data3[$value2->id_ac]))
                    @php $temp = $total; @endphp
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            <tr>
                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                @php $sub_total = 0; @endphp
                                @foreach ($perush as $key => $value)
                                    @isset($data[$value->id_perush])
                                        @if(isset($data[$value->id_perush][$value3->id_ac]))
                                            <td class="text-right"> {{ number_format($data[$value->id_perush][$value3->id_ac] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                                            @php
                                                if ($value3->tipe == "K") {
                                                    $temp[$value->id_perush]+=$data[$value->id_perush][$value3->id_ac];
                                                    // $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                } else {
                                                    $temp[$value->id_perush]-=$data[$value->id_perush][$value3->id_ac];
                                                    // $sub_total -= $data[$value->id_perush][$value3->id_ac];
                                                }
                                                $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                
                                            @endphp
                                        @endif
                                    @endisset
                                @endforeach
                                <td class="text-right"> {{ number_format($sub_total / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($temp[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php 
                            $total_pendapatan[$value->id_perush] += $temp[$value->id_perush];
                            // $totalnya += $total[$value->id_perush];
                            $totalnya += $temp[$value->id_perush];
                        @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    @if ($value2->id_ac == 50)
                        <td class="text-center"> LABA KOTOR </td>
                    @elseif ($value2->id_ac == 51)
                        <td class="text-center"> LABA OPERASIONAL </td>
                    @elseif ($value2->id_ac == 52)
                        <td class="text-center"> LABA SETELAH POKOK DAN BUNGA </td>
                    @elseif ($value2->id_ac == 53)
                        <td class="text-center"> LABA SETELAH PENDAPATAN DAN BIAYA LAIN-LAIN </td>
                    @elseif ($value2->id_ac == 54)
                        <td class="text-center"> LABA SETELAH PAJAK </td>
                    @endif
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($total_pendapatan[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php 
                            $totalnya += $total_pendapatan[$value->id_perush];
                        @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                    </tr>
                @endforeach

            @endif
        @endif
    @endforeach

</table>

@endsection
