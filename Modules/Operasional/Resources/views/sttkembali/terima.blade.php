@extends('template.document2')
@section('data')

@if(Request::segment(1)=="sttkembaliterima" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
@include("template.filter2")

<table class="table table-responsive table-striped" id="html_table" width="100%" >
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>No. Dokumen</th>
			<th>Asal</th>
			<th>Tujuan</th>
			<th>Pengirim</th>
			<th>Penerima</th>
			<th>Tgl Kirim</th>
			<th>Tgl Terima</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	
	<tbody>
		@foreach($data as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ strtoupper($value->id_kembali) }}</td>
            <td>
                @if(isset($value->perush_asal->nm_perush))
                {{ strtoupper($value->perush_asal->nm_perush) }}
                @endif
            </td>
            <td>
                @if(isset($value->perush_tujuan->nm_perush))
                {{ strtoupper($value->perush_tujuan->nm_perush) }}
                @endif
            </td>
            <td>
                @if(isset($value->user->nm_user))
                {{ strtoupper($value->user->nm_user) }}
                @endif
            </td>
            <td>
                @if(isset($value->karyawan->nm_karyawan))
                {{ strtoupper($value->karyawan->nm_karyawan) }}
                @endif
            </td>
            <td>
                {{ strtoupper($value->tgl_kirim) }}
            </td>
            <td>
                {{ strtoupper($value->tgl_tiba) }}
            </td>
            <td>
                @if($value->status=="1")
                Dibuat
                @elseif($value->status=="2")
                Dikirim
                @else
                Diterima
                @endif
            </td>
            <td>
                <div class="dropdown">
					<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action
					</button>
					<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						<form method="POST" action="#" name="#">
							<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_kembali."/cetak_pdf") }}"><i class="fa fa-print"></i> Cetak</a>
							<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_kembali) }}"><i class="fa fa-eye"></i> Detail</a>
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
	<div class="col-md-3">
		{{-- rubah setia view disini --}}
		@if(Request::segment(2)=="filter")
		<form method="POST" action="{{ url('sttkembali/filter') }}" id="form-share" name="form-share">
		@else
		<form method="POST" action="{{ url('sttkembali/page') }}" id="form-share" name="form-share">
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
@else
@include("operasional::sttkembali.show")
@endif

@endsection