@extends('template.document2')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row mt-1">
        <div class="col-md-12" >
            <table class="table table-responsive table-hover" style="width=100%">
                <thead style="background-color: grey; color : #ffff; font-size:11pt;">
                    <tr>
                        <th>No. STT</th>
                        <th>No. DM Asal</th>
                        <th>No. AWB</th>
                        <th>Pengirim</th>
                        <th>Asal > Tujuan</th>
                        <th>Status</th>
                        <th>Status Stt Awb</th>
                        <th width="15%" class="text-center">
                            <label>
                                <input type="checkbox" id="cek_all" name="cek_all" value="1">
                                Pilih Semua
                            </label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data)==null) 
                    <tr>
                        <td colspan="11" class="text-center"> Tidak ada data </td>
                    </tr>
                    @endif
                    @csrf
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            @if(isset($value->kode_stt))
                            <a href="#" onclick="goDetail('{{ $value->id_stt }}')" class="class-edit">
                                {{ $value->kode_stt }}
                            </a>
                            @endif
                            <br>
                            {{ dateindo($value->tgl_masuk) }}
                        </td>
                        <td>
                            @if(isset($value->kode_dm)){{ $value->kode_dm }}@endif
                        </td>
                        <td>
                            @if(isset($value->no_awb))
                            {{ $value->no_awb }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_perush)){{ $value->nm_perush }}@endif
                        </td>
                        <td>
                            @if(isset($value->asal))
                            {{ $value->asal }}
                            <br>
                            >
                            @if(isset($value->tujuan))
                            {{ $value->tujuan }}
                            @endif
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_status)){{ $value->nm_status }}@endif
                        </td>
                        <td>
                            @if(isset($value->status_asal)){{ $value->status_asal }}@endif
                        </td>
                        <td width="6%" class="text-center">
                            @if($value->id_status != $value->id_status_asal and $value->id_status > $value->id_status_asal)
                            <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_stt }}" class="form-control c_pro" value="{{  $value->id_stt }}">
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginator')
    </div>
</form>

<div class="modal fade" id="modal-detail"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Detail Stt</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="hasil">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-status"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Apakah Anda Ingin Update Status AWB ?</h3>
                        <hr>
                    </div>
                    
                    <div class="col-md-12 text-right">
                        <button type="button" onclick="goSubmit()" class="btn btn-md btn-success"><i class="fa fa-check"> </i> Iya </button>
                        <button type="button" class="btn btn-md btn-danger" onclick="goCancel()" data-dismiss="modal"><i class="fa fa-times"> </i> Tidak </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection


@section('script')
<script type="text/javascript">
    
    $('#cek_all').click(function() {
        if ($('#cek_all').is(':checked')) {
            $('input:checkbox').attr('checked', true);
        } else{
            $('input:checkbox').attr('checked', false);
        }
    });
    
    function goDetail(id) {
        $("#modal-detail").modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('detailstt') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $("#hasil").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    function goSubmit(){
        $("#form-select").submit();
    }
    
    function goUpdate(){
        $("#modal-status").modal("show");
        $("#form-select").attr("method", "POST");
        $("input[name='_method']").val("POST");
        $("#form-select").attr("action", "{{ url(Request::segment(1).'/updatestatus') }}");
    }
    
    function goCancel(){
        $("#form-select").attr("method", "GET");
        $("input[name='_method']").val("GET");
        $("#form-select").attr("action", "{{ url(Request::segment(1).'/filter') }}");
    }
    
    function goFilter(){
        $("input[name='_method']").val("GET");
        $("#form-select").attr("method", "GET");
        $("#form-select").attr("action", "{{ url(Request::segment(1).'/filter') }}");
        $("#form-select").submit();
    }
    
    $('#filterasal').select2({
        placeholder: 'Cari Kota Asal ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterasal').empty();
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

    $('#filtertujuan').select2({
        placeholder: 'Cari Kota Tujuan ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filtertujuan').empty();
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

    $('#filterdm').select2({
        placeholder: 'Cari DM Asal ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url("sttawb/getDmAwb") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterdm').empty();
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

    $('#filterawb').select2({
        placeholder: 'Cari Nomor Awb ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url("sttawb/getSttAwb") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterawb').empty();
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

    @if(isset($filter["id_layanan"]))
    $("#filterlayanan").val('{{ $filter["id_layanan"] }}');
    @endif

    @if(isset($filter["id_status"]))
    $("#filterstatusstt").val('{{ $filter["id_status"] }}');
    @endif

    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif

    @if(isset($filter["id_dm"]->kode_dm))
    $("#filterdm").empty();
	$("#filterdm").append('<option value="{{ $filter["id_dm"]->id_dm }}">{{ strtoupper($filter["id_dm"]->kode_dm) }}</option>');
    @endif

    @if(isset($filter["no_awb"]->kode_stt))
    $("#filterawb").empty();
	$("#filterawb").append('<option value="{{ $filter["no_awb"]->id_stt }}">{{ strtoupper($filter["no_awb"]->kode_stt) }}</option>');
    @endif

    @if(isset($filter["id_asal"]->nama_wil))
    $("#filterasal").empty();
	$("#filterasal").append('<option value="{{ $filter["id_asal"]->id_wil }}">{{ strtoupper($filter["id_asal"]->nama_wil) }}</option>');
    @endif

    @if(isset($filter["id_tujuan"]->nama_wil))
    $("#filtertujuan").empty();
	$("#filtertujuan").append('<option value="{{ $filter["id_tujuan"]->id_wil }}">{{ strtoupper($filter["id_tujuan"]->nama_wil) }}</option>');
    @endif
    
    @if(isset($filter["no_awb"]->id_awb))
    $("#filterawb").empty();
	$("#filterawb").append('<option value="{{ $filter["no_awb"]->id_awb }}">{{ strtoupper($filter["no_awb"]->no_awb) }}</option>');
    @endif

    $("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});

</script>
@endsection