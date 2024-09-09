<div class="col-md-3">
	<label style="font-weight: bold;">
		Dari Tanggal
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label style="font-weight: bold;">
		Sampai Tanggal
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-6">
	<label style="font-weight: bold;">
		Perusahaan
	</label>
	<select class="form-control" id="id_perush" name="id_perush[]" multiple></select>
</div>

@section('script')
<script>
	function html(){
		window.location = "{{ url(Request::segment(1)."/cetak") }}";
	}
	function excel(){
		window.location = "{{ url(Request::segment(1)."/excel") }}";
	}

    $('#id_perush').select2({
        placeholder: 'Cari Perusahaan ....',
        ajax: {
            url: '{{ url('getPerusahaan') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filterperush').empty();
                return {
                    results: $.map(data, function(item) {
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
</script>
@endsection
