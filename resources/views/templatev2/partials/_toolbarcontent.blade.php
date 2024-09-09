<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar d-flex flex-stack py-lg-8 py-4">
    <!--begin::Toolbar wrapper-->
    <div class="d-flex flex-grow-1 flex-stack mb-n10 flex-wrap gap-2" id="kt_toolbar">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center me-3 flex-wrap">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                @if (isset($page_title))
                    {{ $page_title }}
                @else
                    Title Page
                @endif
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">{{ \App\Models\Menu::getModule(Request::segment(1))->nm_module ?? '' }}</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet w-5px h-2px bg-gray-400"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">{{ ucwords(strtolower(Request::segment(1))) }}</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center pt-lg-1 pb-lg-2 pb-7 pt-4">
            @yield('toolbar_action')
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Toolbar wrapper-->
</div>
<!--end::Toolbar-->

