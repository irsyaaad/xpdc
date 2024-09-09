@if(get_admin())
<div class="col-md-3">
	<label style="font-weight: bold;">
		Perusahaan Tujuan
	</label>
	<select class="form-control" id="filterperush" name="filterperush"></select>
</div>
@endif
<div class="col-md-3">
	<label style="font-weight: bold;">
		Perusahaan Asal
	</label>
	<select class="form-control" id="perushtj" name="perushtj">			
		@if(isset($perusahaan->nm_perush))
		<option value="{{ $perusahaan->id_perush }}">{{ strtoupper($perusahaan->nm_perush) }}</option>
		@endif
	</select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;">
		Status
	</label>
	<select class="form-control" id="statusstt" name="statusstt">
		<option value="0">-- Pilih Status --</option>
		<option value="1"> Dibuat </option>
		<option value="2"> Dikirim </option>
		<option value="3"> Diterima </option>
	</select>
</div>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Tanggal</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@php
			$ldate = date('Y-m-d H:i:s')
			@endphp
			<div class="modal-body">
				<p>Tanggal Kirim</p>
				<div class="row">
					<div class="col">
						<h6>Dari Tanggal</h6>
						<input type="date" class="form-control" name="dr_tgl_krm" id="dr_tgl_krm" value="@if(Session('dr_tgl_krm')!= null){{Session('dr_tgl_krm')}}@endif">
					</div>
					<div class="col">
						<h6>Sampai Tanggal</h6>
						<input type="date" class="form-control" name="sp_tgl_krm" id="sp_tgl_krm" value="@if(Session('sp_tgl_krm')!= null){{Session('sp_tgl_krm')}}@endif">
					</div>
				</div>
				<hr>
				<p>Tanggal Terima</p>
				<div class="row">
					<div class="col">
						<h6>Dari Tanggal</h6>
						<input type="date" class="form-control" name="dr_tgl_trm" id="dr_tgl_trm" value="@if(Session('dr_tgl_trm')!= null){{Session('dr_tgl_trm')}}@endif">
					</div>
					<div class="col">
						<h6>Sampai Tanggal</h6>
						<input type="date" class="form-control" name="sp_tgl_trm" id="sp_tgl_trm" value="@if(Session('sp_tgl_trm')!= null){{Session('sp_tgl_trm')}}@endif">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-search"></i>	Pilih</span></button>
			</div>
		</div>
	</div>
</div>

@section('script')
<script type="text/javascript">
	$('#perushtj').select2({
		placeholder: 'Cari Perusahaan ....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url('getPerusahaan') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#perushtj').empty();
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
	$('#filterasal').select2({
		placeholder: 'Cari Wilayah ....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url('getwilayah') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#pengirim_id_region').empty();
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
	@if(Session('statusstt')!=null)
	$("#statusstt").val('{{ Session("statusstt") }}');
	@endif
	var idstatus = "";
	function CheckStatus(){
		$("#modal-status").modal('show');
	}
	
	function goSubmitUpdate() {
		$("#form-status").submit();
	}
	function html(){
		window.location = "{{ url(Request::segment(1)."/cetak") }}";
	}
	
</script>
@endsection