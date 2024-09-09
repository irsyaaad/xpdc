<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<title>Cetak</title>
	<style>
        @media print{
            @page {size: A4 landscape;}
        }
    </style>
    
</head>
<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
</div>
<div class="container"  style=" margin-top:10px;">
  <div class="row">
    <div class="col-3">
        <center>
        @php
        
		if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
			$path = 'public/uploads/perusahaan/'.$perusahaan->logo;
			
			$full_path = Storage::path($path);
			$base64 = base64_encode(Storage::get($path));
			$image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
			$perusahaan->logo = $image;
        }

	    @endphp
            <img src="{{$perusahaan->logo}}" style="width: 120px">
        </center>
    </div>
    <div class="col-8">

        <h5 class="text-center">{{$perusahaan->nm_perush}}</h5>
        <h6 class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</h6>
        <h6 class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</h6>
       
    </div>
  </div>
</div>
    <table class="table table-bordered table-sm" style=" margin-top:10px; font-size:12px;">
    <thead class="text-center">
			<tr>
			<th>No</th>
			<th>No. STT</th>
			<th>Perusahaan</th>
			<th>Layanan</th>
			<th>Masuk</th>
			<th>Pengirim</th>
			<th>Asal</th>
			<th>Penerima</th>
			<th>Tujuan</th>
			<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
            <td class="text-center">{{$key+1}}</td>
			<td>
                {{ strtoupper($value->id_stt) }}
			</td>
			<td>
				@if(isset($value->perush_asal)){{ $value->perush_asal->nm_perush }}@endif
			</td>
			<td>
				@if(isset($value->layanan)){{ $value->layanan->nm_layanan }}@endif
			</td>
			<td>
				{{ dateindo($value->tgl_masuk) }}
			</td>
			<td>
				{{ $value->pengirim_nm }}
			</td>
			<td>
				@if(isset($value->asal)){{ $value->asal->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				{{ $value->penerima_nm }}
			</td>
			<td>
				@if(isset($value->tujuan)){{ $value->tujuan->nama_wil.", ".$value->penerima_alm }}@endif
			</td>
			<td>
				@if(isset($value->status->nm_ord_stt_stat)){{ $value->status->nm_ord_stt_stat }}@endif
			</td>
			</tr>
			@endforeach
		</tbody>
    </table>
</body>
</html>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>