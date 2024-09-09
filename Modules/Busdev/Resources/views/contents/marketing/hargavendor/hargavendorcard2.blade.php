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
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column me-3">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder my-1 fs-1">Rute dan Harga Vendor</h1>
                    <h1 class="d-flex text-dark fw-light my-1 fs-4 pt-2">Anda Sebagai :
                        {{ Str::upper(Session('role')['nm_role']) }}
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
                        <div class="card bgi-no-repeat bgi-size-contain mb-5 mb-lg-10"
                            style="background-color: #1B283F; ">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column justify-content-between">
                                <!--begin::Title-->
                                <h2 class="text-white fw-bold mb-5">
                                    <span class="lh-lg">Tarif Tujuan
                                        <br />Berdasarkan pilihan vendor, asal dan tujuan</span>
                                </h2>
                                <!--end::Title-->
                                <form>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1">
                                            <div class="form-group">
                                                <label for="name" class="text-white h3">Vendor</label>
                                                <select class="form-select" data-control="select2" id="id_ven"
                                                    data-placeholder="Pilih Vendor" name="id_ven">
                                                    <option disabled selected value="">Pilih Vendor</option>
                                                    @foreach ($vendor as $key => $value)
                                                        <option value="{{ $value->id_ven }}">
                                                            {{ strtoupper($value->nm_ven) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1">
                                            <div class="form-group">
                                                <label for="name" class="text-white h3">Asal</label>
                                                <select class="form-select" id="id_asal" name="id_asal"
                                                    data-control="select2" data-placeholder="Pilih Asal" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 pt-1">
                                            <div class="form-group">
                                                <label for="name" class="text-white h3">Tujuan</label>
                                                <select class="form-select" id="id_tujuan" name="id_tujuan"
                                                    data-control="select2" data-placeholder="Pilih Tujuan" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5 text-center px-0">
                                        <div class="col-lg-6 col-md-6 col-sm-6 pt-2">
                                            <a href='{{ url(Request::segment(1)) }}'
                                                class="btn btn-secondary fw-semibold"
                                                style="display: block;width: 100%;">Refresh</a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 pt-2">
                                            <button class="btn btn-danger fw-semibold"
                                                style="display: block;width: 100%;"onclick="goFilter()">Cari
                                                Tujuan</button>
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
                                <div class="card card-flush mb-5 pb-7">
                                    @if ($value->type == 1)
                                        <!--begin::Card header-->
                                        <div class="card-header pt-9 mx-5">
                                            <!--begin::Info-->
                                            <div class="flex-grow-1 align-middle">
                                                <div class="row">
                                                    <div class=" col-lg-8 col-md-8 col-sm-12">
                                                        <!--begin::Avatar-->
                                                        <div class="symbol symbol-35px me-2">
                                                            <img src="assets-busdevmarketing/ship.png" class=""
                                                                alt="Icon" />
                                                        </div>
                                                        <!--end::Avatar-->
                                                        <!--begin::Name-->
                                                        <a href="#"
                                                            class="text-gray-800 text-hover-primary fs-6 fw-bold align-middle">
                                                            {{ $value->nm_ven }}
                                                        </a>
                                                        <!--end::Name-->
                                                        @if ($value->rekomendasi == '1')
                                                            {{-- <i class="fa fa-star text-warning"></i> --}}
                                                            <span class="text-dark fw-semibold d-block pt-2">
                                                                <span
                                                                    class="badge badge-primary badge-outline">Rekomendasi
                                                                    Vendor
                                                                </span>
                                                            </span>
                                                        @endif
                                                        <!--begin::Date-->
                                                        <span class="text-dark fw-semibold d-block pt-2">
                                                            <span class="badge badge-pill badge-info badge-outline"><i
                                                                    class="fa fa-plane text-info px-2"></i>
                                                                Direct</span>
                                                        </span>
                                                    </div>
                                                    <div class=" col-lg-4 col-md-4 col-sm-12 text-sm-end pt-3">
                                                        <span class=" fs-7 mt-5 text-muted">
                                                            Last Updated :
                                                            @if ($value->update_user != null)
                                                                {{ $value->updated_at }}<br>{{ $value->update_user }}
                                                                @else{{ $value->created_at }}<br>{{ $value->insert_user }}
                                                            @endif
                                                        </span>
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
                                                                        <div
                                                                            class="timeline-label fw-bold text-muted fs-7">
                                                                            Rute
                                                                            Awal</div>
                                                                        <!--end::Label-->

                                                                        <!--begin::Badge-->
                                                                        <div class="timeline-badge">
                                                                            <i
                                                                                class="fa fa-genderless text-primary fs-1"></i>
                                                                        </div>
                                                                        <!--end::Badge-->

                                                                        <!--begin::Text-->
                                                                        <div
                                                                            class="fw-mormal timeline-content text-muted ps-3">
                                                                            {{ $value->wil_asal }}
                                                                        </div>
                                                                        <!--end::Text-->
                                                                    </div>
                                                                    <!--end::Item-->

                                                                    {{-- akhir --}}
                                                                    <!--begin::Item-->
                                                                    <div class="timeline-item">
                                                                        <!--begin::Label-->
                                                                        <div
                                                                            class="timeline-label fw-bold text-gray-800 fs-6">
                                                                            <b>{{ $value->time . ' Hari ' }}</b>
                                                                        </div>
                                                                        <!--end::Label-->

                                                                        <!--begin::Badge-->
                                                                        <div class="timeline-badge">
                                                                            <i
                                                                                class="fa fa-circle text-primary fs-6"></i>
                                                                        </div>
                                                                        <!--end::Badge-->

                                                                        <!--begin::Content-->
                                                                        <div class="timeline-content d-flex">
                                                                            <span
                                                                                class="fw-bold text-gray-800 ps-3">{{ $value->wil_tujuan }}</span>
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
                                                        <div class="row pt-6">
                                                            <div class=" col-lg-12 col-md-12 col-sm-6 text-sm-end">
                                                                <h5 class="text-warning fw-semibold utamahrg">
                                                                    {{ toRupiah($value->harga) . '/Kg' }} <br><span
                                                                        class="text-muted fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kg . ' KG' }}</span>
                                                                </h5>
                                                            </div>
                                                            <div class=" col-lg-12 col-md-12 col-sm-6 text-sm-end">
                                                                <h5
                                                                    class="text-warning fw-semibold d-block pt-2 utamahrg">
                                                                    {{ toRupiah($value->hrg_kubik) . '/M3' }} <br><span
                                                                        class="text-muted fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kubik . ' M3' }}</span>
                                                                </h5>
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
                                            <div class="card-body mx-5">
                                                <hr>
                                                <div class="row">
                                                    @if ($value->keterangan != null && $value->keterangan != '')
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <!--begin::Post content-->
                                                            <div class="fs-6 fw-bold text-gray-700">Keterangan : </div>
                                                            <p><b>{{ $value->keterangan }}</b>
                                                            </p>
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
                                        <div class="card-header border-0 pt-9 mx-5">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px me-5">
                                                <img src="assets-busdevmarketing/ship.png" class=""
                                                    alt="Icon" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <div class="row">
                                                    <div class="col">
                                                        <!--begin::Name-->
                                                        <a class="text-gray-800 text-hover-primary fs-6 fw-bold">
                                                            MULTI VENDOR ( {{ $value->tven }} )
                                                        </a>
                                                        <!--end::Name-->
                                                        @if ($value->rekomendasi == '1')
                                                            <span class="text-dark fw-semibold d-block pt-2">
                                                                <span
                                                                    class="badge badge-primary badge-outline">Rekomendasi
                                                                    Vendor
                                                                </span>
                                                            </span>
                                                        @endif
                                                        <!--begin::Date-->
                                                        <span class="text-dark fw-semibold d-block pt-2">
                                                            <span
                                                                class="badge badge-pill badge-success badge-outline"><i
                                                                    class="fa fa-truck px-2 text-success"></i>
                                                                Transit</span>
                                                        </span>
                                                    </div>

                                                    <div class="col text-end">
                                                        <h5 class="text-warning fw-semibold utamahrg">
                                                            {{ toRupiah($value->harga) . '/Kg' }} <br><span
                                                                class="text-muted fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kg . ' KG' }}</span>
                                                        </h5>

                                                        <h5 class="text-warning fw-semibold d-block pt-2 utamahrg">
                                                            {{ toRupiah($value->hrg_kubik) . '/M3' }} <br><span
                                                                class="text-muted fw-light secondhrg pt-1">{{ 'min : ' . $value->min_kubik . ' M3' }}</span>
                                                        </h5>
                                                    </div>
                                                </div>

                                                <div class="row text-start align-middle pt-2">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <!--begin::Timeline-->
                                                        <div class="timeline-label">
                                                            {{-- awal --}}
                                                            <!--begin::Item-->
                                                            <div class="timeline-item">
                                                                <!--begin::Label-->
                                                                <div class="timeline-label fw-bold text-gray fs-6">
                                                                </div>
                                                                <!--end::Label-->

                                                                <!--begin::Badge-->
                                                                <div class="timeline-badge">
                                                                    <i class="fa fa-genderless text-primary fs-1"></i>
                                                                </div>
                                                                <!--end::Badge-->

                                                                <!--begin::Text-->
                                                                <div
                                                                    class="fw-mormal timeline-content text-muted ps-3">
                                                                    {{ $value->wil_asal }}
                                                                </div>
                                                                <!--end::Text-->
                                                            </div>
                                                            <!--end::Item-->

                                                            {{-- akhir --}}
                                                            <!--begin::Item-->
                                                            <div class="timeline-item">
                                                                <!--begin::Label-->
                                                                <div class="timeline-label fw-bold text-gray-800 fs-6">
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
                                                                        class="fw-bold text-gray-800 ps-3">{{ $value->wil_tujuan }}</span>
                                                                </div>
                                                                <!--end::Content-->
                                                            </div>
                                                            <!--end::Item-->
                                                        </div>
                                                        <!--end::Timeline-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Card header-->
                                        @if ($value->keterangan != null && $value->keterangan != '')
                                            <hr>
                                            <!--begin::Card body-->
                                            <div class="card-body">
                                                <div class="row">
                                                    @if ($value->keterangan != null && $value->keterangan != '')
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <!--begin::Post content-->
                                                            <div class="fs-6 fw-bold text-gray-700">Keterangan : </div>
                                                            <p><b>{{ $value->keterangan }}</b>
                                                            </p>
                                                            <!--end::Post content-->
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--end::Card body-->
                                        @endif
                                        <hr>
                                        <div class="row mx-10">
                                            <button class="btn btn-outline btn-outline-primary text-dark"
                                                data-bs-toggle="collapse" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseExample{{ $value->id_harga }}"
                                                aria-expanded="false"
                                                aria-controls="collapseExample{{ $value->id_harga }}"
                                                onclick="goDetail('{{ $value->id_harga }}')">Rincian
                                                Vendor</button>
                                        </div>
                                        <hr>
                                        <div class="collapse" id="collapseExample{{ $value->id_harga }}">
                                            <!--begin::Card footer-->
                                            <div class="card-footer border-0 pt-0">
                                                <!--begin::Info-->
                                                <div class="mb-6">
                                                    <!--begin::Separator-->
                                                    <div class="separator separator-solid"></div>
                                                    <!--end::Separator-->
                                                    <!--begin::Table container-->
                                                    <div class="table-responsive">
                                                        <!--begin::Table-->
                                                        <table
                                                            class="table table-row-dashed align-middle gs-0 gy-3 my-0">
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
        </div>

    </div>
    <!--end::Post-->
</div>
<script>
    $(document).ready(function() {
        // asal wilayah
        $.ajax({
            url: '{{ url('getwilayah') }}',
            type: 'GET',
            delay: 250,
            dataType: 'json',
            success: function(response) {
                $("#id_asal").attr('disabled', false);
                var $select = $('#id_asal');
                $.each(response, function(key, value) {
                    $select.append('<option value=' + value.kode + '>' + value.value +
                        '</option>'); // return empty
                });


            }
        });



        // tujuan wilayah
        $.ajax({
            url: '{{ url('getwilayah') }}',
            type: 'GET',
            delay: 250,
            dataType: 'json',
            success: function(response) {
                $("#id_tujuan").attr('disabled', false);
                var $select = $('#id_tujuan');
                $.each(response, function(key, value) {
                    $select.append('<option value=' + value.kode + '>' + value.value +
                        '</option>'); // return empty
                });


            }
        });

    });

    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });



    // $('#id_tujuan').select2({
    //     placeholder: 'Cari Wilayah Asal ....',
    //     ajax: {
    //         url: '{{ url('getwilayah') }}',
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function(data) {
    //             $('#id_tujuan').empty();

    //             return {
    //                 results: $.map(data, function(item) {
    //                     return {
    //                         text: item.value,
    //                         id: item.kode
    //                     }
    //                 })
    //             };
    //         },
    //         success: function() {
    //             $('#id_tujuan').addClass('form-select');
    //         },
    //         cache: false
    //     }
    // });

    // $('#id_asal').select2({
    //     placeholder: 'Cari Wilayah Tujuan ....',
    //     ajax: {
    //         url: '{{ url('getwilayah') }}',
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function(data) {
    //             $('#id_asal').empty();
    //             return {
    //                 results: $.map(data, function(item) {
    //                     return {
    //                         text: item.value,
    //                         id: item.kode
    //                     }
    //                 })
    //             };
    //         },
    //         success: function() {
    //             $('#id_asal').addClass('form-select');
    //         },
    //         cache: false
    //     }
    // });

    // $('#id_ven').select2();
    var total = 0;

    @if (isset($filter['page']))
        $("#shareselect").val('{{ $filter['page'] }}');
    @endif

    @if (isset($filter['id_ven']))
        $("#id_ven").select2().val("{{ $filter['id_ven'] }}").trigger("change");
    @endif

    // @if (isset($filter['asal']->id_wil))
    //     $('#id_asal').append(
    //         '<option value="{{ $filter['asal']->id_wil }}">{{ $filter['asal']->nama_wil }}</option>');
    // @endif

    // @if (isset($filter['tujuan']->id_wil))

    //     $('#id_tujuan').append(
    //         '<option value="{{ $filter['tujuan']->id_wil }}">{{ $filter['tujuan']->nama_wil }}</option>'
    //     );
    // @endif

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
