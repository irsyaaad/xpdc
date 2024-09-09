<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                    </h1>
                    <!--end::Title-->
                    <ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-semibold">
                        @for ($i = 1; $i < 6; $i++)
                            @if (Request::segment($i) !== null)
                                <li class="breadcrumb-item">
                                    <a href="{{ url(Request::segment(1)) }}" class="m-nav__link">
                                        <span class="m-nav__link-text">
                                            @if ($i == 1)
                                                {{ strtoupper(str_replace('_', ' ', get_menu(Request::segment($i)))) }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            {{ strtoupper(str_replace('_', ' ', Request::segment($i))) }}
                            @endif
                            </span>
                            </a>
                            </li>
                        @endif
                        @endfor
                    </ol>
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class=" card">
            <div class=" card-header">
                <div class=" card-title fs-2">
                    Form Tambah Data Harga Vendor
                </div>
                <div class=" card-toolbar">
                    <span class=" fs-6 text-danger">Pastikan data bertanda * Wajib Diisi!</span>
                </div>
            </div>
            <div class=" card-body">
                <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                    action="@if (Request::segment(2) == 'create') {{ url(Request::segment(1)) }} @else{{ route('hargavendor.update', $data->id_harga) }} @endif"
                    enctype="multipart/form-data">
                    @if (Request::segment(3) == 'edit')
                        {{ method_field('PUT') }}
                    @endif
                    @csrf
                    <div class="row">
                        <div class=" px-6">
                            @include('template.notif')
                        </div>
                        <div class=" col-md-4 pb-7 pt-2">
                            <div class="form-group">
                                <b><label class=" fs-7">Type Hpp : <span class=" text-danger">*</span></label></b>
                                <br>
                                <label for="type" class=" pt-2">
                                    <b> <input type="radio" class="form-check-input" value="1" id="type"
                                            name="type" @if (Request::segment(3) == 'edit') disabled @endif>
                                        Direct</b>
                                    <b style="margin-left: 10px"> <input class="form-check-input" type="radio"
                                            value="2" id="types"
                                            @if (Request::segment(3) == 'edit') disabled @endif name="type">
                                        Multivendor</b>
                                </label>
                            </div>
                        </div>

                        <div class=" col-md-4 pb-7">
                            <div class="form-group ">
                                <label for="id_ven">
                                    <b>Wilayah Asal</b> <span class="text-danger"> *</span>
                                </label>
                                <select class="form-control form-select" id="id_asal"
                                    name="id_asal"@if (Request::segment(3) == 'edit') disabled @endif required>
                                    <input type="hidden" name="id_asal" id="nama_asal" value="{{ old('nama_asal') }}">
                                </select>
                                @if ($errors->has('id_asal'))
                                    <label style="color: red">
                                        {{ $errors->first('id_asal') }}
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 pb-7">
                            <div class="form-group">
                                <label for="id_tujuan">
                                    <b>Wilayah Tujuan<span class="text-danger">*</span></b>
                                </label>
                                <select class="form-control form-select" id="id_tujuan"
                                    name="id_tujuan"@if (Request::segment(3) == 'edit') disabled @endif required>
                                    <input type="hidden" name="id_tujuan"
                                        id="nama_tujuan"value="{{ old('nama_tujuan') }}">
                                </select>
                                @if ($errors->has('id_tujuan'))
                                    <label style="color: red">
                                        {{ $errors->first('id_tujuan') }}
                                    </label>
                                @endif
                            </div>

                        </div>

                        <div class=" col-md-4 pb-7">
                            <div class="form-group" id="lbl-vendor">
                                <label for="id_ven">
                                    <b>Vendor<span class=" text-danger">*</span></b>
                                </label>

                                <select class="form-control form-select" id="id_ven"
                                    @if (Request::segment(3) == 'edit') disabled @endif name="id_ven">
                                    <option value=""> -- Pilih Vendor --</option>
                                    @foreach ($vendor as $key => $value)
                                        <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('id_ven'))
                                    <label style="color: red">
                                        {{ $errors->first('id_ven') }}
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div class=" col-md-4 pb-7">
                            <div class="form-group" id="lbl-harga">
                                <label for="harga">
                                    <b>Hpp Per Kg</b>
                                </label>

                                <input type="number" class="form-control" name="harga" id="harga"
                                    value="@if (old('harga') != null) {{ old('harga') }}@elseif(isset($data->harga)){{ trim($data->harga)}}@endif">
                                @if ($errors->has('harga'))
                                    <label style="color: red">
                                        {{ $errors->first('harga') }}
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div class=" col-md-4 pb-7">
                            <div class="form-group" id="lbl-kubik">
                                <label for="harga">
                                    <b>Hpp Per M3</b>
                                </label>

                                <input type="number" class="form-control" name="hrg_kubik" id="hrg_kubik"
                                    value="@if (old('hrg_kubik') != null) {{ old('hrg_kubik') }}@elseif(isset($data->hrg_kubik)){{ str_replace('', '', $data->hrg_kubik)}}@endif">
                                @if ($errors->has('hrg_kubik'))
                                    <label style="color: red">
                                        {{ $errors->first('hrg_kubik') }}
                                    </label>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 pb-7">
                            <div class="form-group" id="lbl-min_kg">
                                <label for="min_kg">
                                    <b>Min Kg</b>
                                </label>

                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="min_kg" id="min_kg"
                                    value="@if (old('min_kg') != null) {{ old('min_kg') }}@elseif(isset($data->min_kg)){{ str_replace('', '', $data->min_kg)}}@endif">
                                @if ($errors->has('min_kg'))
                                    <label style="color: red">
                                        {{ $errors->first('min_kg') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class=" col-md-4 pb-7">
                            <div class="form-group" id="lbl-min_kubik">
                                <label for="min_kubik">
                                    <b>Min M3</b>
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="min_kubik" id="min_kubik"
                                    value="@if (old('min_kubik') != null) {{ old('min_kubik') }}@elseif(isset($data->min_kubik)){{ str_replace('', '', $data->min_kubik)}}@endif">
                                @if ($errors->has('min_kubik'))
                                    <label style="color: red">
                                        {{ $errors->first('min_kubik') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 pb-7">
                            <div class="form-group" id="lbl-time">
                                <label for="harga">
                                    <b>Lead Time etimasi (Hari)</b>
                                </label>
                                <input type="number" class="form-control" name="time" id="time"
                                    value="@if (old('time') != null) {{ old('time') }}@elseif(isset($data->time)){{ str_replace('', '', $data->time)}}@endif">
                                @if ($errors->has('time'))
                                    <label style="color: red">
                                        {{ $errors->first('time') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class=" col-md-4 pb-7">
                            <div class="form-group">
                                <label for="rekomendasi">
                                    <input type="checkbox" name="rekomendasi" id="rekomendasi" value="1"> <b>
                                        Rekomendasikan Harga ini </b>
                                </label>
                                @if ($errors->has('rekomendasi'))
                                    <label style="color: red">
                                        {{ $errors->first('rekomendasi') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class=" col-md-4 pb-7">
                            <div class="form-group">
                                <label for="sebaliknya">
                                    <input type="checkbox" name="sebaliknya" id="sebaliknya" value="1"> <b>
                                        Berlaku Sebaliknya </b>
                                </label>

                                @if ($errors->has('sebaliknya'))
                                    <label style="color: red">
                                        {{ $errors->first('sebaliknya') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 pb-7">
                            <div class="form-group">
                                <label for="keterangan">
                                    <b>Keterangan</b>
                                </label>
                                <textarea type="text" style="white-space: pre-line"  rows="6" cols="10"  class="form-control" name="keterangan" id="keterangan">@if(old('keterangan') != null){{ old('keterangan') }}@elseif(isset($data->keterangan)){{ $data->keterangan}}@endif</textarea>
                                @if ($errors->has('keterangan'))
                                    <label style="color: red">{{ $errors->first('keterangan') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            @include('template.inc_action')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $('#id_ven').select2();

    @if (Request::segment(2) == 'create')
        $('#id_asal').select2({
            placeholder: 'Pilih Wilayah Asal',
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#id_asal').empty();

                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                success: function() {
                    $('#id_asal').addClass('form-select');
                },
                cache: false
            }
        });
        $('#id_asal').on("change", function(e) {
            $("#nama_asal").val($('#id_asal').find(":selected").val());
        });

        $('#id_tujuan').select2({
            placeholder: 'Pilih Wilayah Tujuan',
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#id_tujuan').empty();
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                success: function() {
                    $('#id_tujuan').addClass('form-select');
                },
                cache: false
            }
        });
        $('#id_tujuan').on("change", function(e) {
            $("#nama_tujuan").val($('#id_tujuan').find(":selected").val());
        });
    @endif

    $("input[type='radio'][name='type']").on("change", function(e) {
        if (this.value == 1) {
            $("#lbl-harga").show();
            $("#lbl-vendor").show();
            $("#lbl-kubik").show();
            $("#lbl-min_kubik").show();
            $("#lbl-min_kg").show();
            $("#lbl-time").show();
        } else {
            $("#lbl-harga").hide();
            $("#lbl-vendor").hide();
            $("#lbl-kubik").hide();
            $("#lbl-min_kubik").hide();
            $("#lbl-min_kg").hide();
            $("#lbl-time").hide();
        }
    });

    @if (old('nama_asal') != null)
        $('#id_asal').append('<option value="{{ old('id_asal') }}">{{ strtoupper(old('nama_asal')) }}</option>');
        $("#nama_asal").val($('#id_asal').find(":selected").val());
    @elseif (isset($asal->id_wil))
        $('#id_asal').append('<option value="{{ $asal->id_wil }}">{{ $asal->nama_wil }}</option>');
        $("#nama_asal").val($('#id_asal').find(":selected").val());
    @endif

    @if (old('nama_tujuan') != null)
        $('#id_tujuan').append(
            '<option value="{{ old('id_tujuan') }}">{{ strtoupper(old('nama_tujuan')) }}</option>');
        $("#nama_tujuan").val($('#id_tujuan').find(":selected").val());
    @elseif (isset($tujuan->id_wil))
        $('#id_tujuan').append('<option value="{{ $tujuan->id_wil }}">{{ $tujuan->nama_wil }}</option>');
        $("#nama_tujuan").val($('#id_tujuan').find(":selected").val());
    @endif


    @if (isset($data->id_ven))
        $("#id_ven").select2().val("{{ $data->id_ven }}").trigger("change");
    @endif

    @if (isset($data->type) and $data->type == '1')
        $("input[name='type'][value='1']").attr("checked", true);
    @elseif (isset($data->type) and $data->type == '2')
        $("input[name='type'][value='2']").attr("checked", true);
    @endif

    @if (old('type') != null and old('type') == 1)
        $("input[name='type'][value='1']").attr("checked", true);
    @elseif (old('type') != null and old('type') == 2)
        $("input[name='type'][value='2']").attr("checked", true);
    @endif

    @if (old('id_asal') != null)
        $("#id_asal").select2().val("{{ old('id_asal') }}").trigger("change");
    @endif
    @if (old('id_ven') != null)
        $("#id_ven").select2().val("{{ old('id_ven') }}").trigger("change");
    @endif
    @if (old('id_tujuan') != null)
        $("#id_tujuan").select2().val("{{ old('id_tujuan') }}").trigger("change");
    @endif

    @if (isset($data->type) and $data->type == '1')
        $("#lbl-harga").show();
        $("#lbl-vendor").show();
        $("#lbl-kubik").show();
        $("#lbl-min_kubik").show();
        $("#lbl-min_kg").show();
        $("#lbl-time").show();
    @elseif (isset($data->type) and $data->type == 2)
        $("#lbl-harga").hide();
        $("#lbl-vendor").hide();
        $("#lbl-kubik").hide();
        $("#lbl-min_kubik").hide();
        $("#lbl-min_kg").hide();
        $("#lbl-time").hide();
    @endif

    @if (old('type') == 1)
        $("#lbl-harga").show();
        $("#lbl-vendor").show();
        $("#lbl-kubik").show();
        $("#lbl-min_kubik").show();
        $("#lbl-min_kg").show();
        $("#lbl-time").show();
    @elseif (old('type') == 2)
        $("#lbl-harga").hide();
        $("#lbl-vendor").hide();
        $("#lbl-kubik").hide();
        $("#lbl-min_kubik").hide();
        $("#lbl-min_kg").hide();
        $("#lbl-time").hide();
    @endif

    @if (old('rekomendasi') != null and old('rekomendasi') == 1)
        $('#rekomendasi').attr("checked", true);
    @elseif (old('rekomendasi') != null and old('rekomendasi') == 0)
        $('#rekomendasi').attr("checked", false);
    @elseif (isset($data->rekomendasi) and $data->rekomendasi == 1)
        $('#rekomendasi').attr("checked", true);
    @elseif (isset($data->rekomendasi) and $data->rekomendasi == 0)
        $('#rekomendasi').attr("checked", false);
    @endif

    @if (old('sebaliknya') != null and old('sebaliknya') == 1)
        $('#sebaliknya').attr("checked", true);
    @elseif (old('sebaliknya') != null and old('sebaliknya') == 0)
        $('#sebaliknya').attr("checked", false);
    @elseif (isset($data->same_balik) and $data->same_balik == 1)
        $('#sebaliknya').attr("checked", true);
    @elseif (isset($data->same_balik) and $data->same_balik == 0)
        $('#sebaliknya').attr("checked", false);
    @endif
</script>
