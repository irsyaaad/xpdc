<style>
    * {
        filter: none;
    }

    @media (min-width:1025px) {
        .select2-selection--single {
            height: 100% !important;
        }

        .select2-selection__rendered {
            word-wrap: break-word !important;
            overflow: hidden;
            text-overflow: inherit !important;
            white-space: inherit !important;
        }

        .utamahrg {
            font-size: 15px;
        }

        .secondhrg {
            font-size: 12px;
        }
    }

    @media (max-width:500px) {
        .utamahrg {
            font-size: 12px;
        }

        .secondhrg {
            font-size: 10px;
        }
    }
</style>
<!--begin::Toolbar-->
<div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column me-3">
        <!--begin::Title-->
        <h1 class="d-flex text-dark fw-bolder my-1 fs-1">Rute dan Harga Vendor</h1>
        <h1 class="d-flex text-dark fw-light my-1 fs-4 pt-2">Anda Sebagai : {{ Str::upper(Session('role')['nm_role']) }}
        </h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
</div>
<!--end::Toolbar-->
<!--begin::Post-->
<form method="GET" action="{{ url('hargavendor') }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--begin::Col-->
        <div class="col-xl-12">
            <!--begin::Tiles Widget 2-->
            <div class="card bgi-no-repeat bgi-size-contain mb-5 mb-lg-10" style="background-color: #303078; ">
                <!--begin::Body-->
                <div class="card-body d-flex flex-column justify-content-between">
                    <form>
                        <div class="row mb-5">
                            <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                <div class="form-group">
                                    <label for="name" class="text-white">Vendor</label>
                                    <select class="form-control form-select" data-control="select2" id="id_ven"
                                        data-placeholder="Pilih Vendor" name="id_ven">
                                        <option disabled selected value="">Pilih Vendor</option>
                                        @foreach ($vendor as $key => $value)
                                            <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                <div class="form-group">
                                    <label for="name" class="text-white">Asal</label>
                                    <select class=" form-control" id="id_asal" name="id_asal">
                                        {{-- <option disabled selected value="">Pilih Asal</option> --}}
                                        {{-- @foreach ($wilayahrute as $wil)
                                            <option value="{{ $wil->value }}">
                                                {{ strtoupper($wil->label) }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 pt-1 pb-2">
                                <div class="form-group">
                                    <label for="name" class="text-white">Tujuan</label>
                                    <select class=" form-control" id="id_tujuan" name="id_tujuan">
                                        {{-- <option disabled selected value="">Pilih Tujuan</option>
                                        @foreach ($wilayahrute as $wil)
                                            <option value="{{ $wil->value }}">
                                                {{ strtoupper($wil->label) }}</option>
                                        @endforeach --}}
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
                                        <label class="text-white pb-3" for="">Jenis Rute</label>
                                        <div class="d-flex">
                                            <label class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input class="form-check-input" id="type" name="type"
                                                    type="radio"
                                                    @if (isset($filter['type']) && $filter['type'] == '1') {{ 'checked' }} @endif
                                                    value="1" id="flexRadioLg" />
                                                <span for="name"
                                                    class=" form-check-inline form-check-label text-white label label-inline">Direct</span>
                                            </label>
                                            <label class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input class="form-check-input" id="type" name="type"
                                                    type="radio"
                                                    @if (isset($filter['type']) && $filter['type'] == '2') {{ 'checked' }} @endif
                                                    value="2" id="flexRadioLg" />
                                                <span for="name"
                                                    class=" form-check-inline form-check-label text-white label label-inline">mulitvendor</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class=" col-4 col-md-8 mt-md-7 text-lg-start text-end mt-sm-15">
                                        {{-- <div
                                            class="form-check form-switch form-check-custom form-check-primary form-check-solid">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault" />
                                            <label class="form-check-label text-dark"
                                                for="flexCheckDefault">
                                                Area
                                            </label>
                                        </div> --}}
                                        <label class="text-white" for="">Rute Area : </label>
                                        <button type="button" class="btn btn-sm btn-danger" id="btn-area"
                                            name="btn-are" data-toggle="tooltip" data-placement="top"
                                            title="Dibawah Area Wilayah"><i class="fa fa-level-down"></i> Area</button>
                                    </div>

                                </div>
                            </div>
                            <div class=" col-lg-4 col-sm-12 pt-3">
                                <div class="row flex-row-fluid">
                                    <div class="col-6 col-lg-8 text-start text-lg-end text-sm-start">
                                        <button class="btn btn-danger fw-semibold" onclick="goFilter()">Cari</button>
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
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-body row">
                @include('busdev::metroniclapan-template.paginator-busdevs')
            </div>
        </div>
    </div>
    <br>
    @foreach ($data as $key => $value)
        <!--begin::Social - Feeds -->
        <div class="row">
            <div class="d-flex flex-row">
                <!--begin::Content-->
                <!--begin::Posts-->
                <div class="col-lg-12 col-md-12 col-sm-12" id="kt_social_feeds_posts">
                    <!--begin::Post 2-->
                    <!--begin::Card-->
                    <div class="card card-flush mb-5 pb-5">
                        @if ($value->type == 1)
                            <!--begin::Card header-->
                            <div class="card-header pt-9">
                                <!--begin::Info-->
                                <div class="flex-grow-1 align-middle">
                                    <div class="row mb-2">
                                        <div class="col-7">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px me-2">
                                                <img src="assets-busdevmarketing/ship.png" class=""
                                                    alt="Icon" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a class="text-gray-800 text-hover-primary fs-6 fw-bold align-middle">
                                                {{ $value->nm_ven }}
                                            </a> <span class="badge badge-pill badge-info badge-outline"><i
                                                    class="fa fa-plane text-info px-2"></i> Direct</span>

                                            <!--begin::Date-->

                                            <!--end::Name-->
                                            @if ($value->rekomendasi == '1')
                                                {{-- <i class="fa fa-star text-warning"></i> --}}
                                                <span class="text-dark fw-semibold d-block pt-3">
                                                    <span class="badge badge-warning ">Di Rekomendasikan</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-5 text-end">
                                            <span class=" fs-8 mt-5 text-muted">
                                                Last Updated :
                                                @if ($value->update_user != null)
                                                    {{ $value->updated_at }}<br>{{ $value->update_user }}
                                                    @else{{ $value->created_at }}<br>{{ $value->insert_user }}
                                                @endif
                                            </span><br>
                                            @if ($value->same_balik == '1')
                                                {{-- <i class="fa fa-star text-warning"></i> --}}
                                                <span class="badge badge-outline badge-info badge-success mt-2">Berlaku
                                                    Sebaliknya</span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class=" col-lg-8 col-md-8 col-sm-8 flex-column-reverse">
                                            <div class="row text-start align-middle pt-4">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <!--begin::Timeline-->
                                                    <div class="timeline-label">
                                                        {{-- awal --}}
                                                        <!--begin::Item-->
                                                        <div class="timeline-item">
                                                            <!--begin::Label-->
                                                            <div class="timeline-label text-muted fs-7">Rute
                                                                Awal</div>
                                                            <!--end::Label-->

                                                            <!--begin::Badge-->
                                                            <div class="timeline-badge">
                                                                <i class="fa fa-genderless text-primary fs-1"></i>
                                                            </div>
                                                            <!--end::Badge-->

                                                            <!--begin::Text-->
                                                            <div class="fw-mormal timeline-content fs-7 ps-3">
                                                                {{ $value->wil_asal }}
                                                            </div>
                                                            <!--end::Text-->
                                                        </div>
                                                        <!--end::Item-->

                                                        {{-- akhir --}}
                                                        <!--begin::Item-->
                                                        <div class="timeline-item">
                                                            <!--begin::Label-->
                                                            <div class="timeline-label fw-bold text-gray-800 fs-7">
                                                                <b>{{ $value->time . ' Hari ' }}</b>
                                                            </div>
                                                            <!--end::Label-->

                                                            <!--begin::Badge-->
                                                            <div class="timeline-badge">
                                                                <i class="fa fa-circle text-primary fs-6"></i>
                                                            </div>
                                                            <!--end::Badge-->

                                                            <!--begin::Content-->
                                                            <div class="timeline-content d-flex">
                                                                <span
                                                                    class="fw-bold fs-7 ps-3">{{ $value->wil_tujuan }}</span>
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Item-->
                                                    </div>
                                                    <!--end::Timeline-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-lg-4 col-md-4 col-sm-4">
                                            <div class="separator text-muted d-block d-sm-none"></div>
                                            <div class="d-block text-muted d-sm-none fs-7 pt-4">Harga Kiriman</div>
                                            <div class="row ">
                                                <div class="col-6 col-md-12 col-xl-12">
                                                    <div class="text-dark fs-5 fw-semibold text-sm-end pt-1">
                                                        {{ toRupiah($value->harga) . '/Kg' }} <br>
                                                        <span
                                                            class="fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kg . ' KG' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-12 col-xl-12">
                                                    <div class="">
                                                        <div
                                                            class="text-dark fs-5 fw-semibold pt-1 utamahrg  text-sm-end">
                                                            {{ toRupiah($value->hrg_kubik) . '/M3' }} <br><span
                                                                class="fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kubik . ' M3' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Card header-->
                            @if ($value->keterangan != null && $value->keterangan != '')
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <div class=" separator my-2"></div>
                                    <div class="row">
                                        @if ($value->keterangan != null && $value->keterangan != '')
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <!--begin::Post content-->
                                                <div class="fs-7 text-muted">Keterangan :
                                                    <span style="white-space: pre-line" class=" fs-7 text-dark">{{ $value->keterangan }}</span>
                                                </div>
                                                <!--end::Post content-->
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!--end::Card body-->
                            @endif
                        @endif
                        @if ($value->type == 2)
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-9">
                                <!--begin::Info-->
                                <div class="flex-grow-1 align-middle">
                                    <div class="row">
                                        <div class="col">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px me-1 pb-0">
                                                <img src="assets-busdevmarketing/ship.png" class=""
                                                    alt="Icon" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                                MULTIVENDOR ( {{ $value->tven }} )
                                            </a><span class="badge badge-pill badge-success badge-outline"><i
                                                    class="fa fa-truck px-2 text-success"></i>Transit</span>
                                            <!--end::Name-->
                                            @if ($value->rekomendasi == '1')
                                                <span class="text-dark fw-semibold d-block pt-2">
                                                    <span class="badge badge-warning">Di Rekomendasikan</span>
                                                </span>
                                            @endif
                                            <!--begin::Date-->
                                        </div>

                                        <div class="col text-end">
                                            <span class=" fs-7 mt-5 text-muted">
                                                Last Updated :
                                                @if ($value->update_user != null)
                                                    {{ $value->updated_at }}<br>{{ $value->update_user }}
                                                    @else{{ $value->created_at }}<br>{{ $value->insert_user }}
                                                @endif
                                            </span><br>
                                            @if ($value->same_balik == '1')
                                                <span class="badge badge-outline badge-info badge-success pt-1">Berlaku
                                                    Sebaliknya</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row pt-5">
                                        <div class=" col-lg-6 col-md-6 col-sm-12">
                                            <div class="row text-start align-middle pt-2">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <!--begin::Timeline-->
                                                    <div class="timeline-label">
                                                        {{-- awal --}}
                                                        <!--begin::Item-->
                                                        <div class="timeline-item">
                                                            <!--begin::Label-->
                                                            <div class="timeline-label text-muted fs-7">Rute
                                                                Awal</div>
                                                            <!--end::Label-->

                                                            <!--begin::Badge-->
                                                            <div class="timeline-badge">
                                                                <i class="fa fa-genderless text-primary fs-1"></i>
                                                            </div>
                                                            <!--end::Badge-->

                                                            <!--begin::Text-->
                                                            <div class="fw-mormal timeline-content ps-5">
                                                                {{ $value->wil_asal }}
                                                            </div>
                                                            <!--end::Text-->
                                                        </div>
                                                        <!--end::Item-->

                                                        {{-- akhir --}}
                                                        <!--begin::Item-->
                                                        <div class="timeline-item">
                                                            <!--begin::Label-->
                                                            <div class="timeline-label fw-bold text-gray-800 fs-7">
                                                                <b>{{ $value->time . ' Hari ' }}</b>
                                                            </div>
                                                            <!--end::Label-->

                                                            <!--begin::Badge-->
                                                            <div class="timeline-badge">
                                                                <i class="fa fa-circle text-primary fs-6"></i>
                                                            </div>
                                                            <!--end::Badge-->

                                                            <!--begin::Content-->
                                                            <div class="timeline-content d-flex">
                                                                <span class=" ps-3">{{ $value->wil_tujuan }}</span>
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Item-->
                                                    </div>
                                                    <!--end::Timeline-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 pt-lg-0 pt-sm-5 pt-md-0">
                                            <div class=" separator text-dark d-block d-sm-none"></div>
                                            <h4 class="d-block d-sm-none pb-2 pt-4">Harga Kiriman</h4>
                                            <div class="row flex-sm-row-auto">
                                                <div class="col-6 col-md-12 col-xl-12">
                                                    <div class="pt-sm-4">
                                                        <h6 class="text-dark fs-5 fw-semibold text-sm-end">
                                                            {{ toRupiah($value->harga) . '/Kg' }}
                                                            <br><span
                                                                class="text-muted fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kg . ' KG' }}</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-12 col-xl-12">
                                                    <div class=" pt-sm-0 pt-lg-4">
                                                        <h6
                                                            class="text-dark fs-5 fw-semibold  text-sm-end text-lg-end">
                                                            {{ toRupiah($value->hrg_kubik) . '/M3' }} <br><span
                                                                class="text-muted fw-light text-end secondhrg pt-1">{{ 'min : ' . $value->min_kubik . ' M3' }}</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Card header-->
                            @if ($value->keterangan != null && $value->keterangan != '')
                                <div class=" separator my-2"></div>
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <div class="row">
                                        @if ($value->keterangan != null && $value->keterangan != '')
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <!--begin::Post content-->
                                                <div class="fs-7 text-muted">Keterangan : </div>
                                                <p><span style="white-space: pre-line" class=" fs-7 fw-bolder text-muted">{{ $value->keterangan }}</span>
                                                </p>
                                                <!--end::Post content-->
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!--end::Card body-->
                            @endif
                            <div class="separator border-primary border-4 my-2 ">
                            </div>
                            <a class=" btn btn-active-primary pl-5 pr-5 mx-20 align-content-center text-center"
                                data-bs-toggle="collapse" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample{{ $value->id_harga }}" aria-expanded="false"
                                aria-controls="collapseExample{{ $value->id_harga }}"
                                onclick="goDetail('{{ $value->id_harga }}')">
                                <i class="fa fa-arrow-alt-circle-down"></i>
                                Rincian Data Vendor
                            </a>

                            <div class="collapse" id="collapseExample{{ $value->id_harga }}">
                                <div class=" separator my-5"></div>
                                <!--begin::Card footer-->
                                <div class="card-footer border-0 pt-0">
                                    <!--begin::Info-->
                                    <div class="mb-6">
                                        <!--begin::Separator-->
                                        <div class="separator separator-solid"></div>
                                        <!--end::Separator-->
                                        <!--begin::Table container-->
                                        <div class=" table-responsive">
                                            <!--begin::Table-->
                                            <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <tr class="fw-bold text-left text-muted bg-light">
                                                        <th>Vendor</th>
                                                        <th>Asal</th>
                                                        <th>Tujuan</th>
                                                        <th>Vendor</th>
                                                        <th>Harga</th>
                                                        <th>Est. Lead Time</th>
                                                    </tr>
                                                </thead>
                                                <!--end::Table head-->
                                                <!--begin::Table body-->
                                                <tbody id="table-{{ $value->id_harga }}"></tbody>
                                                <!--end::Table body-->
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Card footer-->
                            </div>
                        @endif
                    </div>
                    <!--end::Card-->
                    <!--end::Post 2-->
                </div>
                <!--end::Posts-->

                <!--end::Content-->
            </div>
        </div>
        <!--end::Social - Feeds-->
    @endforeach
</form>
<!--end::Post-->

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
