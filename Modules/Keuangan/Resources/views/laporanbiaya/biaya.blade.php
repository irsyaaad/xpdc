@extends('template.document2')

@section('data')

@if(Request::segment(1)=="biayabydm" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("filter.filter-".Request::segment(1))
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-striped" id="biaya-table">
        <thead style="background-color: grey; color : #ffff">
            <th>No</th>
            <th>Kode DM</th>
            <th>Tgl Berangkat</th>
            <th>Cabang Tujuan</th>
            <th>Nama Kapal</th>
            <th>Nama Sopir</th>
            <th>No PLAT</th>
            <th>Biaya</th>
            <th>Bayar</th>
            <th>Sisa</th>
            <th>Tgl DM Dibuat</th>
            <th>ID User</th>
        </thead>
        <tbody>
            @php
                $total_biaya = 0;
                $total_bayar = 0;
                $total_sisa  = 0;
            @endphp
            @foreach($data as $key => $value)
            <tr>
                <td>{{$key+1}}</td>
                <td>@if(isset($value->kode_dm)) <a href="{{ route('showbiayabydm', [
                    'id' => $value->id_dm, 
                    'dr_tgl' => $filter['dr_tgl'], 
                    'sp_tgl' => $filter['sp_tgl'],
                    ]) }}">{{$value->kode_dm}}</a> @endif</td>
                <td>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
                <td>@if(isset($value->nm_perush)){{strtoupper($value->nm_perush)}}@else {{strtoupper($value->nm_ven)}} @endif</td>
                <td>@if(isset($value->nm_kapal_perush)){{$value->nm_kapal_perush}} @else - @endif</td>
                <td>@if(isset($value->nm_sopir)){{$value->nm_sopir}} @else - @endif</td>
                <td>@if(isset($value->no_plat)){{$value->no_plat}} @else - @endif</td>
                <td class="text-right">@if(isset($value->biaya)){{ number_format($value->biaya, 0, ',', '.') }} @else 0 @endif</td>
                <td class="text-right">@if(isset($value->bayar)){{ number_format($value->bayar, 0, ',', '.') }} @else 0 @endif</td>
                <td class="text-right">{{ isset($value->sisa) ? number_format($value->sisa, 0, ',', '.') : '0' }}</td>
                <td>@if(isset($value->created_at)){{$value->created_at}}@endif</td>
                <td>@if(isset($value->nm_user)){{$value->nm_user}}@endif</td>
            </tr>
            @php
                $total_biaya += $value->biaya;
                $total_bayar += $value->bayar;
                $total_sisa  += $value->sisa;
            @endphp
            @endforeach
            <tr style="background-color: grey; color : #ffff">
                <td colspan=7 class="text-center">Total</td>
                <td class="text-right">{{ number_format($total_biaya, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_sisa, 0, ',', '.') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
@endif
@if(Request::segment(1)=="omsetvsbiaya" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<div class="table-responsive">
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
    <thead style="background-color: grey; color : #ffff">
        <th>No</th>
        <th>Kode DM</th>
        <th>Tgl Berangkat</th>
        <th>Nama Vendor</th>
        <th>No SEAL / Container</th>
        <th>Omset</th>
        <th>Biaya</th>
        <th> % </th>
        <th>Laba</th>
        <th> % </th>
    </thead>
    <tbody>
        @php
            $total_omset = 0;
            $total_biaya = 0;
            $total_laba  = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->kode_dm)){{$value->kode_dm}}@endif</td>
            <td>
                @if(isset($value->tgl_berangkat))
                    {{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}
                @endif
            </td>
            <td>
                @if(isset($value->p_perush) and $value->p_perush!=null)
                    {{strtoupper($value->p_perush)}}
                @else 
                    {{strtoupper($value->nm_ven)}} 
                @endif
            </td>
            <td>
                @if(isset($value->no_seal))
                    {{$value->no_seal}}
                @endif
                /
                @if(isset($value->no_container))
                    {{$value->no_container}}
                @endif
            </td>
            <td>@if(isset($value->total_omset))
                    Rp. {{ number_format($value->total_omset, 0, ',', '.') }} 
                @else 
                    Rp. 0 
                @endif
            </td>
            <td>
                @if(isset($value->total_biaya))
                    Rp. {{ number_format($value->total_biaya, 0, ',', '.') }} 
                @else 
                    Rp. 0 
                @endif
            </td>
            <td>
                @if($value->total_biaya > 0)
                    {{round(($value->total_biaya/$value->total_omset) * 100,2)}} %
                @endif
            </td>
            <td>
                @if(isset($value->total_laba))
                    Rp. {{ number_format($value->total_laba, 0, ',', '.') }} 
                @else 
                    Rp. 0 
                @endif
            </td>
            <td>
                @if($value->total_laba > 0)
                    {{round(($value->total_laba/$value->total_omset) * 100,2)}} %
                @endif
            </td>
        </tr>
        @php
            $total_biaya += $value->total_biaya;
            $total_omset += $value->total_omset;
            $total_laba += $value->total_laba;
        @endphp
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td colspan=5 class="text-center">Total</td>
            <td>Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($total_biaya, 0, ',', '.') }}</td>
            <td>
                @if($total_biaya > 0)
                    {{round(($total_biaya/$total_omset) * 100,2)}} %
                @endif
            </td>
            <td>Rp. {{ number_format($total_laba, 0, ',', '.') }}</td>
            <td>
                @if($total_laba > 0)
                    {{round(($total_laba/$total_omset) * 100,2)}} %
                @endif
            </td>
        </tr>
    </tbody>
</table>
</div>
@endif
@endsection

