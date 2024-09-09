@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url('rolemenu') }}@else{{ route('rolemenu.update', $data->id_rm) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit" )
    {{ method_field("PUT") }}
    @endif
    @csrf
    <div class="row">
        <div class="form-group col-md-4">
            <label for="id_role">
                <b>Role User</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="id_role" name="id_role" required>
                <option value="">-- Pilih Role --</option>
                @foreach($role as $key => $value)
                <option value="{{ $value->id_role }}">{{ strtoupper($value->nm_role) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_role'))
            <label style="color: red">
                {{ $errors->first('id_role') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="module">
                <b>Module</b>
            </label>
            
            <select class="form-control" id="id_module" name="id_module" required>
                <option>-- Pilih Module --</option>
                @foreach($module as $key => $value)
                <option value="{{ $value->id_module }}">{{ strtoupper($value->nm_module) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('module'))
            <label style="color: red">
                {{ $errors->first('module') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="id_menu">
                <b>Menu</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="id_menu" name="id_menu" required>
                <option value="">-- Pilih Menu --</option>
                @foreach($menu as $key => $value)
                <option value="{{ $value->id_menu }}">{{ strtoupper($value->nm_menu) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_menu'))
            <label style="color: red">
                {{ $errors->first('id_menu') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-8">
            <label for="akses">
                <b>Akses</b>
            </label>
            
            <div class="row">
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_all" name="c_all"> Semua</label>
                </div>
                
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_read" name="c_read"> View</label>
                </div>
                
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_insert" name="c_insert"> Insert</label>
                </div>
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_update" name="c_update"> Update</label>
                </div>
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_delete" name="c_delete"> Delete</label>
                </div>
                <div class="col-md-2 checkbox">
                    <label><input type="checkbox" value="1" id="c_other" name="c_other"> Others</label>
                </div>
            </div>
        </div>
        
        @include('template.inc_action')
    </div>
</form>
@endsection

@section('script')
<script>
    $("#id_module").select2();
    $("#id_menu").select2();
    $("#id_role").select2();
    
    @if(isset($data->c_read) and $data->c_read==1)
    $("#c_read").prop("checked", true);
    @endif
    
    @if(isset($data->c_insert) and $data->c_insert==1)
    $("#c_insert").prop("checked", true);
    @endif
    
    @if(isset($data->c_update) and $data->c_update==1)
    $("#c_update").prop("checked", true);
    @endif
    
    @if(isset($data->c_delete) and $data->c_delete==1)
    $("#c_delete").prop("checked", true);
    @endif
    
    @if(isset($data->c_other) and $data->c_other==1)
    $("#c_other").prop("checked", true);
    @endif
    
    @if(old("id_role")!=null)
    $("#id_role").val('{{ old("id_role") }}').trigger("change");
    @elseif(isset($data->id_role))
    $("#id_role").val('{{ $data->id_role }}').trigger("change");
    @endif
    
    @if(old("id_module")!=null)
    $("#id_module").val('{{ old("id_module") }}').trigger("change");
    @elseif(isset($data->id_module))
    $("#id_module").val('{{ $data->id_module }}').trigger("change");
    @endif
    
    @if(old("id_menu")!=null)
    $("#id_menu").val('{{ old("id_menu") }}').trigger("change");
    @elseif(isset($data->id_role))
    $("#id_menu").val('{{ $data->id_menu }}').trigger("change");
    @endif
    
    $("#id_module").change(function(){
        $.ajax({
            type: "GET",
            url: "{{ url("getmenu") }}/"+$("#id_module").val(),
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#id_menu").prop("disabled", false);
                $("#id_menu").empty();
                $.each(response,function(key, value)
                {
                    if(value.level==1){
                        $("#id_menu").append('<option value=' + value.id_menu + '>  ' + value.nm_menu.toUpperCase() + '</option>');
                    }else if(value.level==2){
                        $("#id_menu").append('<option value=' + value.id_menu + '>&nbsp;  &nbsp;' + value.nm_menu.toUpperCase() + '</option>');
                    }else{
                        $("#id_menu").append('<option class="tab" value=' + value.id_menu + '> &nbsp; &nbsp;  &nbsp;' +value.nm_menu.toUpperCase() + '</option>');
                    }
                    
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });
    
    $(function(){
        $('#c_all').change(function()
        {
            if($(this).is(':checked')) {
                $("#c_read").prop("checked", true);
                $("#c_insert").prop("checked", true);
                $("#c_update").prop("checked", true);
                $("#c_other").prop("checked", true);
                $("#c_delete").prop("checked", true);
                
            }else{
                $("#c_read").prop("checked", false);
                $("#c_insert").prop("checked", false);
                $("#c_update").prop("checked", false);
                $("#c_other").prop("checked", false);
                $("#c_delete").prop("checked", false);
            }
        });
    });
</script>
@endsection