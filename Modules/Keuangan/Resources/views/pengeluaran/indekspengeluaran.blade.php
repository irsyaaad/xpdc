
@extends('template.document')

@section('data')

@if(Request::segment(2)=="filter" or Request::segment(2)==null)
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Perkiraan Akun</th>
                        <th>Terima / Admin</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            <a class="label" href="{{ url(Request::segment(1)."/".$value->id_pengeluaran."/show") }}">{{ strtoupper($value->kode_pengeluaran) }}</a>
                            <br>{{ dateindo($value->tgl_keluar) }}
                        </td>
                        <td>
                            @if(isset($value->debet->nama))
                            {{ strtoupper(" ( ".$value->debet->id_ac." ) ".$value->debet->nama) }}
                            @endif
                        </td>
                        <td>
                            {{ strtoupper($value->terima_dr) }}
                            <br>
                            @if(isset($value->user->nm_user))
                            {{ strtoupper($value->user->nm_user) }}
                            @endif
                        </td>
                        <td>
                            {{ toRupiah($value->c_total) }}
                        </td>
                        <td>
                            {{ $value->info }}
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_pengeluaran."/show") }}"><i class="fa fa-eye"></i> Detail</a>
                                    @if($value->is_approve!=true)
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_pengeluaran."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
                                    <a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_pengeluaran) }}')"><i class="fa fa-times"></i> Delete</a>
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_pengeluaran."/cetak") }}"><i class="fa fa-print"></i> Cetak</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($data)<1)
                    <tr>
                        <td colspan="7" class="text-center"> Data Kosong</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @include('template.paginate')
    </div>
</form>

@endif

@endsection

@section('script')
<script type="text/javascript">
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    $("#f_id_pengeluaran").select2();
    @if(isset($filter["f_id_pengeluaran"]) and $filter["f_id_pengeluaran"] != null)
        $("#f_id_pengeluaran").val('{{ $filter["f_id_pengeluaran"] }}').trigger("change");
    @endif

    @if(isset($filter["f_id_ac"]) and $filter["f_id_ac"] != null)
        $("#f_id_ac").val('{{ $filter["f_id_ac"] }}').trigger("change");
    @endif

    @if(isset($filter["page"]) and $filter["page"] != null)
        $("#shareselect").val('{{ $filter["page"] }}');
    @endif
    
</script>
@endsection
