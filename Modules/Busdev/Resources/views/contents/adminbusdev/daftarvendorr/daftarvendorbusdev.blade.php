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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Daftar
                        Vendor</h1>
                    <!--end::Title-->
                    <h5>Anda Sebagai : {{ Str::upper(Session('role')['nm_role']) }}</h3>
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
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

                        @if (isset($cekplus->c_insert) and $cekplus->c_insert == 1)
                            <div class="btn btn-primary btn-sm btn-active">
                                <a href="{{ url(Request::segment(1) . '/create') }}" class=" text-white">
                                    TAMBAH {{ strtoupper(get_menu(Request::segment(1))) }}
                                </a>
                                <i class="fa fa-plus"></i>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            {{-- begin::filtervendor --}}
            @php
            $params = request()->query();
            $a_params = getParamsUrl($params);
            $ulrs = url('vendorbusdev') . $a_params;
        @endphp 
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class=" card">
            <div class=" card-header">
                <div class="card-title">
                    Daftar Vendor
                </div>
            </div>
            <div class=" card-body">
                <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data"
                    id="form-select">
                    @csrf
                    @method('DELETE')
                    {{-- <div class=" pb-5"> @include('template.paginate')</div> --}}
                    <div class=" pb-5"> @include('busdev::metronictigelapan-template.paginator-adminbusdev')</div>
                    <div class=" px-5">
                        @include('template.notif')
                    </div>
                    <div class=" table-responsive">
                        <table id="kt_datatable_dom_positioning"  class="table table-bordered table-hover">
                            <thead class="">
                                <tr>
                                    <th>No</th>
                                    <th width="250">Nama Vendor <br> Nomor Handphone</th>
                                    <th width="100">Kode Vendor</th>
                                    <th width="500">Kota <br> Alamat Vendor</th>
                                    <th width="200">Group</th>
                                    <th class="text-center">Status Data</th>
                                    <th width="200" class="text-center">Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class=" fs-7">
                                @foreach ($data as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <b>{{ strtoupper($value->nm_ven) }}</b> <br>
                                            {{ $value->telp_ven }}<br>
                                        </td>
                                        <td>
                                            {{$value->id_ven}}
                                        </td>
                                        <td>
                                            @if (isset($value->wilayah->nama_wil))
                                                {{ strtoupper($value->wilayah->nama_wil) }}
                                            @endif
                                            <br>
                                        </td>
                                        <td>
                                            @if (isset($value->group->nm_grup_ven))
                                                {{ strtoupper($value->group->nm_grup_ven) }}
                                            @endif
                                        </td>
                                        <td class=" text-center">
                                            @if ($value->is_aktif == 1)
                                                <i class="fa fa-check" style="color: green"></i>
                                            @else
                                                <i class="fa fa-times" style="color: red"></i>
                                            @endif
                                        </td>
                                        <td class=" fs-8">
                                            <span>{!! inc_delete($value->id_ven) !!}</span>
                                            <button class="btn btn-danger btn-sm" type="button" data-toggle="tooltip"
                                                data-placement="bottom" title="Hapus"
                                                onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_ven . '/deletevendor') }}')">
                                                <span><i class="fa fa-times"></i></span>
                                            </button>
                                        </td>
                                        @include('busdev::contents.adminbusdev.include.deletelist')
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>

@section('script')
    <script type="text/javascript">
        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });

        @if (isset($filter['page']))
            $("#shareselect").val('{{ $filter['page'] }}');
        @endif

        $('#f_id_ven').select2({
            placeholder: 'Cari Nama Vendor ....',
            ajax: {
                url: '{{ url('getVendor') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#f_id_ven').empty();
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

        @if (isset($filter['f_id_grup_ven']))
            $("#f_id_grup_ven").val('{{ $filter['f_id_grup_ven'] }}');
        @endif
        @if (isset($filter['f_id_ven']->nm_ven))
            $("#f_id_ven").empty();
            $("#f_id_ven").append(
                '<option value="{{ $filter['f_id_ven']->id_ven }}">{{ strtoupper($filter['f_id_ven']->nm_ven) }}</option>'
            );
        @endif
    </script>
@endsection
