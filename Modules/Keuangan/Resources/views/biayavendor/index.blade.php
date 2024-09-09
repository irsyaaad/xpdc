@extends('template.document2')

@section('data')

<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row mt-1">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No. </th>
                        <th>Nomor DM</th>
                        <th class="text-center" colspan="3">Asal Dan Vendor Tujuan</th>
                        <th>Est. Omzet</th>
                        <th>Biaya Hpp</th>
                        <th>Dibayar</th>
                        <th>Kurang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            <a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/detail' }}" class="class-edit">{{ strtoupper($value->kode_dm) }}</a>
                            <br>
                            @if(isset($value->nm_layanan)){{ strtoupper($value->nm_layanan) }}@endif
                        </td>
                        <td>@if(isset($value->nm_perush)){{ strtoupper($value->nm_perush) }}@endif
                            <br>
                            @if(isset($value->tgl_berangkat)){{ dateindo($value->tgl_berangkat) }}@endif
                        </td>
                        <td><i class="fa fa-arrow-right"></i></td>
                        <td>@if(isset($value->nm_ven))
                            {{ strtoupper($value->nm_ven) }}
                            @elseif (isset($value->nm_perush))
                            {{ $value->nm_perush }} 
                            @endif
                            <br>
                            @if(isset($value->tgl_berangkat)){{ dateindo($value->tgl_berangkat) }}@endif
                        </td>
                        <td>{{ strtoupper(toRupiah($value->c_total)) }}</td>
                        <td>{{ strtoupper(toRupiah($value->c_pro)) }}</td>
                        <td>
                            {{ strtoupper(toRupiah($value->n_bayar)) }}
                        </td>
                        <td>
                            @php
                                $kurang = ($value->c_pro-$value->n_bayar);
                            @endphp
                            {{ strtoupper(toRupiah($kurang)) }}
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <form method="POST">
                                        <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_dm."/print") }}" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/detail' }}"><i class="fa fa-eye"></i> Detail Biaya</a>
                                    </form>
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginator')
    </div>
    
</form>
<script type="text/javascript">
    $("#f_id_dm").select2();
    $("#f_id_stt").select2();
    $("#f_id_ven").select2();
    $("#f_no").select2();

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
    
    @if(isset($filter["dr_tgl"]))
    $("#dr_tgl").val('{{ $filter["dr_tgl"] }}');
    @endif
    
    @if(isset($filter["sp_tgl"]))
    $("#sp_tgl").val('{{ $filter["sp_tgl"] }}');
    @endif
    
    @if(isset($filter["f_id_ven"]))
    $("#f_id_ven").val('{{ $filter["f_id_ven"] }}').trigger("change");
    @endif

    @if(isset($filter["f_no"]))
    $("#f_no").val('{{ $filter["f_no"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_id_dm"]))
    $("#f_id_dm").val('{{ $filter["f_id_dm"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_id_stt"]))
    $("#f_id_stt").val('{{ $filter["f_id_stt"] }}').trigger("change");
    @endif
</script>
@endsection
