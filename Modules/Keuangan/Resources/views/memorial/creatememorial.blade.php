@extends('template.document')

@section('data')
@if(Request::segment(2)=="create")
<form method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
    @else
    <form method="POST" action="{{ url(Request::segment(1), $data->id_pengeluaran) }}" enctype="multipart/form-data">
        {{ method_field("PUT") }}
        @endif
        @csrf
        <div class="row">
            <div class="col">
                    <div class="form-group m-form__group">
                        <label for="tgl_masuk">
                            <b>Tanggal Transaksi</b><span class="span-required"> *</span>
                        </label>

                        <input type="date" class="form-control"  id="tgl_masuk" name="tgl_masuk" value="@if(isset($data->tgl_masuk)){{ $data->tgl_masuk }}@else{{old('tgl_masuk')}}@endif" maxlength="32" required/>

                        @if ($errors->has('tgl_masuk'))
                        <label style="color: red">
                            {{ $errors->first('tgl_masuk') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group m-form__group">
                        <label for="terima_dr">
                            <b>No Referensi</b><span class="span-required"> *</span>
                        </label>

                        <input class="form-control" type="text" id="no_referensi" name="no_referensi" value="@if(isset($data->terima_dr)){{ $data->terima_dr }}@else{{old('terima_dr')}}@endif" maxlength="32" required/>

                        @if ($errors->has('terima_dr'))
                        <label style="color: red">
                            {{ $errors->first('terima_dr') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group m-form__group">
                        <label for="info">
                            <b>Keterangan</b><span class="span-required"> *</span>
                        </label>
                        <textarea class="form-control" style="min-height: 100px"  id="info" name="info" minlength="4" maxlength="256" required>@if(isset($data->info)){{ $data->info }}@else{{old('info')}}@endif</textarea>
                        @if ($errors->has('info'))
                        <label style="color: red">
                            {{ $errors->first('info') }}
                        </label>
                        @endif
                    </div>
            </div>

            <div class="col">
                    <div class="form-group m-form__group">
                        <label for="tgl_masuk">
                            <b>Akun Debet</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="id_ac_debet" id="id_ac_debet" required>
                            <option value="">-- Pilih Akun Debet --</option>
                        </select>

                        @if ($errors->has('tgl_masuk'))
                        <label style="color: red">
                            {{ $errors->first('tgl_masuk') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group m-form__group">
                        <label for="terima_dr">
                            <b>Akun Kredit</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="id_ac_kredit" id="id_ac_kredit" required>
                            <option value="">-- Pilih Akun Debet --</option>
                        </select>

                        @if ($errors->has('terima_dr'))
                        <label style="color: red">
                            {{ $errors->first('terima_dr') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group m-form__group">
                        <label for="info">
                            <b>Nominal</b><span class="span-required"> *</span>
                        </label>
                        <input class="form-control" type="number" id="nominal" name="nominal" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{old('nominal')}}@endif" maxlength="32" required/>

                        @if ($errors->has('info'))
                        <label style="color: red">
                            {{ $errors->first('info') }}
                        </label>
                        @endif
                    </div>
            </div>
        </div>


            <div class="col-md-12 text-right">
                @include('template.inc_action')
            </div>
    </form>
    <script>
        var today = new Date().toISOString().split('T')[0];
        $("#tgl_masuk").val(today);

        @if(isset($data->id_ac))
        $("#id_ac").val('{{ $data->id_ac }}');
        @else
        $("#id_ac").val('{{ old('id_ac') }}');
        @endif

    $('#id_ac_debet').select2({
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

    $('#id_ac_kredit').select2({
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
