@extends('template.document2')

@section('data')
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
    @include('filter.filter-' . Request::segment(1))
    <div class="table-responsive" style="display: block; overflow-x: auto;white-space: nowrap;">
        <br>
        <table class="table table-responsive table-sm" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Marketing</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Koli</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Omset</th>
                    <th colspan="3" class="text-center">Pelanggan</th>
                    <th colspan="8" class="text-center">Jenis</th>
                </tr>
                <tr>
                    <th class="text-center">Aktif</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Baru</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Baru </th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Reorder</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Reorder </th>
                    <th class="text-center"> % </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_stt = 0;
                    $total_koli = 0;
                    $total_omset = 0;
                    $total_jumlah = 0;
                    $total_aktif = 0;
                    $total_baru = 0;
                    $total_reorder = 0;
                    $total_omset_baru = 0;
                    $total_omset_reorder = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key += 1 }}</td>
                        <td class="text-center">
                            <a href="{{ route('detail-analisa-pelanggan', [
                                'id_marketing' => $value->id_marketing,
                                'dr_tgl' => $filter['dr_tgl'],
                                'sp_tgl' => $filter['sp_tgl'],
                            ]) }}"
                                style="color:black;">
                                {{ !empty($value->nm_marketing) ? strtoupper($value->nm_marketing) : 'DATANG SENDIRI' }}
                            </a>
                        </td>
                        <td class="text-center">{{ $value->total_stt }}</td>
                        <td class="text-center">{{ $value->koli }}</td>
                        <td class="text-right">{{ number_format($value->omset, 0, ',', '.') }}</td>
                        <td class="text-center"><a
                                href="{{ route('detail-pelanggan-aktif', [
                                    'type' => 'aktif',
                                    'id_marketing' => $value->id_marketing,
                                    'dr_tgl' => $filter['dr_tgl'],
                                    'sp_tgl' => $filter['sp_tgl'],
                                ]) }}"
                                style="color:black;">{{ $value->aktif }}</a></td>
                        <td class="text-center">{{ $value->jumlah }}</td>
                        <td class="text-center">
                            @if (($value->aktif and $value->jumlah) > 0)
                                {{ round(($value->jumlah / $value->aktif) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center"><a
                                href="{{ route('detail-pelanggan-aktif', [
                                    'type' => 'baru',
                                    'id_marketing' => $value->id_marketing,
                                    'dr_tgl' => $filter['dr_tgl'],
                                    'sp_tgl' => $filter['sp_tgl'],
                                ]) }}"
                                style="color:black;">{{ $value->baru }}</a></td>
                        <td class="text-center">
                            @if (($value->baru and $value->jumlah) > 0)
                                {{ round(($value->baru / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ toNumber($value->omset_baru) }}</td>
                        <td class="text-center">
                            @if (($value->omset and $value->omset_baru) > 0)
                                {{ round(($value->omset_baru / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center">{{ $value->reorder }} </td>
                        <td class="text-center">
                            @if (($value->reorder and $value->aktif) > 0)
                                {{ round(($value->reorder / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ toNumber($value->omset_reorder) }} </td>
                        <td class="text-center">
                            @if (($value->omset_reorder and $value->omset) > 0)
                                {{ round(($value->omset_reorder / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                    </tr>
                    @php
                        $total_stt += $value->total_stt;
                        $total_koli += $value->koli;
                        $total_omset += $value->omset;
                        $total_jumlah += $value->jumlah;
                        $total_aktif += $value->aktif;
                        $total_baru += $value->baru;
                        $total_reorder += $value->reorder;
                        $total_omset_baru += $value->omset_baru;
                        $total_omset_reorder += $value->omset_reorder;
                    @endphp
                @endforeach
                <tr style="background-color: grey; color : #ffff">
                    <td colspan = "2" class="text-center">Total Pelanggan Unik : {{ $pelanggan_unik['unik'] }}, dari
                        {{ $pelanggan_unik['unik_aktif'] }} aktif</td>
                    <td class="text-center">{{ $total_stt }}</td>
                    <td class="text-center">{{ $total_koli }}</td>
                    <td class="text-center">Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $total_aktif }}</td>
                    <td class="text-center">{{ $total_jumlah }}</td>
                    <td></td>
                    <td class="text-center">{{ $total_baru }}</td>
                    <td>
                        @if (($total_baru and $total_jumlah) > 0)
                            {{ round(($total_baru / $total_jumlah) * 100, 2) }} %
                        @endif
                    </td>
                    <td class="text-right">{{ toNumber($total_omset_baru) }}</td>
                    <td>
                        @if (($total_omset_baru and $total_omset) > 0)
                            {{ round(($total_omset_baru / $total_omset) * 100, 2) }} %
                        @endif
                    </td>
                    <td class="text-center">{{ $total_reorder }}</td>
                    <td>
                        @if (($total_reorder and $total_jumlah) > 0)
                            {{ round(($total_reorder / $total_jumlah) * 100, 2) }} %
                        @endif
                    </td>
                    <td class="text-right">{{ toNumber($total_omset_reorder) }}</td>
                    <td>
                        @if (($total_omset_reorder and $total_omset) > 0)
                            {{ round(($total_omset_reorder / $total_omset) * 100, 2) }} %
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>
    <ul>
        <li>Baru, Jika -12 Bulan kebelakang tidak pernah melakukan pengiriman dari tanggal kirim pada range periode</li>
        <li>Reorder, Jika -12 Bulan kebelakang ada melakukan pengiriman dari tanggal kirim pada range periode</li>
        <li>Aktif, dihitung -36 Bulan kebelakang dari tanggal akhir periode</li>
        <li>Pelanggan Unik, adalah pelanggan unik selama range periode, mengabaikan grouping dari marketing</li>
        <li>Aktif Unik, adalah pelanggan unik dari periode akhir hingga -36 bulan kebelakang</li>
    </ul>
@endsection
