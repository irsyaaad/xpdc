@extends('template.document')

@section('data')

@if((Request::segment(1)=="tarifpacking" && Request::segment(2)==null) or (Request::segment(2)=="filter" or Request::segment(2)=="page"))
@include("template.filter2")

<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead  style="background-color: grey; color : #ffff">>
		<tr>
			<th>No</th>
			<th>Jenis Packing</th>
			<th>Panjang</th>
			<th>Lebar</th>
			<th>Tinggi</th>
            <th>Volme</th>
            <th>Tarif</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
            <td>{{ $key+1 }}</td>
            <td>@if(isset($value->packing->nm_packing)){{ $value->packing->nm_packing }}@endif</td>
            <td>{{ $value->panjang }}</td>
            <td>{{ $value->lebar }}</td>
            <td>{{ $value->tinggi }}</td>
            <td>{{ tonumber($value->tinggi * $value->panjang * $value->lebar) }}</td>
            <td>{{ $value->tarif }}</td>
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
		<form method="POST" action="{{ url('tarifpacking/filter') }}" id="form-share" name="form-share">
		@else
		<form method="POST" action="{{ url('tarifpacking/page') }}" id="form-share" name="form-share">
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
@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">	
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('tarifpacking.update', $data->id_tarif) }}" enctype="multipart/form-data">
{{ method_field("PUT") }} 
@endif
<div class="row">
    @csrf
    <div class="col-md-4">
        <label for="id_jenis_packing">
            <b>Jenis Packing Barang</b>
        </label>

        <select class="form-control m-input m-input--square" name="id_jenis_packing" id="id_jenis_packing">
            <option value=""> -- Pilih Packing Barang --</option>
            @foreach($packing as $key => $value)
                <option value="{{ $value->id_packing }}">{{ strtoupper($value->nm_packing) }}</option>
            @endforeach
        </select>

        @if ($errors->has('id_jenis_packing'))
        <label style="color: red">
            {{ $errors->first('id_jenis_packing') }}
        </label>
        @endif
    </div>

    <div class="col-md-4">
        <label for="panjang">
            <b>Panjang Barang (Cm)</b> <span class="text-danger"> * </span>
        </label>
        
        <input class="form-control m-input m-input--square" required value="@if(isset($data->panjang)){{ $data->panjang }}@else {{ old("panjang") }}@endif" type="number" placeholder="Masukan Panjang Barang (cm)" name="panjang" id="panjang" />
        
        @if ($errors->has('panjang'))
        <label style="color: red">
            {{ $errors->first('panjang') }}
        </label>
        @endif
    </div>
    
    <div class="col-md-4">
        <label for="lebar"> 
            <b>Lebar Barang (Cm)</b> <span class="text-danger"> * </span>
        </label>
        
        <input class="form-control m-input m-input--square" required value="@if(isset($data->lebar)){{ $data->lebar }}@else {{ old("lebar") }}@endif" type="number" placeholder="Masukan lebar Barang (cm)" name="lebar" id="lebar" />
        
        @if ($errors->has('lebar'))
        <label style="color: red">
            {{ $errors->first('lebar') }}
        </label>
        @endif
    </div>

    <div class="col-md-4">
        <br>
        <label for="tinggi">
            <b>Tinggi Barang (Cm)</b> <span class="text-danger"> * </span>
        </label>
        
        <input class="form-control m-input m-input--square" required value="@if(isset($data->tinggi)){{ $data->tinggi }}@else {{ old("tinggi") }}@endif" type="number" placeholder="Masukan tinggi Barang (cm)" name="tinggi" id="tinggi" />
        
        @if ($errors->has('tinggi'))
        <label style="color: red">
            {{ $errors->first('tinggi') }}
        </label>
        @endif
    </div>

    <div class="col-md-4">
        <br>
        <label for="tarif"> 
            <b>Tarif Packing</b> <span class="text-danger"> * </span>
        </label>
        
        <input class="form-control m-input m-input--square" type="number" placeholder="Masukan tarif Barang (Rp.)"  value="@if(isset($data->tarif)){{ $data->tarif }}@else{{ old("tarif") }}@endif" required name="tarif" id="tarif" />
        
        @if ($errors->has('tarif'))
        <label style="color: red">
            {{ $errors->first('tarif') }}
        </label>
        @endif
    </div>

    <div class="col-md-4 text-left" style="padding-top: 20px">
        @include('template.inc_action')
    </div>

    
</div>

@endif

@endsection

@section('script')
<script>
    @if(isset($data->id_jenis))
        $("#id_jenis_packing").val('{{ $data->id_jenis }}');
    @endif
</script>
@endsection