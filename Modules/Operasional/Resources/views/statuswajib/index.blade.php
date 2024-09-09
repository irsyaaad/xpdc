@extends('template.document2')

@section('data')
<form method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    <input type="hidden" name="_method" value="POST">
	@csrf
	<div class="row mt-1">
		<div class="col-md-12" >
			<table class="table table-striped table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Status</th>
						<th>Kode Status</th>
						<th>Pengirim</th>
						<th>Penerima</th>
					</tr>
				</thead>
				<tbody>
					@foreach($status as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $value->nm_ord_stt_stat }}</td>
						<td>{{ $value->id_ord_stt_stat }}</td>
						<td><input type="checkbox" class="form-control" name="pengirim[]" id="pengirim" value="{{ $value->id_ord_stt_stat }}"
							@foreach ($data as $item)
								@if (($item->type == 1) && ($item->id_status == $value->id_ord_stt_stat )) 
									checked 
								@endif
							@endforeach>
						</td>
						<td><input type="checkbox" class="form-control" name="penerima[]" id="penerima" value="{{ $value->id_ord_stt_stat }}"
							@foreach ($data as $item)
								@if (($item->type == 2) && ($item->id_status == $value->id_ord_stt_stat )) 
									checked 
								@endif
							@endforeach>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="col-md-12 text-right">
				<button type="submit" class="btn btn-primary" > <i class="fa fa-paste"></i> Simpan</button>
			</div>
		</div>
	</div>
</form>
@endsection