@if(get_admin())
<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="filterperush" name="filterperush">
        <option value="0">-- Pilih Perusahaan --</option>
    </select>
</div>
@endif
<div class="col-md-3">
    <label style="font-weight: bold;">
        Pelanggan
    </label>
    <select class="form-control" id="id_plgn" name="id_plgn">
        @if(Session('id_plgn')!=null)
            <option value="{{ Session('id_plgn') }}">{{ strtoupper($pelanggan->nm_pelanggan) }}</option>
        @endif
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        ID STT
    </label>
    <select class="form-control" id="stt" name="stt">
        @if(Session('stt')!=null)
            <option value="{{ Session('stt') }}">{{ Session('stt') }}</option>
        @endif
    </select>
</div>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Tanggal Bayar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
		    $ldate = date('Y-m-d H:i:s')
	        @endphp
            <div class="modal-body">
                   <div class="row">
				   	<div class="col">
						<h6>Dari Tanggal</h6>
                    	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(Session('dr_tgl')!= null){{Session('dr_tgl')}}@endif">
					</div>
					<div class="col">
						<h6>Sampai Tanggal</h6>
                    	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(Session('sp_tgl')!= null){{Session('sp_tgl')}}@endif">
					</div>
				   </div>
				   <!-- End Form Tanggal -->
				   
				<!-- end Modal Body -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Save</span></button>
            </div>
        </div>
    </div>
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
                $("#filterperush").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(Session('id_perush')!=null)
				$("#filterperush").val('{{ Session('id_perush') }}');
			@endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $('#id_plgn').select2({
		placeholder: 'Cari Pelanggan ....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url('getPelanggan') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_plgn').empty();
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

    $('#stt').select2({
        placeholder: 'Masukkan STT yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getStt') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#stt').empty();
                return {
                    results: $.map(data, function (item) {
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
    function CheckStatus(){
        $("#modal-status").modal('show');
    }
</script>
@endsection
