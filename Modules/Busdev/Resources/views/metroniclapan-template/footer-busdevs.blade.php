 <!--begin::Footer-->
 <div class="footer py-4 d-flex flex-column flex-md-row flex-stack" id="kt_footer">
     <!--begin::Copyright-->
     <div class="text-dark order-2 order-md-1">
         <span class="text-muted fw-semibold me-1">{{ date('Y') }}</span>
         <a href="https://lsjexpress.com/" target="_blank" class="text-gray-800 text-hover-primary">Copyright © Lintas
             Samudra Jaya All rights reserved</a>
     </div>
     <!--end::Copyright-->
     <!--begin::Menu-->
     <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
         <li class="menu-item">
             <a href="https://lsjexpress.com/" target="_blank" class="menu-link px-2">About</a>
         </li>
     </ul>
     <!--end::Menu-->
 </div>
 <!--end::Footer-->
 </div>
 <!--end::Container-->
 </div>
 <!--end::Content wrapper-->
 </div>
 <!--end::Wrapper-->
 </div>
 <!--end::Page-->
 </div>
 <!--end::Root-->
 <!--end::Main-->
 <!--begin::Scrolltop-->
 <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
     <i class="ki-duotone ki-arrow-up">
         <span class="path1"></span>
         <span class="path2"></span>
     </i>
 </div>
 <!--end::Scrolltop-->
 <!--begin::Theme mode-->
 <div class="d-flex align-items-center ms-1">
     <!--begin::Menu toggle-->
     <a href="#"
         class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-30px h-30px h-40px w-40px"
         data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
         data-kt-menu-placement="bottom-end">
         <i class="ki-duotone ki-night-day theme-light-show fs-1">
             <span class="path1"></span>
             <span class="path2"></span>
             <span class="path3"></span>
             <span class="path4"></span>
             <span class="path5"></span>
             <span class="path6"></span>
             <span class="path7"></span>
             <span class="path8"></span>
             <span class="path9"></span>
             <span class="path10"></span>
         </i>
         <i class="ki-duotone ki-moon theme-dark-show fs-1">
             <span class="path1"></span>
             <span class="path2"></span>
         </i>
     </a>
     <!--begin::Menu toggle-->
     <!--begin::Menu-->
     <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
         data-kt-menu="true" data-kt-element="theme-mode-menu">
         <!--begin::Menu item-->
         <div class="menu-item px-3 my-0">
             <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                 <span class="menu-icon" data-kt-element="icon">
                     <i class="ki-duotone ki-night-day fs-2">
                         <span class="path1"></span>
                         <span class="path2"></span>
                         <span class="path3"></span>
                         <span class="path4"></span>
                         <span class="path5"></span>
                         <span class="path6"></span>
                         <span class="path7"></span>
                         <span class="path8"></span>
                         <span class="path9"></span>
                         <span class="path10"></span>
                     </i>
                 </span>
                 <span class="menu-title">Light</span>
             </a>
         </div>
         <!--end::Menu item-->
         <!--begin::Menu item-->
         <div class="menu-item px-3 my-0">
             <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                 <span class="menu-icon" data-kt-element="icon">
                     <i class="ki-duotone ki-moon fs-2">
                         <span class="path1"></span>
                         <span class="path2"></span>
                     </i>
                 </span>
                 <span class="menu-title">Dark</span>
             </a>
         </div>
         <!--end::Menu item-->
         <!--begin::Menu item-->
         <div class="menu-item px-3 my-0">
             <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                 <span class="menu-icon" data-kt-element="icon">
                     <i class="ki-duotone ki-screen fs-2">
                         <span class="path1"></span>
                         <span class="path2"></span>
                         <span class="path3"></span>
                         <span class="path4"></span>
                     </i>
                 </span>
                 <span class="menu-title">System</span>
             </a>
         </div>
         <!--end::Menu item-->
     </div>
     <!--end::Menu-->
 </div>
 <!--end::Theme mode-->
 <!--begin::Vendors Javascript(used for this page only)-->
 <script>
    $(document).ready(function() {
        $("#kt_datatable_dom_positioning").DataTable({
            "language": {
                "lengthMenu": "Show _MENU_",
            },
            "dom": "<'row mb-2'" +
                "<'col-sm-6 d-flex align-items-center justify-content-start dt-toolbar'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });
    });
</script>
 <script src="{{ url('assets-busdevmarketing/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
 <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
 <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
 <script src="assets-busdevmarketing/plugins/custom/datatables/datatables.bundle.js"></script>
 <!--end::Vendors Javascript-->
 <!--begin::Custom Javascript(used for this page only)-->
 <script src="{{ url('assets-busdevmarketing/js/widgets.bundle.js') }}"></script>
 <script src="{{ url('assets-busdevmarketing/js/custom/widgets.js') }}"></script>
 <script src="{{ url('assets-busdevmarketing/js/custom/apps/chat/chat.js') }}"></script>
 <script src="{{ url('assets-busdevmarketing/js/custom/utilities/modals/create-app.js') }}"></script>
 <script src="{{ url('assets-busdevmarketing/js/custom/utilities/modals/create-campaign.js') }}"></script>
 <script src="{{ url('assets-busdevmarketing/js/custom/utilities/modals/users-search.js') }}"></script>
 <!--end::Custom Javascript-->
 <script>
     $(".submit").click(function(e) {
         $(this).attr("data-kt-indicator", "on");
         $(this).addClass('disabled');
     });

     //  function loadFunction() {
     //      console.log('a');
     //      // Populate the page loading element dynamically.
     //      // Optionally you can skipt this part and place the HTML
     //      // code in the body element by refer to the above HTML code tab.
     //      const loadingEl = document.createElement("div");
     //      document.body.prepend(loadingEl);
     //      loadingEl.classList.add("page-loader");
     //      loadingEl.classList.add("flex-column");
     //      loadingEl.classList.add("bg-dark");
     //      loadingEl.classList.add("bg-opacity-25");
     //      loadingEl.innerHTML = `
    //     <span class="spinner-border text-primary" role="status"></span>
    //     <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
    // `;

     //      // Show page loading
     //      KTApp.showPageLoading();
 </script>
 </body>
 <!--end::Body-->

 </html>
