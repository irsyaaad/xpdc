<script src="{{ url('assets-adminbusdev/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
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
                        <li class="breadcrumb-item">
                            <a href="hargavendor" class="m-nav__link">
                                <span class="m-nav__link-text text-muted">
                                    HARGA VENDOR
                                </span>
                            </a>
                        </li>
                        @for ($i = 1; $i < 6; $i++)
                            @if (Request::segment($i) !== null)
                                <li class="breadcrumb-item">
                                    <a href="{{ url(Request::segment($i)) }}" class="m-nav__link">
                                        <span class="m-nav__link-text">
                                            @if ($i == 1)
                                                {{ strtoupper(str_replace('_', ' ', get_menu(Request::segment($i)))) }}
                                            @else
                                                <span class="text-muted">
                                                    {{ strtoupper(str_replace('_', ' ', Request::segment($i))) }}
                                                </span>
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
            <div class="card-header">
                <div class="card-title fs-2">
                    Form Import Vendor Direct
                </div>
            </div>
            <div class="card-body">
                <form class="m-form m-form--fit m-form--label-align-right" method="POST"
                    action="{{ url('hargavendor/1/importbaru') }}" enctype="multipart/form-data" id="formDirect">
                    @csrf
                    <div class="row">
                        <div class="px-6">
                            @if (Session::has('success'))
                                <div class="alert alert-success">
                                    <strong>PERHATIAN !</strong>
                                    <br>
                                    <h6> <b>{{ Session::get('success') }}</b> </h6>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    <ul>
                                        @php
                                            $errors = json_decode(session('error_data'), true) ?? [];
                                        @endphp
                                        @if (is_countable($errors) && count($errors) > 0)
                                            @foreach ($errors as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        @else
                                            <li>{{ session('error') }}</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group row mb-5">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h3>
                                        Data Vendor direct
                                    </h3>
                                </div>
                                <hr>
                                <!-- step 1: placeholder -->
                                <div class="table-responsive" id="spreadsheet-container">
                                    <div id="spreadsheet"></div>
                                </div>
                                <input type="hidden" name="data" id="data">
                                @if (isset($errors) && count($errors) > 0)
                                    <div class="table-responsive mt-5" id="error_spreadsheet-container">
                                        <div id="error_spreadsheet"></div>
                                    </div>
                                    <input type="hidden" name="error_data" id="error_data"
                                        value="{{ json_encode($errors) }}">
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>

                            <a id="btl" href="{{ url('hargavendor') }}" class="btn btn-sm btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="http://bossanova.uk/jexcel/v3/jexcel.js"></script>
<link rel="stylesheet" href="http://bossanova.uk/jexcel/v3/jexcel.css" type="text/css">
<script src="https://jsuites.net/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v2/jsuites.css" type="text/css" />

<script>
    $("#btl").click(function() {
        @php
            Session::forget('items');
        @endphp
    });

    // step 3: ubah data dari Controller menjadi JSON
    var data = @json($items ?? '?');
    var error_data = @json($errors ?? []);
    console.log(data)
    // step 4: instansiasi jExcel dan definisikan kolom
    $('#spreadsheet').jexcel({
        data: data,
        columns: [{
                type: 'text',
                title: 'ORIGIN',
                width: 200
            },
            {
                type: 'text',
                title: 'KOTA',
                width: 200
            },
            {
                type: 'text',
                title: 'TUJUAN',
                width: 300
            },
            {
                type: 'text',
                title: 'VENDOR',
                width: 300
            },
            {
                type: 'text',
                title: 'HPP/KG',
                width: 300
            },
            {
                type: 'text',
                title: 'MIN KG',
                width: 300
            },
            {
                type: 'text',
                title: 'HPP/M3',
                width: 300
            },
            {
                type: 'text',
                title: 'MIN M3',
                width: 300
            },
            {
                type: 'text',
                title: 'HARI',
                width: 300
            },
            {
                type: 'text',
                title: 'KETERANGAN',
                width: 300
            },
            {
                type: 'text',
                title: 'IS BALIK',
                width: 300
            },
            {
                type: 'text',
                title: 'KD. ORIGIN',
                width: 300
            },
            {
                type: 'text',
                title: 'KD. DEST',
                width: 300
            },
            {
                type: 'text',
                title: 'KD. VEND',
                width: 300
            },
            {
                type: 'text',
                title: 'is_Rekomendasi',
                width: 300
            },
            {
                type: 'text',
                title: 'is_replace',
                width: 300
            },
        ]
    });

    if (error_data.length > 0) {
        $('#error_spreadsheet').jexcel({
            data: error_data,
            columns: [{
                    type: 'text',
                    title: 'ORIGIN',
                    width: 200
                },
                {
                    type: 'text',
                    title: 'KOTA',
                    width: 200
                },
                {
                    type: 'text',
                    title: 'TUJUAN',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'VENDOR',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'HPP/KG',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'MIN KG',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'HPP/M3',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'MIN M3',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'HARI',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'KETERANGAN',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'IS BALIK',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'KD. ORIGIN',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'KD. DEST',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'KD. VEND',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'is_Rekomendasi',
                    width: 300
                },
                {
                    type: 'text',
                    title: 'is_replace',
                    width: 300
                },
            ]
        });
    }

    $(function() {
        $('#formDirect').submit(function(event) {
            var data = $('#spreadsheet').jexcel('getData');
            $('#data').val(JSON.stringify(data));
        });
    });
</script>
