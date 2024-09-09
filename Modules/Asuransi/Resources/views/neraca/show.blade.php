@extends('templatev2.defaultlayout')

@section('toolbar_action')
    <div class="me-3">
        <a class="btn btn-sm btn-flex btn-light fw-bold" href="{{ $filter['back'] }}">
            <i class="ki-outline ki-arrow-left fs-5 me-1 text-gray-500"></i> Kembali
        </a>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body d-flex flex-center flex-column pt-12 p-9">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 text-dark fw-bold fs-6">
                <thead class="fw-bold text-muted bg-light">
                    <th>ID AC</th>
                    <th>Nama Account</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @php
                        $total_debit = 0;
                        $total_kredit = 0;
                        $total_debitkredit = 0;
                    @endphp
                    @foreach ($ac as $key => $value)
                        <tr>
                            <td>{{ $value->id_ac }}</td>
                            <td>
                                @if (Request::segment(1) == 'neraca-asuransi')
                                    <a href="{{ route('showneracadetail-asuransi', [
                                        'id_ac' => $value->id_ac,
                                        'dr_tgl' => $filter['dr_tgl'],
                                        'sp_tgl' => $filter['sp_tgl'],
                                    ]) }}"
                                        style="color:black;">{{ $value->nama }}
                                @endif

                                @if (Request::segment(1) == 'rugilaba-asuransi')
                                    <a href="{{ route('showrugilabadetail', [
                                        'id_ac' => $value->id_ac,
                                        'dr_tgl' => $filter['dr_tgl'],
                                        'sp_tgl' => $filter['sp_tgl'],
                                    ]) }}"
                                        style="color:black;">{{ $value->nama }}
                                @endif

                            </td>
                            {{-- <td><a href="{{ url(Request::segment(1)."/".$value->id_ac."/showdetail") }}" style="color:black;">{{$value->nama}}</td> --}}
                            <td class="text-right">
                                @if (isset($debit[$value->id_ac]))
                                    {{ number_format($debit[$value->id_ac], 0, ',', '.') }}
                                    @php $total_debit+=$debit[$value->id_ac] @endphp
                                @endif
                            </td>
                            <td class="text-right">
                                @if (isset($kredit[$value->id_ac]))
                                    {{ number_format($kredit[$value->id_ac], 0, ',', '.') }}
                                    @php $total_kredit+=$kredit[$value->id_ac] @endphp
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($value->def_pos == 'D')
                                    {{ number_format($debit[$value->id_ac] - $kredit[$value->id_ac], 0, ',', '.') }}
                                    @php $total_debitkredit+=($debit[$value->id_ac]-$kredit[$value->id_ac]) @endphp
                                @elseif ($value->def_pos == 'K')
                                    {{ number_format($kredit[$value->id_ac] - $debit[$value->id_ac], 0, ',', '.') }}
                                    @php $total_debitkredit+=($kredit[$value->id_ac]-$debit[$value->id_ac]) @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold text-muted bg-light">
                        <td colspan="2" class="text-center">TOTAL</td>
                        <td class="text-right"> {{ number_format($total_debit, 0, ',', '.') }}</td>
                        <td class="text-right"> {{ number_format($total_kredit, 0, ',', '.') }}</td>
                        <td class="text-right"> {{ number_format($total_debitkredit, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
