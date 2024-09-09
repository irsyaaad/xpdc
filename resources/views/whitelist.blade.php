@extends('template.document2')
@section('data')
<div class="text-right">
    <button class="btn btn-md btn-info" type="button" onclick="goPopUp()"><span><i class="fa fa-plus"></i> </span> Tambah White List</button>
</div>
<br>
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-responsive table-striped" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Perusahaan</th>
                <th>IP Address</th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @if(count($data)<1)
            <tr>
                <td colspan="4" class="text-center"><b>Data Kosong</b></td>
            </tr>
            @endif
            @foreach($data as $key => $value)
            <tr>
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ strtoupper($value->perusahaan->nm_perush) }}</td>
                    <td>{{ strtoupper($value->ip_address) }}</td>
                    <td>
                        @if(get_admin())
                        <center>
                            <a href="#" onclick="goEdit('{{ $value->id_whitelist }}', '{{ $value->ip_address }}')" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" id="hapus" type="button" onclick="CheckDelete('{{ Request::segment(1) }}/{{ $value->id_whitelist }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                <i class="fa fa-times"></i>
                            </button>
                        </center>
                        @endif
                    </td>
                </tr>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

<div class="modal fade" id="modalip" tabindex="-1" role="dialog" aria-labelledby="ModalWhiteList" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>White List IP Address</h4>
            </div>
            <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" id="form_ip" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="ip_method" value="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_address">
                            <b>IP Address <span class="span-required"> * </span> </b>
                        </label>
                        
                        <input type="text" class="form-control m-input m-input--square" placeholder="https://192.168.1.1/" name="ip_address" id="ip_address" required="required" minlength="6" maxlength="50">
                        
                        @if ($errors->has('id_address'))
                        <label style="color: red">
                            {{ $errors->first('id_address') }}
                        </label>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success"> <i class="fa fa-save"> </i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    
    function goPopUp(){
        $("#ip_address").val("");
        $("#ip_method").val("POST");
        $("#form_ip").attr("action", "{{ url(Request::segment(1)) }}");
        $("#modalip").modal("show");
    }
    function goEdit(id, ip){
        $("#ip_address").val(ip);
        $("#form_ip").attr("action", "{{ url(Request::segment(1)) }}/"+id);
        $("#ip_method").val("PUT");
        $("#modalip").modal("show");
    }
</script>
@endsection