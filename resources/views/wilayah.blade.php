@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row mt-1">
        <div class="col-md-12"  style="overflow-x:auto;">
            <table class="table table-responsive table-hover" style="width=100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Wilayah</th>
                        <th>Nama Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ strtoupper($value->id_wil) }}
                        </td>
                        <td>
                            {{ strtoupper($value->nama_wil) }}
                        </td>
                    </tr>
                    
                    @endforeach
                </tbody>
            </table>
        </div>
        @include("template.paginator")
    </div>
</form>
@elseif(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{'save_wilayah'}}" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group m-form__group">
        <label for="tingkat">
            <b>Tingkat</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="tingkat" id="tingkat">
            <option value="1">Provinsi</option>
            <option value="2">Kabupaten / Kota</option>
            <option value="3">Kecamatan</option>
        </select>
        
        @if ($errors->has('tingkat'))
        <label class="class-error">
            {{ $errors->first('tingkat') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group" id="inputProvinsi">
        <label for="Provinsi">
            <b>Provinsi</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="provinsi" id="provinsi">
        </select>
        
        @if ($errors->has('provinsi'))
        <label class="class-error">
            {{ $errors->first('provinsi') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group" id="inputKabupaten">
        <label for="Provinsi">
            <b>Kabupaten</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="kabupaten" id="kabupaten">
        </select>
        
        @if ($errors->has('provinsi'))
        <label class="class-error">
            {{ $errors->first('provinsi') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <label for="Provinsi">
            <b>Nama Wilayah</b>
        </label>
        
        <input type="text" class="form-control m-input m-input--square" name="wilayah" id="wilayah" placeholder="Masukan Nama Wilayah" value="{{ old('kecamatan') }}">
        @if ($errors->has('kecamatan'))
        <label class="class-error">
            {{ $errors->first('kecamatan') }}
        </label>
        @endif
    </div>
    
    <div class="form-group m-form__group">
        <div class="m-form__actions">
            <button type="submit" class="btn btn-success">
                Submit
            </button>
            
            <a href="{{ url()->previous() }}" class="btn btn-danger">
                Cancel
            </a>
        </div>
    </div>
    
</form>
@endif

@endsection
@section('script')

<script type="text/javascript">
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    @if(isset($filter['page']))
    $("#shareselect").val('{{ $filter['page'] }}');
    @endif
    
    $('#f_id_wil').select2({
        placeholder: 'Cari Wilayah ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#f_id_wil').empty();
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
    
    @if(isset($filter["wilayah"]->nama_wil))
    $("#f_id_wil").empty();
    $("#f_id_wil").append('<option value="{{ $filter["wilayah"]->id_wil }}">{{ strtoupper($filter["wilayah"]->nama_wil) }}</option>');
    @endif
    
    @if (Request::segment(2) == "create")
    $("#inputProvinsi").prop("hidden", true);
    $("#inputKabupaten").prop("hidden", true);
    
    $("#tingkat").change(function(){
        var level = this.value;
        if(level == 1){
            $("#inputProvinsi").prop("hidden", true);
            $("#inputKabupaten").prop("hidden", true);
        }
        if (level == 2) {
            $("#inputProvinsi").prop("hidden", false);
            $("#inputKabupaten").prop("hidden", true);
        }
        if (level == 3) {
            $("#inputProvinsi").prop("hidden", false);
            $("#inputKabupaten").prop("hidden", false);
        }
    });
    $('#provinsi').select2({
        placeholder: 'Cari Provinsi ....',
        ajax: {
            url: '{{ url('getProvinsi') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterperush').empty();
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
    $('#kabupaten').select2({
        placeholder: 'Cari Kabupaten ....',
        ajax: {
            url: '{{ url('getKota') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterperush').empty();
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
    @endif
</script>
@endsection
