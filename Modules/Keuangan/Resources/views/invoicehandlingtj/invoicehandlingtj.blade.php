@extends('template.document2')
@section('data')
@section('style')
<style>
    .modal {
        text-align: center;
    }

    @media screen and (min-width: 768px) {
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: " ";
            height: 66%;
        }
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
@endsection

@if(Request::segment(1)=="invoicehandlingterima" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include("template.filter-collapse")
    <div class="row-mt 1">
        <div class="col-md-12" style="overflow-x:auto">
            <table class="table table-striped table-responsive" width="100%" style="margin-top: 15px">
                <thead style="background-color: grey; color : #ffff; font-size:11pt">
                    <tr>
                        <th rowspan="2">No. Invoice</th>
                        <th rowspan="2">Perusahaan Pengirim</th>
                        <th colspan="3" class="text-center">Tanggal</th>
                        <th rowspan="2"> </th>
                        <th colspan="3" class="text-center">Nominal</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2">Is Lunas?</th>
                        <th rowspan="2">Admin</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <td>Invoice</td>
                        <td>Tagihan</td>
                        <td>Jth Tempo</td>

                        <td>Invoice</td>
                        <td>Bayar</td>
                        <td>Sisa</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ strtoupper($value->kode_invoice) }}</td>
                        <td>{{ strtoupper($value->nm_perush) }}</td>
                        <td>{{ dateindo($value->tgl_invoice) }}</td>
                        <td>@if($value->tgl_tagihan!=null){{ dateindo($value->tgl_tagihan) }}@endif</td>
                        <td>@if($value->tgl_jatuh_tempo!=null){{ dateindo($value->tgl_jatuh_tempo) }}@endif</td>
                        <td></td>
                        <td>{{ tonumber($value->c_total) }}</td>
                        <td>{{ tonumber($value->dibayar) }}</td>
                        <td>{{ tonumber($value->c_total - $value->dibayar) }}</td>
                        <td>{{ strtoupper($value->nm_status) }}</td>
                        <td>
                            @if($value->is_lunas==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>{{ strtoupper($value->admin) }}</td>
                        <td>
                            <center>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <form method="POST" action="{{ url(Request::segment(1).'/'.$value->id_invoice."/terima") }}" id="form-send{{ $value->id_invoice }}">
                                            @csrf
                                            @if($value->id_status == 2)
                                            <button class="dropdown-item" type="button" onclick="goTerima('{{ $value->id_invoice }}', '{{ $value->kode_invoice }}')"><i class="fa fa-check"></i> Terima</button>
                                            @endif

                                            <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_invoice."/show") }}"><i class="fa fa-money"></i> Detail</a>
                                            <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_invoice."/cetak") }}"><i class="fa fa-print"></i> Cetak</a>
                                            {{-- <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_invoice."/ttd") }}"><i class="fa fa-print"></i> Ttd</a> --}}
                                        </form>
                                    </div>
                                </div>
                            </center>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            @include("template.paginator")
        </div>
    </div>
</form>

<div class="modal fade" id="modal-terima" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <center>
                    <h4 style="margin-left: 5%; font-weight: bold;" id="textterima"> </h4>
                </center>
                <hr>
                <div class="text-right">
                    <button type="button" class="btn btn-sm btn-success" onclick="goSubmit()"><i class="fa fa-check"></i> Terima</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" style="margin-left:10px"><span aria-hidden="true"><i class="fa fa-times"></i> Batal</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

@section('script')
<script>
    var id = null;
    var kode = null;

    function goTerima(ide, kodes){
        id = ide;
        kode = kodes;
        $("#modal-terima").modal("show");
        $("#textterima").text("Terima Biaya Invoice Handling "+kode+" ?");
    }

    function goSubmit(){
        $("#form-send"+id).submit();
    }

    @if(isset($filter["invoice"]->kode_invoice))
	$("#id_invoice").empty();
	$("#id_invoice").append('<option value="{{ $filter["invoice"]->id_invoice }}">{{ strtoupper($filter["invoice"]->kode_invoice) }}</option>');
	@endif


    @if (isset($filter['perusahaan']))
        $("#id_perush").val('{{$filter['perusahaan']}}');
    @endif

    $('#id_invoice').select2({
        placeholder: 'Cari Kode Invoice ....',
        ajax: {
            url: '{{ url('getInvoiceHandlingtj') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_invoice').empty();
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
