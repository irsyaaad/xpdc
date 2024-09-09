@extends('template.document')

@section('data')

@if(Request::segment(1)=="denda" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
@include("template.filter2")
<table class="table table-striped table-responsive" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>Nama Denda </th>
            <th>Nominal</th>
			<th>id_user</th>
			<th>
				<center>Action</center>
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>{{ strtoupper($value->nm_denda) }}</td>
			<td>{{ strtoupper($value->nominal) }}</td>
            <td>
                @if(isset($value->user->nm_user))
                {{ strtoupper($value->user->nm_user) }}
                @endif
            </td>
			<td>
				{!! inc_edit($value->id_denda) !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
@if(Request::segment(2)=="create" )
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
	@else
	<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('denda.update', $data->id_gaji) }}" enctype="multipart/form-data">
		{{ method_field("PUT") }} 
		@endif
		@csrf
	
        <div class="form-group m-form__group">
			<label for="nm_jenis">
				<b>Nama Denda</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" id="nm_denda" name="nm_denda" maxlength="32" class="form-control" value="@if(isset($data->nm_denda)){{ $data->nm_denda }}@else{{ old('nm_denda') }}@endif">

			@if ($errors->has('nominal'))
			<label style="color: red">
				{{ $errors->first('nominal') }}
			</label>
			@endif
		</div>

        <div class="form-group m-form__group">
			<label for="nm_jenis">
				<b>Nominal</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" id="nominal" name="nominal" maxlength="32" class="form-control" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{ old('nominal') }}@endif">

			@if ($errors->has('nominal'))
			<label style="color: red">
				{{ $errors->first('nominal') }}
			</label>
			@endif
		</div>

        <div class="text-right">
            @include('template.inc_action')
        </div>
	</form>
@endif
@endsection

@section('script')
<script>
	@if(isset($data->format))
		$("#format").val('{{ $data->format }}');
	@endif

	@if(isset($data->id_jenis))
		$("#id_jenis").attr("readonly", true);
	@endif
    $('#id_karyawan').select2({
        placeholder: 'Masukkan Nama Karyawan yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getKaryawan1') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#getKaryawan').empty();
                return {
                    results: $.map(data, function (item) {
                        console.log(item);
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