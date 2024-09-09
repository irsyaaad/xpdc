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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Rincian
                        Data Harga Vendor
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
        <div class="card">
            @php
                $params = request()->query();
                $a_params = getParamsUrl($params);
            @endphp
            <form method="POST" action="#" enctype="multipart/form-data" id="form-select">
                @csrf
                <div class=" card-header">
                    <div class=" card-title">
                        Type harga : <span
                            @if ($data->rekomendasi == '1') data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan" @endif>
                            @if ($data->type == 1)
                                <span class="badge badge-pill badge-info">Direct</span>
                            @else
                                <span class="badge badge-pill badge-success">Multivendor</span>
                            @endif
                            @if ($data->rekomendasi == '1')
                                <label style="cursor: pointer">
                                    <i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i
                                        class="fa fa-star text-warning"></i>
                                </label>
                            @endif
                        </span>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ url('hargavendor' . $a_params) }}" class="btn btn-sm btn-warning"><i
                                class="fa fa-reply"></i> Kembali</a>
                    </div>
                </div>
                <div class=" card-body">
                    <input type="hidden" name="_method" value="GET">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Wilayah Asal :<br> <b>{{ $data->wil_asal }}</b>
                            </label>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Wilayah Tujuan : <br><b>{{ $data->wil_tujuan }}</b>
                            </label>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Vendor :
                                <b>
                                    @if (isset($data->nm_ven) and $data->type == '2')
                                        <label><span class="badge badge-pill badge-success">Multivendor</span></label>
                                    @else
                                        {{ $data->nm_ven }}
                                    @endif
                                </b>
                            </label>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Total Hpp Kg :
                                <b>
                                    @if (isset($data->harga))
                                        {{ toRupiah($data->harga) . ' / Kg' }}
                                    @endif
                                    <br>
                                    @if (isset($data->min_kg))
                                        Min : {{ $data->min_kg }}
                                    @endif
                                </b>
                            </label>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Total Hpp M3 :
                                <b>
                                    @if (isset($data->hrg_kubik))
                                        {{ toRupiah($data->hrg_kubik) . ' / M3' }}
                                    @endif
                                    <br>
                                    @if (isset($data->min_kubik))
                                        Min : {{ $data->min_kubik }}
                                    @endif
                                </b>
                            </label>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12 pb-7">
                            <label for="id_ven">
                                Estimasi Lead Time :
                                <b>
                                    @if (isset($data->time))
                                        {{ $data->time }} Hari
                                    @endif
                                </b>
                            </label>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 pb-7">
                            <label for="keterangan">
                                Keterangan :
                                <b style="white-space: pre-line">
                                    @if (isset($data->keterangan))
                                        {{ $data->keterangan }}
                                    @endif
                                </b>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" px-6">
                            @include('template.notif')
                        </div>
                        @if (isset($data->type) and $data->type == 2)
                            <div class=" separator my-10"></div>
                            <div class=" col-lg-6 col-md-6 col-sm-12">
                                <h4>
                                    <i class="fa fa-thumb-tack"></i> Detail Harga
                                </h4>
                            </div>

                            <div class="col-md-6 text-end">
                                <button type="button" onclick="goCopy()" data-bs-target="#modal-copy"
                                    data-bs-toggle="modal" class="btn btn-sm btn-success">
                                    <i class="fa fa-copy"></i> Copy Detail
                                </button>
                                <button type="button" onclick="goPlus()" data-bs-toggle="modal"
                                    data-bs-target="#modal-harga" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Detail
                                </button>
                            </div>
                            <div class="col-md-12 table-responsive mt-2">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>Vendor</th>
                                            <th>Asal</th>
                                            <th>Tujuan</th>
                                            <th width="150">HPP Kg </th>
                                            <th width="150">Hpp M3</th>
                                            <th>Est. Lead Time</th>
                                            <th>Last Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @php
                                    $urutanorder = 1;
                                        // Mengurutkan array secara ascending berdasarkan nilai 'created_at'
                                        asort($detail);
                                    @endphp

                                    <tbody>
                                        @foreach ($detail as $key => $value)
                                            <tr>
                                                <td>
                                                   {{ $urutanorder++}}
                                                </td>
                                                <td>
                                                    @if ($value->rekomendasi == '1')
                                                        <label style="cursor: pointer" data-toggle="tooltip"
                                                            data-placement="bottom" title="Harga ini direkomendasikan">
                                                            {{ $value->nm_ven }} <br>
                                                            <i class="fa fa-star text-warning"></i><i
                                                                class="fa fa-star text-warning"></i><i
                                                                class="fa fa-star text-warning"></i>
                                                        </label>
                                                    @else
                                                        {{ $value->nm_ven }}
                                                    @endif
                                                    @if ($value->same_balik == '1')
                                                        <span
                                                            class="badge badge-outline badge-info badge-success pt-1">Berlaku
                                                            Sebaliknya</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ strtoupper($value->wil_asal) }}
                                                </td>
                                                <td>
                                                    {{ strtoupper($value->wil_tujuan) }}
                                                </td>
                                                <td>
                                                    {{ toRupiah($value->harga) . ' / Kg' }} <br>
                                                    {{ 'Min : ' . $value->min_kg }}
                                                </td>
                                                <td>
                                                    {{ toRupiah($value->hrg_kubik) . ' / M3' }}<br>{{ 'Min : ' . $value->min_kubik }}
                                                </td>
                                                <td>{{ $value->time . ' Hari ' }}</td>
                                                <td>
                                                    @if ($value->update_user != null)
                                                        <label
                                                            style="font-size: 8pt">{{ $value->updated_at }}<br>{{ $value->update_user }}</label>
                                                    @else
                                                        <label
                                                            style="font-size: 8pt">{{ $value->created_at }}<br>{{ $value->insert_user }}</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $datas = json_encode($value, true);
                                                    @endphp
                                                    <a class="btn btn-sm btn-warning"
                                                        onclick="goEdit('{{ $datas }}')"><i
                                                            class="fa fa-edit"></i>
                                                        Edit</a>
                                                    @php
                                                        $urls = url('hargavendor/' . $value->id_harga);
                                                    @endphp
                                                    <a class="btn btn-sm btn-danger mt-2"
                                                        onclick="CheckDelete('{{ $urls }}')"><i
                                                            class="fa fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
            </form>
            <div>
                @include('busdev::contents.adminbusdev.include.deletelist')
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="modal-harga">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Form Input Detail HPP
                        </h5>
                    </div>
                    <form action="{{ url('hargavendor/' . $data->id_harga . '/savedetail') }}" method="POST"
                        id="form-harga" enctype="multipart/form-data">
                        @csrf
                        <input name="_method" id="_methods" type="hidden" value="POST" />
                        <div class="modal-body">
                            <div class=" row">
                                <input id="type" name="type" value="1" type="hidden" />
                                <div class=" col-lg-6 col-md-6 col-sm-12 pb-5">
                                    <div class="form-group">
                                        <label for="id_ven">
                                            <b>Wilayah Asal</b> <span class="span-required text-danger"> *</span>
                                        </label>

                                        <select class="form-control form-select" id="id_asal" name="id_asal"
                                            required></select>
                                        @if ($errors->has('id_asal'))
                                            <label style="color: red">
                                                {{ $errors->first('id_asal') }}
                                            </label>
                                        @endif
                                    </div>
                                </div>
                                <div class=" col-lg-6 col-md-6 col-sm-12 pb-5">
                                    <div class="form-group">
                                        <label for="id_tujuan">
                                            <b>Wilayah Tujuan</b> <span class="span-required text-danger"> *</span>
                                        </label>

                                        <select class="form-control form-select" id="id_tujuan" name="id_tujuan"
                                            required></select>

                                        @if ($errors->has('id_tujuan'))
                                            <label style="color: red">
                                                {{ $errors->first('id_tujuan') }}
                                            </label>
                                        @endif
                                    </div>
                                </div>
                                <div class=" col-lg-12 pb-5">
                                    <div class="form-group" id="lbl-vendor">
                                        <label for="id_ven">
                                            <b>Vendor<span class="text-danger">*</span></b>
                                        </label>

                                        <select class="form-control form-select" id="id_ven" name="id_ven"
                                            required>
                                            <option value=""> -- Pilih Vendor --</option>
                                            @foreach ($vendor as $key => $value)
                                                <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('id_ven'))
                                            <label style="color: red">
                                                {{ $errors->first('id_ven') }}
                                            </label>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group col-md-6 pb-2" id="lbl-harga">
                                    <label for="harga">
                                        <b>Hpp Per Kg<span class="text-danger">*</span></b>
                                    </label>

                                    <input type="number" required step="any" class="form-control"
                                        name="harga" id="harga" value="{{ old('harga') }}">

                                    @if ($errors->has('harga'))
                                        <label style="color: red">
                                            {{ $errors->first('harga') }}
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 pb-2" id="lbl-kubik">
                                    <label for="hrg_kubik">
                                        <b>Hpp Per M3<span class="text-danger">*</span></b>
                                    </label>

                                    <input type="number" required step="any" class="form-control"
                                        name="hrg_kubik" id="hrg_kubik" value="{{ old('hrg_kubik') }}">

                                    @if ($errors->has('hrg_kubik'))
                                        <label style="color: red">
                                            {{ $errors->first('hrg_kubik') }}
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 pb-2" id="lbl-min_kg">
                                    <label for="min_kg">
                                        <b>Min Kg <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="number" required step="any" class="form-control"
                                        name="min_kg" id="min_kg" value="{{ old('min_kg') }}">

                                    @if ($errors->has('min_kg'))
                                        <label style="color: red">
                                            {{ $errors->first('min_kg') }}
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 pb-2" id="lbl-min_kubik">
                                    <label for="min_kubik">
                                        <b>Min M3 <span class="text-danger">*</span></b>
                                    </label>

                                    <input type="number" required step="any" class="form-control"
                                        name="min_kubik" id="min_kubik" value="{{ old('min_kubik') }}">

                                    @if ($errors->has('min_kubik'))
                                        <label style="color: red">
                                            {{ $errors->first('min_kubik') }}
                                        </label>
                                    @endif
                                </div>


                                <div class="form-group col-lg-12 pb-2 pt-2" id="lbl-kubik">
                                    <label for="time">
                                        <b>Lead Time (Hari)<span class="text-danger">*</span></b>
                                    </label>

                                    <input type="number" required step="any" class="form-control"
                                        name="time" id="time" value="{{ old('time') }}">

                                    @if ($errors->has('time'))
                                        <label style="color: red">
                                            {{ $errors->first('time') }}
                                        </label>
                                    @endif
                                </div>

                                <div class="form-group pt-3 pb-3 col-lg-12">
                                    <label for="rekomendasi">
                                        <input type="checkbox" name="rekomendasi" id="rekomendasi" value="1">
                                        <b> Rekomendasikan Harga ini </b>
                                    </label>

                                    @if ($errors->has('rekomendasi'))
                                        <label style="color: red">
                                            {{ $errors->first('rekomendasi') }}
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group pt-3 pb-3 col-lg-12">
                                    <div class="form-group">
                                        <label for="sebaliknya">
                                            <input type="checkbox" name="sebaliknya" id="sebaliknya" value="1">
                                            <b>Berlaku Sebaliknya</b>
                                        </label>
                                        @if ($errors->has('sebaliknya'))
                                            <label style="color: red">
                                                {{ $errors->first('sebaliknya') }}
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <div class=" col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="keterangan">
                                            <b>Keterangan</b>
                                        </label>

                                        <textarea type="text" class="form-control" name="keterangan" id="keterangan">{{ old('keterangan') }}</textarea>

                                        @if ($errors->has('keterangan'))
                                            <label style="color: red">
                                                {{ $errors->first('keterangan') }}
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class=" modal-footer px-0">
                                <div class="form-group m-2 text-end">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                                            class="fa fa-times"></i> Batal</button>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="modal-copy">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                        </div>
                        <button type="button" class="close btn btn-icon" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('hargavendor/' . $data->id_harga . '/saveimport') }}" method="POST"
                        id="form-copy" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body row">
                            <div class="col-md-6">
                                <label for="casal">
                                    <b>Wilayah Asal</b> <span class="span-required"> *</span>
                                </label>

                                <select class="form-control" id="casal" name="casal"></select>

                                @if ($errors->has('ctujuan'))
                                    <label style="color: red">
                                        {{ $errors->first('ctujuan') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="ctujuan">
                                    <b>Wilayah Tujuan</b> <span class="span-required"> *</span>
                                </label>

                                <select class="form-control" id="ctujuan" name="ctujuan"></select>

                                @if ($errors->has('ctujuan'))
                                    <label style="color: red">
                                        {{ $errors->first('ctujuan') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 text-end pt-10 pb-5">
                                <button class="btn btn-sm btn-primary" type="button" onclick="goCari()">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <button class="btn btn-sm btn-warning" type="button" onclick="goReset()">
                                    <i class="fa fa-times"></i> Reset
                                </button>
                                <button class="btn btn-sm btn-success" type="submit">
                                    <i class="fa fa-download"></i> Import
                                </button>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive mt-1">
                                    <table class="table table-borderdd">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>Vendor</th>
                                                <th>Asal</th>
                                                <th>Tujuan</th>
                                                <th width="150">HPP Kg </th>
                                                <th width="150">Hpp M3</th>
                                                <th>Est. Lead Time</th>
                                                <th>Check</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-copy">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $('#id_ven').select2({
        dropdownParent: $("#modal-harga")
    });

    $('#id_asal').select2({
        dropdownParent: $("#modal-harga"),
        placeholder: 'Cari Wilayah Asal ....',
        minimumInputLength: 0,
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
            cache: true
        }
    });

    $('#id_tujuan').select2({
        dropdownParent: $("#modal-harga"),
        placeholder: 'Cari Wilayah Tujuan ....',
        minimumInputLength: 0,
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
            cache: true
        }
    });

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    $('#casal').select2({
        dropdownParent: $("#modal-copy"),
        placeholder: 'Cari Wilayah Asal ....',
        minimumInputLength: 3,
        allowClear:true,
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
            cache: true
        }
    });

    $('#ctujuan').select2({
        dropdownParent: $("#modal-copy"),
        placeholder: 'Cari Wilayah Tujuan ....',
        minimumInputLength: 3,
        allowClear:true,
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
            cache: true
        }
    });

    function goCopy() {
        $("#modal-copy").modal("show");
    }

    function goPlus() {
        $("#id_asal").empty();
        $("#id_tujuan").empty();
        $("#type").val('');
        $("#id_ven").val('').trigger("change");
        $("#harga").val('');
        $("#keterangan").text('');
        $("#hrg_kubik").val('');
        $("#min_kg").val('');
        $("#min_kubik").val('');
        $("#time").val('');
        $("#form-harga").attr("action", "{{ url('hargavendor') }}/" + {{ $data->id_harga }} + '/savedetail');
        $("#_methods").val("POST");
        $("#modal-harga").modal("show");
    }

    function goEdit(datas) {
        var data = JSON.parse(datas);

        var id_harga = data["id_harga"];
        var wil_asal = data["wil_asal"];
        var wil_tujuan = data["wil_tujuan"];
        var id_asal = data["id_asal"];
        var id_tujuan = data["id_tujuan"];
        var id_ven = data["id_ven"];
        var min_kubik = data["min_kubik"];
        var min_kg = data["min_kg"];
        var hrg = data["harga"];
        var kubik = data["hrg_kubik"];
        var time = data["time"];
        var keterangan = data["keterangan"];
        var type = data["type"];
        var rekomendasi = data["rekomendasi"];
        var same_balik = data["same_balik"];

        $("#id_asal").append('<option value="' + id_asal + '">' + wil_asal + '</option>');
        $("#id_tujuan").append('<option value="' + id_tujuan + '">' + wil_tujuan + '</option>');
        $("#type").val(type);
        $("#id_ven").val(id_ven).trigger("change");
        $("#harga").val(hrg);
        $("#keterangan").text(keterangan);
        $("#hrg_kubik").val(kubik);
        $("#min_kg").val(min_kg);
        $("#min_kubik").val(min_kubik);
        $("#time").val(time);
        $("#form-harga").attr("action", "{{ url('hargavendor') }}/" + id_harga + '/updatedetail');
        $("#_methods").val("PUT");
        if (same_balik == true) {
            $("#sebaliknya").attr("checked", true);
        } else {
            $("#sebaliknya").attr("checked", false);
        }
        if (rekomendasi == "1") {
            $("#rekomendasi").attr("checked", true);
        } else {
            $("#rekomendasi").attr("checked", false);
        }
        $("#modal-harga").modal("show");
    }

    // function goCari() {
    //     var asal = $("#casal").val();
    //     var tujuan = $("#ctujuan").val();
    //     $.ajax({
    //         type: "GET",
    //         dataType: "json",
    //         url: "{{ url('hargavendor') }}/{{ Request::segment(2) }}/getharga?asal=" + asal + "&tujuan=" +
    //             tujuan,
    //         success: function(data) {
    //             $("#table-copy").empty();
    //             $("#table-copy").append(data);
    //         },
    //     });
    // }

    // function goReset() {
    //     $("#table-copy").empty();
    //     $("#casal").empty();
    //     $("#ctujuan").empty();
    // }

    function goCari() {
        var asal = $("#casal").val();
        var tujuan = $("#ctujuan").val();

        // Validasi apakah asal dan tujuan sudah dipilih
        if (asal && tujuan) {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ url('hargavendor') }}/{{ Request::segment(2) }}/getharga?asal=" + asal + "&tujuan=" +
                    tujuan,
                success: function(data) {
                    // Append data baru tanpa menimpa data sebelumnya
                    $("#table-copy").append(data);
                },
            });
        } else {
            alert("Pilih asal dan tujuan terlebih dahulu.");
        }
    }

    function goReset() {
    // Reset formulir asal dan tujuan
    $("#casal").val(null).trigger('change'); // Gunakan 'trigger' untuk memperbarui tampilan Select2
    $("#ctujuan").val(null).trigger('change');

    // Jika ingin mengosongkan tabel hasil juga, uncomment baris berikut:
    $("#table-copy").empty();
}
</script>
