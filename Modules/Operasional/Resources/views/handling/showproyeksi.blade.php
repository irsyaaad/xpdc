<div class="row" style="padding:15px">
	<div class="col-md-4">
		<h4 style="margin-left: 3%"><i class="fa fa-money"></i>
			<b>Data Biaya Handling</b>
		</h4>
	</div>
	<div class="col-md-8 text-right">
		<button class="btn btn-md btn-primary" style="margin-top: 10px" data-toggle="modal" data-target="#modal-create" onclick="refresh()"><span> <i class="fa fa-plus"> </i> </span> Tambah Biaya</button>
	</div>
	<div class="col-md-12" style="margin-top:10px">
		<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
			@csrf
			<input type="hidden" name="_method" value="GET">
			<table class="table table-responsive table-bordered" id="tableasal">
				<thead style="background-color : #ececec">
					<tr>
						<th>No. </th>
						<th>
							Biaya
							> Tgl Posting
						</th>
						<th>Kelompok</th>
						<th>Nomor STT</th>
						<th>Nominal</th>
						<th>Keterangan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($biaya as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							@if(isset($value->group->nm_biaya_grup))
							{{ $value->group->nm_biaya_grup }}
							@endif <br> >
							{{ $value->tgl_posting }}
						</td>
						<td>
							@if(isset($value->group->klp))
							{{ $value->group->klp }}
							@endif
						</td>
						<td>{{ $value->kode_stt }}</td>
						<td>
							{{ toRupiah($value->nominal) }}
						</td>
						<td>{{ $value->keterangan }}</td>
						<td>
							<button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_biaya }}', '{{ $value->id_biaya_grup }}', '{{ $value->nominal }}', '{{ $value->tgl_posting }}', '{{ $value->keterangan }}')" data-toggle="tooltip" data-placement="bottom" title="Edit">
								<span><i class="fa fa-edit"></i></span>
							</button>
							
							<button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmhandling/'.$value->id_biaya.'/deletebiaya') }}')" data-toggle="tooltip" data-placement="bottom" title="Hapus STT">
								<span><i class="fa fa-times"></i></span>
							</button>
						</td>
					</tr>
					@endforeach
					
				</tbody>
			</table>
		</form>
	</div>
</div>

<div class="modal fade" id="modal-create" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			
			<form method="POST" action="{{ url(Request::segment(1)."/savebiaya") }}" id="form-data">
				<input type="hidden" name="_method" id="_method" value="PUT">
				<input type="hidden" name="id_handling" id="id_handling" value="{{ Request::segment(2) }}">
				@csrf
				<div class="modal-body">
					<div class="row">
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="id_stt">
								<b>Nomor STT</b> <span class="span-required"></span>
							</label>
							
							<select class="form-control m-input m-input--square" id="id_stt" name="id_stt">
								<option value="">-- Pilih STT --</option>
								@foreach($detail as $key => $value)
								<option value="{{ $value->id_stt }}">{{ $value->kode_stt }}</option>
								@endforeach
							</select>
							
							@if ($errors->has('id_stt'))
							<label style="color: red">
								{{ $errors->first('id_stt') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="id_biaya_grup">
								<b>Group Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<select class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" required>
								<option value="">-- Pilih Group Biaya --</option>
								@foreach($group as $key => $value)
								<option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
								@endforeach
							</select>
							
							@if ($errors->has('id_biaya_grup'))
							<label style="color: red">
								{{ $errors->first('id_biaya_grup') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="nominal">
								<b>Nominal Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required maxlength="16" />
							
							@if ($errors->has('nominal'))
							<label style="color: red">
								{{ $errors->first('nominal') }}
							</label>
							@endif
						</div>

						<div class="col-md-12 text-left" style="padding-top: 10px">
                            <label for="nominal">
                                <b>Tanggal Posting</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" id="tgl_posting" name="tgl_posting" type="date" required/>
                            
                            @if ($errors->has('tgl_posting'))
                            <label style="color: red">
                                {{ $errors->first('tgl_posting') }}
                            </label>
                            @endif
                        </div>

						<div class="col-md-12" style="padding-top: 10px">
							<label for="keterangan">
								<b>Keterangan</b></span>
							</label>
							
							<textarea  class="form-control" id="keterangan" name="keterangan" maxlength="150" placeholder="Masukan Keterangan"></textarea>

							@if ($errors->has('keterangan'))
							<label style="color: red">
								{{ $errors->first('keterangan') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-right" style="padding-top: 15px">
							<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
							<button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
						</div>
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>

@section('script')
<script>
	$( document ).ready(function() {
		$('#id_biaya_grup').select2({
			placeholder: 'Cari Biaya ....',
			ajax: {
				url: '{{ url('getSettingBiaya') }}',
				minimumInputLength: 3,
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results:  $.map(data, function (item) {
							return {
								text: item.value,
								id: item.kode
							}
						})
					};
				},
				cache: true
			}
		});
		
	});

	function goEdit(id, id_group, nominal, tgl_posting, keterangan){
		$("#_method").val("PUT");
		$("#nominal").val(nominal);
		// $("#id_biaya_grup").val(id_group);
		$("#id_biaya_grup").val(id_group).trigger("change");
		$("#form-data").attr("action", "{{ url(Request::segment(1)) }}/"+id+"/updatebiaya");
		$("#modal-create").modal("show");
		$("#tgl_posting").val(tgl_posting);
		$("#keterangan").text(keterangan);
	}
	
	function refresh(){
		$("#_method").val("POST");
		$("#form-data").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2)) }}/savebiaya");
		$("#nominal").val("");
		$("#id_biaya_grup").val("").trigger("change");
		$("#keterangan").text("");
		$("#tgl_posting").val('{{ date("Y-m-d") }}');
	}
</script>
@endsection