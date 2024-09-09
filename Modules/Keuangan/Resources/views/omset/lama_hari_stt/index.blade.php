@extends('template.document2')

@section('data')
    @include('filter.filter-' . Request::segment(1))
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }

        td {
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
    </style>
    <div class="table-responsive mt-3" style="display: block; overflow-x: auto;white-space: nowrap;">
        <br><br>
        <table class="table-responsive table-borderless table-sm" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <th>#</th>
                <th>No STT</th>
                <th>Smp</th>
                <th>STT Kirim</th>
                <th>Krm</th>
                <th>STT Kembali</th>
                <th>Kmb</th>
                <th>Tgl Invoice</th>
                <th>No Invoice</th>
                <th>Inv</th>
                <th>Tgl Bayar</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Piutang</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td></td>
                        <td colspan=2>No DM</td>
                        <td colspan=3>{{ isset($value->kode_dm) ? $value->kode_dm : '-' }}</td>
                        <td colspan=2>Nama Sopir</td>
                        <td colspan=3>{{ isset($value->nm_sopir) ? $value->nm_sopir : '-' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan=2>Cab Tujuan</td>
                        <td colspan=3>{{ isset($value->nm_perush) ? $value->nm_perush : $value->nm_ven }}</td>
                        <td colspan=2>Nama Kapal</td>
                        <td colspan=3>{{ isset($value->nm_kapal) ? $value->nm_kapal : '-' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan=2>Tgl Berangkat</td>
                        <td colspan=3>
                            @if (isset($value->tgl_berangkat))
                                {{ daydate($value->tgl_berangkat) . ', ' . dateindo($value->tgl_berangkat) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan=2>Tgl Tiba</td>
                        <td colspan=3>
                            @if (isset($value->tgl_sampai))
                                {{ daydate($value->tgl_sampai) . ', ' . dateindo($value->tgl_sampai) }}
                            @endif
                        </td>
                    </tr>
                    @php
                        $no = 0;
                    @endphp

                    @foreach ($stt[$value->id_dm] as $key2 => $value2)
                        <tr>
                            <td>{{ $no += 1 }}</td>
                            <td>{{ isset($value2->kode_stt) ? $value2->kode_stt : '-' }}</td>
                            <td>{{ isset($value2->sampai) ? $value2->sampai : '-' }}</td>
                            <td>{{ isset($value2->tgl_masuk) ? dateindo($value2->tgl_masuk) : '-' }}</td>
                            <td>-</td>
                            <td>{{ isset($value2->tgl_kembali) ? dateindo($value2->tgl_kembali) : '-' }}</td>
                            <td>{{ isset($value2->stt_kembali) ? $value2->stt_kembali : '-' }}</td>
                            <td>{{ isset($value2->tgl) ? dateindo($value2->tgl) : '-' }}</td>
                            <td>{{ isset($value2->kode_invoice) ? $value2->kode_invoice : '-' }}</td>
                            <td>{{ isset($value2->inv) ? $value2->inv : '-' }}</td>
                            @php
                                $tgl = explode(',', $value2->tgl_bayar);
                            @endphp
                            <td>
                                @foreach ($tgl as $index => $item)
                                    {{ $index + 1 . ') ' . $item }} <br>
                                @endforeach
                            </td>
                            <td>{{ isset($value2->c_total) ? toNumber($value2->c_total) : '0' }}</td>
                            <td>
                                @php
                                    $bayar = explode(',', $value2->n_bayar);
                                    foreach ($bayar as $index => $item) {
                                        echo $index + 1 . ') ' . toNumber((int) $item) . '<br>';
                                    }
                                @endphp
                            </td>
                            <td>{{ isset($value2->piutang) ? toNumber($value2->piutang) : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="14" style="background-color: grey; color : #ffff"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
