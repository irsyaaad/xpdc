@extends('template.document')

@section('data')

    <table class="table table-responsive table-stripped" width="100%" style="margin-top: 2%">
		<thead style="background-color:grey; color: #fff">
			<tr>
				<th>No</th>
				<th>ID STT</th>
                <th>Kode STT</th>
				<th>Nama Pelanggan</th>
				<th>Jenis Barang</th>
				<th>Harga Pertanggungan</th>
				<th>Nominal Asuransi </th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ ($key+1) }}</td>
				<td>{{ $value->id_stt }}</td>
				<td>{{ $value->kode_stt }}</td>
				<td>@isset($value->pelanggan->nm_pelanggan)
                    {{ $value->pelanggan->nm_pelanggan }}
                @endisset</td>
                <td>@isset($value->tipekirim->nm_tipe_kirim)
                    {{ $value->tipekirim->nm_tipe_kirim }}
                @endisset</td>
                <td>Rp. {{ number_format($value->n_harga_pertanggungan, 2, ',', '.') }}</td>
				<td>Rp. {{ number_format($value->n_asuransi, 2, ',', '.') }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

<script>
    $(function(){
			$('#c_all').change(function(){

				if($(this).is(':checked')) {
					$(".id").prop("checked", true);
				}else{   
					$(".id").prop("checked", false);
				}

			});
		});
</script>
@endsection