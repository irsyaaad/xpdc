@extends('template.document')
@section('data')

@if(Request::segment(1)=="sttkembali" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
@include("filter.filter-".Request::segment(1))

<table class="table table-responsive table-striped" id="html_table" width="100%" >
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>No. Dokumen</th>
			<th>Penerima</th>
			<th>Tgl Terima</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	
	<tbody>
		@foreach($data as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>
				<a href="{{ url(Request::segment(1).'/'.$value->id_stt_kembali.'/show') }}">{{ $value->kode_stt_kembali }}</a>
			</td>
            <td>
                @if(isset($value->user->nm_user))
                {{ strtoupper($value->user->nm_user) }}
                @endif
            </td>
            <td>{{ dateindo($value->tgl) }}</td>
            <td>{{ $value->status }}</td>
            <td>
                <div class="dropdown">
					<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action
					</button>
					<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						<form method="POST" action="{{ url(Request::segment(1).'/'.$value->id_stt_kembali) }}" id="form-delete{{ $value->id_stt_kembali }}" name="form-delete{{ $value->id_stt_kembali }}">@csrf
                            <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_stt_kembali.'/edit') }}"><i class="fa fa-edit"></i> Edit</a>
                            @method('DELETE')
							<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_stt_kembali.'/deletedokumen') }}"><i class="fa fa-trash"></i> Hapus</a>
							<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_stt_kembali . '/show') }}"><i class="fa fa-eye"></i> Detail</a>
							<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_stt_kembali . '/cetak') }}" target="_blank"><i class="fa fa-print"></i> Print</a>
						</form>
					</div>
				</div>
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
	<div class="col-md-5" style="width: 100%">
		{{ $data->appends(request()->query())->links() }}
	</div>
</div>

@elseif(Request::segment(2)=="create")
@include("operasional::sttkembali.create")
@elseif(Request::segment(3)=="edit")
@include("operasional::sttkembali.create")
@else
@include("operasional::sttkembali.show")
@endif

@endsection