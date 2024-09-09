@extends('template.document')

@section('data')
    <div class="row">
        <div class="col-md-4">
            <h5><i class="fa fa-thumb-tack"></i>
                <b>{{ $nm_akun->nama }}</b>
            </h5>
        </div>
        <div class="col-md-4 text-center">
            <h5><b>Lampiran Neraca Berdasarkan Perkiraan</b><br>
                Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
        </div>

        <div class="col-md-4 text-right">
            <a href="{{ $filter['back'] }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i> Kembali
            </a>
        </div>
    </div>
    <br>

    <table class="table-borderless table-sm table">
        <thead style="background-color: grey; color : #ffff">
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
                        @if (Request::segment(1) == 'neraca')
                            <a href="{{ route('showneracadetail', [
                                'id_ac' => $value->id_ac,
                                'dr_tgl' => $filter['dr_tgl'],
                                'sp_tgl' => $filter['sp_tgl'],
                            ]) }}"
                                style="color:black;">{{ $value->nama }}
                        @endif

                        @if (Request::segment(1) == 'rugilaba')
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
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="text-right"> {{ number_format($total_debit, 0, ',', '.') }}</td>
                <td class="text-right"> {{ number_format($total_kredit, 0, ',', '.') }}</td>
                <td class="text-right"> {{ number_format($total_debitkredit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection
