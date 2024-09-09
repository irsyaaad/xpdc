@extends('template.document')

@section('data')

@if(Request::segment(1)=="jamkerja" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>Perusahaan / Devisi</th>
                        <th>Shift</th>
                        <th>Jam Masuk</th>
                        <th>Jam Terlambat</th>
                        <th>Toleransi Masuk</th>
                        <th>Jam Istirahat</th>
                        <th>Jam Istirahat Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Jam Pulang Sabtu</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            @if(isset($value->perush->nm_perush))
                            {{ $value->perush->nm_perush }}
                            @endif
                        </td>
                        <td>{{ $value->shift }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_masuk)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_terlambat)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_toleransi)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_istirahat)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_istirahat_masuk)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_pulang)) }}</td>
                        <td>{{ date("H:i:s", strtotime($value->jam_sabtu)) }}</td>
                        <td>
                            {!! inc_edit($value->id_setting) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@else


<form method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }}@else{{ url(Request::segment(1), $data->id_setting) }} @endif" enctype="multipart/form-data">
    
    @if(Request::segment(3)=="edit")>
    {{ method_field("PUT") }} 
    @endif
    @csrf
    <div class="row">
        @if(Request::segment(2)=="create")
        <div class="col-md-3 form-group m-form__group">
            <label for="shift">
                <b>Shift</b><span class="span-required"> *</span>
            </label>
            
            <input placeholder="Masukan Jam Shift" type="number" class="form-control" name="shift" id="shift" value="@if(isset($data->shift)){{ $data->shift }}@else{{ old("shift") }}@endif" />
            
            @if ($errors->has('shift'))
            <label style="color: red">
                {{ $errors->first('shift') }}
            </label>
            @endif
        </div>
        @endif
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_masuk">
                    <b>Jam Masuk</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Masuk" type="time" class="form-control" name="jam_masuk" id="jam_masuk" value="@if(isset($data->jam_masuk)){{ $data->jam_masuk }}@else{{ old("jam_masuk") }}@endif" />
                
                @if ($errors->has('jam_masuk'))
                <label style="color: red">
                    {{ $errors->first('jam_masuk') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_terlambat">
                    <b>Jam Terlambat</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Terlambat" type="time" class="form-control" name="jam_terlambat" id="jam_terlambat" value="@if(isset($data->jam_terlambat)){{ $data->jam_terlambat }}@else{{ old("jam_terlambat") }}@endif" />
                
                @if ($errors->has('jam_terlambat'))
                <label style="color: red">
                    {{ $errors->first('jam_terlambat') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_toleransi">
                    <b>Jam Toleransi Masuk</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Toleransi Masuk" type="time" class="form-control" name="jam_toleransi" id="jam_toleransi" value="@if(isset($data->jam_toleransi)){{ $data->jam_toleransi }}@else{{ old("jam_toleransi") }}@endif" />
                
                @if ($errors->has('jam_toleransi'))
                <label style="color: red">
                    {{ $errors->first('jam_toleransi') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_istirahat">
                    <b>Jam Istirahat</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Istirahat" type="time" class="form-control" name="jam_istirahat" id="jam_istirahat" value="@if(isset($data->jam_istirahat)){{ $data->jam_istirahat }}@else{{ old("jam_istirahat") }}@endif" />
                
                @if ($errors->has('jam_istirahat'))
                <label style="color: red">
                    {{ $errors->first('jam_istirahat') }}
                </label>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_istirahat">
                    <b>Jam Istirahat Masuk</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Istirahat Masuk" type="time" class="form-control" name="jam_istirahat_masuk" id="jam_istirahat_masuk" value="@if(isset($data->jam_istirahat_masuk)){{ $data->jam_istirahat_masuk }}@else{{ old("jam_istirahat_masuk") }}@endif" />
                
                @if ($errors->has('jam_istirahat_masuk'))
                <label style="color: red">
                    {{ $errors->first('jam_istirahat_masuk') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_pulang">
                    <b>Jam Pulang</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Pulang" type="time" class="form-control" name="jam_pulang" id="jam_pulang" value="@if(isset($data->jam_pulang)){{ $data->jam_pulang }}@else{{ old("jam_pulang") }}@endif" />
                
                @if ($errors->has('jam_pulang'))
                <label style="color: red">
                    {{ $errors->first('jam_pulang') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group m-form__group">
                <label for="jam_sabtu">
                    <b>Jam Pulang Sabtu</b><span class="span-required"> *</span>
                </label>
                
                <input placeholder="Masukan Jam Pulang" type="time" class="form-control" name="jam_sabtu" id="jam_sabtu" value="@if(isset($data->jam_sabtu)){{ $data->jam_sabtu }}@else{{ old("jam_sabtu") }}@endif" />
                
                @if ($errors->has('jam_sabtu'))
                <label style="color: red">
                    {{ $errors->first('jam_sabtu') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="col-md-12 text-right">
            @include('template.inc_action')
        </div>
        
    </div>
    @endif
    
    @endsection
    
    @section('script')
    <script type="text/javascript">
        function CheckStatus(){
            $("#modal-status").modal('show');
        }
        
        function goSubmitUpdate() {
            $("#form-status").submit();
        }
    </script>
    @endsection