<!--begin::Modals-->
@if (isset($page_plugin_modal))
    @foreach ($page_plugin_modal as $item)
        @include($item)
    @endforeach
@endif
<!--end::Modals-->
