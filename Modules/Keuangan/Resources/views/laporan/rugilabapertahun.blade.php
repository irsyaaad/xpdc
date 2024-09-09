@extends('template.document2')

@section('data')
@if(Request::segment(1)=="rugilabapertahun" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")

<table class="table table-bordered table-sm ">
    <thead style="background-color: grey; color : #ffff">
        <th class="text-center">Keterangan</th>
        <th class="text-center">Jan</th>
        <th class="text-center">Feb</th>
        <th class="text-center">Mar</th>
        <th class="text-center">Apr</th>
        <th class="text-center">Mei</th>
        <th class="text-center">Jun</th>
        <th class="text-center">Jul</th>
        <th class="text-center">Agu</th>
        <th class="text-center">Sep</th>
        <th class="text-center">Okt</th>
        <th class="text-center">Nov</th>
        <th class="text-center">Des</th>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>

@endif
@endsection