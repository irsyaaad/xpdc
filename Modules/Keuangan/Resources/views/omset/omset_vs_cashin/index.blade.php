@extends('template.document')

@section('data')
@include("template.filter2")
<style>
    th{
        text-align: center;
        padding: 5px 20px 5px 20px !important;
        vertical-align: center;
    }
</style>
<div class="table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
    <table class="table table-bordered table-sm" id="html_table" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr height="50px">
                <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Bulan</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Tahun</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Saldo Awal</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Pendapatan Kirim</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Asuransi</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Packing</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Diskon</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Total</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Pembayaran</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">%</th>
                <th colspan="2" class="text-center">Jenis</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Selisih</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Jadi Piut</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">%</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Saldo Akhir</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">%</th>
            </tr>
            <tr>
                <th class="text-center" >Tunai</th>
                <th class="text-center" >Invoice</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 4; $i++)
                    <td class="text-center" >{{ $i }}</td>
                @endfor()
                @foreach(range('a','d') as $v)
                    <td class="text-center" >{{ $v }}</td>
                @endforeach()
                <th class="text-center" >5 <br>(a + b + c - d)</th>
                <th class="text-center" >6</th>
                <th class="text-center" >6/5 * 100</th>
                <th class="text-center" >8</th>
                <th class="text-center" >9</th>
                <th class="text-center" >10 = 6-5</th>
                <th class="text-center" >11 = 5-8</th>
                <th class="text-center" >9/5 * 100</th>
                <th class="text-center" >13 = 1+5-6</th>
                <th class="text-center" >(4-13)/4 * 100</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $saldo_awal = $saldo_awal[0]->total; 
                $total_omset = 0;
                $total_pembayaran = 0;
                $tunai = 0;
                $invoice = 0;
                $selisih = 0;
                $jadi_piut = 0;

                $total_pend_kirim = 0;
                $total_asuransi = 0;
                $total_packing = 0;
                $total_diskon = 0;
                $total_all = 0;

                $total_saldo_awal = 0;
                $total_total_saldo = 0;
            @endphp
            @foreach($data as $key => $value)
                @if($value->bulan != 0)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->bulan }}</td>
                        <td>{{ $value->tahun }}</td>
                        @if ($key == 0)
                            @php $total_saldo = $saldo_awal; @endphp
                            <td class="text-center" >
                                <a href="{{ route('show-saldo-awal-piutang', [
                                            'sp_tgl' => $value->tahun."-".sprintf("%02d", $value->bulan-1)."-30",
                                        ])
                                        }}" style="color:black;"> {{number_format($total_saldo, 0, ',', '.')}}
                                </a>
                            </td>
                        @else
                            @php $total_saldo = $saldo_awal; @endphp
                            <td class="text-center" >
                                <a href="{{ route('show-saldo-awal-piutang', [
                                            'sp_tgl' => $value->tahun."-".sprintf("%02d", $value->bulan-1)."-30",
                                        ])
                                        }}" style="color:black;"> {{number_format($total_saldo, 0, ',', '.')}}
                                </a>
                            </td>
                        @endif
                            @php
                             $saldo_awal += $value->total - $value->bayar
                            @endphp
                        <td class="text-right" > {{number_format($value->pend_kirim, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->asuransi, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->packing, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->diskon, 0, ',', '.')}}</td>
                        <td class="text-right" >
                            <a href="{{ route('show-total-omset', [
                                        'dr_tgl' => date($value->tahun."-".sprintf("%02d", $value->bulan).'-01'),
                                        'sp_tgl' => date($value->tahun."-".sprintf("%02d", $value->bulan).'-t'),
                                    ])
                                    }}" style="color:black;"> {{number_format($value->total, 0, ',', '.')}}
                            </a>
                        </td>
                        <td class="text-right" > {{number_format($value->bayar, 0, ',', '.')}}</td>
                        <td class="text-right" > {{round((($value->bayar)/$value->total) * 100,2)}} %</td>
                        <td class="text-right" > {{number_format($value->tunai, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->invoice, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->bayar - $value->total, 0, ',', '.')}}</td>
                        <td class="text-right" > {{number_format($value->total - $value->tunai, 0, ',', '.')}}</td>
                        <td class="text-right" >{{round((($value->invoice)/$value->total) * 100,2)}} %</td>
                        <td class="text-right" > {{number_format($saldo_awal, 0, ',', '.')}}</td>
                        @php
                            $total_pembayaran += $value->bayar;
                            $tunai += $value->tunai;
                            $invoice += $value->invoice;
                            $selisih += $value->bayar - $value->total;
                            $jadi_piut += $value->omset - $value->tunai;

                            $total_pend_kirim += $value->pend_kirim;
                            $total_asuransi += $value->asuransi;
                            $total_packing += $value->packing;
                            $total_diskon += $value->diskon;
                            $total_all += $value->total;

                            $total_saldo_awal += $saldo_awal;
                            $total_total_saldo += $total_saldo;
                        @endphp
                        @if($total_saldo == 0)
                            <td class="text-right" > 0 %</td>
                        @else
                            <td class="text-right" >{{round((($total_saldo - ($saldo_awal + $value->total - $value->bayar)) / $total_saldo ) * 100,2)}} %</td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: rgb(221, 218, 218)">
                <td colspan="4" class="text-center">Total</td>
                <td class="text-right" >{{ toNumber($total_pend_kirim) }}</td>
                <td class="text-right" >{{ toNumber($total_asuransi) }}</td>
                <td class="text-right" >{{ toNumber($total_packing) }}</td>
                <td class="text-right" >{{ toNumber($total_diskon) }}</td>
                <td class="text-right" >{{ toNumber($total_all) }}</td>
                <td class="text-right" >{{ toNumber($total_pembayaran) }}</td>
                <td class="text-right" >{{ ($total_pembayaran && $total_all > 0) ? round(( $total_pembayaran/$total_all ) * 100,2) : 0 }} % </td>
                <td class="text-right" >{{ toNumber($tunai) }}</td>
                <td class="text-right" >{{ toNumber($invoice) }}</td>
                <td class="text-right" >{{ toNumber($selisih) }}</td>
                <td class="text-right" >{{ toNumber($jadi_piut) }}</td>
                <td class="text-right" >{{ round(( $jadi_piut/$total_all ) * 100,2) }} %</td>
                <td></td>
                <td class="text-right" >{{ round(( ($total_total_saldo-$total_saldo_awal)/$total_total_saldo ) * 100,2) }} %</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
