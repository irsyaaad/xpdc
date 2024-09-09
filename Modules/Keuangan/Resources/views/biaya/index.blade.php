@extends('template.document2')

@section('data')
<style>
    .class-edit{
        text-decoration: none;
    }
</style>
@if(Request::segment(1)=="biayahpp" && (Request::segment(2)==null or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row mt-1">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No. DM</th>
                        <th class="text-center" colspan="3">Asal Dan Tujuan</th>
                        <th>Sopir</th>
                        <th>Perkiraan Omzet</th>
                        <th>Biaya Hpp</th>
                        <th>Dibayar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            <a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/detail' }}" class="class-edit">
                                {{ strtoupper($value->kode_dm) }}
                            </a>
                            <br>
                            @if(isset($value->layanan->nm_layanan))
                            {{ strtoupper($value->layanan->nm_layanan) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->perush_asal->nm_perush))
                            {{ strtoupper($value->perush_asal->nm_perush) }}
                            @endif
                            <br>{{dateindo($value->tgl_berangkat)}}
                        </td>
                        <td><i class="fa fa-arrow-right"></i></td>
                        <td>
                            @if(isset($value->perush_tujuan->nm_perush))
                            {{ strtoupper($value->perush_tujuan->nm_perush) }}
                            @endif
                            <br>{{dateindo($value->tgl_sampai)}}
                        </td>
                        <td>@if(isset($value->sopir->nm_sopir))
                            {{ strtoupper($value->sopir->nm_sopir) }}
                            @endif
                            <br>
                            @if(isset($value->armada->nm_armada))
                            {{ strtoupper($value->armada->nm_armada) }}
                            @endif
                            <br>
                            @if(isset($value->kapal->nm_kapal))
                            {{ strtoupper($value->kapal->nm_kapal) }}
                            @endif
                        </td>
                        <td>{{ torupiah($value->c_total) }}</td>
                        <td>{{ torupiah($value->c_pro) }}</td>
                        <td>{{ torupiah($value->n_bayar) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <form method="POST">
                                        <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_dm."/print") }}"><i class="fa fa-print"></i> Cetak</a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/detail' }}"><i class="fa fa-eye"></i> Detail Biaya</a>
                                        
                                        @if($value->is_approve==true and $value->is_lunas != true)
                                        <a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/setbayar' }}"><i class="fa fa-money"></i> Set Bayar</a>
                                        @endif
                                    </form>
                                </div>
                            </div> 
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginate')
    </div>
</form>

<script type="text/javascript">
    $("#f_perushtj").select2();
    $("#f_id_dm").select2();
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    @if(isset($filter["dr_tgl"]))
    $("#dr_tgl").val('{{ $filter["dr_tgl"] }}');
    @endif

    @if(isset($filter["sp_tgl"]))
    $("#sp_tgl").val('{{ $filter["sp_tgl"] }}');
    @endif

    @if(isset($filter["f_perushtj"]))
    $("#f_perushtj").val('{{ $filter["f_perushtj"] }}').trigger("change");
    @endif

    @if(isset($filter["f_perushtj"]))
    $("#f_perushtj").val('{{ $filter["f_perushtj"] }}').trigger("change");
    @endif
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif

</script>

@elseif(Request::segment(2)=="create")
@include('keuangan::biaya.create')
@elseif(Request::segment(3)=="bayar" or Request::segment(3)=="setbayar")
@include('keuangan::biaya.bayar')
@elseif(Request::segment(3)=="detail")
@include('keuangan::biaya.detail')
@endif
@endsection