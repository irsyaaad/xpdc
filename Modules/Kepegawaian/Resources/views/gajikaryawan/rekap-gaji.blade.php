@extends('template.document2')

@section('data')
    <div class="row">
        <div class="col-md-12">
            @include('kepegawaian::filter.filter-rekap-gaji')
        </div>
        <div class="col-md-12" style="margin-top: 10px">
            @php
                $kolom = [
                    'gaji' => 'Gaji Pokok',
                    'n_tunjangan_jabatan' => 'Tunjangan Jabatan',
                    'n_tunjangan_kinerja' => 'Tunjangan Kinerja',
                    'n_tunjangan_kpi' => 'KPI',
                    'n_tunjangan_kesehatan' => 'Tunj. BPJS Kesehatan',
                    'n_tunjangan_jht' => 'JHT',
                    'n_tunjangan_jkk' => 'JKK',
                    'n_tunjangan_jkm' => 'JKM',
                    'n_tunjangan_jp' => 'JP',
                    'n_potongan_pph' => 'PPH 21',
                    'n_potongan_kesehatan' => 'Potongan Kesehatan',
                    'n_potongan_jht' => 'Potngan JHT',
                    'n_potongan_jp' => 'Potngan JP',
                    'n_denda' => 'Absensi Kehadiran',
                ];
            @endphp
            <div class="table-responsive mt-3" style="display: block; overflow-x: auto;white-space: nowrap;">
                <table class="table-lg table-bordered table-responsive table" width="100%">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            @foreach ($tahun as $item)
                                <th colspan="{{ count($bulan[$item])*2 }}" class="text-center">{{ $item }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th></th>
                            @foreach ($tahun as $item)
                                @isset($bulan[$item])
                                    @foreach ($bulan[$item] as $value)
                                        <th class="text-center">{{ $value }}</th>
                                        <th>%</th>
                                    @endforeach
                                @endisset
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kolom as $key => $item)
                            <tr>
                                <td>{{ $item }}</td>
                                @php
                                    $lastGaji = 0;
                                @endphp
                                @foreach ($tahun as $item)
                                    @isset($bulan[$item])
                                        @foreach ($bulan[$item] as $value)
                                            @php
                                                $currentGaji = isset($data[$item][$value])
                                                    ? $data[$item][$value]->$key
                                                    : 0;
                                            @endphp
                                            <td class="text-right">
                                                {{ toNumber($currentGaji) }}
                                            </td>
                                            @php
                                                $diffGaji = $currentGaji - $lastGaji;
                                                $prosentase = $diffGaji != 0 && $currentGaji != 0 ? round(($diffGaji/($currentGaji)) * 100,2) : 0;
                                                $lastGaji = $currentGaji;
                                            @endphp
                                            <td>{{ $prosentase }}</td>
                                        @endforeach
                                    @endisset
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12" style="margin-top: 20px"></div>
    </div>
@endsection
