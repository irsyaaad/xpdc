<!--begin::Header-->
<div id="kt_app_header" class="app-header">
    <!--begin::Header primary-->
    <div class="app-header-primary" data-kt-sticky="true" data-kt-sticky-name="app-header-primary-sticky"
        data-kt-sticky-offset="{default: 'false', lg: '300px'}">
        <!--begin::Header primary container-->
        <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
            id="kt_app_header_primary_container">
            <!--begin::Logo and search-->
            <div class="d-flex flex-grow-1 flex-lg-grow-0">
                <!--begin::Logo wrapper-->
                <div class="d-flex align-items-center me-7" id="kt_app_header_logo_wrapper">
                    <!--begin::Header toggle-->
                    <button
                        class="d-lg-none btn btn-icon btn-flex btn-color-gray-600 btn-active-color-primary w-35px h-35px ms-n2 me-2"
                        id="kt_app_header_menu_toggle">
                        <i class="ki-outline ki-abstract-14 fs-2"></i> </button>
                    <!--end::Header toggle-->
                    <!--begin::Logo-->
                    <a href="?page=index" class="d-flex align-items-center me-lg-20 me-5">
                        <img alt="Logo" src="{{ asset('assets-templatev2/media/logos/lsjlogo-small.svg') }}"
                            class="h-50px theme-light-show d-sm-none d-inline" />
                        <img alt="Logo" src="{{ asset('assets-templatev2/media/logos/lsjlogo-small-dark.svg') }}"
                            class="h-50px theme-dark-show d-sm-none d-inline" />
                        <img alt="Logo" src="{{ asset('assets-templatev2/media/logos/lsj-logo.svg') }}"
                            class="h-50px h-lg-55px theme-light-show d-none d-sm-inline" />
                        <img alt="Logo" src="{{ asset('assets-templatev2/media/logos/lsjlogo-dark.png') }}"
                            class="h-50px h-lg-55px theme-dark-show d-none d-sm-inline" />
                    </a>
                    <!--end::Logo-->
                </div>
                <!--end::Logo wrapper-->
                <!--layout-partial:layout/partials/header/__menu.html-->
                @if (strtolower(Session('role')['nm_role']) == 'asuransi')
                    @include('templatev2.partials/header/__menu-asuransi')
                @else
                    @include('templatev2.partials/header/__menu')
                @endif
            </div>
            <!--end::Logo and search-->
            <!--layout-partial:layout/partials/header/__topbar.html-->
            @include('templatev2.partials/header/__topbar')
        </div>
        <!--end::Header primary container-->
    </div>
    <!--end::Header primary-->
    <!--begin::Header secondary-->
    <div class="app-header-secondary" style="display: none">
        <!--begin::Header secondary container-->
        <div class="app-container container-xxl" id="kt_app_header_secondary_container">
            <!--begin::Wrapper slider-->
            <div class="app-header-slider d-flex flex-stack h-100">
                <!--begin::Slider button-->
                <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary ms-xxl-n18"
                    id="kt_app_header_slider_prev">
                    <i class="ki-outline ki-left-square fs-2x"></i> </button>
                <!--end::Slider button-->
                <!--begin::Header slider-->
                <div class="tns tns-fit w-100">
                    <!--begin::Slider-->
                    <div data-tns="true" data-tns-loop="false" data-tns-swipe-angle="false" data-tns-speed="2000"
                        data-tns-autoplay="true" data-tns-autoplay-timeout="10000" data-tns-controls="true"
                        data-tns-nav="false" data-tns-items="1" data-tns-gutter="0"
                        data-tns-responsive="{470: {items: 2}, 670: {items: 3, gutter: 15}, 992: {items: 5, gutter: 20}, 1300: {items: 6, gutter: 40}}"
                        data-tns-center="false" data-tns-dots="false" data-tns-prev-button="#kt_app_header_slider_prev"
                        data-tns-next-button="#kt_app_header_slider_next">
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #6441A5">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/twitch-2.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Twitch Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">4 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/activity"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #E34984">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/dribbble-2.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Dribbble Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">1 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #00BF6D">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/gab.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Gab Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">no active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/followers"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #001935">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/tumblr.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Tumblr Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">3 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #E60000">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/youtube-2.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Youtube Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">28 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/settings"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #0B66C3">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/linkedin-3.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">LinkedIn Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">no active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #001935">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/tumblr.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Tumblr Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">3 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #00BF6D">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/gab.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Gab Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">no active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/followers"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #001935">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/tumblr.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Tumblr Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">3 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #E60000">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/youtube-2.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Youtube Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">28 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/activity"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #E34984">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/dribbble-2.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Dribbble Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">1 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #2682ff">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/eolic-energy.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Eolic Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">no active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/followers"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #cfe2ff">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/vimeo.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Vimeo Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">3 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <a href="?page=pages/social/feeds"
                            class="parent-hover d-flex align-items-center flex-md-row-fluid py-lg-2 cursor-pointer px-0">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px symbol-lg-40px me-3">
                                <span class="symbol-label rounded-4" style="background-color: #f1aeb5">
                                    <img src="{{ asset('assets-templatev2/media/svg/brand-logos/foursquare.svg') }}"
                                        class="mw-25px" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fw-bold parent-hover-primary fs-5 mb-1 text-gray-800">Foursquare
                                    Ads</span>
                                <span class="fw-semibold d-block fs-7 text-gray-500">5 active campaings</span>
                            </div>
                            <!--end::Info-->
                        </a>
                        <!--end::Item-->
                    </div>
                    <!--end::Slider-->
                </div>
                <!--end::Header slider-->
                <!--begin::Slider button-->
                <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary me-xxl-n18"
                    id="kt_app_header_slider_next">
                    <i class="ki-outline ki-right-square fs-2x"></i> </button>
                <!--end::Slider button-->
            </div>
            <!--end::Wrapper slider-->
        </div>
        <!--end::Header secondary container-->
    </div>
    <!--end::Header secondary-->
</div>
<!--end::Header-->
