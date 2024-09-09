<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="filterperush" name="filterperush">
        <option value="0">-- Penerima --</option>
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
<div class="col-md-3">
    <label style="font-weight: bold;">
        Armada
    </label>
    <select class="form-control" id="armada" name="armada">
        <option value="0">-- Pilih Armada --</option>
    </select>
</div>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Harap Isi Form Dengan Benar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
		    $ldate = date('Y-m-d H:i:s')
	        @endphp
            <div class="modal-body">
                    <div class="m-form__label">
                        <label style="font-weight: bold;">
                            Tanggal Handling
                        </label>
                    </div>
                    <div class="m-form__control">
                        <input type="date" class="form-control" name="tgl" id="tgl" value="@if(Session('tgl')!= null){{Session('tgl')}}@endif">
                    </div>
                    <br>
				   <!-- End Form Tanggal -->
				   <div class="m-form__control">
					<label style="font-weight: bold;">
						Sopir
					</label>
					    <select class="form-control" id="sopir" name="sopir"></select>
					</div>
				<!-- End Form Cara Bayar -->
				<br>
				<div class="m-form__control">
					<label style="font-weight: bold;">
						Is Konfirmasi
					</label>
					<input type="checkbox" name="is_konfirmasi" id="is_konfirmasi" value="1">
				</div>
				
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
    
        $('#armada').select2({
            placeholder: 'Cari Armada ....',
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '{{ url('getArmada') }}',
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
    
            $.ajax({
                type: "GET",
                url: "{{ url("getSopir") }}",
                dataType: "json",
                beforeSend: function (e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function (response) {
                    $.each(response, function (key, value) {
                        $("#sopir").append('<option value="0">-- Pilih Sopir --</option>');
                        $("#sopir").append('<option value=' + value.kode + '>' + value.value + '</option>');
                    });
    
                    @if(Session('sopir')!=null)
                        $("#sopir").val('{{ Session('sopir') }}');
                    @endif
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
    
            function CheckStatus(){
            $("#modal-status").modal('show');
        }
        
        function goSubmitUpdate() {
            $("#form-status").submit();
        }
    </script>
@endsection