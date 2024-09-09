@extends('template.document2')

@section('data')
@if(Request::segment(1)=="rugilabaproyeksi" && (Request::segment(2)==null or Request::segment(2)=="filter"))

<table class="table table-sm table-bordered">
    <thead>
        <th rowspan=3 class="text-center"></th>
        <th colspan=2 class="text-center">Realisasi Sebelum</th>
        <th colspan=2 class="text-center">Proyeksi</th>
        <th colspan=2 class="text-center">Realisasi</th>
        <th colspan=4 class="text-center">Rasio</th>
        <tr>
            <th colspan=2 class="text-center">Bulan</th>
            <th colspan=2 class="text-center">Bulan</th>
            <th colspan=2 class="text-center">Bulan</th>
            <th colspan=2 class="text-center">Pencapaian</th>
            <th colspan=2 class="text-center">Pertumbuhan</th>
        </tr>
        <tr>
            <th class="text-center">A</th>
            <th class="text-center">%</th>
            <th class="text-center">B</th>
            <th class="text-center">%</th>
            <th class="text-center">C</th>
            <th class="text-center">%</th>
            <th class="text-center">C - B</th>
            <th class="text-center">%</th>
            <th class="text-center">C - A</th>
            <th class="text-center">%</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td><p>{{$value2->nama}}</p></td>
                        @php
                        $total_sebelum = 0;
                        $total_proyeksi = 0;
                        $total_realisasi = 0;
                        $total_pencapaian = 0;
                        $total_pertumbuhan = 0;
                        @endphp
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            <tr>
                                <td><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($sebelum[$value3->id_ac]))
                                    <td>Rp. {{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_sebelum+=$sebelum[$value3->id_ac]; @endphp
                                    @endif
                                    <td>tes 2</td>
                                    @if(isset($proyeksi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_proyeksi+=$proyeksi[$value3->id_ac]; @endphp
                                    @else
                                    <td>0</td>
                                    @endif 
                                    <td>tes 4</td> 
                                    @if(isset($realisasi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_realisasi+=$realisasi[$value3->id_ac]; @endphp
                                    @endif 
                                    <td>tes 6</td> 
                                    @if(isset($proyeksi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac]-$proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_pencapaian+=($realisasi[$value3->id_ac]-$proyeksi[$value3->id_ac]); @endphp
                                    @else
                                    <td>0</td>
                                    @endif 
                                    <td>8</td> 
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac]-$sebelum[$value3->id_ac], 0, ',', '.') }}</td> 
                                    @php $total_pertumbuhan+=($realisasi[$value3->id_ac]-$sebelum[$value3->id_ac]); @endphp
                                    <td>10</td>                             
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                    <td colspan=2>Rp. {{ number_format($total_sebelum, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_proyeksi, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_realisasi, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>                 
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
                        @php
                        $total_sebelum = 0;
                        $total_proyeksi = 0;
                        $total_realisasi = 0;
                        $total_pencapaian = 0;
                        $total_pertumbuhan = 0;
                        @endphp
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td>
                                    @if(isset($sebelum[$value3->id_ac]))
                                    <td>Rp. {{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_sebelum+=$sebelum[$value3->id_ac]; @endphp
                                    @endif
                                    <td>tes 2</td>
                                    @if(isset($proyeksi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_proyeksi+=$proyeksi[$value3->id_ac]; @endphp
                                    @else
                                    <td>0</td>
                                    @endif 
                                    <td>tes 4</td> 
                                    @if(isset($realisasi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_realisasi+=$realisasi[$value3->id_ac]; @endphp
                                    @endif 
                                    <td>tes 6</td> 
                                    @if(isset($proyeksi[$value3->id_ac]))
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac]-$proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                    @php $total_pencapaian+=($realisasi[$value3->id_ac]-$proyeksi[$value3->id_ac]); @endphp
                                    @else
                                    <td>0</td>
                                    @endif 
                                    <td>8</td> 
                                    <td>Rp. {{ number_format($realisasi[$value3->id_ac]-$sebelum[$value3->id_ac], 0, ',', '.') }}</td> 
                                    @php $total_pertumbuhan+=($realisasi[$value3->id_ac]-$sebelum[$value3->id_ac]); @endphp
                                    <td>10</td>                             
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                    <td colspan=2>Rp. {{ number_format($total_sebelum, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_proyeksi, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_realisasi, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                    <td colspan=2>Rp. {{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>                 
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>

@endif
@endsection