@extends('template.document')
@section('data')
@if(Request::segment(2)==null)
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row mt-1">
        <div class="col-md-12">
            <table class="table table-responsive table-hover" width="100%" >
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Nama Marketing</th>
                        <th>Telp</th>
                        <th>Kelamin</th>
                        <th>Perusahaan</th>
                        <th>Is Aktif</th>
                        <th class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                    @if(count($data) < 1)
                    <tr>
                        <td class="text-center" colspan="9"> Data Kosong </td>
                    </tr>
                    @endif
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ strtoupper($value->nm_marketing) }}</td>
                        <td>{{ strtoupper($value->telp) }}</td>
                        <td>
                            @if(isset($value->karyawan->jenis_kelamin))
                            {{ $value->karyawan->jenis_kelamin }} 
                            @endif
                        </td>
                        <td>
                            @if(isset($value->perush->nm_perush))
                            {{ $value->perush->nm_perush }} 
                            @endif
                        </td>
                        <td>
                            @if(isset($value->karyawan->is_aktif))
                            @if($value->karyawan->is_aktif == 1)
                            <i class="fa fa-check text-success"> </i>
                            @else
                            <i class="fa fa-times text-danger"> </i>
                            @endif
                            @endif
                        </td>
                        <td>
                            {!! inc_edit($value->id_marketing) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('marketing') }}@else{{ route('marketing.update', $data->id_marketing) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    @csrf
    
    <div class="row">
        <div class="col-md-4">
            <label for="id_karyawan">
                <b>Marketing</b> <span class="span-required"></span>
            </label>
            
            <select class="form-control" id="id_karyawan" name="id_karyawan">
                <option value=""> -- Pilih Marketing -- </option>
                @foreach($karyawan as $key => $value)
                <option value="{{ $value->id_karyawan }}"> {{ strtoupper($value->nm_karyawan)." ( ".$value->nm_perush." )" }} </option>
                @endforeach
            </select>
            
            @if ($errors->has('id_karyawan'))
            <label style="color: red">
                {{ $errors->first('id_karyawan') }}
            </label>
            @endif
            
        </div>
        
        <div class="form-group col-md-4" >
            <label for="telp">
                <b>Telp Marketing</b> <span class="span-required">*</span>
            </label>
            
            <input type="number" class="form-control" id="telp" name="telp" required="required" maxlength="16" />
            
            @if ($errors->has('telp'))
            <label style="color: red">
                {{ $errors->first('telp') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3" >
            @include('template.inc_action')
        </div>
    </div>
</form>

@endif

@endsection

@section('script')

<script>
    $("#id_karyawan").select2();

    @if(old("id_karyawan")!=null)  
    $("#id_karyawan").val('{{ old("id_karyawan") }}').trigger("change");
    @elseif(isset($data->id_karyawan))  
    $("#id_karyawan").val('{{ $data->id_karyawan }}').trigger("change");
    @endif
    
    @if(isset($data->telp))  
    $("#telp").val('{{ $data->telp }}');
    @endif
</script>

@endsection