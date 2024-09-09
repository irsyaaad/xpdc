<!--begin::Navbar-->
<div class="app-navbar flex-shrink-4">
    <!--begin::perusahaan-->
    <div class="app-navbar-item">
        <!--begin::Menu- wrapper-->
        <div class="d-flex align-items-center btn btn-accent btn-icon-gray-600 btn-active-color-primary"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom">
            <div class="symbol symbol-35px me-2">
                <span class="symbol-label bg-light-warning">
                    <i class="ki-outline ki-briefcase fs-2 text-warning"></i></span>
            </div>
            @if (strtolower(Session('role')['nm_role']) == 'asuransi')
                <span class="fs-7 fw-bold mw-50px mw-md-100px mw-lg-200px text-truncate text-gray-800"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" title="LSJ INSURANCE">
                    LSJ INSURANCE
                </span>
            @else
                <span class="fs-7 fw-bold mw-50px mw-md-100px mw-lg-200px text-truncate text-gray-800"
                    data-bs-toggle="tooltip" data-bs-trigger="hover"
                    title="@if (isset(Session('perusahaan')['nm_perush'])) {{ strtoupper(Session('perusahaan')['nm_perush']) }} @endif">
                    @if (isset(Session('perusahaan')['nm_perush']))
                        {{ strtoupper(Session('perusahaan')['nm_perush']) }}
                    @endif
                </span>
            @endif
        </div>
        <!--layout-partial:partials/menus/_notifications-menu.html-->
        @include('templatev2.partials.menus._notifications-menu')
        <!--end::Menu wrapper-->
    </div>
    <!--end::Notifications-->
    <!--end::Perusahaan-->

    <!--begin::Role-->
    <div class="app-navbar-item" id="kt_header_role_toggle">
        <div class="d-none d-md-block">
            <!--begin:Info-->
            <div class="d-flex flex-column bg-light-info text-truncate ms-1 rounded px-4 py-1 text-end">
                <span class="fs-8 fw-bold text-gray-500">Role</span>
                <span
                    class="fs-7 fw-bold text-truncate text-capitalize text-gray-800">{{ Session('role')['nm_role'] }}</span>
            </div>
        </div>
        <!--end:Info-->
    </div>
    <!--end::Role-->
    <!--begin::User menu-->
    <div class="app-navbar-item ms-lg-9 ms-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <!--begin:Info-->
            <div class="d-none d-sm-flex flex-column justify-content-center me-3 text-end">
                <span class="fs-8 fw-bold text-gray-500">Halo</span>
                <a href="#"
                    class="text-hover-primary fs-7 fw-bold d-block text-gray-800">{{ Auth::user()->username }}</a>
            </div>
            <!--end:Info-->
            <!--begin::User-->
            <div class="symbol symbol symbol-circle symbol-35px symbol-md-40px cursor-pointer">
                {{-- <img class="" src="{{ asset('img/logo.png') }}" alt="user" /> --}}
                <i class="fa fa-user-astronaut text-primary fs-3x me-2"></i>
                <div
                    class="position-absolute translate-middle start-100 ms-n1 bg-success rounded-circle h-8px w-8px bottom-0 mb-1">
                </div>
            </div>
            <!--end::User-->
        </div>

        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px mt-1 py-4"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px me-5">
                        <i class="fa fa-user-astronaut text-primary fs-3x"></i>
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Username-->
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-7">
                            @php
                                $ceks = \App\Models\Karyawan::find(Auth::user()->id_karyawan);
                                if (isset($ceks->nm_karyawan)) {
                                    echo $ceks->nm_karyawan;
                                } else {
                                    echo Auth::user()->username;
                                }
                            @endphp
                        </div>
                        <a href="#"
                            class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                    </div>
                    <!--end::Username-->
                </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->

            <!--begin::Menu sub-->
            @if (Auth::check())

                <!--begin::Menu item-->
                @php
                    $auth_role = \App\Models\RoleUser::ChekRole(Auth::user()->id_user);
                @endphp
                @foreach ($auth_role as $key => $value)
                    <div class="menu-item my-1 px-5">
                        <a href="{{ url('changerole') . '/' . $value->id_role }}"
                            class="menu-link {{ Session('role')['id_role'] == $value->id_role ? 'active' : '' }} px-5">{{ ucwords(strtolower($value->nm_role)) }}</a>
                    </div>
                @endforeach
                <!--end::Menu item-->

            @endif
            <!--end::Menu sub-->

            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->

            <!--begin::Menu item-->
            <div class="menu-item my-1 px-5">
                <a href="#" class="menu-link px-5">Account Settings</a>
            </div>
            <!--end::Menu item-->
            <div class="separator my-2"></div>
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="{{ url('auth/logout') }}" class="menu-link px-5">Sign Out</a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
    <!--begin::Header menu toggle-->
    <div class="app-navbar-item d-lg-none me-n3 ms-2" title="Show header menu">
        <div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px"
            id="kt_app_sidebar_mobile_toggle">
            <i class="ki-outline ki-text-align-left fs-1"></i>
        </div>
    </div>
    <!--end::Header menu toggle-->
</div>
<!--end::Navbar-->
