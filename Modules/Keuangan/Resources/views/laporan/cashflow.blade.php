@extends('template.document2')

@section('data')
    <form method="GET" action="{{ url('cashflow') }}" enctype="multipart/form-data" id="form-select">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label style="font-weight: bold;">
                    Dari Tanggal
                </label>
                <input type="date" class="form-control" name="dr_tgl" id="dr_tgl"
                    value="@if (isset($filter['dr_tgl'])) {{ $filter['dr_tgl'] }} @endif">
            </div>

            <div class="col-md-3">
                <label style="font-weight: bold;">
                    Sampai Tanggal
                </label>
                <input type="date" class="form-control" name="sp_tgl" id="sp_tgl"
                    value="@if (isset($filter['sp_tgl'])) {{ $filter['sp_tgl'] }} @endif">
            </div>

            <div class="col-md-3" style="margin-top: 25px">
                <button type="submit" class="btn btn-md btn-primary" class="btn btn-primary" title="Cari Data">
                    <i class="fa fa-search"></i> Cari
                </button>
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip"
                    data-placement="top" title="Refresh">
                    <i class="fa fa-refresh"></i> Reset
                </a>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Cetak
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="@if (isset($filter['cetak'])) {{ $filter['cetak'] }} @endif" class="dropdown-item">
                            <i class="fa fa-file-pdf-o"></i>Pdf
                        </a>
                        <a href="@if (isset($filter['excel'])) {{ $filter['excel'] }} @endif" class="dropdown-item">
                            <i class="fa fa-print"></i>Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <table class="table table-borderless table-sm ">
                    @php
                        $total_cashin = 0;
                        $total_cashout = 0;
                    @endphp
                    <tr>
                        <td>CASH IN</td>
                    </tr>
                    @foreach ($head as $key => $value)
                        @if ($value->tipe == 1)
                            <tr>
                                <td>{{ $value->nama_cashflow }}</td>
                            </tr>
                            @if (isset($child[$value->id_cf]))
                                @foreach ($child[$value->id_cf] as $key2 => $value2)
                                    <tr>
                                        <td style="padding-left: 100px"><a
                                                href="{{ route('showcashflow', [
                                                    'id_ac' => $value2->id_cf,
                                                    'dr_tgl' => $filter['dr_tgl'],
                                                    'sp_tgl' => $filter['sp_tgl'],
                                                ]) }}"
                                                style="color:black;">{{ $value2->nama_cashflow }}</a>
                                        </td>
                                        <td class="text-right">
                                            @if (isset($total[$value2->id_cf]))
                                                 {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                                @php
                                                    $total_cashin += $total[$value2->id_cf];
                                                @endphp
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if (isset($total[$value2->id_cf]))
                                                {{ ($total[$value2->id_cf] && ($total_cashIn)) > 0 ? round(($total[$value2->id_cf]/($total_cashIn)) * 100,2) : 0 }} %
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                    <tr>
                        <td style="padding-left: 100px">
                            Pemasukan Lain (Pemasukan yang blum di Mapping)
                            <ul>
                                @if (isset($ac_in_belum_mapping))
                                    @foreach ($ac_in_belum_mapping as $key => $value)
                                        <li>{{ $value }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td class="text-right">
                             {{ number_format($total['inlain'], 0, ',', '.') }}
                            @php
                                $total_cashin += $total['inlain'];
                            @endphp
                        </td>
                        <td class="text-right">{{ $total['inlain'] > 0 ? round(($total['inlain']/($total_cashIn)) * 100,2) : 0 }} %</td>
                    </tr>
                    <tr>
                        <td class="text-center">TOTAL CASH IN</td>
                        <td class="text-right"> {{ number_format($total_cashIn, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $total_cashIn > 0 ? round(($total_cashIn/($total_cashIn)) * 100,2) : 0 }} %</td>
                    </tr>
                    <tr>
                        <td>CASH OUT</td>
                    </tr>
                    @foreach ($head as $key => $value)
                        @if ($value->tipe == 2)
                            <tr>
                                <td>{{ $value->nama_cashflow }}</td>
                            </tr>
                            @if (isset($child[$value->id_cf]))
                                @php
                                    $sub_total_ac = 0;
                                @endphp
                                @foreach ($child[$value->id_cf] as $key2 => $value2)
                                    <tr>
                                        <td style="padding-left: 100px"><a
                                                href="{{ route('showcashflow', [
                                                    'id_ac' => $value2->id_cf,
                                                    'dr_tgl' => $filter['dr_tgl'],
                                                    'sp_tgl' => $filter['sp_tgl'],
                                                ]) }}"
                                                style="color:black;">{{ $value2->nama_cashflow }}</a>
                                        </td>
                                        <td class="text-right">
                                            @if (isset($total[$value2->id_cf]))
                                                 {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                                @php
                                                    $total_cashout += $total[$value2->id_cf];
                                                    $sub_total_ac += $total[$value2->id_cf];
                                                @endphp
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if (isset($total[$value2->id_cf]))
                                                {{ $total[$value2->id_cf] > 0 && $total_cashIn > 0 ? round(($total[$value2->id_cf]/($total_cashIn)) * 100,2) : '0' }} %
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center">SUB TOTAL {{ $value->nama_cashflow }}</td>
                                    <td class="text-right">{{ toNumber($sub_total_ac) }}</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    <tr>
                        <td style="padding-left: 100px">
                            Pengeluaran Lain (Pengeluaran yang blum di Mapping)
                            <ul>
                                @if (isset($ac_out_belum_mapping))
                                    @foreach ($ac_out_belum_mapping as $key => $value)
                                        <li>{{ $value }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td class="text-right">
                             {{ number_format($total['outlain'], 0, ',', '.') }}
                            @php
                                $total_cashout += $total['outlain'];
                            @endphp
                        </td>
                        <td class="text-right">{{ $total['outlain'] > 0 ? round(($total['outlain']/($total_cashIn)) * 100,2) : 0 }} %</td>
                    </tr>
                    <tr>
                        <td class="text-center">TOTAL CASH OUT</td>
                        <td class="text-right"> {{ number_format($total_cashOut, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $total_cashOut > 0 ? round(($total_cashOut/($total_cashIn)) * 100,2) : 0 }} %</td>
                    </tr>
                    <tr>
                        <td class="text-center">Surplus / Defisit Setelah Investasi</td>
                        @php
                            $total_akhir = $total_cashIn - $total_cashOut;
                        @endphp
                        <td class="text-right"> {{ number_format($total_akhir - $saldo_awal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Saldo Awal</td>
                        <td class="text-right"> {{ number_format($saldo_awal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Saldo Akhir</td>
                        <td class="text-right"> {{ number_format($total_akhir, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
@endsection
