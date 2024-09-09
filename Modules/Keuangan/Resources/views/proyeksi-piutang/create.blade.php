@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('proyeksipiutang') }}@else{{ route('proyeksipiutang.update', $data->id) }}@endif" enctype="multipart/form-data">
    @csrf	
    @if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
    <div class="row">
        
        <div class="form-group col-md-3">
            <label for="level">
                <b>Bulan</b>
            </label>
            
            <select class="form-control m-input m-input--square" name="bulan" id="bulan" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach($bulan as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            
            @if($errors->has('bulan'))
            <label style="color: red">
                {{ $errors->first('bulan') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-3">
            <label for="level">
                <b>Tahun</b>
            </label>
            
            <select class="form-control m-input m-input--square" name="tahun" id="tahun">
                <option value="">-- Pilih Tahun --</option>
                @foreach($tahun as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @if($errors->has('tahun'))
            <label style="color: red">
                {{ $errors->first('tahun') }}
            </label>
            @endif
        </div>
        <div class="col-md-3">
            @include('template.inc_action')
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
    
    @if(old("bulan")!=null)
    $("#bulan").val("{{ old('bulan') }}");
    @elseif(isset($data->bulan))
    $("#bulan").val("{{ $data->bulan }}");
    @endif
    
    @if(old("tahun")!=null)
    $("#tahun").val("{{ old('tahun') }}");
    @elseif(isset($data->tahun))
    $("#tahun").val("{{ $data->tahun }}");
    @endif

</script>
@endsection