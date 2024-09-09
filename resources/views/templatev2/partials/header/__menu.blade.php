<!--begin::Menu wrapper-->
<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
    data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
    data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
    data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
    <!--begin::Menu-->
    @php
        $modules = \App\Models\Module::all();
        $level1 = \App\Models\Menu::where('level', 1)->get();
        $level2 = \App\Models\Menu::where('level', 2)->get();
        $level3 = \App\Models\Menu::where('level', 3)->get();

        $menu = [];
        $menu_2 = [];
        $menu_3 = [];
        foreach ($level1 as $value) {
            $menu[$value->id_module][] = $value;
        }
        foreach ($level2 as $value) {
            $menu_2[$value->parent][] = $value;
        }
        foreach ($level3 as $value) {
            $menu_3[$value->parent][] = $value;
        }

        // dd($modules, $menu, $menu_2, $menu_3);

    @endphp
    <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
        data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
            id="kt_app_header_menu" data-kt-menu="true">
            @if (strtolower(Session('role')['nm_role']) != 'asuransi')
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item here menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Administrator</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px"
                        style="">
                        <!--begin:Dashboards menu-->
                        <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible"
                            data-kt-menu-dismiss="true">
                            <!--begin:Row-->
                            <div class="row">
                                <!--begin:Col-->
                                <div class="col-lg-8 mb-3 mb-lg-0 py-3 px-3 py-lg-6 px-lg-6">
                                    <!--begin:Row-->
                                    <div class="row">
                                        @php
                                            $menu = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 1)
                                                ->where('menu.level', 1)
                                                ->get();
                                        @endphp
                                        @foreach ($menu as $item)
                                            <!--begin:Col-->
                                            <div class="col-lg-6 mb-3">
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    @if (isset($item->route))
                                                        <a href="{{ url($item->route) }}"
                                                            class="menu-link {{ $item->route == Request::segment(1) ? 'active' : '' }}">
                                                            <span
                                                                class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                                <i
                                                                    class="ki-outline ki-element-11 text-primary fs-1"></i>
                                                            </span>
                                                            <span class="d-flex flex-column">
                                                                <span
                                                                    class="fs-6 fw-bold text-gray-800">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                                <span class="fs-7 fw-semibold text-muted">Untuk Mengatur
                                                                    {{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </span>
                                                        </a>
                                                    @endif
                                                </div>
                                                <!--end:Menu item-->
                                            </div>
                                            <!--end:Col-->
                                        @endforeach
                                    </div>
                                    <!--end:Row-->
                                    <div class="separator separator-dashed mx-5 my-5"></div>
                                    <!--begin:Landing-->
                                    <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-2 mx-5">
                                        <div class="d-flex flex-column me-5">
                                            <div class="fs-6 fw-bold text-gray-800">Landing Page Template</div>
                                            <div class="fs-7 fw-semibold text-muted">Onpe page landing template with
                                                pricing
                                                &amp; others</div>
                                        </div>
                                        <a href="../../demo35/dist/landing.html"
                                            class="btn btn-sm btn-primary fw-bold">Explore</a>
                                    </div>
                                    <!--end:Landing-->
                                </div>
                                <!--end:Col-->
                                <!--begin:Col-->
                                <div class="menu-more bg-light col-lg-4 py-3 px-3 py-lg-6 px-lg-6 rounded-end">
                                    <!--begin:Heading-->
                                    <h4 class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">Menu Lainnya</h4>
                                    <!--end:Heading-->
                                    @php
                                        $menu = \App\Models\Menu::getMenu()
                                            ->where('menu.id_module', 1)
                                            ->where('menu.level', 2)
                                            ->get();
                                    @endphp
                                    @foreach ($menu as $item)
                                        @if (isset($item->route))
                                            <div class="menu-item p-0 m-0">
                                                <!--begin:Menu link-->
                                                <a href="{{ url($item->route) }}" class="menu-link py-2">
                                                    <span
                                                        class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                </a>
                                                <!--end:Menu link-->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!--end:Col-->
                            </div>
                            <!--end:Row-->
                        </div>
                        <!--end:Dashboards menu-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    data-kt-menu-offset="-200,0" class="menu-item menu-lg-down-accordion me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Operasional</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0" style="">
                        <!--begin:Pages menu-->
                        <div class="menu-active-bg px-4 px-lg-0">
                            <!--begin:Tabs nav-->
                            <div class="d-flex w-100 overflow-auto">
                                <ul class="nav nav-stretch nav-line-tabs fw-bold fs-6 p-0 p-lg-10 flex-nowrap flex-grow-1"
                                    role="tablist">
                                    @php
                                        $menu = \App\Models\Menu::getMenu()
                                            ->where('menu.id_module', 2)
                                            ->where('menu.level', 1)
                                            ->get();
                                    @endphp
                                    @foreach ($menu as $item)
                                        <li class="nav-item mx-lg-1" role="presentation">
                                            <a class="nav-link py-3 py-lg-6 {{ $item->id_menu == 20 ? '' : '' }} text-active-primary"
                                                href="#" data-bs-toggle="tab"
                                                data-bs-target="#{{ $item->id_menu }}" aria-selected="true"
                                                role="tab">{{ ucwords(strtolower($item->nm_menu)) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!--end:Tabs nav-->
                            <!--begin:Tab content-->
                            <div class="tab-content py-4 py-lg-8 px-lg-7">
                                <!--begin:Tab pane-->
                                <div class="tab-pane w-lg-1000px" id="20" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-8">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 20)
                                                        ->get();
                                                @endphp
                                                @foreach ($menu as $item)
                                                    <div class="col-lg-3 mb-6 mb-lg-0">
                                                        <!--begin:Menu heading-->
                                                        <h4 class="fs-6 fs-lg-4 fw-bold mb-3 ms-4">
                                                            {{ ucwords(strtolower($item->nm_menu)) }}</h4>
                                                        <!--end:Menu heading-->
                                                        <!--begin:Menu item-->
                                                        @php
                                                            $menu2 = \App\Models\Menu::getMenu()
                                                                ->where('menu.id_module', 2)
                                                                ->where('menu.level', 3)
                                                                ->where('menu.parent', $item->id_menu)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($menu2 as $item2)
                                                            <div class="menu-item p-0 m-0">
                                                                <!--begin:Menu link-->
                                                                <a href="{{ url($item2->route) }}" class="menu-link">
                                                                    <span
                                                                        class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-4">
                                            <img src="{{ asset('assets-templatev2/media/stock/600x600/img-82.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="14" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 38)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ url($item->route) }}" class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="38" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 38)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ url($item->route) }}" class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="64" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 64)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ url($item->route) }}" class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="172" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 172)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ url($item->route) }}" class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="174" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_module', 2)
                                                        ->where('menu.level', 2)
                                                        ->where('menu.parent', 174)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ isset($item->route) ? url($item->route) : url('/') }}"
                                                                class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <div class="tab-pane w-lg-600px" id="300" role="tabpanel">
                                    <!--begin:Row-->
                                    <div class="row">
                                        <!--begin:Col-->
                                        <div class="col-lg-5 mb-6 mb-lg-0">
                                            <!--begin:Row-->
                                            <div class="row">
                                                @php
                                                    $menu = $menu = \App\Models\Menu::getMenu()
                                                        ->where('menu.id_menu', 300)
                                                        ->get();
                                                @endphp
                                                <!--begin:Col-->
                                                <div class="col-lg-12">
                                                    <!--begin:Menu item-->
                                                    @foreach ($menu as $item)
                                                        <div class="menu-item p-0 m-0">
                                                            <!--begin:Menu link-->
                                                            <a href="{{ isset($item->route) ? url($item->route) : url('/') }}"
                                                                class="menu-link">
                                                                <span
                                                                    class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!--end:Col-->
                                            </div>
                                            <!--end:Row-->
                                        </div>
                                        <!--end:Col-->
                                        <!--begin:Col-->
                                        <div class="col-lg-7">
                                            <img src="{{ asset('assets-templatev2/media/stock/900x600/46.jpg') }}"
                                                class="rounded mw-100" alt="">
                                        </div>
                                        <!--end:Col-->
                                    </div>
                                    <!--end:Row-->
                                </div>
                                <!--end:Tab pane-->
                            </div>
                            <!--end:Tab content-->
                        </div>
                        <!--end:Pages menu-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Keuangan</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px"
                        style="">
                        @php
                            $menu = \App\Models\Menu::getMenu()
                                ->where('menu.id_module', 3)
                                ->where('menu.level', 1)
                                ->get();
                            $icon = [
                                'ki-outline ki-calendar-8 fs-2',
                                'ki-outline ki-rocket fs-2',
                                'ki-outline ki-handcart fs-2',
                                'ki-outline ki-chart fs-2',
                                'ki-outline ki-shield-tick fs-2',
                                'ki-outline ki-phone fs-2',
                                'ki-outline ki-basket fs-2',
                                'ki-outline ki-briefcase fs-2',
                                'ki-outline ki-credit-cart fs-2',
                                'ki-outline ki-file-added fs-2',
                                'ki-outline ki-sms fs-2',
                                'ki-outline ki-message-text-2 fs-2',
                                'ki-outline ki-calendar-8 fs-2',
                            ];
                        @endphp
                        <!--begin:Menu item-->
                        @foreach ($menu as $item)
                            @if (isset($item->route))
                                <a href="{{ url($item->route) }}">
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                </a>
                            @else
                                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                    data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                    <!--begin:Menu link-->
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                                        <!--begin:Menu item-->
                                        @php
                                            $menu2 = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 3)
                                                ->where('menu.level', 2)
                                                ->where('menu.parent', $item->id_menu)
                                                ->get();
                                        @endphp
                                        @foreach ($menu2 as $item2)
                                            @if (isset($item2->route))
                                                <div class="menu-item">
                                                    <!--begin:Menu link-->
                                                    <a class="menu-link py-3" href="{{ url($item2->route) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                            @else
                                                <div data-kt-menu-trigger="click"
                                                    class="menu-item menu-accordion menu-sub-indention">
                                                    <!--begin:Menu link-->
                                                    <span class="menu-link py-3">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                    <!--end:Menu link-->
                                                    <!--begin:Menu sub-->
                                                    <div class="menu-sub menu-sub-accordion">
                                                        @php
                                                            $menu3 = \App\Models\Menu::getMenu()
                                                                ->where('menu.id_module', 3)
                                                                ->where('menu.level', 3)
                                                                ->where('menu.parent', $item2->id_menu)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($menu3 as $item3)
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link py-3"
                                                                    href="{{ isset($item3->route) ? url($item3->route) : url('/') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span
                                                                        class="menu-title">{{ ucwords(strtolower($item3->nm_menu)) }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        @endforeach
                                                    </div>
                                                    <!--end:Menu sub-->
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                            @endif
                        @endforeach
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Kepegawaian</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px"
                        style="">
                        @php
                            $menu = \App\Models\Menu::getMenu()
                                ->where('menu.id_module', 4)
                                ->where('menu.level', 1)
                                ->get();
                            $icon = [
                                'ki-outline ki-calendar-8 fs-2',
                                'ki-outline ki-rocket fs-2',
                                'ki-outline ki-handcart fs-2',
                                'ki-outline ki-chart fs-2',
                                'ki-outline ki-shield-tick fs-2',
                                'ki-outline ki-phone fs-2',
                                'ki-outline ki-basket fs-2',
                                'ki-outline ki-briefcase fs-2',
                                'ki-outline ki-credit-cart fs-2',
                                'ki-outline ki-file-added fs-2',
                                'ki-outline ki-sms fs-2',
                                'ki-outline ki-message-text-2 fs-2',
                                'ki-outline ki-calendar-8 fs-2',
                            ];
                        @endphp
                        <!--begin:Menu item-->
                        @foreach ($menu as $item)
                            @if (isset($item->route))
                                <a href="{{ url($item->route) }}">
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                </a>
                            @else
                                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                    data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                    <!--begin:Menu link-->
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                                        <!--begin:Menu item-->
                                        @php
                                            $menu2 = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 4)
                                                ->where('menu.level', 2)
                                                ->where('menu.parent', $item->id_menu)
                                                ->get();
                                        @endphp
                                        @foreach ($menu2 as $item2)
                                            @if (isset($item2->route))
                                                <div class="menu-item">
                                                    <!--begin:Menu link-->
                                                    <a class="menu-link py-3" href="{{ url($item2->route) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                            @else
                                                <div data-kt-menu-trigger="click"
                                                    class="menu-item menu-accordion menu-sub-indention">
                                                    <!--begin:Menu link-->
                                                    <span class="menu-link py-3">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                    <!--end:Menu link-->
                                                    <!--begin:Menu sub-->
                                                    <div class="menu-sub menu-sub-accordion">
                                                        @php
                                                            $menu3 = \App\Models\Menu::getMenu()
                                                                ->where('menu.id_module', 4)
                                                                ->where('menu.level', 3)
                                                                ->where('menu.parent', $item2->id_menu)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($menu3 as $item3)
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link py-3"
                                                                    href="{{ isset($item3->route) ? url($item3->route) : url('/') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span
                                                                        class="menu-title">{{ ucwords(strtolower($item3->nm_menu)) }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        @endforeach
                                                    </div>
                                                    <!--end:Menu sub-->
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                            @endif
                        @endforeach
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Laporan</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px"
                        style="">
                        @php
                            $menu = \App\Models\Menu::getMenu()
                                ->where('menu.id_module', 6)
                                ->where('menu.level', 1)
                                ->get();
                            $icon = [
                                'ki-outline ki-calendar-8 fs-2',
                                'ki-outline ki-rocket fs-2',
                                'ki-outline ki-handcart fs-2',
                                'ki-outline ki-chart fs-2',
                                'ki-outline ki-shield-tick fs-2',
                                'ki-outline ki-phone fs-2',
                                'ki-outline ki-basket fs-2',
                                'ki-outline ki-briefcase fs-2',
                                'ki-outline ki-credit-cart fs-2',
                                'ki-outline ki-file-added fs-2',
                                'ki-outline ki-sms fs-2',
                                'ki-outline ki-message-text-2 fs-2',
                                'ki-outline ki-calendar-8 fs-2',
                            ];
                        @endphp
                        <!--begin:Menu item-->
                        @foreach ($menu as $item)
                            @if (isset($item->route))
                                <a href="{{ url($item->route) }}">
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                </a>
                            @else
                                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                    data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                    <!--begin:Menu link-->
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                                        <!--begin:Menu item-->
                                        @php
                                            $menu2 = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 6)
                                                ->where('menu.level', 2)
                                                ->where('menu.parent', $item->id_menu)
                                                ->get();
                                        @endphp
                                        @foreach ($menu2 as $item2)
                                            @if (isset($item2->route))
                                                <div class="menu-item">
                                                    <!--begin:Menu link-->
                                                    <a class="menu-link py-3" href="{{ url($item2->route) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                            @else
                                                <div data-kt-menu-trigger="click"
                                                    class="menu-item menu-accordion menu-sub-indention">
                                                    <!--begin:Menu link-->
                                                    <span class="menu-link py-3">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                    <!--end:Menu link-->
                                                    <!--begin:Menu sub-->
                                                    <div class="menu-sub menu-sub-accordion">
                                                        @php
                                                            $menu3 = \App\Models\Menu::getMenu()
                                                                ->where('menu.id_module', 6)
                                                                ->where('menu.level', 3)
                                                                ->where('menu.parent', $item2->id_menu)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($menu3 as $item3)
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link py-3"
                                                                    href="{{ isset($item3->route) ? url($item3->route) : url('/') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span
                                                                        class="menu-title">{{ ucwords(strtolower($item3->nm_menu)) }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        @endforeach
                                                    </div>
                                                    <!--end:Menu sub-->
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                            @endif
                        @endforeach
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            @else
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link py-3">
                        <span class="menu-title">Asuransi</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px"
                        style="">
                        @php
                            $menu = \App\Models\Menu::getMenu()
                                ->where('menu.id_module', 7)
                                ->where('menu.level', 1)
                                ->get();
                            $icon = [
                                'ki-outline ki-calendar-8 fs-2',
                                'ki-outline ki-rocket fs-2',
                                'ki-outline ki-handcart fs-2',
                                'ki-outline ki-chart fs-2',
                                'ki-outline ki-shield-tick fs-2',
                                'ki-outline ki-phone fs-2',
                                'ki-outline ki-basket fs-2',
                                'ki-outline ki-briefcase fs-2',
                                'ki-outline ki-credit-cart fs-2',
                                'ki-outline ki-file-added fs-2',
                                'ki-outline ki-sms fs-2',
                                'ki-outline ki-message-text-2 fs-2',
                                'ki-outline ki-calendar-8 fs-2',
                            ];
                        @endphp
                        <!--begin:Menu item-->
                        @foreach ($menu as $item)
                            @if (isset($item->route))
                                <a href="{{ url($item->route) }}">
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                </a>
                            @else
                                <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                    data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                    <!--begin:Menu link-->
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <i class="{{ $icon[array_rand($icon)] }}"></i>
                                        </span>
                                        <span class="menu-title">{{ ucwords(strtolower($item->nm_menu)) }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                                        <!--begin:Menu item-->
                                        @php
                                            $menu2 = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 7)
                                                ->where('menu.level', 2)
                                                ->where('menu.parent', $item->id_menu)
                                                ->get();
                                        @endphp
                                        @foreach ($menu2 as $item2)
                                            @if (isset($item2->route))
                                                <div class="menu-item">
                                                    <!--begin:Menu link-->
                                                    <a class="menu-link py-3" href="{{ url($item2->route) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                            @else
                                                <div data-kt-menu-trigger="click"
                                                    class="menu-item menu-accordion menu-sub-indention">
                                                    <!--begin:Menu link-->
                                                    <span class="menu-link py-3">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span
                                                            class="menu-title">{{ ucwords(strtolower($item2->nm_menu)) }}</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                    <!--end:Menu link-->
                                                    <!--begin:Menu sub-->
                                                    <div class="menu-sub menu-sub-accordion">
                                                        @php
                                                            $menu3 = \App\Models\Menu::getMenu()
                                                                ->where('menu.id_module', 7)
                                                                ->where('menu.level', 3)
                                                                ->where('menu.parent', $item2->id_menu)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($menu3 as $item3)
                                                            <!--begin:Menu item-->
                                                            <div class="menu-item">
                                                                <!--begin:Menu link-->
                                                                <a class="menu-link py-3"
                                                                    href="{{ isset($item3->route) ? url($item3->route) : url('/') }}">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span
                                                                        class="menu-title">{{ ucwords(strtolower($item3->nm_menu)) }}</span>
                                                                </a>
                                                                <!--end:Menu link-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        @endforeach
                                                    </div>
                                                    <!--end:Menu sub-->
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                            @endif
                        @endforeach
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
            @endif

        </div>
        <!--end::Menu-->
    </div>
</div>
<!--end::Menu wrapper-->
