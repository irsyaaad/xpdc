@extends('template.document')

@section('data')
@if(Request::segment(1)=="cabanggroup" && Request::segment(2)==null)
@include("template.filter")
<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead  style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>Nama Cabang Group</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $key => $value)
		<tr>
			<td>
				{{ $key+1 }}
			</td>
            <td>
				{{ strtoupper($value->nm_cabgroup) }}
			</td>
			<td>
				{!! inc_edit($value->id_cabgroup) !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class="row" style="margin-top: 4%; font-weight: bold;">
	<div class="col-md-3">
		Halaman : <b>{{ $data->currentPage() }}</b>
	</div>
	<div class="col-md-3">
		Jumlah Data : <b>{{ $data->total() }}</b>
	</div>
	<div class="col-md-6" style="width: 100%">
		{{ $data->links() }}
	</div>
</div>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
{{-- for insert data --}}
    @if(Request::segment(2)=="create")
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('cabanggroup') }}" enctype="multipart/form-data">
    @else
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('cabanggroup.update', $data->id_cabgroup) }}" enctype="multipart/form-data">
        {{ method_field("PUT") }} 
    @endif

    @csrf
    <div class="row">
        <div class="form-group m-form__group col-md-6">
            <label for="nm_cabgroup">
                <b>Nama Cabang Group</b>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="nm_cabgroup" id="nm_cabgroup" placeholder="Masukan Nama Cabang Group" value="@if(isset($data->nm_cabgroup)){{ $data->nm_cabgroup }}@else {{ old('nm_cabgroup') }} @endif" required="required" maxlength="50">
            
            @if ($errors->has('nm_cabgroup'))
            <label style="color: red">
                {{ $errors->first('nm_cabgroup') }}
            </label>
            @endif
        </div>
        <div class="col-md-6">
            @include('template.inc_action')
        </div>
    </div>
	
</form>
@endif
@endsection

{{-- this for loading javascript data --}}
@section('script')

@endsection