<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan Asal
    </label>
    <select class="form-control" id="perush" name="perush">
        <option value="0">-- Pilih Perusahaan --</option>
    </select>
</div>
<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan tujuan
    </label>
    <select class="form-control" id="perushtj" name="perushtj">
        <option value="0">-- Pilih Perusahaan --</option>
    </select>
</div>
<div class="col-md-3">
    <label style="font-weight: bold;">
        Layanan
    </label>
    <select class="form-control" id="layanan" name="layanan">
        <option value="0">-- Pilih Layanan --</option>
    </select>
</div>

@section('script')
<script>
$.ajax({
        type: "GET",
        url: "{{ url("getPerusahaan") }}",
        dataType: "json",
        beforeSend: function (e) {
            if (e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function (response) {
            $.each(response, function (key, value) {
                $("#perush").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(Session('perush')!=null)
				$("#perush").val('{{ Session('perush') }}');
			@endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ url("getLayanan") }}",
        dataType: "json",
        beforeSend: function (e) {
            if (e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function (response) {
            $.each(response, function (key, value) {
                $("#layanan").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(Session('layanan')!=null)
				$("#layanan").val('{{ Session('layanan') }}');
			@endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

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
    $('#perush').select2({
		placeholder: 'Cari Perusahaan ....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url('getPerusahaan') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#perush').empty();
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
</script>

@endsection