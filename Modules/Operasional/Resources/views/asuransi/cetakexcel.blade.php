<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Data Asuransi</title>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data xls");
    ?>
    <style>

    </style>
</head>
<body>
    <style type="text/css">
        body{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        table{
            margin: 20px auto;
            border-collapse: collapse;
        }
        table th,
        table td{
            /* border: 1px solid #3c3c3c; */
            border-width: 0.1em;
            padding: 3px 8px;

        }
        a{
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
        </style>
    <table>
        <thead>
            <th >No</th>
            <th >DO DATE</th>
            <th >ETD</th>
            <th >CABANG</th>
            <th >NAMA KAPAL</th>
            <th >NO PLAT</th>
            <th >DM/STT</th>
            <th >PELANGGAN</th>
            <th >JENIS BARANG</th>
            <th >QTY</th>
            <th >KOTA ASAL</th>
            <th >KOTA TUJUAN</th>
            <th >NILAI PERTANGGUNGAN</th>
            <th >KETERANGAN</th>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td >{{ $key+1 }}</td>
                    <td >@isset($value->tgl_berangkat)
                        {{dateindo($value->tgl_berangkat)}}
                    @endisset</td>
                    <td >@isset($value->tgl_sampai)
                        {{dateindo($value->tgl_sampai)}}
                    @endisset</td>
                    <td >-</td>
                    <td >@isset($value->nm_kapal)
                        {{$value->nm_kapal}}
                    @endisset</td>
                    <td >@isset($value->no_identity)
                        {{$value->no_identity}}
                    @endisset</td>
                    <td >
                        @isset($value->id_stt){{$value->id_stt}} @endisset / @isset($value->no_dm) {{$value->no_dm}}@endisset
                    </td>
                    <td >@isset($value->pelanggan->nm_pelanggan)
                        {{$value->pelanggan->nm_pelanggan}}
                    @endisset</td>
                    <td >@isset($value->tipebarang->nm_tipe_kirim)
                        {{$value->tipebarang->nm_tipe_kirim}}
                    @endisset</td>
                    <td >@isset($value->qty)
                        {{$value->qty}}
                    @endisset</td>
                    <td >@isset($value->asal->nama_wil)
                        {{$value->asal->nama_wil}}
                    @endisset</td>
                    <td >@isset($value->tujuan->nama_wil)
                        {{$value->tujuan->nama_wil}}
                    @endisset</td>
                    <td >@isset($value->harga_pertanggungan)
                        {{$value->harga_pertanggungan}}
                    @endisset</td>
                    <td >@isset($value->keterangan)
                        {{strtoupper($value->keterangan)}}
                    @endisset</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>
</html>
