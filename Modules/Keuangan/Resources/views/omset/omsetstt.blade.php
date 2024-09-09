@extends('template.document2')

@section('data')

@if(Request::segment(1)=="sttbycarabayar" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("filter.filter-".Request::segment(1))
<style>
    th {
        text-align: center;
    }
</style>
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    <table class="table table-responsive table-sm table-bordered" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kode Stt</th>
                <th>Tanggal Masuk</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Penerima</th>
                <th>No Bukti Pembayaran</th>
                <th>Total</th>
                <th>Piutang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carabayar as $key => $value)
                @if(isset($data[$value->id_cr_byr_o]))
                    <tr class="tr-bold">
                        <td colspan="9" style="background-color: #e3e5e8;"><b>{{ strtoupper($value->nm_cr_byr_o) }}</b></td>
                    </tr>
                    @php
                        $count = 0;
                        $omset = 0;
                        $piutang = 0;
                    @endphp
                    @foreach($data[$value->id_cr_byr_o] as $key2 => $value2)
                    <tr>
                        <td>{{$count+=1}}</td>
                        <td>@if(isset($value2->kode_stt)){{$value2->kode_stt}}@endif</td>
                        <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                        <td>@if(isset($value2->id_plgn)){{$value2->id_plgn}}@endif</td>
                        <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                        <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                        <td><?= !empty($value2->id_order_pay)? $value2->id_order_pay : '-' ?></td>
                        <td class="text-right">@if(isset($value2->c_total)) {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>
                        <td class="text-right">@if(isset($value2->piutang)) {{ number_format($value2->piutang, 0, ',', '.') }}@endif</td>
                        @php
                            $omset+=$value2->c_total;
                            $piutang+=$value2->piutang;
                        @endphp
                    </tr>
                    @endforeach
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                        <td colspan="7" class="text-center">Sub Total Omset by {{$value->nm_cr_byr_o}}</td>
                        <td class="text-right"> {{ number_format($omset, 0, ',', '.') }}</td>
                        <td class="text-right"> {{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>    
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if(Request::segment(1)=="sttbydm" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>No Stt</th>
            <th>Tanggal Masuk</th>
            <th>Nama Pelanggan</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Total</th>
            <th>Berangkat</th>
		</tr>
    </thead>
    <tbody>
        @foreach($dm as $key => $value)
            @if(isset($data[$value->id_dm]))
                <tr>
                    <td colspan="9" style="background-color: #e3e5e8;">{{$value->id_dm}}</td>
                </tr>
                @php
                    $count = 0;
                    $omset = 0;
                    $piutang = 0;
                @endphp
                @foreach($data[$value->id_dm] as $key2 => $value2)
                <tr>
                    <td>{{$count+=1}}</td>
                    <td>@if(isset($value2->id_stt)){{$value2->id_stt}}@endif</td>
                    <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                    <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                    <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                    <td>@if(isset($value2->n_berat)){{$value2->n_berat}}@endif</td>
                    <td>@if(isset($value2->n_volume)){{$value2->n_berat}}@endif</td>
                    <td>@if(isset($value2->c_total)) {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>
                    <td>@if(isset($value2->tgl_berangkat)){{ daydate($value2->tgl_berangkat).", ".dateindo($value2->tgl_berangkat) }}@endif</td>
                    @php
                        $omset+=$value2->c_total;
                    @endphp
                </tr>
                @endforeach
                <tr >
                    <td colspan="8" class="text-center">Sub Total Omset by {{$value->nm_user}}</td>
                    <td> {{ number_format($omset, 0, ',', '.') }}</td>
                </tr>
            @else

            @endif
        @endforeach
    </tbody>
</table>
@endif

@if(Request::segment(1)=="omsetbypelanggan" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>ID Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Mei</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Okt</th>
            <th>Nov</th>
            <th>Des</th>
		</tr>
    </thead>
    <tbody>
        @foreach($grouppelanggan as $key => $value)
           @if(isset($pelanggan[$value->id_plgn_group]))
                <tr>
                    <td colspan="15">{{$value->nm_group}}</td>
                </tr>
                @foreach($pelanggan[$value->id_plgn_group] as $key2 => $value2)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$value2->nm_pelanggan}}</td>
                        <td>@if(isset($data[$value2->id_pelanggan][1])) {{ number_format($data[$value2->id_pelanggan][1]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][2])) {{ number_format($data[$value2->id_pelanggan][2]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][3])) {{ number_format($data[$value2->id_pelanggan][3]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][4])) {{ number_format($data[$value2->id_pelanggan][4]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][5])) {{ number_format($data[$value2->id_pelanggan][5]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][6])) {{ number_format($data[$value2->id_pelanggan][6]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][7])) {{ number_format($data[$value2->id_pelanggan][7]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][8])) {{ number_format($data[$value2->id_pelanggan][8]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][9])) {{ number_format($data[$value2->id_pelanggan][9]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][10])) {{ number_format($data[$value2->id_pelanggan][10]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][11])) {{ number_format($data[$value2->id_pelanggan][11]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][12])) {{ number_format($data[$value2->id_pelanggan][12]->totalamount, 0, ',', '.') }}@else @endif</td>
                    </tr>
                @endforeach
           @endif
        @endforeach
    </tbody>
</table>
@endif

@if(Request::segment(1)=="bygrouppelanggan" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th rowspan="2">No</th>
            <th rowspan="2">ID Group</th>
            <th rowspan="2">Nama Group</th>
            <th colspan="2" class="text-center">Keseluruhan</th>
            <th colspan="2" class="text-center">Sudah Tiba</th>
		</tr>
        <tr >
            <th class="text-center">Koli</th>
            <th class="text-center">Total</th>
            <th class="text-center">Koli</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($wilayah as $key => $value)
            <tr>
                <td colspan="5">{{$value}}</td>
            </tr>
            @if(isset($data[$value]))
                @foreach($data[$value] as $key2 => $value2)
                    <tr>
                        <td></td>
                        <td>@if(isset($value2->id_plgn_group)){{$value2->id_plgn_group}}@endif</td>
                        <td>@if(isset($value2->nm_group)){{$value2->nm_group}}@endif</td>
                        <td class="text-center">@if(isset($value2->total)) {{ number_format($value2->total, 0, ',', '.') }}@endif</td>
                        <td class="text-center">@if(isset($value2->koli)){{$value2->koli}}@endif</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
@endif

@if(Request::segment(1)=="omsetbycarabayar" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-responsive table-bordered table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th rowspan=2 class="text-center">ID Perush</th>
            <th rowspan=2 class="text-center">Bulan</th>
            <th rowspan=2 class="text-center">Tahun</th>
            <th rowspan=2 class="text-center">Omset</th>
            <th rowspan=2 class="text-center">Bayar</th>
            <th rowspan=2 class="text-center">Piutang</th>
            <th colspan=7 class="text-center">Metode Pembayaran</th>
        </tr>
        <tr>
            @foreach($carabayar as $key => $value)
            <th class="text-center">{{strtoupper($value->nm_cr_byr_o)}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $total_omset = 0;
            $total_bayar = 0;
            $total_piutang = 0;

            $total_semua = [];
            foreach ($carabayar as $key => $value) {
                $total_semua[$value->id_cr_byr_o] = 0;
            }

        @endphp
        @for($i=1; $i<=12; $i++)
            @if(isset($kolom[$i]))
                <tr>
                <td>{{Session("perusahaan")["nm_perush"]}}</td>
                <td>{{$i}}</td>
                <td>@if(Session('tahun') != null ){{Session('tahun')}}@else {{date('Y')}} @endif</td>
                <td>@if(isset($omset[$i])) {{ number_format($omset[$i], 0, ',', '.') }}  @endif</td>
                <td>@if(isset($bayar[$i])) {{ number_format($bayar[$i], 0, ',', '.') }}  @endif</td>
                <td>@if(isset($piutang[$i])) {{ number_format($piutang[$i], 0, ',', '.') }}  @endif</td>
                @foreach ($carabayar as $key => $value)
                <td>
                    @if(isset($kolom[$i][$value->id_cr_byr_o])) {{ number_format($kolom[$i][$value->id_cr_byr_o]->bayar, 0, ',', '.') }}
                    @php
                        $total_semua[$value->id_cr_byr_o] += $kolom[$i][$value->id_cr_byr_o]->bayar;
                    @endphp
                    @else
                     0
                    @endif
                </td>
                @endforeach
                </tr>
            @endif
            @php
                $total_omset+=$omset[$i];
                $total_bayar+=$bayar[$i];
                $total_piutang+=$piutang[$i];
            @endphp
        @endfor
        <tr style="background-color: grey; color : #ffff">
            <td class="text-center" colspan=3>TOTAL</td>
            <td> {{ number_format($total_omset, 0, ',', '.') }}</td>
            <td> {{ number_format($total_bayar, 0, ',', '.') }}</td>
            <td> {{ number_format($total_piutang, 0, ',', '.') }}</td>
            @for ($i = 1; $i <= count($total_semua); $i++)
                <td> {{ number_format($total_semua[$i], 0, ',', '.') }}</td>
            @endfor
        </tr>
    </tbody>
</table>
@endif

@endsection
