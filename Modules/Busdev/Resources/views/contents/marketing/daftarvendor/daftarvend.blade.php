 <!--begin::Toolbar-->
 <div class="toolbar mb-5 mb-lg-7" id="kt_toolbar">
     <!--begin::Page title-->
     <div class="page-title d-flex flex-column me-3">
         <!--begin::Title-->
         <h1 class="d-flex text-dark fw-bolder my-1 fs-1">Daftar Vendor</h1>
         <h1 class="d-flex text-dark fw-light my-1 fs-4 pt-2">Anda Sebagai : {{ Str::upper(Session('role')['nm_role']) }}
         </h1>
         <!--end::Title-->
     </div>

     <!--begin::Actions-->
     <div class="d-flex align-items-center py-2 py-md-1">

         @if (Str::upper(Session('role')['nm_role']) != 'MARKETING')
             <!--begin::Wrapper-->
             <div class="me-3">
                 <!--begin::Menu-->
                 <a href="#" class="btn btn-light fw-bold" data-kt-menu-trigger="click"
                     data-kt-menu-placement="bottom-end">
                     <i class="ki-duotone ki-filter fs-5 text-gray-500 me-1">
                         <span class="path1"></span>
                         <span class="path2"></span>
                     </i>Filter</a>
                 <!--begin::Menu 1-->
                 <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                     id="kt_menu_641ac65418aeb">
                     <!--begin::Header-->
                     <div class="px-7 py-5">
                         <div class="fs-5 text-dark fw-bold">Filter Options</div>
                     </div>
                     <!--end::Header-->
                     <!--begin::Menu separator-->
                     <div class="separator border-gray-200"></div>
                     <!--end::Menu separator-->
                     <!--begin::Form-->
                     <div class="px-7 py-5">
                         <!--begin::Input group-->
                         <div class="mb-10">
                             <!--begin::Label-->
                             <label class="form-label fw-semibold">Status:</label>
                             <!--end::Label-->
                             <!--begin::Input-->
                             <div>
                                 <select class="form-select form-select-solid" data-kt-select2="true"
                                     data-placeholder="Select option" data-dropdown-parent="#kt_menu_641ac65418aeb"
                                     data-allow-clear="true">
                                     <option></option>
                                     <option value="1">Approved</option>
                                     <option value="2">Pending</option>
                                     <option value="2">In Process</option>
                                     <option value="2">Rejected</option>
                                 </select>
                             </div>
                             <!--end::Input-->
                         </div>
                         <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="mb-10">
                             <!--begin::Label-->
                             <label class="form-label fw-semibold">Member Type:</label>
                             <!--end::Label-->
                             <!--begin::Options-->
                             <div class="d-flex">
                                 <!--begin::Options-->
                                 <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                     <input class="form-check-input" type="checkbox" value="1" />
                                     <span class="form-check-label">Author</span>
                                 </label>
                                 <!--end::Options-->
                                 <!--begin::Options-->
                                 <label class="form-check form-check-sm form-check-custom form-check-solid">
                                     <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                     <span class="form-check-label">Customer</span>
                                 </label>
                                 <!--end::Options-->
                             </div>
                             <!--end::Options-->
                         </div>
                         <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="mb-10">
                             <!--begin::Label-->
                             <label class="form-label fw-semibold">Notifications:</label>
                             <!--end::Label-->
                             <!--begin::Switch-->
                             <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                 <input class="form-check-input" type="checkbox" value="" name="notifications"
                                     checked="checked" />
                                 <label class="form-check-label">Enabled</label>
                             </div>
                             <!--end::Switch-->
                         </div>
                         <!--end::Input group-->
                         <!--begin::Actions-->
                         <div class="d-flex justify-content-end">
                             <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                 data-kt-menu-dismiss="true">Reset</button>
                             <button type="submit" class="btn btn-sm btn-primary"
                                 data-kt-menu-dismiss="true">Apply</button>
                         </div>
                         <!--end::Actions-->
                     </div>
                     <!--end::Form-->
                 </div>
                 <!--end::Menu 1-->
                 <!--end::Menu-->
             </div>
             <!--end::Wrapper-->
             <!--begin::Button-->
             <a href="#" class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app"
                 id="kt_toolbar_primary_button">Create</a>
             <!--end::Button-->
         @endif
     </div>
     <!--end::Actions-->
 </div>
 <!--end::Toolbar-->
 <!--end::Page title-->
 <div class="col-md-12 mt-2">
     <div class="card">
         <div class="card-body row">
             @include('busdev::metroniclapan-template.paginator-busdevs')
         </div>
     </div>
 </div>
 <hr>
 <!--begin::Post-->
 <div class="content flex-column-fluid" id="kt_content">
     <!--begin::Card widget 15-->
     <div class="card card-flush h-xl-100">
         <!--begin::Body-->
         <div class="card-body">
             <!--begin::Table container-->
             <div class="table-responsive">
                 <!--begin::Table-->
                 <table id="kt_datatable_dom_positioning"  class="table table-hover table-rounded table-striped table-bordered border-1 border-dark gy-7 gs-7">
                     <!--begin::Table head-->
                     <thead>
                         <tr class="fs-7 fw-bold text-gray-400 border-bottom-0 text-start bg-dark align-middle">
                             <th class="text-center">No</th>
                             <th class="text-left" width="150">Nama Vendor<br>Nomor HP</th>
                             <th>Kota Wilayah <br> Alamat Vendor</th>
                             <th>Group</th>
                             <th class=" text-center">Status Vendor</th>
                             @if (Str::upper(Session('role')['nm_role']) != 'MARKETING')
                                 <th>
                                     Opsi
                                 </th>
                             @endif
                         </tr>
                     </thead>
                     <!--end::Table head-->
                     <!--begin::Table body-->
                     {{-- <tbody>
                         @foreach ($data as $key => $value)
                             <tr>
                                 <td class="text-center">{{ $key + 1 }}</td>
                                 <td>
                                     <b>{{ strtoupper($value->nm_ven) }}</b> <br>
                                     {{ $value->telp_ven }}
                                 </td>
                                 <td>
                                     @if (isset($value->wilayah->nama_wil))
                                         {{ strtoupper($value->wilayah->nama_wil) }}
                                     @endif
                                     <br>
                                     <b>{{ strtoupper($value->alm_ven) }}</b>
                                 </td>
                                 <td>
                                    @if (isset($value->group->nm_grup_ven))
                                        {{ strtoupper($value->group->nm_grup_ven) }}
                                    @endif
                                </td>
                                 <td>
                                     @if ($value->is_aktif == 1)
                                         <i class="fa fa-check" style="color: green"></i>
                                     @else
                                         <i class="fa fa-times" style="color: red"></i>
                                     @endif
                                 </td>
                                 @if (Str::upper(Session('role')['nm_role']) != 'MARKETING')
                                     <td>
                                         <div class="dropdown">
                                             <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                 id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                 aria-expanded="false">
                                                 <i class="fa fa-bars"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu dropdown-menu-right"
                                                 aria-labelledby="dropdownMenuButton">
                                                 <a class="dropdown-item"
                                                     href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/detail') }}"><i
                                                         class="fa fa-eye"></i> Detail</a>
                                                 <a class="dropdown-item"
                                                     href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/edit') }}"><i
                                                         class="fa fa-pencil"></i> Edit</a>
                                                 <button class="dropdown-item" type="button" data-toggle="tooltip"
                                                     data-placement="bottom" title="Hapus"
                                                     onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_harga) }}')">
                                                     <span><i class="fa fa-times"></i></span> Hapus
                                                 </button>
                                             </div>
                                         </div>
                                     </td>
                                 @endif
                             </tr>
                         @endforeach
                     </tbody> --}}
                     <tbody>
                        @foreach ($data as $key => $value)
                            @if ($value->is_aktif == 1)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <b>{{ strtoupper($value->nm_ven) }}</b> <br>
                                        {{ $value->telp_ven }}
                                    </td>
                                    <td>
                                        @if (isset($value->wilayah->nama_wil))
                                            {{ strtoupper($value->wilayah->nama_wil) }}
                                        @endif
                                        <br>
                                        <b>{{ strtoupper($value->alm_ven) }}</b>
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
                                    @if (Str::upper(Session('role')['nm_role']) != 'MARKETING')
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/detail') }}"><i
                                                            class="fa fa-eye"></i> Detail</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url(Request::segment(1) . '/' . $value->id_harga . '/edit') }}"><i
                                                            class="fa fa-pencil"></i> Edit</a>
                                                    <button class="dropdown-item" type="button" data-toggle="tooltip"
                                                        data-placement="bottom" title="Hapus"
                                                        onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_harga) }}')">
                                                        <span><i class="fa fa-times"></i></span> Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                     <!--end::Table body-->
                 </table>
             </div>
             <!--end::Table-->
         </div>
         <!--end: Card Body-->
     </div>
     <!--end::Card widget 15-->
 </div>
 <!--end::Post-->

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
