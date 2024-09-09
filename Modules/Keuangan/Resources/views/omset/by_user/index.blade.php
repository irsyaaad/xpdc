@extends('template.document2')

@section('data')

@if(Request::segment(1)=="sttbyusers" && (Request::segment(2)==null or Request::segment(2)=="filter"))
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
                <th>No Stt</th>
                <th>Tanggal Masuk</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Penerima</th>
                <th>Tipe Kirim</th>
                <th>No Bukti Pembayaran</th>
                <th>Total</th>
                <th>Piutang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $key => $value)
                @if(isset($data[$value->id_user]))
                    <tr class="tr-bold">
                        <td colspan="10" style="background-color: #e3e5e8;"><b>{{ strtoupper($value->nm_user) }}</b></td>
                    </tr>
                    @php
                        $count = 0;
                        $omset = 0;
                        $piutang = 0;
                    @endphp
                    @foreach($data[$value->id_user] as $key2 => $value2)
                    <tr>
                        <td>{{$count+=1}}</td>
                        <td>@if(isset($value2->kode_stt)){{$value2->kode_stt}}@endif</td>
                        <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                        <td>@if(isset($value2->id_plgn)){{$value2->id_plgn}}@endif</td>
                        <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                        <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                        <td>{{ isset($value2->nm_tipe_kirim)? $value2->nm_tipe_kirim : '' }}</td>
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
                        <td colspan="8" class="text-center">Sub Total Omset by {{$value->nm_user}}</td>
                        <td> {{ number_format($omset, 0, ',', '.') }}</td>
                        <td> {{ number_format($piutang, 0, ',', '.') }}</td>
                    </tr>
                @else
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection