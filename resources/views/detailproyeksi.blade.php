<div class="row">
	<div class="col-md-12">
		<center><h4><b>Data Proyeksi Tarif</b></h4></center>
		<table class="table table-responsive table-striped">
			<thead>
				<tr>
					<th>No </th>
					<th>Proyeksi </th>
					<th>Biaya </th>
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach($proyeksi as $key => $value)
				<tr>
					<td>{{ $key+1 }}</td>
					<td>@if(isset($value->proyeksi->nm_proyeksi)) {{ strtoupper($value->proyeksi->nm_proyeksi) }} @endif</td>
					<td>{{ $value->biaya }}</td>
					<td class="text-center">
						<form action="{{ url('tarifproyeksi').'/'.$value->id_tarifpro }}" method="post" id="form-delete{{ $value->id_tarifpro }}" name="form-delete{{ $value->id_tarifpro }}">
							{{ method_field("DELETE") }}
							@csrf
							
							<a href="{{ url(Request::segment(1)).'/'.$value->id_tarifpro.'/editproyeksi' }}" class="btn btn-sm btn-warning">
								<span><i class="fa fa-pencil"></i></span>
							</a>
							
							<button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ $value->id_tarifpro }}')">
								<span><i class="fa fa-times"></i></span>
							</button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>