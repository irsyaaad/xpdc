@extends('template.document2')

@section('data')
    <div class="card-header border-0 bg-transparent text-center">
        <div class=" card-title font-weight-bolder">
            <h5 class=" font-weight-bold">
                (@if (isset(Session('perusahaan')['id_perush']))
                    {{ strtoupper(Session('perusahaan')['id_perush']) }}
                @endif) Entri Status Barang Detail STT
            </h5>
            <p>
                Periode {{ $status_awal }} s/d {{ $status_akhir }}
            </p>
        </div>

    </div>
    <hr>
    <table class="table table-responsive table-bordered" id="tableasal">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th class="text-center" style="font-size: 12px;">No</th>
                <th class="text-center" style="font-size: 12px">Tanggal Masuk </th>
                <th class="text-center" style="font-size: 12px">ID STATUS</th>
                <th class="text-center" style="font-size: 12px">TGL BRK</th>
                <th class="text-center" style="font-size: 12px">TGL SBL</th>
                <th class="text-center" style="font-size: 12px">TGL STAT</th>
                <th class="text-center" style="font-size: 12px">INFO STAT</th>
                <th class="text-center" style="font-size: 12px">GRUP STAT</th>
                <th class="text-center" style="font-size: 12px">ID DM</th>
                <th class="text-center" style="font-size: 12px">SELISIH</th>
            </tr>
            <tr>
                <th class="text-left" colspan="10" style="font-size: 12px;">ID STT : {{ $detailstt[0]->kode_stt }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($detailstt as $detstt)
                <tr class="text-center" style="font-size: 12px;">
                    <td>{{ $i++ }}.</td>
                    <td>{{ $detstt->tgl_masuk }}</td>
                    <td>{{ $detstt->id_history }}</td>
                    <td>
                        @if ($detstt->tgl_berangkat != null)
                            {{ $detstt->tgl_berangkat }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td>{{ $detstt->tgl_sbl }}</td>
                    <td>{{ $detstt->tgl_update }}</td>
                    <td>{{ $detstt->keterangan }}</td>
                    <td>{{ $detstt->nm_status }}</td>
                    <td>
                        @if ($detstt->kode_dm != null)
                            {{ $detstt->kode_dm }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td>{{ $detstt->selisih }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            <a id="backpage" href="#" class="btn btn-primary">
                Kembali
            </a>
            <form id="rekapsttback" action="{{ route('rekapstatusbystt') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="nm_sbg" value="{{ $nm_sbg }}" hidden>
                <input type="text" name="status_awal" value="{{ $status_awal }}" hidden>
                <input type="text" name="status_akhir" value="{{ $status_akhir }}" hidden>
            </form>
        </div>
    </div>
    <script>
        $('#backpage').click(function() {
            $('#rekapsttback').submit();
        });
    </script>
@endsection
