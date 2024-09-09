@extends('template.document2')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)."/inject") }}" enctype="multipart/form-data">
    <div class="row">
        @csrf
        <div class="form-group m-form__group col-md-3">
            <label for="id_perush">
                <b>Pilih Perusahaan / Devisi</b> <span class="span-required"> *</span>
            </label>
            
            <select id="id_perush" name="id_perush" class="form-control" required>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group m-form__group col-md-3" >
            <label for="id_karyawan">
                <b>Karyawan</b> <span class="span-required"> *</span>
            </label>

            <select id="id_karyawan" name="id_karyawan" class="form-control" required>
                @foreach($karyawan as $key => $value)
                <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_karyawan'))
            <label style="color: red">
                {{ $errors->first('id_karyawan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-3">
            <label for="tgl_absen">
                <b>Tgl Absen</b> <span class="span-required"> *</span>
            </label>
            
            <input id="tgl_absen" type="date" name="tgl_absen" class="form-control" required />
            
            @if ($errors->has('tgl_absen'))
            <label style="color: red">
                {{ $errors->first('tgl_absen') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-3">
            <label for="jam_datang">
                <b>Jam Datang</b> <span class="span-required"> *</span>
            </label>
            
            <input type="time" name="jam_datang" id="jam_datang" class="form-control" required />
            
            @if ($errors->has('jam_datang'))
            <label style="color: red">
                {{ $errors->first('jam_datang') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-3">
            <label for="jam_istirahat">
                <b>Jam Istirahat</b> <span class="span-required"> *</span>
            </label>
            
            <input type="time" name="jam_istirahat" id="jam_istirahat" class="form-control" required />
            
            @if ($errors->has('jam_istirahat'))
            <label style="color: red">
                {{ $errors->first('jam_istirahat') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-3">
            <label for="jam_istirahat_masuk">
                <b>Jam Istirahat Masuk</b> <span class="span-required"> *</span>
            </label>
            
            <input type="time" name="jam_istirahat_masuk" id="jam_istirahat_masuk" class="form-control" required />
            
            @if ($errors->has('jam_istirahat_masuk'))
            <label style="color: red">
                {{ $errors->first('jam_istirahat_masuk') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-3">
            <label for="jam_pulang">
                <b>Jam Pulang</b> <span class="span-required"> *</span>
            </label>
            
            <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" required />
            
            @if ($errors->has('jam_pulang'))
            <label style="color: red">
                {{ $errors->first('jam_pulang') }}
            </label>
            @endif
        </div>

        <div class=" col-md-12 text-right">
            @include('template.inc_action')
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
$('#id_perush').on("change", function(e) {
        $('#id_karyawan').empty();
        $.ajax({
            type: "GET",
            url: "{{ url('absensi/getkaryawan') }}/"+$("#id_perush").val(),
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $('#id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
                $.each(response, function(index, value) {
                    $('#id_karyawan').append('<option value="'+value.id_karyawan+'">'+value.nm_karyawan+'</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });

</script>
@endsection