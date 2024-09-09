@extends('template.document')

@section('data')
<style>
    .txt-style{
        font-size: 12px;
    }
    
    thead{
        font-size: 16px;
        
    }
</style>

<form method="POST" action="{{ url(Request::segment(1)."/import"."/".Request::segment(3)) }}" id="form-data">
    @csrf
    <input type="hidden" id="id_handling" name="id_handling" value="{{ $id_handling }}"/>
    <div class="row">
        @if(Request::segment(1)!="handlingkirim")
        <div class="col-md-3">
            <label for="id_perush">
                <b>Perusahaan Pengirim : </b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_perush" name="id_perush">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach    
            </select>
            @if ($errors->has('id_perush'))
            <label style="color: red">
                {{ $errors->first('id_perush') }}
            </label>
            @endif
        </div>
        @endif
        <div class="col-md-3">
            <label for="id_dm">
                <b>Nomor Manifest (DM) : </b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_dm" name="id_dm">
                <option value="">-- Pilih Manifest --</option>
                @if(Request::segment(1)=="handlingkirim")
                    @foreach($dm as $key => $value)
                    <option value="{{ $value->id_dm }}">{{ strtoupper($value->kode_dm) }}</option>
                    @endforeach
                @endif
            </select>
            
            @if ($errors->has('id_dm'))
            <label style="color: red">
                {{ $errors->first('id_dm') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3">
            <label for="id_stt">
                <b>Nomor RESI : </b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_stt" name="id_stt"></select>
            @if ($errors->has('id_stt'))
            <label style="color: red">
                {{ $errors->first('id_stt') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-5">
            <button class="btn btn-sm btn-primary" type="button"  onclick="setMethod(1)"><i class="fa fa-search"></i> 
                Search
            </button>
            <a href="{{ url(Request::url()) }}" class="btn btn-sm btn-danger" ><i class="fa fa-refresh"></i> 
                Reset
            </a>
            <button class="btn btn-sm btn-success" type="button" onclick="setMethod(2)">
                <i class="fa fa-save"></i> Import
            </button>
            <a href="{{ url(Request::segment(1)."/".Request::segment(3)."/show") }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
        </div>
        
    </div>
</form>

<div class="row mt-1">
    <div class="col-md-12">
        <table class="table table-responsive table-stripped">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No. RESI </th> 
                    <th rowspan="2">Perusahaan Asal</th>
                    <th rowspan="2">Penerima</th>
                    <th rowspan="2"></th>
                    <th colspan="4"  class="text-center">Jumlah</th>
                    <th rowspan="2">
                        <label><input type="checkbox" value="1" id="c_all" name="c_all" > <b>Pilih Semua</b></label>
                    </th>
                </tr>
                <tr>
                    <th>Koli</th>
                    <th>Berat</th>
                    <th>Volume</th>
                    <th>Kubik</th>
                </tr>
            </thead>
            <tbody>
                <form method="POST" action="{{ url(Request::segment(1)."/doimport"."/".Request::segment(3)) }}" id="form-check">
                    @csrf
                    @if($data==null)
                    <tr>
                        <td colspan="10" class="text-center"><b> Data Kosong </b></td>
                    </tr>
                    @endif
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            <a href="#"  class="btn btn-primary-outline" onclick="getDetail({{$value->id_stt}})">{{ strtoupper($value->kode_stt) }}</a>
                        </td>
                        <td>{{ strtoupper($value->perush_asal) }}</td>
                        <td>{{ strtoupper($value->penerima_nm).PHP_EOL }}
                            <br> <label class="txt-style">{{ $value->penerima_alm.PHP_EOL }}</label>
                            <br> <label class="txt-style">{{ $value->nama_wil." - ".$value->prov." ".$value->kab }}</label>
                            <br> <label class="txt-style">{{ $value->penerima_telp }}</label>
                        </td>
                        <td></td>
                        <td>
                            {{ $value->n_koli }}
                        </td>
                        <td>
                            {{ $value->n_berat }}
                        </td>
                        <td>
                            {{ $value->n_volume }}
                        </td>
                        <td></td>
                        <td>
                            <input type="checkbox" name="c_stt[]" id="c_stt{{ $value->id_stt }}" class="form-control c_stt" value="{{  $value->id_stt }}">
                        </td>
                    </tr>
                    @endforeach
                </form>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Detail Stt</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="hasil">
                    <!-- end Modal Body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    
    function getDetail(id) {
        $("#modal-detail").modal('show');
        // alert(id);
        $.ajax({
            type: "GET",
            url: "{{ url('getDetailStt') }}/"+id,
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
    
    function setMethod(cek) {
        if(cek==1){
            $("#form-data").submit();
        }else{
            $("#form-check").submit();
        }
    }

    $(function(){
        $('#c_all').change(function()
        {
            if($(this).is(':checked')) {
                $(".c_stt").prop("checked", true);
            }else{   
                $(".c_stt").prop("checked", false);
            }
        });
    });

    @if(isset($id_stt->kode_stt))
    $('#id_stt').empty();
    $('#id_stt').append('<option value="{{ $id_stt->id_stt }}">{{ $id_stt->kode_stt }}</option>');
    @endif
    
    @if(Request::segment(1)=="handlingkirim")
    $('#id_stt').select2({
        placeholder: 'Cari STT ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('handlingkirim/getstttiba') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_stt').empty();
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

    @if(isset($id_dm))
    $("#id_dm").val('{{ $id_dm }}');
    @endif

    @else
    
    $('#id_perush').on('change', function() {
        $.ajax({
            type: "GET", 
            url: "{{ url("dmhandling") }}/getdm/"+$("#id_perush").val(), 
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $('#id_dm').empty();
                $('#id_dm').append('<option value="">-- Pilih DM --</option>');
                
                $.each(response, function(index, value) {
                    $('#id_dm').append('<option value="'+value.id_dm+'">'+value.kode_dm+'</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });
    
    $('#id_stt').select2({
        placeholder: 'Cari STT ....',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('dmhandling/getstttiba') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_stt').empty();
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

    @if(isset($id_perush))
    $("#id_perush").val('{{ $id_perush }}');
    @endif
    
    @if(isset($id_dm))
    $('#id_dm').append('<option value="{{ $id_dm }}">{{ $kode_dm }}</option>');
    $("#id_dm").val('{{ $id_dm }}');
    @endif
    @endif

</script>
@endsection