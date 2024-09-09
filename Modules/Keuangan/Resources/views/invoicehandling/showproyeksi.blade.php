@extends('template.document2')
@section('data')

@section('style')
<style>
    .modal {
        text-align: center;
    }
    
    @media screen and (min-width: 768px) { 
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: " ";
            height: 66%;
        }
    }
    
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
@endsection
<form method="POST" action="{{ url(Request::segment(1)."/import"."/".Request::segment(3)) }}" id="form-data">
    @csrf
    <input type="hidden" id="filter_invoice" name="filter_invoice" value="{{ Request::segment(2) }}"/>
    <div class="row">
        <div class="col-md-4 row">
            <div class="col-md-6">
                <label for="filter_biaya">
                    <b>Group Biaya : </b> <span class="span-required"> *</span>
                </label>
                <select class="form-control m-input m-input--square" id="filter_biaya" name="filter_biaya">
                    <option value="">-- Pilih Biaya --</option>
                    @foreach($biaya as $key => $value)
                    <option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="filter_stt">
                    <b>Nomor STT : </b> <span class="span-required"></span>
                </label>
                <select class="form-control m-input m-input--square" id="filter_stt" name="filter_stt"></select>
            </div>
        </div>
        
        <div class="col-md-4">
            <button class="btn btn-sm btn-success" type="button" style="margin-top: 30px" onclick="setMethod(1)"><i class="fa fa-search"></i> 
                Pilih
            </button>
            <a href="{{ url(Request::url()) }}" class="btn btn-sm btn-warning" style="margin-top: 30px"><i class="fa fa-refresh"></i> 
                Reset
            </a>
        </div>
        
        <div class="col-md-4 text-right" style="padding-top: 30px">
            {{-- <button class="btn btn-sm btn-success" type="button" style="margin-left: 10px;" onclick="setMethod(2)">
                <i class="fa fa-save"></i> Import
            </button> --}}
            
            <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/show") }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
            
        </div>
    </div>
</form>

<div style="margin-top:10px">
    {{-- <label style="float:right"><input type="checkbox" value="1" id="c_all" name="c_all" > <b>Pilih Semua</b></label> --}}
    <table class="table table-responsive table-bordered" id="tableasal">
        <thead style="background-color : #ececec">
            <tr>
                <th>No. Handling</th>
                <th>No. Daftar Muat</th>
                <th>No. STT</th>
                <th>Biaya</th>
                <th>Kelompok</th>
                <th>Nominal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{ $value->kode_handling }}</td>
                <td>
                    @if(isset($dm[$value->id_handling]->kode_dm))
                    {{ strtoupper($dm[$value->id_handling]->kode_dm) }}
                    @endif
                </td>
                <td>{{ $value->kode_stt }}</td>
                <td>{{ strtoupper($value->nm_biaya_grup) }}</td>
                <td>{{ $value->klp }}</td>
                <td>{{ strtoupper(number_format($value->nominal, 0, ',', '.')) }}</td>
                <td>
                    <button class="tn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#modal-create" onclick="setMethod('{{ $value->id_biaya }}','{{ $dm[$value->id_handling]->id_dm }}', '{{ $dm[$value->id_handling]->kode_dm }}', '{{ $value->kode_stt }}', '{{ $value->nm_biaya_grup }}', '{{ $value->nominal }}')">
                        <i class="fa fa-plus"></i> Tambah
                    </button> 
                    {{-- <input type="checkbox" name="c_stt[]" id="c_stt{{ $value->id_stt }}" class="form-control c_stt" value="{{  $value->id_stt }}"> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			
			<form method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)."/savebiaya") }}" id="form-data">
                <input type="hidden" name="id_biaya" id="id_biaya" value="">
                <input type="hidden" name="id_dm" id="id_dm" value="">
				@csrf
				<div class="modal-body">
					<div class="row">

						<div class="col-md-12" style="padding-top: 10px">
							<label for="kode_dm">
								<b>Nomor DM</b> <span class="span-required">* </span>
							</label>
							
							<input class="form-control m-input m-input--square" id="kode_dm" name="kode_dm" type="text" required readonly />
						</div>

						<div class="col-md-12" style="padding-top: 10px">
							<label for="kode_stt">
								<b>Nomor STT</b> <span class="span-required"></span>
							</label>
							
							<input class="form-control m-input m-input--square" id="kode_stt" name="kode_stt" type="text" required readonly />
						</div>
						
                        <div class="col-md-12" style="padding-top: 10px">
							<label for="id_biaya_grup">
								<b>Group Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" type="text" readonly required />
						</div>

						<div class="col-md-12" style="padding-top: 10px">
							<label for="nominal">
								<b>Nominal Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required />
							
							@if ($errors->has('nominal'))
							<label style="color: red">
								{{ $errors->first('nominal') }}
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

@endsection

@section('script')
<script>
    function setMethod(id_biaya, id_dm, kode_dm, id_stt, id_biaya_grup, nominal) {
        $("#id_biaya").val(id_biaya);
        $("#id_dm").val(id_dm);
        $("#kode_dm").val(kode_dm);
        $("#kode_stt").val(id_stt);
        $("#id_biaya_grup").val(id_biaya_grup);
        $("#nominal").val(nominal);
    }

    function refresh(){
        $("#id_biaya").val();
        $("#kode_dm").val();
        $("#id_dm").val();
        $("#kode_stt").val();
        $("#id_biaya_grup").val();
        $("#nominal").val();
    }
    
    @if(isset($id_dm))
    $('#id_dm').append('<option value="{{ $id_dm }}">{{ $kode_dm }}</option>');
    $("#id_dm").val('{{ $id_dm }}');
    @endif
    
    $('#filter_dm').on('change', function() {
        $.ajax({
            type: "GET", 
            url: "{{ url("dmhandling") }}/getdm/"+$("#id_perush").val(), 
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $('#filter_dm').empty();
                $('#filter_dm').append('<option value="">-- Pilih DM --</option>');
                
                $.each(response, function(index, value) {
                    $('#filter_dm').append('<option value="'+value.id_dm+'">'+value.kode_dm+'</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });
    
    // $(function(){
    //     $('#c_all').change(function()
    //     {
    //         if($(this).is(':checked')) {
    //             $(".c_stt").prop("checked", true);
    //         }else{   
    //             $(".c_stt").prop("checked", false);
    //         }
    //     });
    // });
    
    $('#filter_stt').select2({
        placeholder: 'Cari STT ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('dmhandling/getstttiba') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filter_stt').empty();
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