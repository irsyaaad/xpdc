@extends('template.document2')

@section('data')
    <div class="card-header border-0 bg-transparent text-center">
        <div class=" card-title font-weight-bolder">
            <h5 class=" font-weight-bold">
                (@if (isset(Session('perusahaan')['id_perush']))
                    {{ strtoupper(Session('perusahaan')['id_perush']) }}
                @endif) Entri Status Barang, Grup By STT
            </h5>
            <p>
                Periode {{ $status_awal }} s/d {{ $status_akhir }}
            </p>
        </div>

    </div>
    <hr>
    <table class="table table-responsive table-bordered" id="tableasal">
        <thead style="background-color: white; color : #000000; border-style:solid;">
            <tr style="font-weight:900;">
                <th class="text-center" style="font-size: 12px;">No</th>
                <th class="text-center" style="font-size: 12px">ID STT</th>
                <th class="text-center" style="font-size: 12px">Jml Stat </th>
                <th class="text-center" style="font-size: 12px">
                    > 0</th>
                <th class="text-center text-success" style="font-size: 12px">= 0</th>
                <th class="text-center text-danger" style="font-size: 12px">
                    < 0</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @if (!empty($sttlist))
                {{-- BLOCK PENGIRIM --}}
                @php
                    $totaldata = 0;
                @endphp
                @foreach ($sttlist as $stt)
                    @if ($stt->nm_sbg_update == 'SEBAGAI PENGIRIM')
                        @php
                            $totaldata++;
                        @endphp
                    @endif
                @endforeach
                @if ($totaldata > 0)
                    <tr>
                        <td colspan="11" class="text-left bg-dark text-white" style="font-size: 12px;">1. Sebagai Pengirim
                        </td>
                    </tr>
                @endif
                @foreach ($sttlist as $stt)
                    @if ($stt->nm_sbg_update == 'SEBAGAI PENGIRIM')
                        <tr class="text-center">
                            <td>{{ $i++ }}. </td>
                            <td>
                                {{-- if else nomor stt link ? --}}
                                <a class="detaillink" href="#" id-data="{{ $stt->id_stt }}">{{ $stt->kode_stt }}</a>
                                <form id="{{ $stt->id_stt }}" action="rekapstatusbysttdetail/{{ $stt->id_stt }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="nm_sbg" value="{{ $stt->nm_sbg_update }}" hidden>
                                    <input type="text" name="status_awal" value="{{ $status_awal }}" hidden>
                                    <input type="text" name="status_akhir" value="{{ $status_akhir }}" hidden>
                                </form>
                            </td>
                            <td>
                                {{ $stt->n_stat }}
                            </td>
                            <td>
                                {{ $stt->n_awal }}
                            </td>
                            <td class=" text-success">
                                {{ $stt->n_tepat }}
                            </td>
                            <td class=" text-danger">
                                {{ $stt->n_telat }}
                            </td>
                        </tr>
                    @endif
                @endforeach

                {{-- BLOCK PENERIMA --}}
                @php
                    $totaldata_pen = 0;
                @endphp
                @foreach ($sttlist as $stt_pen)
                    @if ($stt->nm_sbg_update == 'SEBAGAI PENERIMA')
                        @php
                            $totaldata_pen++;
                        @endphp
                    @endif
                @endforeach
                @if ($totaldata_pen > 0)
                    <tr>
                        <td colspan="11" class="text-left bg-dark text-white" style="font-size: 12px;">2. Sebagai Penerima
                        </td>
                    </tr>
                @endif
                @foreach ($sttlist as $stt_pen_data)
                    @if ($stt_pen_data->nm_sbg_update == 'SEBAGAI PENERIMA')
                        <tr class="text-center">
                            <td>{{ $i++ }}. </td>
                            <td>
                                {{-- if else nomor stt link ? --}}
                                <a class="detaillink" href="#"
                                    id-data="{{ $stt_pen_data->id_stt }}">{{ $stt_pen_data->kode_stt }}</a>
                                <form id="{{ $stt_pen_data->id_stt }}"
                                    action="rekapstatusbysttdetail/{{ $stt_pen_data->id_stt }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="nm_sbg" value="{{ $stt_pen_data->nm_sbg_update }}" hidden>
                                    <input type="text" name="status_awal" value="{{ $status_awal }}" hidden>
                                    <input type="text" name="status_akhir" value="{{ $status_akhir }}" hidden>
                                </form>
                            </td>
                            <td>
                                {{ $stt_pen_data->n_stat }}
                            </td>
                            <td>
                                {{ $stt_pen_data->n_awal }}
                            </td>
                            <td class=" text-success">
                                {{ $stt_pen_data->n_tepat }}
                            </td>
                            <td class=" text-danger">
                                {{ $stt_pen_data->n_telat }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
        {{-- if pengirim --}}
        @if ($totaldata > 0)
            <tfoot>
                @php
                    $grandstat_pengirim = 0;
                    $grandlebih_pengirim = 0;
                    $grandsama_pengirim = 0;
                    $grandkurang_pengirim = 0;
                @endphp
                @foreach ($sttlist as $stt)
                    @php
                        $grandstat_pengirim += $stt->n_stat;
                        $grandlebih_pengirim += $stt->n_awal;
                        $grandsama_pengirim += $stt->n_tepat;
                        $grandkurang_pengirim += $stt->n_telat;
                    @endphp
                @endforeach
                {{-- Grand Total --}}
                <tr class="text-center text-dark" style="background-color:whitesmoke; font-weight:900;">
                    <td colspan="2">Grand Total</td>
                    <td>{{ $grandstat_pengirim }}</td>
                    <td>{{ $grandlebih_pengirim }}</td>
                    <td class=" text-success">{{ $grandsama_pengirim }}</td>
                    <td class=" text-danger">{{ $grandkurang_pengirim }}</td>
                </tr>
            </tfoot>
        @endif
        {{-- if penerima --}}
        @if ($totaldata_pen > 0)
            <tfoot>
                @php
                    $grandstat = 0;
                    $grandlebih = 0;
                    $grandsama = 0;
                    $grandkurang = 0;
                @endphp
                @foreach ($sttlist as $stt_pen)
                    @php
                        $grandstat += $stt_pen->n_stat;
                        $grandlebih += $stt_pen->n_awal;
                        $grandsama += $stt_pen->n_tepat;
                        $grandkurang += $stt_pen->n_telat;
                    @endphp
                @endforeach
                {{-- Grand Total --}}
                <tr class="text-center text-dark" style="background-color:whitesmoke; font-weight:900;">
                    <td colspan="2">Grand Total</td>
                    <td>{{ $grandstat }}</td>
                    <td>{{ $grandlebih }}</td>
                    <td class=" text-success">{{ $grandsama }}</td>
                    <td class=" text-danger">{{ $grandkurang }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            <a id="backpage" href="#" class="btn btn-primary">
                Kembali
            </a>
            <form class="rekapsttback" action="rekapentristatus" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="entri_awal" value="{{ $status_awal }}" hidden>
                <input type="text" name="entri_akhir" value="{{ $status_akhir }}" hidden>
            </form>
        </div>
    </div>
    <script>
        $('.detaillink').click(function(e) {
            e.preventDefault();
            idstt = $(this).attr('id-data');
            $("#" + idstt).submit();
        });
        $('#backpage').click(function() {
            $('.rekapsttback').submit();
        });
    </script>
@endsection
