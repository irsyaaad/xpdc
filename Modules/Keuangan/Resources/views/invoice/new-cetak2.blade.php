
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{$invoice->no_invoice}}</title>
</head>
<style>
    @media print {
        @page {
            overflow: auto;
            height: 100%;
            width: 210mm;
            height: 99mm;
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
        padding: 5px;
        border: 1px solid #000000;
    }

    .judul,
    .judul tr td {
        vertical-align: top;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
        font-size: 10pt;
        line-height: 10px;
        font-weight: bold;
    }

    .border-bottom {
        border-bottom: 1px solid #000000;
    }

    .border-dash-top {
        border-bottom: 1px dashed #000000;
    }

    .bg-td {
        background-color: #b4b4b4;
    }

    .umum,
    .umum tr td {
        vertical-align: top;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
        font-size: 8pt;
        line-height: 15px;
    }

    .umum-invoice,
    .umum-invoice tr td {
        vertical-align: top;
        font-family: Arial, Helvetica, sans-serif;
        text-align: justify;
        font-size: 7pt;
        line-height: 10px;
    }

    .image-logo-kop {
        height: 70px;
        margin-left: 15px;
        margin-right: 15px;
    }

    .image-logo-ttd {
        height: 40px;
    }

    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { font-size : 10px; position: fixed; left: 0px; bottom: -150px; right: 0px; height: 150px; background-color: light; }
    #footer .page:after { content: counter(page, upper-roman); }

</style>
<body>
    <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="70%" style="font-weight: bold;">HEAD OFFICE</td>
            <td width="30%" rowspan="3">
                @php            
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                @endphp
                <img src="{{ $perusahaan->logo }}" alt="" class="image-logo-kop">
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">{{ $perusahaan->alamat }}</td>
        </tr>
        <tr>
            <td>{{ $perusahaan->telp }}</td>
        </tr>
    </table>
    <hr style="border: 1px solid #000000;">
    <br>

    <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="60%">
                <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-weight: bold;">Bill to :</td>
                    </tr>
                    <tr>
                        <td>{{$invoice->pelanggan->nm_pelanggan}}</td>
                    </tr>
                    <tr>
                        <td>{{$invoice->pelanggan->alamat}}</td>
                    </tr>
                </table>
            </td>
            <td width="40%" style="text-align: right;">
                <br><br>
                <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td rowspan="3" width="40%"></td>
                        <td width="20%">No. Invoice</td>
                        <td width="2%">:</td>
                        <td>{{$invoice->kode_invoice}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->tgl)->isoFormat('D MMMM Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" class="judul" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><h3>INVOICE</h3></td>
        </tr>
    </table>
    <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="font-weight: bold;">
                Ditujukan Kepada: <br>
                {{$invoice->pelanggan->nm_pelanggan}} <br>
                HP {{$invoice->pelanggan->telp}}
            </td>
        </tr>
    </table>
    <br>

    <table width="100%" class="umum-invoice border-T" border="0" cellpadding="0" cellspacing="0">
        <thead style="text-align: center;">
            <tr>
                <th width="5%">NO</th>
                <th width="10%">AWB</th>
                <th width="10%">ASAL</th>
                <th width="15%">TUJUAN</th>
                <th width="5%">ZONA</th>
                <th width="5%">KOLI</th>
                <th width="5%">BERAT (KG)</th>
                <th width="5%">MNIM CHARGE</th>
                <th width="10%">HARGA</th>
                <th width="10%">BIAYA TAMBAHAN DELIVERY</th>
                <th width="10%">GRAND TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sum_tot_Price = 0;
            $koliTot = 0;
            $beratTot = 0;
            ?>
            @foreach($stt as $key => $value)
                <tr>
                    <td style="text-align: center;">{{ $key+1 }}</td>
                    <td style="text-align: center;">{{$value->kode_stt}}</td>
                    <td>{{ $value->asal }}</td>
                    <td>{{ $value->tujuan }}</td>
                    <td style="text-align: center;">A</td>
                    <td style="text-align: center;">{{ $value->n_koli }}</td>
                    <td style="text-align: center;">{{ $value->n_berat }}</td>
                    <td style="text-align: center;">{{ $value->min_brt }}</td>
                    <td style="text-align: right;">Rp. {{ number_format($value->n_tarif_brt, 0, ",", ".") }}</td>
                    <td style="text-align: right;">
                        Diskon : Rp. {{number_format($value->n_diskon, 0, ',', '.')}}
                        <br>                        
                        Asuransi : Rp. {{number_format($value->n_asuransi, 0, ',', '.')}}
                        <br>                        
                        PPN : Rp. {{number_format($value->n_ppn, 0, ',', '.')}}
                    </td>
                    <td style="text-align: right;">Rp.{{ number_format($value->c_total, 0, ",", ".") }}</td>
                </tr>
            <?php
            $sum_tot_Price  += $value->c_total;
            $koliTot        += $value->n_koli;
            $beratTot       += $value->n_berat;

            ?>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td style="text-align: center; font-weight: bold;">TOTAL</td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-weight: bold;">{{$koliTot}}</td>
                <td style="text-align: right; font-weight: bold;">{{$beratTot}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-weight: bold;">Rp. {{ number_format($sum_tot_Price, 0, ",", ".")}}</td>
            </tr>
        </tbody>
    </table>
    <br>

    <table width="100%" class="umum" border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td style="font-weight: bold;">Terbilang :</td>
        </tr>
        <tr>
            <td style="font-weight: bold;" class="bg-td">{{ terbilangfunc($sum_tot_Price) }}</td>
        </tr>
    </table>
    <br>

    <table width="100%" class="umum" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="3" style="font-weight: bold;">Keterangan</td>
        </tr>
        <tr>
            <td width="12%">Payment</td>
            <td width="1%">:</td>
            <td width="85%">Segera</td>
        </tr>
        <tr>
            <td>Account Number</td>
            <td>:</td>
            <td>{{$invoice->nomor_rekening}}</td>
        </tr>
        <tr>
            <td>Nama Bank</td>
            <td>:</td>
            <td>{{$invoice->nama_bank}} a.n {{$invoice->atas_nama_bank}}</td>
        </tr>
    </table>
    <br>

    <table width="100%" class="umum" cellpadding="5" cellspacing="4">
        <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%" style="text-align: center;">
                <br>
                <b>{{ $perusahaan->nm_perush }}</b> <br>
                <img src="{{ $perusahaan->logo }}" alt="" class="image-logo-kop">
                {{$invoice->petugas}} <br>
                Supervisor Finance {{ $perusahaan->nm_perush }}
            </td>
        </tr>
    </table>

    <div id="footer">
        <p >Dicetak pada : {{ \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->isoFormat('dddd, D MMMM Y HH:mm:ss A') }}</p>
    </div>
</body>
</html>

@php
function terbilangfunc($angka)
{
    $arr = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

    if ($angka < 12)
        return " " . $arr[$angka];
    elseif ($angka < 20)
        return terbilangfunc($angka - 10) . " belas";
    elseif ($angka < 100)
        return terbilangfunc($angka / 10) . " puluh" . terbilang($angka % 10);
    elseif ($angka < 200)
        return "seratus" . terbilangfunc($angka - 100);
    elseif ($angka < 1000)
        return terbilangfunc($angka / 100) . " ratus" . terbilang($angka % 100);
    elseif ($angka < 2000)
        return "seribu" . terbilangfunc($angka - 1000);
    elseif ($angka < 1000000)
        return terbilangfunc($angka / 1000) . " ribu" . terbilangfunc($angka % 1000);
    elseif ($angka < 1000000000)
        return terbilangfunc($angka / 1000000) . " juta" . terbilangfunc($angka % 1000000);
}
@endphp
