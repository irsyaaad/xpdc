@extends('template.document')

@section('data')
@if((Request::segment(1)=="tarifasuransi" && Request::segment(2)==null) or (Request::segment(2)=="filter" or Request::segment(2)=="page"))
@include("template.filter2")

<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead  style="background-color: grey; color : #ffff">>
		<tr>
			<th>No</th>
            <th>Perusahaan</th>
			<th>Nama Broker</th>
			<th>Jenis Asuransi</th>
			<th>Harga Beli</th>
			<th>Harga Jual</th>
			<th>Min Harga Pertanggungan</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>@isset($value->perusahaan->nm_perush)
				{{ strtoupper($value->perusahaan->nm_perush) }}
			@endisset</td>
			<td>@isset($value->perusahaan_asuransi->nm_perush_asuransi)
				{{ strtoupper($value->perusahaan_asuransi->nm_perush_asuransi) }}
			@endisset</td>
			<td>@if ($value->jenis_asuransi == 1)
				Harga Barang
				@elseif($value->jenis_asuransi == 2)
				Ongkos Kirim
			@endif</td>
			<td>{{ strtoupper($value->harga_beli) }} %</td>
			<td>{{ strtoupper($value->harga_jual) }} %</td>
			<td>@if(isset($value->min_harga_pertanggungan))Rp. {{number_format($value->min_harga_pertanggungan, 2, ',', '.')}}@endif</td>
			<td>
				{!! inc_edit($value->id_tarif) !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class="row" style="margin-top: 4%; font-weight: bold;">
	<div class="col-md-2">
		Halaman : <b>{{ $data->currentPage() }}</b>
	</div>
	<div class="col-md-2">
		Jumlah Data : <b>{{ $data->total() }}</b>
	</div>
	<div class="col-md-3">
		{{-- rubah setia view disini --}}
		@if(Request::segment(2)=="filter")
		<form method="POST" action="{{ url('perusahaan/filter') }}" id="form-share" name="form-share">
		@else
		<form method="POST" action="{{ url('perusahaan/page') }}" id="form-share" name="form-share">
		@endif
			@csrf
			<select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
				<option value="10">-- Tampil 10 Data --</option>
				<option value="50">50 Data</option>
				<option value="100">100 Data</option>
				<option value="500">500 Data</option>
			</select>
		</form>
	</div>
	<div class="col-md-5" style="width: 100%">
		{{ $data->links() }}
	</div>
</div>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )
<style type="text/css">
	.select2-selection {
		height: 43px !important;
		padding: 1px;
	}
</style>

{{-- for insert data --}}
@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('tarifasuransi') }}" enctype="multipart/form-data">
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('tarifupdate', $data->id_tarif) }}" enctype="multipart/form-data">
{{ method_field("PUT") }}
@endif

@csrf
		<div class="form-group m-form__group">
			<label for="nm_perush">
				<b>Nama Perusahaan Asuransi</b> <span class="span-required"> *</span>
			</label>
			<select id="id_perush_asuransi" name="id_perush_asuransi" class="form-control">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach($perusahaan_asuransi as $key => $value)
				<option value="{{$value->id_perush_asuransi}}">{{$value->nm_perush_asuransi}}</option>
                @endforeach
			</select>

			@if ($errors->has('id_perush_asuransi'))
			<label style="color: red">
				{{ $errors->first('id_perush_asuransi') }}
			</label>
			@endif
		</div>

        <div class="form-group m-form__group">
			<label for="jenis_asuransi">
				<b>Jenis Asuransi</b> <span class="span-required"> *</span>
			</label>
			<select id="jenis_asuransi" name="jenis_asuransi" class="form-control" required>
                <option value="">-- Pilih Jenis Asuransi --</option>
				<option value="1">HARGA BARANG</option>
                <option value="2">ONGKOS KIRIM</option>
			</select>

			@if ($errors->has('jenis_asuransi'))
			<label style="color: red">
				{{ $errors->first('jenis_asuransi') }}
			</label>
			@endif
		</div>

		<div class="row">
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Harga Beli (dalam persen)</b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="harga_beli" id="harga_beli" step="any" placeholder="ex : 0,05525" value="@if(isset($data->harga_beli)){{ $data->harga_beli }}@else{{ old('harga_beli') }}@endif" maxlength="16" required>
					@if ($errors->has('fax'))
					<label style="color: red">
						{{ $errors->first('fax') }}
					</label>
					@endif
				</div>
            </div>
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Harga Jual (dalam persen)</b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="harga_jual" id="harga_jual" step="any" placeholder="ex : 0,05525" value="@if(isset($data->harga_jual)){{ $data->harga_jual }}@else{{ old('harga_jual') }}@endif" maxlength="16" required>

					@if ($errors->has('harga_jual'))
					<label style="color: red">
						{{ $errors->first('harga_jual') }}
					</label>
					@endif
				</div>
            </div>

        </div>
		<br>
		<div class="row">
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Harga Asuransi (Minimal)</b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="harga_pertanggungan" id="harga_pertanggungan" placeholder="ex : 2500000" value="@if(isset($data->harga_pertanggungan)){{ $data->harga_pertanggungan }}@else{{ old('harga_pertanggungan') }}@endif" maxlength="16" required>
					@if ($errors->has('harga_pertanggungan'))
					<label style="color: red">
						{{ $errors->first('harga_pertanggungan') }}
					</label>
					@endif
				</div>
            </div>
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Minimal Harga Pertanggungan</b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="min_harga_pertanggungan" id="min_harga_pertanggungan" placeholder="ex : 25000000" value="@if(isset($data->min_harga_pertanggungan)){{ $data->min_harga_pertanggungan }}@else{{ old('min_harga_pertanggungan') }}@endif" maxlength="16" required>

					@if ($errors->has('min_harga_pertanggungan'))
					<label style="color: red">
						{{ $errors->first('min_harga_pertanggungan') }}
					</label>
					@endif
				</div>
            </div>

        </div>
		<div class="col-md-12 text-right">
			@include('template.inc_action')
		</div>

</form>
@endif
<script>
	@if(isset($data->id_perush_asuransi))
		$("#id_perush_asuransi").val('{{$data->id_perush_asuransi}}');
	@endif
	@if(isset($data->jenis_asuransi))
		$("#jenis_asuransi").val('{{$data->jenis_asuransi}}');
	@endif
</script>
@endsection
