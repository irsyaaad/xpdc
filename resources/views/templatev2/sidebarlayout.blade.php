@extends('templatev2.partials.mainlayout')

@section('content_wrapper')
    <!--layout-partial:layout/partials/_sidebar.html-->
    @include('templatev2.partials._sidebar')
    <!--begin::Main-->
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--layout-partial:layout/partials/_content.html-->

            @include('templatev2.partials._toolbarcontent')

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">

                @yield('content')
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
        <!--layout-partial:layout/partials/_footer.html-->
        @include('templatev2.partials._footer')
    </div>
    <!--end:::Main-->
@endsection
