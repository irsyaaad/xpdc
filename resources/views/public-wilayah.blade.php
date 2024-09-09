@extends('kepegawaian::layout')
@section('content')
<hr>
<form method="GET" action="" enctype="multipart/form-data" id="form-search">
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight : bold">
                Wilayah
            </label>
            
            <select class="form-control" id="id_wil" name="id_wil">
                <option value="">-- Pilih Wilayah --</option>
                @foreach($data as $key => $value)
                <option value="{{ $value->id_wil }}">
                    @if($value->provinsi != null){{ $value->provinsi }}@endif 
                    @if($value->kabupaten != null){{ ', '.$value->kabupaten }}@endif 
                    @if($value->kecamatan != null){{ ', KEC. '.$value->kecamatan }}@endif 
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3">
            <div class="m-form__label">
                <label style="font-weight: bold;">
                    Id Provinsi
                </label>
            </div>
            <input type="text" class="form-control" id="id_provinsi" name="id_provinsi" readonly disabled />
        </div>
        
        <div class="col-md-3">
            <div class="m-form__label">
                <label style="font-weight: bold;">
                    Id Kabupaten
                </label>
            </div>
            <input type="text" class="form-control" id="id_kabupaten" name="id_kabupaten" readonly disabled />
        </div>
        
        <div class="col-md-3">
            <div class="m-form__label">
                <label style="font-weight: bold;">
                    Id Kecamatan
                </label>
            </div>
            <input type="text" class="form-control" id="id_kecamatan" name="id_kecamatan" readonly disabled />
        </div>
        
    </div>
    
    <div class="row mt-3">
        <div class="col-md-3">
            <label style="font-weight : bold">
                Vendor
            </label>
            
            <select class="form-control" id="id_vendor" name="id_vendor">
                <option value="">-- Pilih Vendor --</option>
                @foreach($vendor as $key => $value)
                <option value="{{ $value->id_ven }}">
                    {{  $value->nm_ven  }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3">
            <div class="m-form__label">
                <label style="font-weight: bold;">
                    Nama Vendor
                </label>
            </div>
            <input type="text" class="form-control" id="nm_ven" name="nm_ven" readonly disabled />
        </div>
        
        <div class="col-md-3">
            <div class="m-form__label">
                <label style="font-weight: bold;">
                    Id Vendor
                </label>
            </div>
            <input type="text" class="form-control" id="id_ven" name="id_ven" readonly disabled />
        </div>
    </div>
</form>
@endsection
@section('script')
<script>
    $("#id_wil").select2();
    $("#id_vendor").select2();

    $("#id_wil").change(function(){
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('public/getwilayah') }}/"+$("#id_wil").val(),
            success: function(data) {
                $("#id_provinsi").val(data.prov_id);
                $("#id_kabupaten").val(data.kab_id);
                $("#id_kecamatan").val(data.kec_id);
            },
        });
    });

    $("#id_vendor").change(function(){
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('public/getvendor') }}/"+$("#id_vendor").val(),
            success: function(data) {
                $("#id_ven").val(data.id_ven);
                $("#nm_ven").val(data.nm_ven);
            },
        });
    });
</script>
@endsection