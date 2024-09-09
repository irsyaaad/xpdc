@extends('template.document2')

@section('data')
<form method="POST" action="{{ url('saveprofile') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        
        <div class="form-group col-md-4">
            <label for="email"> <span class="span-required">*</span>
                <b>Email</b>
            </label>
            
            <input type="email" class="form-control" name="email" id="email" placeholder="Masukan Email" value="@if(old('email')!=null){{ old('email') }}@elseif(isset($data->email)){{ $data->email }}@endif" required maxlength="40">
            
            @if ($errors->has('email'))
            <label style="color: red">
                {{ $errors->first('email') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="username">
                <b>Username</b> <span class="span-required">*</span>
            </label>
            
            <input type="text" class="form-control" name="username" id="username" placeholder="Masukan Username" value="@if(old('username')!=null){{ old('username') }}@elseif(isset($data->username)){{ $data->username }}@endif" required maxlength="40">
            
            @if ($errors->has('username'))
            <label style="color: red">
                {{ $errors->first('username') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="telp">
                <b>No Telp / Wa (Untuk Autentikasi)</b>
            </label>
            
            <input type="text" class="form-control" name="telp" id="telp" placeholder="Masukan telp" value="@if(old('telp')!=null){{ old('telp') }}@elseif(isset($data->telp)){{ $data->telp }}@endif" maxlength="16">
            
            @if ($errors->has('telp'))
            <label style="color: red">
                {{ $errors->first('telp') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-4">
            <label for="password">
                <b>Password</b>
            </label>
            
            <input type="password" class="form-control" name="password" id="password" placeholder="Masukan Password" maxlength="40">
            
            <div class="col-md-12 checkbox" style="margin-top: 1%">
                <label><input type="checkbox" value="1" id="showpass" name="showpass"> Tampilkan Password</label>
            </div>
            
            @if ($errors->has('password'))
            <label style="color: red">
                {{ $errors->first('password') }}
            </label>
            @endif
        </div>
        <div class="form-group col-md-4" style="margin-top :30px">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-save"> </i> Update
            </button>
            
            <a href="{{ url("dashboard") }}" class="btn btn-sm btn-danger">
                <i class="fa fa-times"> </i> Batal
            </a>
        </div>
    </div>
</form>
@endsection

@section("script")
<script>
    $('#showpass').change(function()
	{
		if($(this).is(':checked')) {
			$("#password").attr("type", "text");
		}else{
			$("#password").attr("type", "password");
		}
	});
</script>
@endsection
