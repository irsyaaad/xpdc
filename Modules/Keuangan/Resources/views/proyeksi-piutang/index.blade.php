@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="level">
                <b>Tahun</b>
            </label>
            <select class="form-control m-input m-input--square" name="tahun" id="tahun">
                <option value="">-- Pilih Tahun --</option>
                @foreach($tahun as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-striped">
                <thead style="background-color: grey; color : #ffff">
                    <th>No</th>
                    <th>Admin Piutang</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Proyeksi Piutang (Rp.)</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->users }}</td>
                        <td>{{ $value->bulan }}</td>
                        <td>{{ $value->tahun }}</td>
                        <td>{{ toRupiah($value->piutang) }}</td>
                        <td>{{ $value->updated_at }}</td>
                        <td>
                            <div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id) }}"><i class="fa fa-eye"></i>  Detail</a>
                                    <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
									<button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id) }}')">
										<span><i class="fa fa-times"></i></span> Hapus
									</button>
								</div>
							</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    @if(isset($filter["tahun"]))
    $("#tahun").val("{{ $filter['tahun'] }}");
    @endif

    $("#tahun").on("change", function() {
        $("#form-select").submit();
    });
</script>
@endsection
