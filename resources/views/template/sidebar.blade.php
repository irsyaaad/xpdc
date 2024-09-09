@if (Request::segment(1) != '' and Auth::check())
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
            <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light "
                data-menu-vertical="true"data-menu-scrollable="false" data-menu-dropdown-timeout="500">
                <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                    @php
                    $modules = \App\Models\Module::all();
                    if (get_admin()) {
                        $modules = $modules;
                    } else {
                        $modules = $modules->where('id_module', '!=', 1);
                    }                    
                    @endphp

                    @foreach ($modules as $module)
                        @php
                            $level1 = \App\Models\Menu::getLevel($module->id_module, 1);
                            $level2 = \App\Models\Menu::getLevel($module->id_module, 2);
                            $level3 = \App\Models\Menu::getLevel($module->id_module, 3);
                            // dd($level1, $level2, $level3);
                        @endphp
                        @if (count($level1) > 0)
                            <li class="m-menu__section">
                                <h4 class="m-menu__section-text">
                                    {{ ucwords(strtolower($module->nm_module)) }}
                                </h4>
                                <i class="m-menu__section-icon flaticon-more-v3"></i>
                            </li>
                        @endif

                        @foreach ($level1 as $key => $value)
                            @if (strtolower($value->nm_menu) != 'dashboard')
                                <li class="m-menu__item  m-menu__item--submenu @if (Request::segment(1) == strtolower($value->route)) {{ 'm-menu__item--active' }} @endif"
                                    aria-haspopup="true" data-menu-submenu-toggle="hover"
                                    id="{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}">

                                    <a href="{{ url(strtolower($value->route)) }}" class="m-menu__link m-menu__toggle">
                                        <i class="m-menu__link-icon {{ $value->icon }}"></i>
                                        <span class="m-menu__link-text">
                                            {{ str_replace('_', ' ', strtoupper($value->nm_menu)) }}
                                        </span>
                                        <i class="m-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    @if (isset($level2[$value->id_menu]))
                                        {{-- <script type="text/javascript">
										$(document).ready(function () {

											$("#sub-{{ str_replace(" ", "", strtoupper($value->nm_menu)) }}").append('<li class="m-menu__item  m-menu__item--submenu @if (Request::segment(1) == strtolower($value->route)) {{ "m-menu__item--active" }} @endif" aria-haspopup="true"  data-menu-submenu-toggle="hover" id="{{ $value->route }}"><a  href="{{ url(strtolower($value->route)) }}" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon {{ $value->icon }}"></i><span class="m-menu__link-text">{{ str_replace("_", "", strtoupper($value->nm_menu)) }}</span><i class="m-menu__ver-arrow la la-angle-right"></i></a></li>');
										});
									</script> --}}

                                        @foreach ($level2[$value->id_menu] as $key1 => $value1)
                                            <div class="m-menu__submenu">
                                                <span class="m-menu__arrow"></span>
                                                <ul class="m-menu__subnav"
                                                    id="sub-{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}">

                                                    <li class="m-menu__item  m-menu__item--submenu @if (Request::segment(1) == strtolower($value1->route)) {{ 'm-menu__item--active' }} @endif"
                                                        aria-haspopup="true" data-menu-submenu-toggle="hover"
                                                        id="{{ str_replace(' ', '', strtoupper($value1->nm_menu)) }}">

                                                        <a href="{{ url(strtolower($value1->route)) }}"
                                                            class="m-menu__link m-menu__toggle">
                                                            <i class="m-menu__link-icon {{ $value1->icon }}"></i>
                                                            <span class="m-menu__link-text">
                                                                {{ str_replace('_', ' ', strtoupper($value1->nm_menu)) }}
                                                            </span>
                                                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                        </a>

                                                        @if (Request::segment(1) == strtolower($value1->route) or Request::segment(1) == strtolower($value->route))
                                                            <script type="text/javascript">
                                                                $(document).ready(function() {
                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--open");
                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--expanded");
                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--active");
                                                                });
                                                            </script>
                                                        @endif

                                                        @if (isset($level3[$value1->id_menu]))
                                                            <div class="m-menu__submenu">
                                                                <span class="m-menu__arrow"></span>
                                                                <ul class="m-menu__subnav"
                                                                    id="sub-{{ $value1->route }}">
                                                                    {{-- <script type="text/javascript">
																		$(document).ready(function () {
																			$("#sub-{{ str_replace(" ", "", strtoupper($value1->nm_menu)) }}").append('<li class="m-menu__item @if (Request::segment(1) == strtolower($value1->route)) {{ "m-menu__item--active" }} @endif"><a  href="{{ url(strtolower($value1->route)) }}" class="m-menu__link "><i class="m-menu__link-icon {{ $value1->icon }}"></i><span></span></i><span class="m-menu__link-text">{{ str_replace("_", " ", strtoupper($value1->route)) }}</span></a></li>');
																		});
																	</script> --}}
                                                                    @foreach ($level3[$value1->id_menu] as $key2 => $value2)
                                                                        @if (Request::segment(1) == strtolower($value2->route) or Request::segment(1) == strtolower($value1->route))
                                                                            <script type="text/javascript">
                                                                                $(document).ready(function() {

                                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--open");
                                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--expanded");

                                                                                    $("#{{ str_replace(' ', '', strtoupper($value1->nm_menu)) }}").addClass("m-menu__item--open");
                                                                                    $("#{{ str_replace(' ', '', strtoupper($value1->nm_menu)) }}").addClass("m-menu__item--expanded");

                                                                                    $("#{{ str_replace(' ', '', strtoupper($value->nm_menu)) }}").addClass("m-menu__item--active");
                                                                                    $("#{{ str_replace(' ', '', strtoupper($value1->nm_menu)) }}").addClass("m-menu__item--active");
                                                                                });
                                                                            </script>
                                                                        @endif

                                                                        <li
                                                                            class="m-menu__item @if (Request::segment(1) == strtolower($value2->route)) {{ 'm-menu__item--active' }} @endif">
                                                                            <a href="{{ url(strtolower($value2->route)) }}"
                                                                                class="m-menu__link ">
                                                                                <i
                                                                                    class="m-menu__link-icon {{ $value2->icon }}"></i>
                                                                                <span></span>
                                                                                </i>
                                                                                <span class="m-menu__link-text">
                                                                                    {{ str_replace('_', ' ', strtoupper($value2->nm_menu)) }}
                                                                                </span>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
@endif
