<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Laporan Import Rute</title>
    <style>
        @media print {
            @page {
                size: A4 portrait;
            }
        }

        body {
            font-family: Tahoma !important;
            font-size: 12px;
        }
    </style>
    @php
        $excel = preg_match('/excel/', url()->full());
    @endphp
    @if ($excel == 1)
        <?php
        header('Content-type: application/vnd-ms-excel');
        header('Content-Disposition: attachment; filename=LaporanAbsensi' . $perusahaan->nm_perush . date('mY') . '.xls');
        ?>
    @endif
</head>

<body class="container">
    @if ($excel == 0)
        <div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
            <a href="#" class="btn btn-sm btn-warning" id="winclose"><i class="fa fa-reply"></i>
                Tutup</a>
            <button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i> Cetak</button>
        </div>
    @endif
    <div class="container" style=" margin-top:10px;">
        <div class="row">
            <div class="col-3">
                <center>
                    @php

                        if (Storage::exists('public/uploads/perusahaan/' . $perusahaan->logo)) {
                            $path = 'public/uploads/perusahaan/' . $perusahaan->logo;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                            $perusahaan->logo = $image;
                        }
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </center>
            </div>
            <div class="col-8">

                <h5 class="text-center">{{ $perusahaan->nm_perush }}</h5>
                <h6 class="text-center">{{ $perusahaan->alamat }},
                    {{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</h6>
                <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>

            </div>
        </div>
    </div>
    <div class="container" style="margin-top:20px">
        @if (count($data) > 0)
            <h5>Total Data: {{ count($data) }}</h5>

            @php
                // Hitung total data yang lengkap
                $totalDataWithValues = $data
                    ->where('harga', '>=', 1)
                    ->whereNotNull('harga')
                    ->whereNotNull('nm_ven')
                    ->count();

                // Hitung total data yang belum lengkap
                $totalDataEmpty = count($data) - $totalDataWithValues;
            @endphp

            <h5 class="card-title">
                Total Data Lengkap : {{ $totalDataWithValues }}
            </h5>

            <h5 class="card-title">
                Total Data Belum Lengkap: {{ $totalDataEmpty }}
            </h5>
        @else
            <h5>Total Data: 0</h5>
        @endif


        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kab/Kota Asal</th>
                    <th>Provinsi Tujuan</th>
                    <th>Kab/Kota Tujuan</th>
                    <th>Kec. Tujuan</th>
                    <th>Vendor</th>
                    <th width="150">Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                $startIndex = 1;
                @endphp
               @foreach ($data as $key => $value)
               @if ($filter['stts'] == 2 && $value->harga >= 1 && !empty($value->nm_ven))
                   <tr>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ $startIndex++ }}.
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           @if (isset($filter['asal']->id_wil))
                               {{ $filter['asal']->nama_wil }}
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->prov_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kab_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kec_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->nm_ven) }}
                           @if ($value->harga < 1)
                               @if (empty($value->nm_ven))
                                   <b><span class="text-danger">Belum Ada vendor</span></b>
                               @endif
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ toRupiah($value->harga) }}</td>
                   </tr>
               @elseif ($filter['stts'] == 3 && $value->harga < 1)
                   <tr>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ $startIndex++ }}.
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           @if (isset($filter['asal']->id_wil))
                               {{ $filter['asal']->nama_wil }}
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->prov_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kab_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kec_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->nm_ven) }}
                           @if ($value->harga < 1)
                               @if (empty($value->nm_ven))
                                   <b><span class="text-danger">Belum Ada vendor</span></b>
                               @endif
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ toRupiah($value->harga) }}</td>
                   </tr>
               @elseif ($filter['stts'] == 1)
                   <tr>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ $startIndex++ }}.
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           @if (isset($filter['asal']->id_wil))
                               {{ $filter['asal']->nama_wil }}
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->prov_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kab_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->kec_tujuan) }}</td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ strtoupper($value->nm_ven) }}
                           @if ($value->harga < 1)
                               @if (empty($value->nm_ven))
                                   <b><span class="text-danger">Belum Ada Data vendor</span></b>
                               @endif
                           @endif
                       </td>
                       <td @if ($value->harga < 1) class="bg-warning text-dark" @endif>
                           {{ toRupiah($value->harga) }}</td>
                   </tr>
               @endif
           @endforeach   
            </tbody>
        </table>
    </div>
</body>

</html>
<script>
    $("#cetak").click(function() {
        $("#tombol").hide();
        window.print();
    });
    $("#winclose").click(function() {
        window.close();
    });
    $('#id_asal').select2({
        placeholder: 'Pilih Wilayah Asal',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_asal').empty();

                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            success: function() {
                $('#id_asal').addClass('form-select');
            },
            cache: false
        }
    });

    $('#id_tujuan').select2({
        placeholder: 'Pilih Wilayah Tujuan',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_tujuan').empty();
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            success: function() {
                $('#id_tujuan').addClass('form-select');
            },
            cache: false
        }
    });

    @if (isset($filter['asal']->id_wil))
        $('#id_asal').append(
            '<option value="{{ $filter['asal']->id_wil }}">{{ $filter['asal']->nama_wil }}</option>');
    @endif

    @if (isset($filter['tujuan']->id_wil))
        $('#id_tujuan').append(
            '<option value="{{ $filter['tujuan']->id_wil }}">{{ $filter['tujuan']->nama_wil }}</option>');
    @endif
</script>
