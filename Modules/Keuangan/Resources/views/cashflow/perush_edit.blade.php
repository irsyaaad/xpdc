@extends('template.document')

@section('data')
<div class="text-right">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>
<table class="table table-sm table-borderless">
    <tr>
        <td>ID CF</td>
        <td>:</td>
        <td>{{$cashflow->id_cf}}</td>
    </tr>
    <tr>
        <td>Nama Cashflow</td>
        <td>:</td>
        <td>{{$cashflow->nama_cashflow}}</td>
    </tr>
    <tr>
        <td>Tipe</td>
        <td>:</td>
        <td>@if ($cashflow->tipe == "1")
        CASH IN
        @else
        CASH OUT
        @endif</td></tr>
</table>
<hr>
@isset($detail)
<table class="table table-responsive table-striped" width="100%">
    <thead style="background-color: grey; color : #ffff">
        <th>ID AC</th>
        <th>Nama</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($detail as $key => $value)
            <tr>
                <td>{{$value->id_ac}}</td>
                <td>@isset($value->akun->nama)
                    {{$value->akun->nama}}
                @endisset</td>
                <td class="text-center">
                    <form method="POST" action="{{ url("mastercashflowperush").'/'.$value->id }}" id="form-delete{{ $value->id }}" name="form-delete{{ $value->id}}">
                        @csrf
                        {{ method_field("DELETE") }}
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="getEdit('{{ $value->id_detail }}')">
                            <span> <i class="fa fa-edit"></i></span>
                        </button>
                        <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus">
                            <span> <i class="fa fa-times"></i></span>
                        </button>
                    </form>
                </td>
            </tr>                     
        @endforeach
        <tr>
            <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-detail" name="form-detail">
                @csrf
                <td>
                    <input type="hidden" name="id_cf" id="id_cf" value="{{ $cashflow->id_cf }}" required />
                    <input type="hidden" name="tipe" id="tipe" value="{{ $cashflow->tipe }}" required />
                </td>
                <td>
                    <select class="form-control" name="id_ac" id="id_ac" required>
                        <option value="">-- Pilih Akun --</option>
                    </select>
                    
                    @if ($errors->has('id_ac'))
                    <label style="color: red">
                        {{ $errors->first('id_ac') }}
                    </label>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"><i class="fa fa-save"></i></button>
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Batal" onclick="reload()"><i class="fa fa-refresh"></i></button>
                </td>
                
            </form>
        </tr>   
    </tbody>
</table>
@endisset

<script>
    $('#id_ac').select2({
		placeholder: 'Cari Akun ....',
		ajax: {
			url: '{{ url('getACPerush') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_ac').empty();
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