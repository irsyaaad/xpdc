<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
    data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
    data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
    data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">

    @php
        $menu = \App\Models\Menu::getMenu()->where('menu.id_module', 7)->where('menu.level', 1)->get();
    @endphp
    <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
        data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        @foreach ($menu as $menu)
            <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                id="kt_app_header_menu" data-kt-menu="true">
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                    <span class="menu-link py-3">
                        <span class="menu-title">{{ $menu->nm_menu }}</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px"
                        style="">
                        <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible"
                            data-kt-menu-dismiss="true">
                            <div class="row">
                                <div class="col-lg-8 mb-3 mb-lg-0 py-3 px-3 py-lg-6 px-lg-6">
                                    <div class="row">
                                        @php
                                            $child = \App\Models\Menu::getMenu()
                                                ->where('menu.id_module', 7)
                                                ->where('menu.level', 2)
                                                ->where('menu.parent', $menu->id_menu)
                                                ->get();
                                            $icon = [
                                                'ki-outline ki-abstract-42 text-danger fs-1',
                                                'ki-outline ki-chart-simple text-dark fs-1',
                                                'ki-outline ki-abstract-44 text-info fs-1',
                                                'ki-outline ki-switch text-warning fs-1',
                                                'ki-outline ki-basket text-danger fs-1',
                                                'ki-outline ki-call text-primary fs-1',
                                            ];
                                        @endphp
                                        @foreach ($child as $item)
                                            <div class="col-lg-6 mb-3">
                                                <div class="menu-item p-0 m-0">
                                                    @if (isset($item->route))
                                                        <a href="{{ url($item->route) }}"
                                                            class="menu-link {{ $item->route == Request::segment(1) ? 'active' : '' }}">
                                                            <span
                                                                class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                                <i class="{{ $icon[array_rand($icon)] }}"></i>
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
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="separator separator-dashed mx-5 my-5"></div>

                                </div>
                                <div class="menu-more bg-light col-lg-4 py-3 px-3 py-lg-6 px-lg-6 rounded-end">
                                    <h4 class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">Menu Lainnya</h4>
                                    @php
                                        $menu_lain = \App\Models\Menu::getMenu()
                                            ->where('menu.id_module', 7)
                                            ->where('menu.level', 2)
                                            ->limit(6)
                                            ->get();
                                    @endphp
                                    @foreach ($menu_lain as $menu_lain)
                                        @if (isset($menu_lain->route))
                                            <div class="menu-item p-0 m-0">
                                                <a href="{{ url($menu_lain->route) }}" class="menu-link py-2">
                                                    <span
                                                        class="menu-title">{{ ucwords(strtolower($menu_lain->nm_menu)) }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
