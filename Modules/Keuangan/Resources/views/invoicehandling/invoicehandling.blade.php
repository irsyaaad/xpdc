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

@if(Request::segment(1)=="invoicehandling" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include("template.filter-collapse")
    <div class="row-mt 1">
        <div class="col-md-12" style="overflow-x:auto">
            <table class="table table-hover table-responsive">
                <thead style="background-color: grey; color : #ffff; font-size:11pt">
                    <tr>
                        <th rowspan="2">No. Invoice</th>
                        <th rowspan="2">Perusahaan Tujuan</th>
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
                                        <form method="POST" action="{{ url(Request::segment(1).'/'.$value->id_invoice."/kirim") }}" id="form-send{{ $value->id_invoice }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_invoice."/show") }}"><i class="fa fa-eye"></i> Detail</a>
                                            <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_invoice."/cetak") }}"><i class="fa fa-print"></i> Cetak</a>
                                            @if($value->id_status==1)
                                            <button class="dropdown-item" type="button" onclick="goKirim('{{ $value->id_invoice }}', '{{ $value->kode_invoice }}')"><i class="fa fa-send"></i> Kirim</button>
                                            <button class="dropdown-item" type="button" onclick="goDelete('{{ $value->id_invoice }}', '{{ $value->kode_invoice }}')"><i class="fa fa-times"></i> Hapus</button>
                                            @endif
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
    </div>
</form>

<div class="row" style="margin-top: 4%; font-weight: bold;">
    @include("template.paginator")
</div>

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-data">
                <input type="hidden" name="_method" id="_method" value="PUT">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <label for="id_perush_tj" >Perusahaan Tujuan<span class="span-required"> *</span></label>
                            <select class="form-control m-input m-input--square" id="id_perush_tj" name="id_perush_tj">
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($perusahaan as $key => $value)
                                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('id_perush_tj'))
                            <label style="color: red">
                                {{ $errors->first('id_perush_tj') }}
                            </label>
                            @endif
                        </div>

                        <div class="col-md-12" style="margin-top: 15px">
                            <label for="id_dm" >Nomor DM<span class="span-required"> *</span></label>
                            <select class="form-control m-input m-input--square" id="id_dm" name="id_dm">
                                <option value="">-- Pilih No. DM --</option>
                            </select>

                            @if ($errors->has('id_dm'))
                            <label style="color: red">
                                {{ $errors->first('id_dm') }}
                            </label>
                            @endif
                        </div>

                        <div class="col-md-12" style="margin-top: 15px">
                            <label for="tgl_jatuh_tempo" >Perkiraan Jatuh Tempo <span class="span-required"> *</span></label>

                            <input type="date" class="form-control" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" value="@if(isset($data->tgl_jatuh_tempo)){{$data->tgl_jatuh_tempo}}@endif" required="required">

                            @if ($errors->has('tgl_jatuh_tempo'))
                            <label style="color: red">
                                {{ $errors->first('tgl_jatuh_tempo') }}
                            </label>
                            @endif
                        </div>

                        <div class="col-md-12 text-right" style="padding-top: 15px">
                            <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <form method="POST" action="#" id="form-delete">
                @csrf
                {{ method_field("DELETE") }}
                <div class="modal-body">
                    <label class="text-center"><h5 id="txt-judul">Apakah Anda Ingin Hapus Data Ini ?</h5></label>
                    <hr>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Iya"> <i class="fa fa-check"> </i> Ya</button>
                        <button type="button" class="btn btn-sm btn-danger"  data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Tidak"><i class="fa fa-times"> </i> Tidak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-kirim" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label class="text-center"><h5 id="textkirim">Apakah Anda Ingin Mengirim Invoice ?</h5></label>
                <hr>
                <div class="text-right">
                    <button type="button" class="btn btn-sm btn-success" onclick="goSubmit()" data-toggle="tooltip" data-placement="bottom" title="Kirim"> <i class="fa fa-send"> </i> Kirim</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

@section('script')
<script>

    $('#id_invoice').select2({
        placeholder: 'Cari Kode Invoice ....',
        ajax: {
            url: '{{ url('getInvoiceHandling') }}',
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

    @if(isset($filter["invoice"]->kode_invoice))
    $("#id_invoice").empty();
    $("#id_invoice").append('<option value="{{ $filter["invoice"]->id_invoice }}">{{ strtoupper($filter["invoice"]->kode_invoice) }}</option>');
    @endif


    @if (isset($filter['perusahaan']))
    $("#id_perush").val('{{$filter['perusahaan']}}');
    @endif

    function goEdit(id, id_group, nominal){
        $("#_method").val("PUT");
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}/"+id);
        $("#modal-create").modal("show");
    }

    function refresh(){
        $("#_method").val("POST");
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}");
    }

    $("#id_perush_tj").on("change", function(e) {
        var id_perush = $("#id_perush_tj").val();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url(Request::segment(1)) }}/getDm/"+id_perush,
            success: function(data) {
                $("#id_dm").empty();
                $.each(data, function(key, value)
                {
                    $("#id_dm").append('<option value=' + value.kode + '>' + value.value + '</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    });

    var id = null;
    var kode = null;

    function goDelete(ides, kodes){
        $("#txt-judul").text("Apakah Ingin Hapus Invoice "+kodes+" ?");
        $("#modal-delete").modal("show");
        $("#form-delete").attr("action", "{{ url(Request::segment(1)) }}"+"/"+ides);
    }

    function goKirim(ide, kodes){
        id = ide;
        kode = kodes;
        $("#modal-kirim").modal("show");
        $("#textkirim").text("Apakah Anda Ingin Mengirim Invoice "+kode+" ?");
    }

    function goSubmit(){
        $("#form-send"+id).submit();
    }
</script>
@endsection
