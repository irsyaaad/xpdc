<!--begin:Pages menu-->
<div class="menu-active-bg px-lg-0 px-4">
    <!--begin:Tabs nav-->
    <div class="d-flex w-100 overflow-auto">
        <ul class="nav nav-stretch nav-line-tabs fw-bold fs-6 p-lg-10 flex-grow-1 flex-nowrap p-0">
            <!--begin:Nav item-->
            <li class="nav-item mx-lg-1">
                <a class="nav-link py-lg-6 active text-active-primary py-3" href="#" data-bs-toggle="tab"
                    data-bs-target="#kt_app_header_menu_transaksi_order">
                    Order </a>
            </li>
            <!--end:Nav item-->
            <!--begin:Nav item-->
            <li class="nav-item mx-lg-1">
                <a class="nav-link py-lg-6 text-active-primary py-3" href="#" data-bs-toggle="tab"
                    data-bs-target="#kt_app_header_menu_transaksi_dm">
                    Daftar Muatan </a>
            </li>
            <!--end:Nav item-->
            <!--begin:Nav item-->
            <li class="nav-item mx-lg-1">
                <a class="nav-link py-lg-6 text-active-primary py-3" href="#" data-bs-toggle="tab"
                    data-bs-target="#kt_app_header_menu_pages_authentication">
                    Status Barang </a>
            </li>
            <!--end:Nav item-->
            <!--begin:Nav item-->
            <li class="nav-item mx-lg-1">
                <a class="nav-link py-lg-6 text-active-primary py-3" href="#" data-bs-toggle="tab"
                    data-bs-target="#kt_app_header_menu_pages_utilities">
                    Status Dokumen </a>
            </li>
            <!--end:Nav item-->
            <!--begin:Nav item-->
            <li class="nav-item mx-lg-1">
                <a class="nav-link py-lg-6 text-active-primary py-3" href="#" data-bs-toggle="tab"
                    data-bs-target="#kt_app_header_menu_pages_widgets">
                    Lain </a>
            </li>
            <!--end:Nav item-->
        </ul>
    </div>
    <!--end:Tabs nav-->
    <!--begin:Tab content-->
    <div class="tab-content py-lg-8 px-lg-7 py-4">
        <!--begin:Tab pane-->
        <div class="tab-pane active w-lg-1000px" id="kt_app_header_menu_transaksi_order">
            @include('templatev2.partials.header._menu.__transaksi-order')
        </div>
        <!--end:Tab pane-->
        <!--begin:Tab pane-->
        <div class="tab-pane w-lg-600px" id="kt_app_header_menu_transaksi_dm">
            <!--layout-partial:layout/partials/header/_menu/__pages-account.html-->
            @include('templatev2.partials.header._menu.__pages-account')
        </div>
        <!--end:Tab pane-->
        <!--begin:Tab pane-->
        <div class="tab-pane w-lg-1000px" id="kt_app_header_menu_pages_authentication">
            <!--layout-partial:layout/partials/header/_menu/__pages-authentication.html-->
            @include('templatev2.partials.header._menu.__pages-authentication')
        </div>
        <!--end:Tab pane-->
        <!--begin:Tab pane-->
        <div class="tab-pane w-lg-1000px" id="kt_app_header_menu_pages_utilities">
            <!--layout-partial:layout/partials/header/_menu/__pages-utilities.html-->
            @include('templatev2.partials.header._menu.__pages-utilities')
        </div>
        <!--end:Tab pane-->
        <!--begin:Tab pane-->
        <div class="tab-pane w-lg-500px" id="kt_app_header_menu_pages_widgets">
            <!--layout-partial:layout/partials/header/_menu/__pages-widgets.html-->
            @include('templatev2.partials.header._menu.__pages-widgets')
        </div>
        <!--end:Tab pane-->
    </div>
    <!--end:Tab content-->
</div>
<!--end:Pages menu-->
