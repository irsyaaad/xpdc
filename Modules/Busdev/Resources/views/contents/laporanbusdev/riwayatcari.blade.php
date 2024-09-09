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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Laporan
                        Riwayat Cari Rute
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
                            $menu = \App\Models\Menu::where('route', Request::segment(1))
                                ->get()
                                ->first();
                            $cekplus = \App\Models\RoleMenu::where('id_menu', $menu->id_menu)
                                ->where('id_role', Session('role')['id_role'])
                                ->get()
                                ->first();
                        @endphp
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
            $ulrs = url('riwayatpencarian') . $a_params;
        @endphp
        <form method="GET" action="{{ url('riwayatpencarian') }}" enctype="multipart/form-data" id="form-select">
            @csrf
            <input type="hidden" name="_method" value="GET">
            <div class=" row g-5 g-lg-10">
                <div class="col-xl-12">
                    <div
                        class="card bgi-no-repeat bgi-size-contain mb-5 mb-lg-10 d-flex flex-column justify-content-between">
                        <div class=" card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse"
                            data-bs-target="#kt_docs_card_collapsible">
                            <div class=" card-title">
                                <h3>Filter Laporan</h3>
                            </div>
                            <div class="card-toolbar rotate-180">
                                <i class="ki-duotone ki-down fs-1"></i>
                            </div>
                        </div>
                        <div id="kt_docs_card_collapsible" class="collapse show">
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-lg-6 col-md-12 col-sm-12 pt-1 pb-2">
                                        <div class="form-group">
                                            <label for="name" class="text-dark">Asal</label>
                                            <select class=" form-control" id="id_asal" name="id_asal">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 pt-1 pb-2">
                                        <div class="form-group">
                                            <label for="name" class="text-dark">Tujuan</label>
                                            <select class=" form-control" name="id_tujuan" id="id_tujuan">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-lg-12 col-sm-12 pt-3">
                                        <div class="row flex-row-fluid">
                                            <div class="col-12 col-lg-12 text-start text-lg-end">
                                                <button class="btn btn-primary fw-semibold  text-lg-end"
                                                    onclick="goFilter()">Cari</button>
                                                <a href='{{ url(Request::segment(1)) }}'
                                                    class="btn btn-secondary text-end fw-semibold">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    </div>
                </div>
                <div class=" px-5">
                    @include('template.notif')
                </div>
            </div>
            <div class=" card-body">
                <div class=" d-flex flex-row-fluid">
                    <!--begin::Card body-->
                    <div class="separator border-4 my-2"></div>
                    <!--begin::Table-->
                    <div class=" table table-responsive">
                        <table class="table align-middle table-row-dashed" id="kt_permissions_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Marketing</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Kab/Kota Asal</th>
                                    <th>Kab/Kota Tujuan</th>
                                    <th>Waktu Cari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $value)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }} .
                                        </td>
                                        <td>
                                            {{ $value->nm_user }}
                                        </td>
                                        <td>
                                            {{ $value->nm_perush }}
                                        </td>
                                        <td>
                                            {{ $value->origin }}
                                        </td>
                                        <td>
                                            {{ $value->destination }}
                                        </td>
                                        <td>
                                            {{ $value->created_at }}
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
            <!--end::Card body-->
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
        placeholder: 'Pilih Kota Asal',
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
        placeholder: 'Pilih Kota Tujuan',
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
</script>
