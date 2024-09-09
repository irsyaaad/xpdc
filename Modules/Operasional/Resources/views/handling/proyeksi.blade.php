@extends('template.document2')

@section('data')
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

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row mt-1">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-md btn-primary" style="margin-top: 10px" data-toggle="modal" data-target="#modal-create" onclick="refresh()"><span> <i class="fa fa-plus"> </i> </span> Tambah Proyeksi</button>
        </div>
        <div class="col-md-12 mt-1" >
            <table class="table table-responsive table-striped">
                <thead style="background-color: grey; color:#fff">
                    <tr>
                        <th>No</th>
                        <th>Group Biaya</th>
                        <th>Nominal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            {{ ($key+1) }}
                        </td>
                        <td>
                            @if(isset($value->nm_biaya_grup)){{ strtoupper($value->nm_biaya_grup) }}@endif
                        </td>
                        <td>
                            {{ $value->nominal }}
                        </td>
                        <td>
                            <button type="button" onclick="goEdit('{{ $value->id_proyeksi }}', '{{ $value->id_biaya_grup }}', '{{ $value->nominal }}')" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmhandling/'.$value->id_proyeksi.'/deletebiaya') }}')" data-toggle="tooltip" data-placement="bottom" title="Hapus STT">
								<span><i class="fa fa-times"></i></span>
							</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</form>

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            
            <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-data">
                <input type="hidden" name="_method" id="_method" value="PUT">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12" style="padding-top: 10px">
                            <label for="id_biaya_grup">
                                <b>Group Biaya</b> <span class="span-required"> *</span>
                            </label>
                            
                            <select class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" required>
                                <option value="">-- Pilih Group Biaya --</option>
                                @foreach($group as $key => $value)
                                <option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_biaya_grup'))
                            <label style="color: red">
                                {{ $errors->first('id_biaya_grup') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12" style="padding-top: 10px">
                            <label for="nominal">
                                <b>Nominal Biaya</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required maxlength="16" />
                            
                            @if ($errors->has('nominal'))
                            <label style="color: red">
                                {{ $errors->first('nominal') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 text-right" style="padding-top: 10px">
                            <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

@endif

@endsection

@section('script')
<script type="text/javascript">
    function goEdit(id, id_group, nominal){
        $("#_method").val("PUT");
        $("#nominal").val(nominal);
        $("#id_biaya_grup").val(id_group);
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}/"+id);
        $("#modal-create").modal("show");
    }
    
    function refresh(){
        $("#_method").val("POST");
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}");
        $("#nominal").val("");
        $("#id_biaya_grup").val("");
    }
    
</script>
@endsection