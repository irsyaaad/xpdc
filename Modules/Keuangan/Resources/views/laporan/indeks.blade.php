@extends('template.document2')

@section('data')
@if(Request::segment(1)=="neraca" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<div class="row">
<div class="col">
<table class="table table-borderless table-sm ">
@php $total_aktiva=0; @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 1)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td>Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php $total_aktiva+=$nilai[$value2->id_ac] @endphp
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
</table>
<br><br><br><br>
<hr>
<table class="table table-borderless table-sm">
<tr>
<td>Total Aktiva</td>
<td style="text-align:center">Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
</tr>
</table>
</div>
<div class="col">
<table class="table table-borderless table-sm ">
@php $total_pasiva=0; @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 2)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td>Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php $total_pasiva+=$nilai[$value2->id_ac] @endphp
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
    <!-- Modal -->
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 3)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>
                                            @if($value3->id_ac == '321')
                                                Rp. {{ number_format($lababerjalan, 0, ',', '.') }}
                                            @else
                                                Rp. {{ number_format($total, 0, ',', '.') }}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td>Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php $total_pasiva+=$nilai[$value2->id_ac] @endphp
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
</table>
<hr>
<table class="table table-borderless table-sm">
<tr>
<td>Total Pasiva</td>
@php $total_pasiva+=$lababerjalan; @endphp
<td style="text-align:center">Rp. {{ number_format($total_pasiva, 0, ',', '.') }}</td>
</tr>
</table>
</div>
</div>
<br><br><br>
<hr>
<div class="text-center">
    <h5>NERACA SALDO</h5>
<br>
<table class="table table-sm">
    <thead style="background-color: grey; color : #ffff">
        <th>Keterangan</th>
        <th>Saldo Awal</th>
        <th>Debet</th>
        <th>Kredit</th>
        <th>Jumlah</th>
        <th>Total</th>
    </thead>
    <tbody class="text-left">
    @php 
    $aktiva_jumlah = 0; 
    $aktiva_debet = 0;
    $aktiva_kredit = 0;
    $sa_jumlah = 0;
    @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 1)
        <tr ><td colspan=6><p style="padding:10px">{{$value->nama}}</p></td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=6><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            @php   
                                    $temp  = 0;
                                    $total = 0;
                                    $deb = 0;
                                    $kre = 0; 
                            @endphp
                                <tr>
                                <td >{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                                $deb+=$value4->debit;
                                                $kre+=$value4->kredit;
                                                if(isset($sa[$value4->ac_perush])){
                                                    $temp+=$sa[$value4->ac_perush];
                                                }                                                                                         
                                            @endphp
                                        @endforeach
                                    @endif
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                @if(isset($sa_debit[$value3->id_ac]))
                                <td>Rp. {{ number_format($deb-$sa_debit[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($deb, 0, ',', '.') }}</td>
                                @endif

                                @if(isset($sa_kredit[$value3->id_ac]))
                                <td>Rp. {{ number_format($kre-$sa_kredit[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($kre, 0, ',', '.') }}</td>
                                @endif

                                @if(isset($sa_total[$value3->id_ac]))
                                <td>Rp. {{ number_format($total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp+$total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                @endif
                                
                                
                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr style="background-color: grey; color : #ffff">
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    <td>Rp. {{ number_format($saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php 
                $aktiva_jumlah+=$nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac];
                $aktiva_debet+=$debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac];
                $aktiva_kredit+=$kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac];
                $sa_jumlah+=$saldo_awal[$value2->id_ac];
                @endphp
                @endforeach
            @endif
        @endif
    @endforeach
    <tr style="background-color: grey; color : #ffff">
    <td><p style="padding:10px"><b>AKTIVA</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_debet, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_kredit, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah+$aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    </tr>
    </tbody>
</table>
<!-- Pasiva -->
<br><br>
<table class="table table-sm">
    <thead style="background-color: grey; color : #ffff">
        <th>Keterangan</th>
        <th>Saldo Awal</th>
        <th>Debet</th>
        <th>Kredit</th>
        <th>Jumlah</th>
        <th>Total</th>
    </thead>
    <tbody class="text-left">
    @php 
    $aktiva_jumlah = 0; 
    $aktiva_debet = 0;
    $aktiva_kredit = 0;
    $sa_jumlah = 0;
    @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 2)
        <tr ><td colspan=6><p style="padding:10px">{{$value->nama}}</p></td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=6><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td >{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $deb = 0;
                                        $kre = 0;
                                        $temp  = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                                $deb+=$value4->debit;
                                                $kre+=$value4->kredit;
                                                $temp+=$sa[$value4->ac_perush];
                                            @endphp
                                        @endforeach
                                    @endif
                                    <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                @if(isset($sa_debit[$value3->id_ac]))
                                <td>Rp. {{ number_format($deb-$sa_debit[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($deb, 0, ',', '.') }}</td>
                                @endif

                                @if(isset($sa_kredit[$value3->id_ac]))
                                <td>Rp. {{ number_format($kre-$sa_kredit[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($kre, 0, ',', '.') }}</td>
                                @endif

                                @if(isset($sa_total[$value3->id_ac]))
                                <td>Rp. {{ number_format($total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp+$total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                @else
                                <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr style="background-color: grey; color : #ffff">
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    <td>Rp. {{ number_format($saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php 
                $aktiva_jumlah+=$nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac];
                $aktiva_debet+=$debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac];
                $aktiva_kredit+=$kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac];
                $sa_jumlah+=$saldo_awal[$value2->id_ac];
                @endphp
                @endforeach
            @endif
        @endif
    @endforeach
    
    <tr style="background-color: grey; color : #ffff">
    <td><p style="padding:10px"><b>PASIVA</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_debet, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_kredit, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah+$aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    </tr>
    </tbody>
</table>
</div>
@endif
@if(Request::segment(1)=="rugilaba" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-borderless table-sm ">
@php $total_aktiva=0; @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <!-- <td style="padding-left:10px"><p>{{$value2->nama}}</p></td> -->
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td><p></p></td></tr>
                @endforeach
                <tr>
                    @php
                        $total_pendapatan = 0;
                     if(isset($nilai[40]) and isset($nilai[41])){
                         $total_pendapatan = $nilai[40]-$nilai[41];
                     }
                    @endphp
                    <td class="text-center">Sub Total PENDAPATAN</td>
                    <td>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                    @php $total_aktiva=$total_pendapatan @endphp
                </tr>
            @endif
        @endif
    @endforeach

    <!-- Biaya -->
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td class="text-center">Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                </tr>
                <tr>
                <td class="text-center">
                @if($value2->id_ac == 500)
                LABA KOTOR
                @elseif($value2->id_ac == 501)
                LABA OPERASIONAL
                @elseif($value2->id_ac == 502)
                LABA SETELAH POKOK DAN BUNGA
                @elseif($value2->id_ac == 503)
                LABA SEBELUM PAJAK
                @else
                LABA SETELAH PAJAK
                @endif
                </td>
                @php 
                if($value2->id_ac != 53){
                    $total_aktiva-=$nilai[$value2->id_ac];
                }else{
                    $total_aktiva+=$nilai[$value2->id_ac];
                }                 
                @endphp
                <td>Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
</table>


@endif
@endsection