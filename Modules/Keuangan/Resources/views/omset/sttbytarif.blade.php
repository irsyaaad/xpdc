@extends('template.document2')

@section('data')
@if(Request::segment(1)=="sttbytarif" and Request::segment(2)==null)
@include("template.filter")

<table class="table table-responsive table-striped table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
            <th>No</th>
            <th>Kode STT</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Tipe Barang</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Kubik</th>
            <th>Omset</th>
		</tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->kode_stt)){{$value->kode_stt}}@endif</td>
            <td>@if(isset($value->tgl_masuk)){{dateindo($value->tgl_masuk)}}@endif</td>
            <td>@if(isset($value->pelanggan->nm_pelanggan)){{$value->pelanggan->nm_pelanggan}}@endif</td>
            <td>@if(isset($value->tipekirim->nm_tipe_kirim)){{$value->tipekirim->nm_tipe_kirim}}@endif</td>
            <td>@if(isset($value->n_berat)){{$value->n_berat}}@endif</td>
            <td>@if(isset($value->n_volume)){{$value->n_volume}}@endif</td>
            <td>@if(isset($value->n_kubik)){{$value->n_kubik}}@endif</td>
            <td>@if(isset($value->c_total))Rp. {{ number_format($value->c_total, 0, ',', '.') }}@endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

@endsection
