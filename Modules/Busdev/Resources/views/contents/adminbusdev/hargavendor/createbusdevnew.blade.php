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
                        Form Tambah Data Vendor
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
            <div class=" card-body">
                <form method="POST"
                    action="@if (Request::segment(2) == 'create') {{ url(Request::segment(1)) }} @else{{ route('hargavendor.update', $data->id_harga) }} @endif"
                    enctype="multipart/form-data">
                    @if (Request::segment(3) == 'edit')
                        {{ method_field('PUT') }}
                    @endif
                    @csrf
                    <div class=" row">

                        <div class=" col-md-4 pb-7 pt-5">
                            <div class="form-group">
                                <label>Type Hpp :</label>
                                <br>
                                <label for="type" class=" pt-2">
                                    <b> <input type="radio" class="form-check-input" value="1" id="type"
                                            name="type" @if (Request::segment(3) == 'edit') disabled @endif checked>
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
                                <label class=" form-label" for="id_ven">
                                    <b>Wilayah Asal</b> <span class="span-required"> *</span>
                                </label>

                                <select class="form-control form-select" id="id_asal" name="id_asal"
                                    @if (Request::segment(3) == 'edit') disabled @endif required></select>

                                <input type="hidden" name="id_asal" id="nama_asal" value="{{ old('nama_asal') }}">

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
                                    <b>Wilayah Tujuan</b> <span class="span-required"> *</span>
                                </label>

                                <select class="form-control form-select" id="id_tujuan" name="id_tujuan"
                                    @if (Request::segment(3) == 'edit') disabled @endif required></select>

                                <input type="hidden" name="id_tujuan" id="nama_tujuan"
                                    value="{{ old('nama_tujuan') }}">

                                @if ($errors->has('id_tujuan'))
                                    <label style="color: red">
                                        {{ $errors->first('id_tujuan') }}
                                    </label>
                                @endif
                            </div>

                        </div>

                        <div class=" col-md-4 pb-7">
                            <div class="form-group" id="lbl-vendor">
                                <label class=" form-label" for="id_ven">
                                    <b>Vendor</b>
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
                        <div class=" col-lg-4 col-sm-12 pb-7">
                            <div class="form-group" id="lbl-harga">
                                <label class="form-label" for="harga">
                                    <b>Hpp Per Kg</b>
                                </label>

                                <input type="number" class="form-control" name="harga" id="harga"
                                    value="@if(old('harga') != null) {{ old('harga') }}@elseif(isset($data->harga)){{ trim($data->harga)}}@endif">

                                @if ($errors->has('harga'))
                                    <label style="color: red">
                                        {{ $errors->first('harga') }}
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class=" col-lg-4 col-sm-12 pb-7">

                        </div>
                    </div>



                </form>

            </div>
        </div>
    </div>
</div>
