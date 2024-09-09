@extends('template.document2')

@section('data')
    <div class="card-header border-0 bg-transparent text-center">
        <div class=" card-title font-weight-bolder">
            <h5 class=" font-weight-bold">
                (@if (isset(Session('perusahaan')['id_perush']))
                    {{ strtoupper(Session('perusahaan')['id_perush']) }}
                @endif) LAPORAN REKAP ENTRI STATUS
            </h5>
            <p>
                Periode {{ $entri_awal }} s/d {{ $entri_akhir }}
            </p>
        </div>

    </div>
    <hr>
    <table class="table table-responsive table-bordered">
        <thead style="background-color: white; color : #000000">
            <tr>
                <th class="text-center" style="font-size: 12px;">#<br>A</th>
                <th class="text-center" style="font-size: 12px">IDP<br>B</th>
                <th class="text-center" style="font-size: 12px">IDP STT<br>C</th>
                <th class="text-center" style="font-size: 12px">JML STT<br>D</th>
                <th class="text-center" style="font-size: 12px;">JML Status <br>E</th>
                <th class="text-center text-dark" style="font-size: 12px; background-color:white; font-weight:700;">> 0<br>F</th>
                <th class="text-center text-dark" style="font-size: 12px; background-color:white; font-weight:700;">%<br>F/E*100</th>
                <th class="text-center text-success " style="font-size: 12px; background-color:white; font-weight:700;  text-color:#508D4E;">=0<br>H</th>
                <th class="text-center text-success " style="font-size: 12px; background-color:white; font-weight:700; text-color:#508D4E;">%<br>H/E*100</th>
                <th class="text-center text-danger" style="font-size: 12px; background-color:white; font-weight:700;"> < 0<br>J
                </th>
                <th class="text-center text-danger" style="font-size: 12px; background-color:white; font-weight:700;">%<br>J/E*100</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($entrilist))
                {{-- kalkulasi pengirim untuk sum --}}
                @php
                    $i = 1;
                    $j = 1;
                @endphp
                @php
                    $sumstt = 0;

                    $sumstat = 0;

                    $n_awal = 0;

                    $per_lebih_awal = 0;

                    $n_tepat = 0;

                    $per_n_tepat = 0;

                    $n_telat = 0;

                    $per_n_telat = 0;
                    $totaldata = 0;
                @endphp
                @foreach ($entrilist as $entri_sum)
                    @if ($entri_sum->nm_sbg_update == 'SEBAGAI PENGIRIM')
                        @php
                            $totaldata++;
                            $sumstt += (int) $entri_sum->n_stt;

                            $sumstat += (int) $entri_sum->n_stat;

                            $n_awal += (int) $entri_sum->n_awal;

                            $per_lebih_awal += (float) number_format(
                                ((int) $entri_sum->n_awal / (int) $entri_sum->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );

                            $n_tepat += (int) $entri_sum->n_tepat;

                            $per_n_tepat += (float) number_format(
                                ((int) $entri_sum->n_tepat / (int) $entri_sum->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );

                            $n_telat += (int) $entri_sum->n_telat;

                            $per_n_telat += (float) number_format(
                                ((int) $entri_sum->n_telat / (int) $entri_sum->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );
                        @endphp
                    @else
                    @endif
                @endforeach
                {{-- header --}}
                @if ($sumstt > 0)
                    <tr>
                        <td colspan="11" class="text-left bg-dark text-white" style="font-size: 12px;">1. Sebagai Pengirim
                        </td>
                    </tr>
                @endif
                {{-- table data pengirim --}}
                @foreach ($entrilist as $entri)
                    @if ($entri->nm_sbg_update == 'SEBAGAI PENGIRIM')
                        <tr>
                            <td class="text-left">{{ $i++ }}. </td>
                            <td class="text-center">{{ $entri->id_perush_asal }} </td>
                            <td class="text-center">{{ $entri->id_perush_status }} </td>
                            <td class="text-center"><a class="sttlink" id-data="{{ $entri->n_stt }}"
                                    href="#">{{ $entri->n_stt }}</a>
                            </td>
                            <form id="{{ $entri->n_stt }}" action="rekapstatusbystt" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="nm_sbg" value="{{ $entri->nm_sbg_update }}" hidden>
                                <input type="text" name="status_awal" value="{{ $entri_awal }}" hidden>
                                <input type="text" name="status_akhir" value="{{ $entri_akhir }}" hidden>
                            </form>
                            <td class="text-center">{{ $entri->n_stat }} </td>
                            <td class="text-center">{{ $entri->n_awal }} </td>
                            <td class="text-center">
                                {{ number_format(((int) $entri->n_awal / (int) $entri->n_stat) * 100, 3, ',', ' ') }} </td>
                            <td class="text-center text-success">{{ $entri->n_tepat }} </td>
                            <td class="text-center text-success">
                                {{ number_format(((int) $entri->n_tepat / (int) $entri->n_stat) * 100, 3, ',', ' ') }}
                            </td>
                            <td class="text-center text-danger">{{ $entri->n_telat }} </td>
                            <td class="text-center text-danger">
                                {{ number_format(((int) $entri->n_telat / (int) $entri->n_stat) * 100, 3, ',', ' ') }}
                            </td>
                        </tr>
                    @else
                        <tr></tr>
                    @endif
                @endforeach
                {{-- row sum pengirim --}}
                @if ($sumstt > 0)
                    <tr class="text-center text-dark" style="background-color:whitesmoke; font-weight:900;">
                        <td colspan="3" style="font-style:italic">SUB TOTAL</td>
                        <td>{{ $sumstt }}</td>
                        <td>{{ $sumstat }}</td>
                        <td>{{ $n_awal }}</td>
                        <td>{{ (float) $per_lebih_awal / (float) $totaldata }}</td>
                        <td class="text-success">{{ $n_tepat }}</td>
                        <td class="text-success">{{ (float) $per_n_tepat / (float) $totaldata }}</td>
                        <td class=" text-danger">{{ $n_telat }}</td>
                        <td class=" text-danger">{{ (float) $per_n_telat / (float) $totaldata }}</td>
                    </tr>
                @endif

                {{-- logic kalkulasi penerima --}}
                @php
                    $sumstt_pen = 0;

                    $sumstat_pen = 0;

                    $n_awal_pen = 0;

                    $per_lebih_awal_pen = 0;

                    $n_tepat_pen = 0;

                    $per_n_tepat_pen = 0;

                    $n_telat_pen = 0;

                    $per_n_telat_pen = 0;
                    $totaldata_pen = 0;
                @endphp
                @foreach ($entrilist as $entri_sum_pen)
                    @if ($entri_sum_pen->nm_sbg_update == 'SEBAGAI PENERIMA')
                        @php
                            $totaldata_pen++;
                            $sumstt_pen += (int) $entri_sum_pen->n_stt;

                            $sumstat_pen += (int) $entri_sum_pen->n_stat;

                            $n_awal_pen += (int) $entri_sum_pen->n_awal;

                            $per_lebih_awal_pen += (float) number_format(
                                ((int) $entri_sum_pen->n_awal / (int) $entri_sum_pen->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );

                            $n_tepat_pen += (int) $entri_sum_pen->n_tepat;

                            $per_n_tepat_pen += (float) number_format(
                                ((int) $entri_sum_pen->n_tepat / (int) $entri_sum_pen->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );

                            $n_telat_pen += (int) $entri_sum_pen->n_telat;

                            $per_n_telat_pen += (float) number_format(
                                ((int) $entri_sum_pen->n_telat / (int) $entri_sum_pen->n_stat) * 100,
                                3,
                                ',',
                                ' ',
                            );
                        @endphp
                    @else
                    @endif
                @endforeach
                {{-- header --}}
                @if ($sumstt_pen > 0)
                    <tr>
                        <td colspan="11" class="text-left bg-dark text-white" style="font-size: 12px;">2. Sebagai Penerima
                        </td>
                    </tr>
                @endif
                {{-- table data penerima --}}
                @foreach ($entrilist as $entri_pen)
                    @if ($entri_pen->nm_sbg_update == 'SEBAGAI PENERIMA')
                        <tr>
                            <td class="text-center">{{ $j++ }}. </td>
                            <td class="text-center">{{ $entri_pen->id_perush_asal }} </td>
                            <td class="text-center">{{ $entri_pen->id_perush_status }} </td>
                            <td class="text-center"><a class="sttlink" id-data="{{ $entri_pen->n_stt }}"
                                    href="#">{{ $entri_pen->n_stt }}</a>
                            </td>
                            <form id="{{ $entri_pen->n_stt }}" action="rekapstatusbystt" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="nm_sbg" value="{{ $entri_pen->nm_sbg_update }}" hidden>
                                <input type="text" name="status_awal" value="{{ $entri_awal }}" hidden>
                                <input type="text" name="status_akhir" value="{{ $entri_akhir }}" hidden>
                            </form>
                            <td class="text-center">{{ $entri_pen->n_stat }} </td>
                            <td class="text-center">{{ $entri_pen->n_awal }} </td>
                            <td class="text-center">
                                {{ number_format(((int) $entri_pen->n_awal / (int) $entri_pen->n_stat) * 100, 3, ',', ' ') }}
                            </td>
                            <td class="text-center">{{ $entri_pen->n_tepat }} </td>
                            <td class="text-center">
                                {{ number_format(((int) $entri_pen->n_tepat / (int) $entri_pen->n_stat) * 100, 3, ',', ' ') }}
                            </td>
                            <td class="text-center">{{ $entri_pen->n_telat }} </td>
                            <td class="text-center">
                                {{ number_format(((int) $entri_pen->n_telat / (int) $entri_pen->n_stat) * 100, 3, ',', ' ') }}
                            </td>
                        </tr>
                    @else
                        <tr></tr>
                    @endif
                @endforeach
                {{-- row sum penerima --}}
                @if ($sumstt_pen > 0)
                    <tr class="text-center text-dark" style="background-color:whitesmoke; font-weight:900;">
                        <td colspan="3" style="font-style: italic;"> SUB TOTAL</td>
                        <td>{{ $sumstt_pen }}</td>
                        <td>{{ $sumstat_pen }}</td>
                        <td>{{ $n_awal_pen }}</td>
                        <td>{{ (float) $per_lebih_awal_pen / (float) $totaldata_pen }}</td>
                        <td class=" text-success">{{ $n_tepat_pen }}</td>
                        <td class=" text-success">{{ (float) $per_n_tepat_pen / (float) $totaldata_pen }}</td>
                        <td class=" text-danger">{{ $n_telat_pen }}</td>
                        <td class=" text-danger">{{ (float) $per_n_telat_pen / (float) $totaldata_pen }}</td>
                    </tr>
                @endif

              <tfoot>
                    {{-- Grand Total --}}
                    @if ($sumstt > 0 || $sumstt_pen > 0)
                    <tr class="text-center bg-white text-dark" style=" font-weight:900;">
                        <td colspan="3" style="background-color:#343a40; font-style:italic; color:white;">GRAND TOTAL</td>
                        <td>{{ $sumstt + $sumstt_pen }}</td>
                        <td>{{ $sumstat + $sumstat_pen }}</td>
                        <td>{{ $n_awal + $n_awal_pen }}</td>
                        <td>{{ number_format(($per_lebih_awal + $per_lebih_awal_pen) / ($totaldata + $totaldata_pen), 3, ',', ' ') }}</td>
                        {{-- <td></td> --}}
                        <td class=" text-success">{{ $n_tepat + $n_tepat_pen }}</td>
                        <td class=" text-success">{{ number_format(($per_n_tepat + $per_n_tepat_pen) / ($totaldata + $totaldata_pen), 3, ',', ' ') }}</td>
                        {{-- <td></td> --}}
                        <td class=" text-danger">{{ $n_telat + $n_telat_pen }}</td>
                        <td class=" text-danger">{{ number_format(($per_n_telat + $per_n_telat_pen) / ($totaldata + $totaldata_pen), 3, ',', ' ') }}</td>
                        {{-- <td></td> --}}
                    </tr>
              </tfoot>
              @endif
            @endif
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            <a href="outstandingstt" class="btn btn-primary">
                Kembali
            </a>
        </div>
    </div>
    <script>
        $('.sttlink').click(function(e) {
            e.preventDefault();
            iddat = $(this).attr('id-data');
            $("#" + iddat).submit();
        });
    </script>
@endsection
