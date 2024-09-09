<!--begin::Drawers-->
@if (isset($page_plugin_drawer))
    @foreach ($page_plugin_drawer as $item)
        @include($item)
    @endforeach
@endif
<!--layout-partial:partials/drawers/_activity-drawer.html-->
{{-- @include('templatev2.partials.drawers._activity-drawer') --}}
<!--layout-partial:partials/drawers/_chat-messenger.html-->

<!--layout-partial:partials/drawers/_shopping-cart.html-->
{{-- @include('templatev2.partials.drawers._shopping-cart') --}}
<!--end::Drawers-->
