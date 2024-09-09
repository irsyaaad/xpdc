<!--begin::Aside-->
<div id="kt_aside" class="aside card mx-xl-5" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_toggle">
    {{-- if role marketing --}}
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid px-4">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 pe-4 me-n4" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}"
            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_aside_menu"
                data-kt-menu="true">
                <!--begin:Menu item-->
                <div class="menu-item here show ">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Vendor Ekspedisi</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        {{-- foreach disini --}}
                        {{-- <div class="menu-item">
                            @if (Request::segment(1) == strtolower('busdev'))
                                <!--begin:Menu link-->
                                <a class="menu-link active" href="/busdev">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                                <!--end:Menu link-->
                            @else
                                <!--begin:Menu link-->
                                <a class="menu-link" href="/busdev">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                                <!--end:Menu link-->
                            @endif
                        </div> --}}
                        <div class="menu-item">
                            @if (Request::segment(1) == strtolower('hargavendor'))
                                <!--begin:Menu link-->
                                <a class="menu-link active" href="/hargavendor">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Rute Vendor</span>
                                </a>
                                <!--end:Menu link-->
                            @else
                                <!--begin:Menu link-->
                                <a class="menu-link" href="/hargavendor">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Rute Vendor</span>
                                </a>
                                <!--end:Menu link-->
                            @endif
                        </div>
                        <div class="menu-item">
                            @if (Request::segment(1) == strtolower('vendorbusdev'))
                                <!--begin:Menu link-->
                                <a class="menu-link active" href="/vendorbusdev">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Daftar Vendor</span>
                                </a>
                                <!--end:Menu link-->
                            @else
                                <!--begin:Menu link-->
                                <a class="menu-link" href="/vendorbusdev">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Daftar Vendor</span>
                                </a>
                                <!--end:Menu link-->
                            @endif
                        </div>
                        {{-- endforeach --}}
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->
    {{-- end if --}}
    {{-- if role bukan marketing --}}
    {{-- end if --}}
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto pt-5 pb-7 px-7" id="kt_aside_footer">
        <a href="{{ '/auth/logout' }}"
            class="btn btn-bg-danger btn-color-danger-500 btn-active-color-danger-900 text-nowrap w-100">
            <span class="btn-label text-white">{{ Str::upper('Sign Out') }}</span>
        </a>
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->
<!--begin::Container-->
<div class="d-flex flex-column flex-column-fluid container-fluid">
