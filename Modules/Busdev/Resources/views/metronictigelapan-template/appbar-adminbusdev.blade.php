     <!--begin::Navbar-->
     <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
         <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">

         </div>
         <!--begin::Quick links-->
         <div class="app-navbar-item ms-1 ms-md-3">
             <!--begin::Menu- wrapper-->
             <div class="btn btn-icon btn-custom btn-color-primary btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
                 data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                 data-kt-menu-placement="bottom-end">
                 <i class="ki-outline ki-abstract-26 fs-1"></i>
             </div>
             <!--begin::Menu-->
             <div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px" data-kt-menu="true">
                 <!--begin::Heading-->
                 <div class="d-flex flex-column flex-center bgi-no-repeat rounded-top px-9 py-10"
                     style="background-image:url('assets-adminbusdev/media/misc/menu-header-bg.jpg')">
                     <!--begin::Title-->
                     <h3 class="text-white fw-semibold mb-3">Akses Role</h3>
                     <!--end::Title-->
                 </div>
                 <!--end::Heading-->
                 <!--begin:Nav-->
                 <div class="row g-0">
                     @if (Auth::check())
                         @php
                             $auth_role = \App\Models\RoleUser::ChekRole(Auth::user()->id_user);
                         @endphp

                         @foreach ($auth_role as $key => $value)
                             @if (Session('role')['id_role'] != $value->id_role)
                                 <!--begin:Item-->
                                 <div class="col-6">
                                     <div onclick="onSubmit()">
                                         <a href="{{ url('changerole') . '/' . $value->id_role }}"
                                             class="d-flex flex-column flex-center h-100 p-5 bg-hover-light border-end border-bottom">
                                             <i class="ki-duotone ki-element-11 fs-3 text-primary">
                                                 <span class="path1"></span>
                                                 <span class="path2"></span>
                                                 <span class="path3"></span>
                                                 <span class="path4"></span>
                                             </i>
                                             <span
                                                 class="fs-5 fw-semibold text-gray-800 mb-0">{{ Str::ucfirst($value->nm_role) }}</span>
                                             <span class="fs-7 text-gray-500">Akses</span>
                                         </a>
                                     </div>
                                 </div>
                                 <!--end:Item-->
                             @endif
                         @endforeach
                     @endif
                 </div>
                 <!--end:Nav-->
             </div>
             <!--end::Menu-->
             <!--end::Menu wrapper-->
         </div>
         <!--end::Quick links-->
         <!--begin::User menu-->
         <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
             <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
                 <span class="m-card-user__name" style="color: rgb(0, 0, 0)">
                     @php
                         $ceks = \App\Models\Karyawan::find(Auth::user()->id_karyawan);
                         if (isset($ceks->nm_karyawan)) {
                             echo '<span class="text-dark fs-8 fw-semibold lh-1 mb-1">' . $ceks->nm_karyawan . '</span>';
                         } else {
                             echo '<span class="text-dark fs-8 fw-semibold lh-1 mb-1">' . Auth::user()->username . '</span>';
                         }
                     @endphp
                 </span>
                 <span class="text-muted fs-8 fw-bold lh-1">{{ strtoupper(Session('role')['nm_role']) }}</span>
             </div>
             <!--begin::Menu wrapper-->
             <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px"
                 data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                 data-kt-menu-placement="bottom-end">
                 <img src=" {{url('assets-adminbusdev/media/avatars/avatar-new.png')}}" alt="user" />
             </div>
             <!--begin::User account menu-->
             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                 data-kt-menu="true">
                 <!--begin::Menu item-->
                 <div class="menu-item px-3">
                     <div class="menu-content d-flex align-items-center px-3">
                         <!--begin::Avatar-->
                         <div class="symbol symbol-50px me-5">
                             <img alt="Logo" src=" {{url('assets-adminbusdev/media/avatars/avatar-new.png')}}" />
                         </div>
                         <!--end::Avatar-->
                         <!--begin::Username-->
                         <div class="d-flex flex-column">
                             @php
                                 $ceks = \App\Models\Karyawan::find(Auth::user()->id_karyawan);
                                 if (isset($ceks->nm_karyawan)) {
                                     echo '<div class="fw-bold d-flex align-items-center fs-5">' . $ceks->nm_karyawan . '</div>';
                                 } else {
                                     echo '<span class="text-dark fs-8 fw-semibold lh-1 mb-1">' . Auth::user()->username . '</span>';
                                 }
                             @endphp
                             <span
                                 class="text-muted fs-8 fw-bold lh-1">{{ strtoupper(Session('role')['nm_role']) }}</span>
                         </div>
                         <!--end::Username-->
                     </div>
                 </div>
                 <!--end::Menu item-->
                 <!--begin::Menu separator-->
                 <div class="separator my-2"></div>
                 <!--end::Menu separator-->
                 <!--begin::Menu item-->
                 <div class="menu-item px-5">
                     <a href="{{ '/profile' }}" class="menu-link px-5">
                         Profile Saya
                     </a>
                 </div>
                 <!--end::Menu item-->
                 <!--begin::Menu item-->
                 <div class="menu-item px-5">
                     <a href="{{ '/auth/logout' }}" class="menu-link px-5">Sign Out</a>
                 </div>
                 <!--end::Menu item-->
             </div>
             <!--end::User account menu-->
             <!--end::Menu wrapper-->
         </div>
         <!--end::User menu-->
     </div>
     <!--end::Navbar-->
     <!--begin::Separator-->
     <div class="app-navbar-separator separator d-none d-lg-flex"></div>
     <!--end::Separator-->
     </div>
     <!--end::Header container-->
     </div>
     <!--end::Header-->
