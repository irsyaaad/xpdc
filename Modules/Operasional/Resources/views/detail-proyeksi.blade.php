<br>
<div class="row">
	<div class="col-md-12">
		<h4 style="margin-left: 1%"><i class="fa fa-money"></i>
			<b>Data Proyeksi Biaya</b>
		</h4>
		<br>
		<table class="table table-responsive table-striped">
			<thead style="background-color: grey; color:#fff">
				<tr>
					<th>No</th>
					<th>Group Biaya</th>
					<th>Biaya</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($detail as $key => $value)
				<tr>
					<td>
						{{ ($key+1) }}
					</td>
					<td>
						@if(isset($value->grup->nm_biaya_grup)){{ strtoupper($value->grup->nm_biaya_grup) }}@endif
					</td>
					<td>
						{{ $value->nominal }}
					</td>
					<td>
						<form method="POST" action="{{ url(Request::segment(1).'/deletedetail/'.$value->id_detail) }}"  id="form-delete{{ $value->id_detail}}" name="form-delete{{ $value->id_detail}}">
							@csrf
							{{method_field("DELETE") }}
							
							<button type="button" onclick="goEdit('{{ $value->id_detail }}', '{{ $value->id_biaya_grup }}', '{{ $value->nominal }}')" class="btn btn-sm btn-warning">
								<i class="fa fa-edit"></i>
							</button>
							
							<button type="submit" class="btn btn-sm btn-danger">
								<i class="fa fa-times"></i>
							</button>
							
						</form>
					</td>
				</tr>
				@endforeach
				
				<tr>
					<form method="POST" action="{{ url(Request::segment(1).'/savedetail') }}" id="form-detail" name="form-detail">
						<td>
							@csrf
							<input type="hidden" name="_method" id="_method">
							<input type="hidden" name="id_proyeksi" id="id_proyeksi" value="{{ Request::segment(2) }}">
						</td>
						<td>
							
							<select class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" required="required">
								@foreach($group as $key => $value)
								<option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
								@endforeach
							</select>
							
							@if ($errors->has('id_biaya_grup'))
							<label style="color: red">
								{{ $errors->first('id_biaya_grup') }}
							</label>
							@endif
							
						</td>
						<td>
							<input type="number" name="nominal" id="nominal" placeholder="Masukan Biaya" maxlength="100" class="form-control" required="required">
							
							@if ($errors->has('nominal'))
							<label style="color: red">
								{{ $errors->first('nominal') }}
							</label>
							@endif
						</td>
						<td>
							<button class="btn btn-sm btn-success">
								<i class="fa fa-save"></i>
							</button>
							
							<button class="btn btn-sm btn-danger" type="button" onclick="goCancel()">
								<i class="fa fa-times"></i>
							</button>
						</td>
					</form>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	
	function goEdit(id, grup, nominal){
		$("#form-detail").attr("action", "{{ url(Request::segment(1).'/editdetail') }}/"+id);
		$("#id_biaya_grup").val(grup);
		$("#nominal").val(nominal);
		$("#_method").val("PUT");
	}
	
	function goCancel() {
		$("#form-detail").attr("action", "{{ url(Request::segment(1).'/savedetail') }}");
		$("#nominal").val("");
		$("#_method").val("POST");
	}
	
</script>