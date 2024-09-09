<!--begin::Content wrapper-->
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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Rute
                        dan Harga Vendor</h1>
                    <!--end::Title-->
                    <span class=" fw-light fs-5">Anda Sebagai :
                        <strong>{{ Str::upper(Session('role')['nm_role']) }}</strong></span>
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center">
                    <!--begin::Button-->
                    @if (Request::segment(1) != '' and Auth::check())
                        @php
                            $menu = \App\Models\Menu::where('route', Request::segment(1))->get()->first();
                            $cekplus = \App\Models\RoleMenu::where('id_menu', $menu->id_menu)
                                ->where('id_role', Session('role')['id_role'])
                                ->get()
                                ->first();
                        @endphp

                        @if (isset($cekplus->c_insert) and $cekplus->c_insert == 1)
                            <div class="btn btn-primary btn-sm btn-active">
                                <a href="{{ url(Request::segment(1) . '/create') }}" class=" text-white">
                                    TAMBAH {{ strtoupper(get_menu(Request::segment(1))) }}
                                </a>
                                <i class="fa fa-plus"></i>
                            </div>
                        @endif
                    @endif
                    <!--end::Button-->
                </div>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        {{-- begin::filterhargavendor --}}
        @php
            $params = request()->query();
            $a_params = getParamsUrl($params);
            $ulrs = url('hargavendor') . $a_params;
        @endphp
        <form method="GET" action="{{ url('hargavendor') }}" enctype="multipart/form-data" id="form-select">
            @csrf
            <input type="hidden" name="_method" value="GET">
            <div class=" row g-5 g-lg-10">
                <div class=" col-xl-12">
                    <div
                        class="card bgi-no-repeat bgi-size-contain mb-5 mb-lg-10 d-flex flex-column justify-content-between">
                        <div class=" card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                            data-bs-target="#kt_docs_card_collapsible">
                            <div class=" card-title">
                                <h3>Filter harga Vendor</h3>
                            </div>
                            <div class="card-toolbar rotate-180">
                                <i class="ki-duotone ki-down fs-1"></i>
                            </div>
                        </div>
                        <div id="kt_docs_card_collapsible" class="collapse show">
                            <div class="card-body">
                                <form>
                                    <div class="row mb-5">
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                            <div class="form-group">
                                                <label for="name" class="text-dark">Vendor</label>
                                                <select class="form-control form-select" data-control="select2"
                                                    id="id_ven" data-placeholder="Pilih Vendor" name="id_ven">
                                                    <option disabled selected value="">Pilih Vendor</option>
                                                    @foreach ($vendor as $key => $value)
                                                        <option value="{{ $value->id_ven }}">
                                                            {{ strtoupper($value->nm_ven) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                            <div class="form-group">
                                                <label for="name" class="text-dark">Asal</label>
                                                <select class=" form-control" id="id_asal" name="id_asal">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                            <div class="form-group">
                                                <label for="name" class="text-dark">Tujuan</label>
                                                <select class=" form-control" name="id_tujuan" id="id_tujuan">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12 pt-2">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <input type="hidden" name="range" id="range" value="" />
                                        <div class=" col-lg-8 col-sm-12">
                                            <div class="row mb-5">
                                                <div class="col-8 col-md-4">
                                                    <label class="text-dark pb-3" for="">Jenis Rute</label>
                                                    <div class="d-flex">
                                                        <label
                                                            class="form-check form-check-custom form-check-solid form-check-sm">
                                                            <input class="form-check-input" id="type"
                                                                name="type" type="radio"
                                                                @if (isset($filter['type']) && $filter['type'] == '1') {{ 'checked' }} @endif
                                                                value="1" id="flexRadioLg" />
                                                            <span for="name"
                                                                class=" form-check-inline form-check-label text-dark label label-inline">Direct</span>
                                                        </label>
                                                        <label
                                                            class="form-check form-check-custom form-check-solid form-check-sm">
                                                            <input class="form-check-input" id="type"
                                                                name="type" type="radio"
                                                                @if (isset($filter['type']) && $filter['type'] == '2') {{ 'checked' }} @endif
                                                                value="2" id="flexRadioLg" />
                                                            <span for="name"
                                                                class=" form-check-inline form-check-label text-dark label label-inline">mulitvendor</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class=" col-4 col-md-8  text-lg-start text-end mt-md-7 mt-sm-15">
                                                    {{-- <div
                                                        class="form-check form-switch form-check-custom form-check-primary form-check-solid">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="flexCheckDefault" />
                                                        <label class="form-check-label text-dark"
                                                            for="flexCheckDefault">
                                                            Area
                                                        </label>
                                                    </div> --}}
                                                    <label class="text-dark" for="">Rute Area : </label>
                                                    <button type="button" class="btn btn-sm btn-danger" id="btn-area"
                                                        name="btn-are" data-toggle="tooltip" data-placement="top"
                                                        title="Dibawah Area Wilayah"><i class="fa fa-level-down"></i>
                                                        Area</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-lg-4 col-sm-12 pt-3">
                                            <div class="row flex-row-fluid">
                                                <div class="col-6 col-lg-8 text-start text-lg-end text-sm-start">
                                                    <button class="btn btn-primary fw-semibold"
                                                        onclick="goFilter()">Cari</button>
                                                </div>
                                                <div class="col-6 col-lg-2 text-end">
                                                    <a href='{{ url(Request::segment(1)) }}'
                                                        class="btn btn-secondary fw-semibold">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- End::Filterhargavendor --}}

        <!--begin::Card-->
        <div class="card">
            <div class="card-body py-5">
                <div class="row">
                    <div class="col">
                        @include('busdev::metronictigelapan-template.paginator-adminbusdev')
                        <div class="text-end pb-5 mb-2">
                            @if (strtolower(Session('role')['nm_role']) == 'busdev')
                                <div class="dropdown">
                                    <button class="btn btn-outline btn-outline-info dropdown-toggle"
                                        data-bs-toggle="dropdown" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Lainnya
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#modal-upload" href="#"> <i
                                                class="fa fa-upload"></i> Import Data (Versi Lama)</a>
                                        <a class="dropdown-item" href="{{ url('datadirect') }}">
                                            <i class="fa fa-shipping-fast"></i> Data Direct</a>
                                        <a class="dropdown-item" href="{{ url('batchdeleteform') }}"><i
                                                class="fa fa-delete-left"></i> Batch Hapus</a>
                                        <a class="dropdown-item" href="hargavendor/1/templatedirect"><i
                                                class="fa fa-download"></i> Template Direct</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class=" px-5">
                    @include('template.notif')
                </div>
            </div>
                <div class="card-body">
                    <div class=" d-flex flex-row-fluid">
                        <!--begin::Card body-->
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed gs-2 gy-4 gx-5"
                                id="kt_permissions_table">
                                <thead>
                                    <tr class="bg-light fw-bold">
                                        <th class=" ps-4 rounded-start">No</th>
                                        <th>Vendor</th>
                                        <th class=" min-w-150px">Asal</th>
                                        <th class=" min-w-150px">Tujuan</th>
                                        <th class=" min-w-150px">Hpp Kg </th>
                                        <th class=" min-w-100px">HPP M3</th>
                                        <th>Lead Time</th>
                                        <th class=" min-w-100px">Type</th>
                                        <th>Last Updated</th>
                                        <th class=" text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td class="ps-4">{{ $key + 1 }}</td>
                                            <td>
                                                @if ($value->rekomendasi == '1')
                                                    <a href="#" data-toggle="tooltip" data-placement="bottom"
                                                        title="Harga ini direkomendasikan">
                                                        @if ($value->nm_ven != null)
                                                            {{ $value->nm_ven }} <br>
                                                        @else
                                                            <h4><span
                                                                    class="badge badge-pill badge-success">Multivendor</span>
                                                            </h4>
                                                        @endif
                                                        @if ($value->rekomendasi == '1')
                                                            <label><i class="fa fa-star text-warning"></i><i
                                                                    class="fa fa-star text-warning"></i><i
                                                                    class="fa fa-star text-warning"></i></label>
                                                        @endif
                                                        @if ($value->same_balik == '1')
                                                            <span
                                                                class="badge badge-outline badge-info badge-success pt-1">Berlaku
                                                                Sebaliknya</span>
                                                        @endif
                                                    @else
                                                        @if ($value->nm_ven != null)
                                                            {{ $value->nm_ven }}
                                                        @else
                                                            <h4><span
                                                                    class="badge badge-pill badge-success">Multivendor</span>
                                                            </h4>
                                                        @endif
                                                        @if ($value->same_balik == '1')
                                                            <span
                                                                class="badge badge-outline badge-info badge-success pt-1">Berlaku
                                                                Sebaliknya</span>
                                                        @endif
                                                @endif
                                            </td>
                                            <td>{{ strtoupper($value->wil_asal) }}</td>
                                            <td>{{ strtoupper($value->wil_tujuan) }}</td>
                                            <td>{{ toRupiah($value->harga) . ' / Kg ' }}<br> Min :
                                                {{ $value->min_kg }}<br>
                                            </td>
                                            <td>{{ toRupiah($value->hrg_kubik) . ' / M3 ' }}<br> Min :
                                                {{ $value->min_kubik }}<br></td>
                                            <td>{{ $value->time . ' Hari ' }}</td>
                                            <td>
                                                @if ($value->type == 1)
                                                    <span class="badge badge-pill badge-info">Direct</span>
                                                @else
                                                    <span class="badge badge-pill badge-success">Multivendor </span>
                                                    <br><label
                                                        style="font-size: 8pt">({{ $value->tven . ' Vendor' }})</label>
                                                @endif
                                            </td>
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
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Aksi
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/detail') }}"><i
                                                                    class="fa fa-eye"></i> Detail</a>
                                                        </li>
                                                        <li> <a class="dropdown-item"
                                                                href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/edit') }}"><i
                                                                    class="fa fa-pencil"></i> Edit</a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li> <button class="dropdown-item" type="button"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Hapus"
                                                                onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_harga . '/deletedirect') }}')">
                                                                <span><i class="fa fa-times"></i></span>Hapus
                                                            </button></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>



                        <!--end::Table-->
                        <div>
                            @include('busdev::contents.adminbusdev.include.deletelist')
                        </div>
                    </div>
                </div>
            {{-- modal import --}}
            <div class="modal fade" id="modal-upload" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="modal-upload" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-money"> </i> Import
                                Data Harga</h5>
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ url('hargavendor/1/import') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class=" text-left px-2">
                                    <label>Pilih File Excel / CSV</label><br>
                                    <input type="file" id="files" name="files" class="form-control" />
                                </div>
                                <div class=" modal-footer">
                                    <div class="d-flex px-0">
                                        <div class=" separator"></div>
                                        <div class="text-right mt-2">
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fa fa-save"></i> import
                                            </button>
                                            <button class="btn btn-sm btn-danger" type="button"
                                                data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-times"></i> batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->

</div>
<!--end::Content-->
</div>
<!--end::Content wrapper-->




<script>
    @if (isset($filter['range']) and $filter['range'] == 1)
        $("#range").val("1");
        $("#btn-area").removeClass("btn-info");
        $("#btn-area").addClass("btn-danger");
    @else
        $("#range").val("");
        $("#btn-area").removeClass("btn-danger");
        $("#btn-area").addClass("btn-info");
    @endif

    $("#btn-area").on("click", function(e) {
        var range = $("#range").val();
        if (range == "1") {
            $("#range").val("");
            $("#btn-area").removeClass("btn-danger");
            $("#btn-area").addClass("btn-info");
        } else {
            $("#range").val("1");
            $("#btn-area").removeClass("btn-info");
            $("#btn-area").addClass("btn-danger");
        }
    });

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

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

    // $('#id_ven').select2();
    var total = 0;

    @if (isset($filter['page']))
        $("#shareselect").val('{{ $filter['page'] }}');
    @endif

    @if (isset($filter['id_ven']))
        $("#id_ven").select2().val("{{ $filter['id_ven'] }}").trigger("change");
    @endif

    @if (isset($filter['asal']->id_wil))
        $('#id_asal').append(
            '<option value="{{ $filter['asal']->id_wil }}">{{ $filter['asal']->nama_wil }}</option>');
    @endif

    @if (isset($filter['tujuan']->id_wil))
        $('#id_tujuan').append(
            '<option value="{{ $filter['tujuan']->id_wil }}">{{ $filter['tujuan']->nama_wil }}</option>');
    @endif

    @if (isset($filter['updated']))
        $("#updated").val("{{ $filter['updated'] }}");
    @endif

    function goFilter() {
        $("input[name='_method']").val("GET");
        $("#form-select").attr("method", "GET");
        $("#form-select").submit();
    }

    function goDetail(id) {
        $.ajax({
            type: 'GET',
            url: '{{ url('hargavendor') }}/' + id + '/getdetail',
            success: function(response) {
                // console.log(response);
                $("#table-" + id).empty();
                $("#table-" + id).append(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
        });
    }
</script>
