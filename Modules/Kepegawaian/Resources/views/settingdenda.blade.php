@extends('template.document')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filter-collapse')
    @csrf
    <div class="row">
        <table class="table table-responsive table-striped" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Perusahaan / Devisi</th>
                    <th>Jenis</th>
                    <th>Frekuensi</th>
                    <th>Nominal</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
                @php
                $id = 0;
                @endphp
                @foreach($data as $key => $value)
                <tr>
                    <td>{{ $id+1 }}</td>
                    <td>
                        @if(isset($value->perusahaan->nm_perush))
                        {{ $value->perusahaan->nm_perush }}
                        @endif  
                    </td>
                    <td>
                        @if(isset($value->jenis->nm_jenis))
                        {{ $value->jenis->nm_jenis }}
                        @elseif(strtolower($value->id_jenis)=="a")
                        Alpha
                        @elseif(strtolower($value->id_jenis)=="2")
                        Terlambat Absen
                        @elseif(strtolower($value->id_jenis)=="3")
                        Tidak Absen Masuk
                        @elseif(strtolower($value->id_jenis)=="5")
                        Absen Pulang Duluan
                        @elseif(strtolower($value->id_jenis)=="4")
                        Tidak Absen Pulang
                        @endif 
                    </td>
                    <td>
                        {{ $value->frekuensi }}
                    </td>
                    <td>
                        {{ $value->nominal }}
                    </td>
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
</form>
<div class="modal fade" id="modal-copy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <h3 style="font-weight: bold; margin-top:10pt">Copy Settingan Denda</h3>
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
<form method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }} @else{{ url(Request::segment(1), $data->id_setting) }}@endif" enctype="multipart/form-data">
    @csrf
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Perusahaan
            </label>
            <select class="form-control" id="id_perush" name="id_perush" required>
                <option value="">-- Perusahaan --</option>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="id_jenis">
                    <b>Jenis</b><span class="span-required"> *</span>
                </label>
                
                <select class="form-control" name="id_jenis" id="id_jenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenis as $key => $value)
                    <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
                    @endforeach
                    <option value="A">{{ strtoupper("Tidak Masuk (Alpha)") }}</option>
                    <option value="2">{{ strtoupper("Terlambat Absen") }}</option>
                    <option value="3">{{ strtoupper("Tidak Absen Masuk") }}</option>
                    <option value="4">{{ strtoupper("Absen Pulang Duluan") }}</option>
                    <option value="5">{{ strtoupper("Tidak Absen Pulang") }}</option>
                </select>
                
                @if ($errors->has('id_jenis'))
                <label style="color: red">
                    {{ $errors->first('id_jenis') }}
                </label>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="frekuensi">
                    <b>Frekuensi Hari</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan frekuensi waktu" type="number" class="form-control" name="frekuensi" id="frekuensi" value="@if(isset($data->frekuensi)){{ $data->frekuensi }}@else{{ old("frekuensi") }}@endif" />
                
                @if ($errors->has('frekuensi'))
                <label style="color: red">
                    {{ $errors->first('frekuensi') }}
                </label>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="nominal">
                    <b>Nominal Denda</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan nominal Denda" type="number" class="form-control" name="nominal" id="nominal" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{ old("nominal") }}@endif" />
                
                @if ($errors->has('nominal'))
                <label style="color: red">
                    {{ $errors->first('nominal') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-12 text-right">
            @include('template.inc_action')
        </div>
    </div>
</form>
@endif
@endsection
@section('script')
<script type="text/javascript">
    $("#f_id_perush").select2();

    @if(isset($data->id_jenis))
    $("#id_jenis").val("{{ $data->id_jenis }}");
    @endif
    
    @if(isset($filter["f_id_perush"]))
    $("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
    @endif
    
    function CheckStatus(){
        $("#modal-status").modal('show');
    }
    
    function goSubmitUpdate() {
        $("#form-status").submit();
    }
    
    function goSetting(){
        $("#modal-copy").modal("show");
    }

    $("#id_perush").select2();

    @if(isset($data->id_perush))
    $("#id_perush").val("{{ $data->id_perush }}").trigger("chage");
    @endif

    @if(old("id_perush")!=null)
    $("#id_perush").val("{{ old('id_perush') }}").trigger("chage");
    @endif

</script>
@endsection