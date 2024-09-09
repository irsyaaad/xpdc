@extends('template.document')

@section('data')

<form action="{{ url('asuransistt') }}" method="POST">
    @csrf
    <div class="col-md-12">
        <div class="text-right">
            <button class="btn btn-md btn-success" type="submit">
				<i class="fa fa-save"></i> Simpan
			</button>
        </div>
    </div>
    <table class="table table-responsive table-stripped" width="100%" style="margin-top: 2%">
		<thead style="background-color:grey; color: #fff">
			<tr>
				<th>No</th>
				<th>ID STT</th>
                <th>Kode STT</th>
				<th>Nama Pelanggan</th>
				<th>Jenis Barang</th>
				<th>Harga Pertanggungan</th>
				<th>Pilih Semua <label><input type="checkbox" style="width: 18px; height: 18px;" value="1" id="c_all" name="c_all"></label></th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ ($key+1) }}</td>
				<td>{{ $value->id_stt }}</td>
				<td>{{ $value->kode_stt }}</td>
				<td>@isset($value->perush_asal->nm_perush)
                    {{ $value->perush_asal->nm_perush }}
                @endisset</td>
                <td>@isset($value->tipekirim->nm_tipe_kirim)
                    {{ $value->tipekirim->nm_tipe_kirim }}
                @endisset</td>
                <td>{{$value->n_harga_pertanggungan}}</td>
				<td><input style="width: 18px; height: 18px;" type="checkbox" name="id[]" id="id{{ $value->id_stt }}" class="form-control id" value="{{  $value->id_stt }}"></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
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