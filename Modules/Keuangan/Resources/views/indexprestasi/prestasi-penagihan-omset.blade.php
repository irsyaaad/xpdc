@extends('template.document2')

@section('data')
@if(Request::segment(1)=="prestasipenagihanomset" and Request::segment(2)==null)
@include("filter.filter-".Request::segment(1))
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
    td{
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>

<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <br><br>
    <table class="table table-responsive table-bordered table-sm" id="html_table" width="100%">
        <thead style="background-color: grey; color : #ffff">
        <tr>
            <th rowspan="2">BL</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">Omset</th>
            @for ($i = 1; $i <= 12; $i++)
                <th colspan="2">{{ $i }}-{{$tahun}}</th>
            @endfor
        </tr>
        <tr>
            @for ($i = 0; $i < 12; $i++)
                <th>Bayar</th>
                <th>%</th>
            @endfor
        </tr>
        </thead>
        <tbody>
            @foreach($omset as $key => $value)
            <tr>
                <td>{{ $value->bulan_stt }}</td>
                <td>{{ $value->tahun_stt }}</td>
                <td class="text-right">{{ toNumber($value->omset) }}</td>
                @for ($i = 1; $i <= 12; $i++)
                    <td class="text-right">
                        @if (isset($bayar[$value->bulan_stt][$i]))
                            {{ toNumber($bayar[$value->bulan_stt][$i]->bayar) }}
                        @else
                            0
                        @endif
                    </td>
                    <td class="text-right">
                        {{ (isset($bayar[$value->bulan_stt][$i]->bayar) && $bayar[$value->bulan_stt][$i]->bayar > 0 && ($value->omset > 0)) ? round(($bayar[$value->bulan_stt][$i]->bayar / ($value->omset)) * 100,2) : 0 }} %
                    </td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection