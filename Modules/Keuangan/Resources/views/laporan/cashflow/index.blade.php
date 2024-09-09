@extends('template.document2')

@section('data')
@include("filter.filter-".Request::segment(1))
<div class="col-md-12 mt-3">
    <table class="table table-borderless table-sm ">
        <tr>
            <td>CASH IN</td>
        </tr>
        @php
            $total_all_cash_in = 0;
        @endphp
        @foreach ($head as $value)
            @if ($value->tipe == '1')
                <tr>
                    <td>{{ $value->nama_cashflow }}</td>
                </tr>
                @php
                    $sub_total = 0;
                @endphp
                @if (isset($child[$value->id_cf]))
                    @foreach ($child[$value->id_cf] as $value2)
                        @php
                            $total_in = 0;
                            $total_out = 0;
                            if (isset($cashflow[$value2->id_cf])) {
                                foreach ($cashflow[$value2->id_cf] as $index) {
                                    if (isset($cashin[$index])) {
                                        foreach ($cashin[$index] as $item) {
                                            $total_in += $item->nominal;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                'id_ac' => $value2->id_cf,
                                'dr_tgl' => $filter['dr_tgl'],
                                'sp_tgl' => $filter['sp_tgl'],
                            ]) }}"
                            style="color:black;">{{ $value2->nama_cashflow }}</a></td>
                            <td class="text-right">{{ toNumber($total_in) }}</td>
                            <td class="text-right">{{ ($total_in && $total_cash_in) > 0 ? round(($total_in/($total_cash_in)) * 100,2) : 0 }} %</td>
                        </tr>
                        @php
                            $sub_total += $total_in;
                        @endphp
                    @endforeach
                @endif
                <tr>
                    <td class="text-center">Sub Total {{ $value->nama_cashflow }}</td>
                    <td class="text-right">{{ toNumber($sub_total) }}</td>
                    <td class="text-right">{{ ($sub_total && $total_cash_in) > 0 ? round(($sub_total/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
                </tr>
                @php
                    $total_all_cash_in += $sub_total;
                @endphp
            @endif
        @endforeach
        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
            <td class="text-center">TOTAL CASH IN</td>
            <td class="text-right">{{ toNumber($total_all_cash_in) }}</td>
            <td class="text-right">{{ ($total_all_cash_in && $total_cash_in) > 0 ? round(($total_all_cash_in/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
        </tr>
        <tr>
            <td>CASH OUT</td>
        </tr>
        @php
            $total_all_cash_out = 0;
        @endphp
        @foreach ($head as $value)
            @if ($value->tipe == '2')
                <tr>
                    <td>{{ $value->nama_cashflow }}</td>
                </tr>
                @php
                    $sub_total = 0;
                @endphp
                @if (isset($child[$value->id_cf]))
                    @foreach ($child[$value->id_cf] as $value2)
                        @php
                            $total_in = 0;
                            $total_out = 0;
                            if (isset($cashflow[$value2->id_cf])) {
                                foreach ($cashflow[$value2->id_cf] as $index) {
                                    if (isset($cashout[$index])) {
                                        foreach ($cashout[$index] as $item) {
                                            $total_in += $item->nominal;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                    'id_ac' => $value2->id_cf,
                                    'dr_tgl' => $filter['dr_tgl'],
                                    'sp_tgl' => $filter['sp_tgl'],
                                ]) }}"
                                style="color:black;">{{ $value2->nama_cashflow }}</a></td>
                            <td class="text-right">{{ toNumber($total_in) }}</td>
                            <td class="text-right">{{ ($total_in && $total_cash_in) > 0 ? round(($total_in/($total_cash_in)) * 100,2) : 0 }} %</td>
                        </tr>
                        @php
                            $sub_total += $total_in;
                        @endphp
                    @endforeach
                @endif
                <tr>
                    <td class="text-center">Sub Total {{ $value->nama_cashflow }}</td>
                    <td class="text-right">{{ toNumber($sub_total) }}</td>
                    <td class="text-right">{{ ($sub_total && $total_cash_in) > 0 ? round(($sub_total/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
                </tr>
                @php
                    $total_all_cash_out += $sub_total;
                @endphp
            @endif
        @endforeach
        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
            <td class="text-center">TOTAL CASH OUT</td>
            <td class="text-right">{{ toNumber($total_all_cash_out) }}</td>
            <td class="text-right">{{ ($total_all_cash_out && $total_cash_in) > 0 ? round(($total_all_cash_out/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
        </tr>
        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
            <td class="text-center">Surplus / Defisit Setelah Investasi</td>
            <td class="text-right">{{ toNumber($total_all_cash_in - $total_all_cash_out) }}</td>
            <td></td>
        </tr>
        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
            <td class="text-center">Saldo Awal</td>
            <td class="text-right">{{ toNumber($saldo_awal) }}</td>
            <td></td>
        </tr>
        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
            <td class="text-center">Saldo Akhir</td>
            <td class="text-right">{{ toNumber($saldo_awal + ($total_all_cash_in - $total_all_cash_out)) }}</td>
            <td></td>
        </tr>
    </table>
</div>
<hr>
<div class="container" style="color: red">
    <h5>CATATAN : !!</h5>
    <h6>
        Transaksi yang tercatat di cashflow hanya transaksi yang sudah di Mapping di Master Cashflow Perush, 
        Jika ada transaksi yang belum masuk dalam kelompok diatas harap di Mapping kan terlebih dahulu
    </h6>
</div>
@endsection