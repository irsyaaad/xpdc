@php
#dd($data);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data->kode_stt}}</title>
</head>
<style>
    @media print {
        @page {
            overflow: auto;
            height: 100%;
            width: 210mm;
            height: 297mm;
            margin-top: 0cm;
            margin-bottom: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }
    }

    table.border-T {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    table.border-T th,
    table.border-T td {
        padding: 1px;
        border: 1px solid #000000;
    }

    .umum,
    .umum tr td {
        vertical-align: top;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
        font-size: 8pt;
        line-height: 12px;
        
    }

    .image-logo-kop {
        height: 55px;
    }

    .title-bg {
        font-size: 5pt;
        background-color: yellow;
        color: red;
        font-weight: bold;
    }

    .barcode {
        /* height: 45px; */
        height: 32px;
        width: 150px;
    }

    /* CHECKED */
    label,
    input[type='submit'] {
        display: block;
    }
    input:checked {
        border: none;
    }
    .label-check {
        display: flex;
        align-items: center;
    }

    #scissors {
        height: 10px; /* image height */
        width: 100%;
        margin: auto auto;
        /* background-image: url('http://i.stack.imgur.com/cXciH.png'); */
        background-repeat: no-repeat;
        background-position: right;
        position: relative;
    }
    #scissors div {
        position: relative;
        top: 20%;
        border-top: 1px dashed black;
        margin-top: -20px;
    }

</style>
<body>
     @for ($i = 0; $i < 3; $i++)
    <table width="100%" class="umum" 
    <?php 
        echo 'style = "margin-top:11px;"';
    ?>
    border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="30%" rowspan="2" style="text-align: center;">
                @php
                        if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                        }
                    @endphp
                <img src="{{ $perusahaan->logo }}" alt="" class="image-logo-kop"><br>
                <h4 style="margin-top: 15px; margin-bottom: 5px;">NIB(JPT) 9120 3056 52214</h4>
            </td>
            <td width="30%" rowspan="2" style="text-align: center; vertical-align: middle;">
                <h3 style="margin-top: 0px; margin-bottom: 2px;">AIRWAYBILL</h3>
                @php
                    echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(''.$data->kode_stt.'', 'C39+',3,33) . '" alt="barcode" class="barcode"  /><br>';
                @endphp
                <h2 style="margin-top: 10px; margin-bottom: 2px;">{{$data->kode_stt}}</h2>
            </td>
            <td width="20%">
                <table width="100%" class="umum border-T" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="title-bg" style="font-weight: bold; text-align: center;">KOTA ASAL</td>
                    </tr>
                    <tr>
                        <td style="height: 2vh; text-align: center; vertical-align: middle;">
                            {{$data->asal->nama_wil}}
                        </td>
                    </tr>
                </table>
            </td>
            <td width="1%"></td>
            <td width="20%">
                <table width="100%" class="umum border-T" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="title-bg" style="font-weight: bold; text-align: center;">KOTA TUJUAN</td>
                    </tr>
                    <tr>
                        <td style="height: 2vh; text-align: center; vertical-align: middle;">
                            {{$data->tujuan->nama_wil}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">
                TANGGAL KIRIM : {{  \Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('l, d F Y') }}
            </td>
        </tr>
    </table>

    <table width="100%" class="umum border-T" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="30%" class="title-bg" colspan="2"><i>INFORMASI PENERIMA</i></td>
            <td width="30%" class="title-bg" colspan="2" style="text-align: center;">INFORMASI PENGIRIM</td>
            <td width="13%" class="title-bg" style="text-align: center;">ISI BARANG</td>
            <td width="13%" class="title-bg" style="text-align: center;">PACKING</td>
            <td width="13%" class="title-bg" style="text-align: center;">LAYANAN</td>
        </tr>
        <tr>
            <td colspan="2">NAMA / NAME <br>
                <p style="font-weight: bold;">{{$data->penerima_nm}}</p>
            </td>
            <td colspan="2">NAMA / NAME <br>
                <p style="font-weight: bold;">{{$data->pengirim_nm}}</p>
            </td>
            <td style="text-align: center; font-weight: bold;"><p>{{$data->tipekirim->nm_tipe_kirim}}</p></td>
            <td style="text-align: center; font-weight: bold;"><p>{{strtoupper($data->cara_kemas)}}</p></td>
            <td style="text-align: center; font-weight: bold;"><p>{{$data->layanan->nm_layanan}}</p></td>
        </tr>
        <tr>
            <td rowspan="3" colspan="2">ALAMAT / ADDRESS <br>
                <p style="margin: 0.5em 0em;">{{$data->penerima_alm}}</p>
            </td>
            <td rowspan="3" colspan="2">ALAMAT / ADDRESS <br>
                <p style="margin: 0.5em 0em;">{{$data->pengirim_alm}}</p>
            </td>
            <td colspan="3" class="title-bg">BIAYA / CHARGE</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">JENIS PEMBAYARAN</td>
            <td style="font-weight: bold;">{{$data->cara->nm_cr_byr_o}}</td>
        </tr>
        <tr>
            <td colspan="2">KEMAS ULANG / REPACKING</td>
            <td>{{$data->n_packing}}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2">TLP / PHONE <br>
                <p style="margin: 0.5em 0em;">{{$data->penerima_telp}}</p>
            </td>
            <td rowspan="2" colspan="2">TLP / PHONE <br>
                <p style="margin: 0.5em 0em;">{{$data->pengirim_telp}}</p>
            </td>
            <td colspan="2">ASURANSI / INSURANCE</td>
            <td>{{$data->n_asuransi ?? ''}}</td>
        </tr>
        <tr>
            <td colspan="2">TOTAL BIAYA / TOTAL CHARGE</td>
            <td>{{toRupiah($data->c_total)}}</td>
        </tr>
        <tr>
            <td width="20%" rowspan="3">TTD PENGIRIM :</td>
            <td width="20%" rowspan="3" colspan="2">TTD PENERIMA :</td>
            <td width="20%" rowspan="3">ADMIN : <br>
                <p style="font-weight: bold;" >{{$data->user->nm_user}}</p>
            </td>
            <td colspan="2" class="title-bg" style="text-align: center;">BERAT BARANG</td>
            <td rowspan="2" class="title-bg" style="text-align: center; vertical-align: middle;">JUMLAH KOLI</td>
        </tr>
        <tr>
            <td class="title-bg" style="text-align: center;">KG-V</td>
            <td class="title-bg" style="text-align: center;">M3</td>
        </tr>
        <tr>
            <td style="text-align: center;"><p style="margin: 0.3em 0em;">{{$data->n_berat}}</p></td>
            <td style="text-align: center;"><p style="margin: 0.3em 0em;">{{$data->n_volume}}</p></td>
            <td style="text-align: center;"><p style="margin: 0.3em 0em;">{{$data->n_koli}}</p></td>
        </tr>
    </table>

    <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0" style="margin-top: 5px; margin-bottom: 25px;">
        <tr>
            @if($data->asuransi)
            <td style="color: green; font-weight: bold;font-size:8px;">KIRIMAN DI ASURANSIKAN</td>
            @else
            <td style="color: red; font-weight: bold;font-size:8px;">KIRIMAN TIDAK DI ASURANSIKAN, JIKA TERJADI HILANG/RUSAK PIHAK EXPEDISI TIDAK MENERIMA CLAIM BARANG</td>
            @endif
        </tr>
    </table>
     @if ($i == 2)
        @continue
    @else
    <div id="scissors">
        <div></div>
    </div>
    @endif
    @endfor
</body>
</html>
