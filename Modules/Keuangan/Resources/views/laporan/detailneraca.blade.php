@extends('template.document')

@section('data')
    @php
        $url = $filter['back'];
    @endphp
    <div class="row">
        <div class="col-md-3">
            <h5><i class="fa fa-thumb-tack"></i>
                <b>{{ $akun->nama }}</b>
            </h5>
        </div>
        <div class="col-md-6 text-center">
            <h5><b>Detail Laporan Buku Besar</b><br>
                Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
        </div>
        <div class="col-md-3 text-right">
            @if (Request::segment(1) == 'bukubesar' or Request::segment(1) == 'cashflow')
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-warning">
                    <i class="fa fa-reply"></i> Kembali
                </a>
            @else
                @if (Request::segment(1) == 'neraca')
                    <a href="{{ route('showneraca', [
                        'id_ac' => $akun->parent,
                        'dr_tgl' => $filter['dr_tgl'],
                        'sp_tgl' => $filter['sp_tgl'],
                    ]) }}"
                        class="btn btn-sm btn-warning">
                        <i class="fa fa-reply"></i> Kembali
                    </a>
                @else
                    <a href="{{ route('showrugilaba', [
                        'id_ac' => $akun->parent,
                        'dr_tgl' => $filter['dr_tgl'],
                        'sp_tgl' => $filter['sp_tgl'],
                    ]) }}"
                        class="btn btn-sm btn-warning">
                        <i class="fa fa-reply"></i> Kembali
                    </a>
                @endif
            @endif
            <a href="{{ route('cetaktransaksikeuangan', [
                'id_ac' => $id,
                'dr_tgl' => $filter['dr_tgl'],
                'sp_tgl' => $filter['sp_tgl'],
            ]) }}"
                style="color:white;" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="top"
                title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak </a>
        </div>
    </div>
    <br>
    <div class="table-responsive" style="display: block; overflow-x: auto; white-space: nowrap;">
        <table class="table-striped table-hover table-sm table" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
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
                                @if (isset($value2->tgl_masuk))
                                    {{ dateindo($value2->tgl_masuk) }}
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
                                @if (isset($value2->tgl_masuk))
                                    {{ dateindo($value2->tgl_masuk) }}
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
@endsection
