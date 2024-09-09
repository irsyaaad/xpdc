@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <button class="btn btn-sm btn-flex btn-light fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#filter-data" aria-expanded="false" aria-controls="filter-data">
            <i class="ki-outline ki-filter fs-5 me-1 text-gray-500"></i> Filter
        </button>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body d-flex flex-center flex-column pt-12 p-9">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 text-dark fw-bold fs-6">
                <thead class="fw-bold text-muted bg-light">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Detail</th>
                    <th>Reff</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @php
                        $tkredit = 0;
                        $tdebit = 0;
                    @endphp
                    @php
                        $total = 0;
                        $no = 0;
                    @endphp
                    <tr class="tr-bold">
                        <td colspan="5" class="text-center">SALDO AWAL</td>
                        <td colspan="2">
                            @if (isset($saldo_awal))
                                @php $total += $saldo_awal; @endphp
                            @endif
                        </td>
                        <td class="text-right">
                            @if (isset($saldo_awal))
                                {{ number_format($saldo_awal, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>

                    @foreach ($data as $key => $value2)
                        @php
                            ++$no;
                        @endphp
                        <tr>
                            @if ($value2->id_debet == $id)
                                <td>{{ $no }}</td>
                                <td>
                                    @if (isset($value2->tgl_transaksi))
                                        {{ dateindo($value2->tgl_transaksi) }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->id_detail))
                                        {{ $value2->id_detail }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->reff))
                                        {{ $value2->reff }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->info_debet))
                                        {{ $value2->info_debet }}
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if (isset($value2->total_debet))
                                        {{ number_format($value2->total_debet, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="text-right"> 0</td>
                                @php
                                    if ($value2->pos_d == 'D') {
                                        $total += $value2->total_debet;
                                    } else {
                                        $total -= $value2->total_kredit;
                                    }
                                    $tdebit += $value2->total_debet;
                                @endphp
                            @else
                                <td>{{ $no }}</td>
                                <td>
                                    @if (isset($value2->tgl_transaksi))
                                        {{ dateindo($value2->tgl_transaksi) }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->id_detail))
                                        {{ $value2->id_detail }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->reff))
                                        {{ $value2->reff }}
                                    @endif
                                </td>
                                <td>
                                    @if (isset($value2->info_kredit))
                                        {{ $value2->info_kredit }}
                                    @endif
                                </td>
                                <td class="text-right"> 0</td>
                                <td class="text-right">
                                    @if (isset($value2->total_kredit))
                                        {{ number_format($value2->total_kredit, 0, ',', '.') }}
                                    @endif
                                </td>
                                @php
                                    if ($value2->pos_k == 'K') {
                                        $total += $value2->total_kredit;
                                    } else {
                                        $total -= $value2->total_debet;
                                    }
                                    $tkredit += $value2->total_kredit;
                                @endphp
                            @endif

                            <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="tr-bold">
                        <td colspan="5" class="text-right"><b>Total : </b> </td>
                        <td class="text-right">{{ toNumber($tdebit) }}</td>
                        <td class="text-right">{{ toNumber($tkredit) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
