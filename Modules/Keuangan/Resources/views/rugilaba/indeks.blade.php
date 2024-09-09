@extends('template.document')

@section('data')
<div class="row">
<div class="col">
<table class="table table-borderless table-sm ">
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
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
                                    @endif
                                <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
</table>
</div>
<div class="col">
<table class="table table-borderless table-sm ">
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr><td style="padding-left:10px">{{$value2->nama}}</td>
                    <td><p>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</p></td></tr>
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            <tr><td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                @if(isset($data4[$value3->id_ac]))
                                    @php
                                    $total = 0;
                                    @endphp
                                    @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                        @php
                                            $total+=$value4->total;
                                        @endphp
                                    @endforeach
                                @endif
                            <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif    
                @endforeach
            @endif
        @endif
    @endforeach
</table>
</div>
</div>
@endsection