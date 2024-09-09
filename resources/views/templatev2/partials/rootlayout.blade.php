<!DOCTYPE html>
<html lang="en">

<head>
    <base href="" />
    <title>LSJ Group -
        @if (isset($page_title))
            {{ $page_title }}
        @else
            Aman Terdepan Sampai Tujuan
        @endif
    </title>
    <meta charset="utf-8" />
    <meta name="description"
        content="LSJ Express Group adalah perusahaan ekspedisi yang berdiri sejak tahun 2012 dan telah memiliki cabang dihapir semua kota-kota besar di Indonesia" />
    <meta name="keywords" content="lsj, lsj express, cargo, ekspedisi" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="LSJ Express Group - Aman Terdepan Sampai Tujuan" />
    <meta property="og:url" content="https://app.lsjexpress.co.id" />
    <meta property="og:site_name" content="LSJ Express Group" />
    <link rel="canonical" href="https://lsjexpress.com" />
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets-templatev2/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets-templatev2/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets-templatev2/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-templatev2/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!};
        var TOKEN = "{{ csrf_token() }}";
    </script>

    <!--end::Global Stylesheets Bundle-->

    <!--begin::custom page styles-->
    @if (isset($page_plugin_style))
        @foreach ($page_plugin_style as $item)
            <link href="{{ asset($item) }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endif
    <!--end::custom page styles-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>

<!--begin::Body-->

<body id="kt_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
    data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true"
    data-kt-app-header-secondary-enabled="false" data-kt-app-toolbar-enabled="true" class="app-default">

    @include('templatev2.partials.theme-mode._init')
    @include('templatev2.partials._page-loader')

    {{-- @include('templatev2.partials.mainlayout') --}}
    @yield('app_container')

    @include('templatev2.partials._scrolltop')

    @include('templatev2.partials._modals')

    @include('templatev2.partials._drawers')

    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ asset('assets-templatev2/') }}";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets-templatev2/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets-templatev2/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets-templatev2/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <!--<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>-->
    <script src="{{ asset('assets-templatev2/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets-templatev2/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets-templatev2/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets-templatev2/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets-templatev2/js/custom/utilities/modals/users-search.js') }}"></script>
    <!--end::Custom Javascript-->

    <!--begin::custom page Javascript-->
    @if (isset($page_plugin_js))
        @foreach ($page_plugin_js as $item)
            <script type="text/javascript" src="{{ asset($item) }}"></script>
        @endforeach
    @endif
    <!--end::custom page Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
