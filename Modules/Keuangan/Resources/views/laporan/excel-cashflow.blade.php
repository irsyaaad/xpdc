<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashflow</title>
</head>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=cashflow.xls");
?>
<body class="container">
    <div class="container"  style=" margin-top:10px;">
        <table>
            <tr>
                <th colspan="5" class="text-center">{{$perusahaan->nm_perush}}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
            </tr>
        </table>
        <br><br>
    </div>
    <div class="container">
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
                        <td>{{$value->nama_cashflow}}</td>
                    </tr>
                    @if (isset($child[$value->id_cf]))
                        @foreach ($child[$value->id_cf] as $key2 => $value2)
                            <tr>
                                <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                    'id_ac' => $value2->id_cf, 
                                    'dr_tgl' => $filter['dr_tgl'], 
                                    'sp_tgl' => $filter['sp_tgl'],
                                    ]) }}" style="color:black;">{{$value2->nama_cashflow}}</a></td>
                                <td>
                                    @if (isset($total[$value2->id_cf]))
                                        Rp. {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                        @php
                                            $total_cashin+=$total[$value2->id_cf];
                                        @endphp
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        {{-- <tr>
                            <td class="text-center">SUB TOTAL {{$value->nama_cashflow}}</td>
                            <td></td>
                        </tr> --}}
                    @endif
                @endif
            @endforeach
            <tr>
                <td style="padding-left: 100px">
                    Pemasukan Lain (Pemasukan yang blum di Mapping)
                    <ul>
                        @if (isset($ac_in_belum_mapping))
                            @foreach ($ac_in_belum_mapping as $key => $value)
                                <li>{{$value}}</li>
                            @endforeach
                        @endif
                    </ul>
                </td>
                <td>
                    Rp. {{ number_format($total["inlain"], 0, ',', '.') }}
                    @php
                        $total_cashin+=$total["inlain"];
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-center">TOTAL CASH IN</td>
                <td>Rp. {{ number_format($total_cashin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>CASH OUT</td>
            </tr>
            @foreach ($head as $key => $value)
                @if ($value->tipe == 2)
                    <tr>
                        <td>{{$value->nama_cashflow}}</td>
                    </tr>
                    @if (isset($child[$value->id_cf]))
                        @foreach ($child[$value->id_cf] as $key2 => $value2)
                            <tr>
                                <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                    'id_ac' => $value2->id_cf, 
                                    'dr_tgl' => $filter['dr_tgl'], 
                                    'sp_tgl' => $filter['sp_tgl'],
                                    ]) }}" style="color:black;">{{$value2->nama_cashflow}}</a></td>
                                <td>
                                    @if (isset($total[$value2->id_cf]))
                                        Rp. {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                        @php
                                            $total_cashout+=$total[$value2->id_cf];
                                        @endphp
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center">SUB TOTAL {{$value->nama_cashflow}}</td>
                            <td></td>
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
                                <li>{{$value}}</li>
                            @endforeach
                        @endif
                    </ul>
                </td>
                <td>
                    Rp. {{ number_format($total["outlain"], 0, ',', '.') }}
                    @php
                        $total_cashout+=$total["outlain"];
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="text-center">TOTAL CASH OUT</td>
                <td>Rp. {{ number_format($total_cashout, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">Surplus / Defisit Setelah Investasi</td>
                @php
                    $total_akhir = $total_cashin - $total_cashout;
                @endphp
                <td>Rp. {{ number_format($total_akhir-$saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">Saldo Awal</td>
                <td>Rp. {{ number_format($saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-center">Saldo Akhir</td>
                <td>Rp. {{ number_format($total_akhir, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>