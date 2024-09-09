@extends('template.document2')
@section('data')
<form method="GET" action="{{ url("neraca") }}" enctype="multipart/form-data" id="form-select">
    @include('filter.filter-neraca')
    @csrf
    @php 
    $total_aktiva = 0;
    $url = $filter["show"];
    @endphp
    <div class="row mt-2" >
        <div class="col">
            <table class="table table-borderless table-sm ">
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
                        <td style="padding-left:50px">
                        <a href="{{ $url."&id_ac=".$value3->id_ac }}" class="text-dark">{{$value3->nama}}</a></td>
                            @if(isset($nilai[$value3->id_ac]))
                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                            @php
                            $total_aktiva+=$nilai[$value3->id_ac];
                            @endphp
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
                
                @endif
                @endif
                @endforeach
            </table>
            <hr>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Total Aktiva</td>
                    <td style="text-align:right"> {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        @php $total_pasiva = 0;  @endphp
        <div class="col">
            <table class="table table-borderless table-sm ">
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
                        <td style="padding-left:50px"><a href="{{ $url."&id_ac=".$value3->id_ac }}" class="text-dark">{{$value3->nama}}</a></td>
                            @if(isset($nilai[$value3->id_ac]))
                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                            @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
                @endif
                @endforeach
                @foreach($data1 as $key => $value)
                @if($value->id_ac == 3)
                <tr><td>{{$value->nama}}</td></tr>
                @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                <tr>
                    @if(isset($data3[$value2->id_ac]))
                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                    <tr>
                        <td style="padding-left:50px"><a href="{{ route('showneraca', [
                            'id_ac' => $value3->id_ac, 
                            'dr_tgl' => $filter['dr_tgl'], 
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:black;">{{$value3->nama}}</a></td>
                            @if(isset($lababerjalan) and $value3->id_ac == 321)
                            <td class="text-right"> {{ number_format($lababerjalan, 0, ',', '.') }}</td>
                            @php $total_pasiva+=$lababerjalan @endphp
                            @elseif(isset($nilai[$value3->id_ac]))
                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                            @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                            @endif
                        </tr>
                        @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
                @endif
                @endforeach
            </table>
            <br><br>
            <hr>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Total Pasiva</td>
                    <td style="text-align:right"> {{ number_format($total_pasiva, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    
    @if(isset($filter["dr_tgl"]))
    $("#dr_tgl").val('{{ $filter["dr_tgl"] }}');
    @endif

    @if(isset($filter["sp_tgl"]))
    $("#sp_tgl").val('{{ $filter["sp_tgl"] }}');
    @endif
</script>
@endsection