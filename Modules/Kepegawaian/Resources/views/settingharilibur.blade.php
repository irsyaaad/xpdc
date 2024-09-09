@extends('template.document2')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filter-collapse')
    @csrf
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Perusahaan / Devisi</th>
                        <th>Tgl Awal Libur</th>
                        <th>Tgl Akhir Libur</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @php
                    $id = 1;
                    @endphp
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $id }}</td>
                        <td>@if(isset($value->perush->nm_perush)){{ $value->perush->nm_perush }}@endif</td>
                        <td>@if(isset($value->dr_tgl)){{ dateindo($value->dr_tgl) }}@endif</td>
                        <td>@if(isset($value->sp_tgl)){{ dateindo($value->sp_tgl) }}@endif</td>
                        <td>{{ $value->keterangan }}</td>
                        <td>
                            {!! inc_edit($value->id_setting) !!}
                        </td>
                    </tr>
                    
                    @php
                    $id++;
                    @endphp
                    
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginate')
    </div>
</form>

<div class="modal fade" id="modal-copy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <center><h3 style="font-weight: bold; margin-top:10pt">Copy Settingan Denda</h3></center>
            <div class="modal-body">
                <hr>
                <form method="POST" action="{{ url(Request::segment(1)."/copy") }}" enctype="multipart/form-data" id="form-status">
                    <div class="row">
                        
                        @csrf
                        <div class="col-md-12">
                            <label style="font-weight: bold;">
                                Perusahaan Asal
                            </label>
                            <select class="form-control" id="perush_asal" name="perush_asal">
                                <option value="">-- Pilih Perusahaan Asal --</option>
                                @foreach($role_perush as $key => $value)
                                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="margin-top: 15px">
                            <label style="font-weight: bold;">
                                Perusahaan Tujuan
                            </label>
                            <select class="form-control" id="perush_tujuan" name="perush_tujuan">
                                <option value="0">-- Pilih Perusahaan Tujuan --</option>
                                @foreach($role_perush as $key => $value)
                                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-12 text-right" style="margin-top: 15px">
                            <button class="btn btn-md btn-success" type="submit"  data-toggle="tooltip" data-placement="bottom" title="Copy Setting"><span><i class="fa fa-save"></i></span> Ya</button>
                            <button class="btn btn-md btn-danger" type="button" data-dismiss="modal" aria-label="Close"  data-toggle="tooltip" data-placement="bottom" title="Batal Copy Setting"><span><i class="fa fa-times"></i></span> Tidak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@else
<form method="POST" action="@if(Request::segment(3)=="edit"){{ url(Request::segment(1)."/".$data->id_setting) }}@else{{ url(Request::segment(1)) }}@endif" enctype="multipart/form-data" id="form-create">
    <div class="row">
        @if(Request::segment(3)=="edit")
        {{ method_field("PUT") }} 
        @endif
        
        @csrf
        <div class="col-md-4">
            <label style="font-weight: bold;">
                Tanggal Awal Libur <label class="text-danger"> *</label>
            </label>
            <input class="form-control" id="dr_tgl" name="dr_tgl" placeholder="Masukan Tanggal Awal Libur" type="date" value="@if(isset($data->dr_tgl)){{ $data->dr_tgl }}@else{{ old("dr_tgl") }}@endif" required/>
            @if ($errors->has('dr_tgl'))
            <label class="text-danger">
                {{ $errors->first('dr_tgl') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4">
            <label style="font-weight: bold;">
                Tanggal Akhir Libur <label class="text-danger"> *</label>
            </label>
            <input class="form-control" id="sp_tgl" name="sp_tgl" placeholder="Masukan Tanggal Akhir Libur" type="date" value="@if(isset($data->sp_tgl)){{ $data->sp_tgl }}@else{{ old("sp_tgl") }}@endif" required/>
            @if ($errors->has('sp_tgl'))
            <label class="text-danger">
                {{ $errors->first('sp_tgl') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4">
            <label style="font-weight: bold;">
                Keterangan <label class="text-danger"> *</label>
            </label>
            <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Masukan Keterangan Libur" maxlength="100">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif</textarea>
            @if ($errors->has('keterangan'))
            <label class="text-danger">
                {{ $errors->first('keterangan') }}
            </label>
            @endif
            <br>
            @if(Request::segment(3)!="edit")
            <div class="col-md-12 checkbox">
                <label><input type="checkbox" value="1" id="c_all" name="c_all">  Semua Cabang</label>
            </div>
            @endif
        </div>
        
        <div class="col-md-12 text-right" style="margin-top: 15px">
            @include('template.inc_action')
        </div>
    </div>
</form>

@endif

@endsection
@section('script')
<script type="text/javascript">
    $("#f_id_perush").select2();

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif	

    @if(isset($filter["f_id_perush"]))
    $("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
    @endif	
    
    function goSetting(){
        $("#modal-copy").modal("show");
    }
</script>
@endsection
