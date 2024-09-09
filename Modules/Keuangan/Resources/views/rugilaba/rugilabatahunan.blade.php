@extends('template.document2')
@section('data')
    @if (Request::segment(1) == 'rugilabapertahun' && (Request::segment(2) == null or Request::segment(2) == 'filter'))
        @include('template.filter2')

        @php
            $total_pendapatan = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $total_rugilaba = 0;
            $total = 0;
            $pendapatan_pertahun = 0;
        @endphp
        <div class="table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
            <table class="table-bordered table-sm table">
                <thead>
                    <th>Nama Account</th>
                    @php
                        $bulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];
                    @endphp
                    @foreach ($bulan as $item)
                        <th class="text-center">{{ $item }}</th>
                    @endforeach
                    <th class="text-center">Pertahun</th>
                    <th class="text-center">%</th>
                </thead>
                @foreach ($data1 as $key => $value)
                    @if ($value->id_ac == 4)
                        <tr>
                            <td>{{ $value->nama }}</td>
                        </tr>
                        @if (isset($data2[$value->id_ac]))
                            @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                @if (isset($data3[$value2->id_ac]))
                                    @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        @php
                                            $pendapatan_pertahun = 0;
                                        @endphp
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</td>
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if (isset($data[$i]))
                                                    @if (isset($data[$i][$value3->id_ac]))
                                                        <td class="text-right">
                                                            {{-- {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }} --}}
                                                            {{ $data[$i][$value3->id_ac] != 0 ? (!($value3->id_ac == 411 or $value3->id_ac == 412) ? number_format($data[$i][$value3->id_ac], 0, ',', '.') : '-' . number_format($data[$i][$value3->id_ac], 0, ',', '.')) : 0 }}
                                                        </td>
                                                        @php
                                                            if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                                $total_pendapatan[$i] -= $data[$i][$value3->id_ac];
                                                            } else {
                                                                $total_pendapatan[$i] += $data[$i][$value3->id_ac];
                                                            }
                                                            $pendapatan_pertahun += $data[$i][$value3->id_ac];
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endfor
                                            <td class="text-right">
                                                {{ $pendapatan_pertahun != 0 ? (!($value3->id_ac == 411 or $value3->id_ac == 412) ? number_format($pendapatan_pertahun, 0, ',', '.') : '-' . number_format($pendapatan_pertahun, 0, ',', '.')) : 0 }}
                                            </td>
                                            <td class="text-right">
                                                {{ $pendapatan_pertahun != 0 ? (!($value3->id_ac == 411 or $value3->id_ac == 412) ? round(($pendapatan_pertahun / $total_omset) * 100, 2) : '-' . round(($pendapatan_pertahun / $total_omset) * 100, 2)) : 0 }}
                                                %</td>
                                        </tr>
                                        @php

                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                <td class="text-center">Sub Total PENDAPATAN</td>
                                @for ($i = 1; $i <= 12; $i++)
                                    <td class="text-right"> {{ number_format($total_pendapatan[$i], 0, ',', '.') }}</td>
                                @endfor
                                <td class="text-right">{{ number_format($total_omset, 0, ',', '.') }}</td>
                                <td class="text-right"> 100 %</td>
                            </tr>
                        @endif
                    @endif
                @endforeach

                @foreach ($data1 as $key => $value)
                    @if ($value->id_ac == 5)
                        <tr>
                            <td>{{ $value->nama }}</td>
                        </tr>
                        @if (isset($data2[$value->id_ac]))
                            @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                @php
                                    $total = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                                @endphp
                                <td style="padding-left:10px">
                                    <p>{{ $value2->nama }}</p>
                                </td>
                                @if (isset($data3[$value2->id_ac]))
                                    @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        @php $total_pertahun = 0; @endphp
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</td>
                                            @for ($i = 1; $i <= 12; $i++)
                                                @isset($data[$i])
                                                    @if (isset($data[$i][$value3->id_ac]))
                                                        <td class="text-right">
                                                            {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                                        @php
                                                            if ($value3->tipe == 'K') {
                                                                $total[$i] += $data[$i][$value3->id_ac];
                                                            } else {
                                                                $total[$i] -= $data[$i][$value3->id_ac];
                                                            }
                                                            $total_pertahun += $data[$i][$value3->id_ac];
                                                        @endphp
                                                    @endif
                                                @endisset
                                            @endfor
                                            <td class="text-right">{{ number_format($total_pertahun, 0, ',', '.') }}</td>
                                            <td class="text-right">{{ round(($total_pertahun / $total_omset) * 100, 2) }} %
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="text-center">Sub Total {{ $value2->nama }}</td>
                                    @php
                                        $sub_total_pertahun = 0;
                                    @endphp
                                    @for ($i = 1; $i <= 12; $i++)
                                        <td class="text-right"> {{ number_format($total[$i], 0, ',', '.') }}</td>
                                        @php
                                            $total_pendapatan[$i] += $total[$i];
                                            $sub_total_pertahun += $total[$i];
                                        @endphp
                                    @endfor
                                    <td class="text-right">{{ number_format($sub_total_pertahun, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ round(($sub_total_pertahun / $total_omset) * 100, 2) }} %
                                    </td>
                                </tr>
                                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                    @if ($value2->id_ac == 50)
                                        <td class="text-center"> LABA KOTOR </td>
                                    @elseif ($value2->id_ac == 51)
                                        <td class="text-center"> LABA OPERASIONAL </td>
                                    @elseif ($value2->id_ac == 52)
                                        <td class="text-center"> LABA SETELAH POKOK DAN BUNGA </td>
                                    @elseif ($value2->id_ac == 53)
                                        <td class="text-center"> LABA SETELAH PENDAPATAN DAN BIAYA LAIN-LAIN </td>
                                    @elseif ($value2->id_ac == 54)
                                        <td class="text-center"> LABA SETELAH PAJAK </td>
                                    @endif
                                    @php
                                        $total_pendapatan_pertahun = 0;
                                    @endphp
                                    @for ($i = 1; $i <= 12; $i++)
                                        <td class="text-right"> {{ number_format($total_pendapatan[$i], 0, ',', '.') }}
                                        </td>
                                        @php
                                            $total_pendapatan_pertahun += $total_pendapatan[$i];
                                        @endphp
                                    @endfor
                                    <td class="text-right">{{ number_format($total_pendapatan_pertahun, 0, ',', '.') }}
                                    </td>
                                    <td class="text-right">
                                        {{ round(($total_pendapatan_pertahun / $total_omset) * 100, 2) }} %</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </table>

            <div>
    @endif
@endsection
