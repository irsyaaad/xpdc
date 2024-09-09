{{-- @extends('template.document2') --}}

@section('data')
@endsection
<!--begin::Post-->
<div class="content flex-column-fluid" id="kt_content">
    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::Mixed Widget 18-->
            <div class="card h-md-100">
                <!--begin::Beader-->
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Weekly Sales Stats</span>
                        <span class="text-muted fw-semibold fs-7">890,344 Sales</span>
                    </h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_641ac650e72ea">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Menu separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Menu separator-->
                            <!--begin::Form-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" data-kt-select2="true"
                                            data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_641ac650e72ea" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Member Type:</label>
                                    <!--end::Label-->
                                    <!--begin::Options-->
                                    <div class="d-flex">
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                            <span class="form-check-label">Author</span>
                                        </label>
                                        <!--end::Options-->
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="2"
                                                checked="checked" />
                                            <span class="form-check-label">Customer</span>
                                        </label>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Notifications:</label>
                                    <!--end::Label-->
                                    <!--begin::Switch-->
                                    <div
                                        class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="notifications" checked="checked" />
                                        <label class="form-check-label">Enabled</label>
                                    </div>
                                    <!--end::Switch-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body p-0 d-flex flex-column">
                    <!--begin::Items-->
                    <div class="card-px pt-5 pb-10 flex-grow-1">
                        <!--begin::Item-->
                        <div class="d-flex align-items-sm-center mb-7">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label">
                                    <img src="assets-busdevmarketing/media/svg/brand-logos/plurk.svg"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Top
                                        Authors</a>
                                    <span class="text-muted fw-semibold d-block fs-7">Mark, Rowling, Esther</span>
                                </div>
                                <span class="badge badge-light fw-bold my-2">+82$</span>
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-sm-center mb-7">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label">
                                    <img src="assets-busdevmarketing/media/svg/brand-logos/telegram.svg"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Popular
                                        Authors</a>
                                    <span class="text-muted fw-semibold d-block fs-7">Randy, Steve, Mike</span>
                                </div>
                                <span class="badge badge-light fw-bold my-2">+280$</span>
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-sm-center mb-7">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label">
                                    <img src="assets-busdevmarketing/media/svg/brand-logos/vimeo.svg"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">New
                                        Users</a>
                                    <span class="text-muted fw-semibold d-block fs-7">John, Pat, Jimmy</span>
                                </div>
                                <span class="badge badge-light fw-bold my-2">+4500$</span>
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-sm-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label">
                                    <img src="assets-busdevmarketing/media/svg/brand-logos/bebo.svg"
                                        class="h-50 align-self-center" alt="" />
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Section-->
                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Active
                                        Customers</a>
                                    <span class="text-muted fw-semibold d-block fs-7">Mark, Rowling, Esther</span>
                                </div>
                                <span class="badge badge-light fw-bold my-2">+686$</span>
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Items-->
                    <!--begin::Chart-->
                    <div id="kt_charts_mixed_widget_18_chart" class="card-rounded-bottom" style="height: 150px">
                    </div>
                    <!--end::Chart-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 18-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8">
            <!--begin::Row-->
            <div class="row g-5 g-lg-10">
                <!--begin::Col-->
                <div class="col-lg-6 mb-5 mb-lg-10">
                    <!--begin::Tiles Widget 1-->
                    <div class="card h-150px bgi-no-repeat bgi-size-cover h-150px mb-5 mb-lg-10"
                        style="background-image:url('assets-busdevmarketing/media/stock/600x600/img-12.jpg')">
                        <!--begin::Body-->
                        <div class="card-body p-6">
                            <!--begin::Title-->
                            <a href="#" class="text-black text-hover-primary fw-bold fs-2"
                                data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Roofing</a>
                            <!--end::Title-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Tiles Widget 1-->
                    <!--begin::Tiles Widget 5-->
                    <a href="#" class="card bg-body h-150px">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <i class="ki-duotone ki-element-11 text-dark fs-2hx ms-n1 flex-grow-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <div class="text-dark fw-bold fs-1 mb-0 mt-5">8,600</div>
                                <div class="text-muted fw-semibold fs-6">New Customers</div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Tiles Widget 5-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-lg-6 mb-5 mb-lg-10">
                    <!--begin::Mixed Widget 3-->
                    <div class="card h-100 bgi-no-repeat bgi-size-cover h-lg-100"
                        style="background-image:url('assets-busdevmarketing/media/misc/bg-2.jpg')">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <!--begin::Title-->
                            <div class="text-white fw-bold fs-2">
                                <h2 class="fw-bold text-white mb-2">Create Reports</h2>With App
                            </div>
                            <!--end::Title-->
                            <!--begin::Link-->
                            <a href='#' class="text-warning fw-semibold" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_create_campaign">Create Report
                                <i class="ki-duotone ki-arrow-right fs-2 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i></a>
                            <!--end::Link-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 3-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Tiles Widget 2-->
            <div class="card h-175px bgi-no-repeat bgi-size-contain h-200px mb-5 mb-lg-10"
                style="background-color: #1B283F; background-position: right; background-image:url('assets-busdevmarketing/media/svg/misc/taieri.svg')">
                <!--begin::Body-->
                <div class="card-body d-flex flex-column justify-content-between">
                    <!--begin::Title-->
                    <h2 class="text-white fw-bold mb-5">
                        <span class="lh-lg">Brilliant Ideas
                            <br />for Your Web Application</span>
                    </h2>
                    <!--end::Title-->
                    <!--begin::Action-->
                    <div class="m-0">
                        <a href='#' class="btn btn-danger fw-semibold px-6 py-3" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_create_campaign">Create Campaign</a>
                    </div>
                    <!--begin::Action-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Tiles Widget 2-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 3-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold text-dark">Todo</h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--begin::Menu 3-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                            data-kt-menu="true">
                            <!--begin::Heading-->
                            <div class="menu-item px-3">
                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Create Invoice</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link flex-stack px-3">Create Payment
                                    <span class="ms-2" data-bs-toggle="tooltip"
                                        title="Specify a target name for future usage and reference">
                                        <i class="ki-duotone ki-information fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Generate Bill</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                data-kt-menu-placement="right-end">
                                <a href="#" class="menu-link px-3">
                                    <span class="menu-title">Subscription</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <!--begin::Menu sub-->
                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Plans</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Billing</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Statements</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content px-3">
                                            <!--begin::Switch-->
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <!--begin::Input-->
                                                <input class="form-check-input w-30px h-20px" type="checkbox"
                                                    value="1" checked="checked" name="notifications" />
                                                <!--end::Input-->
                                                <!--end::Label-->
                                                <span class="form-check-label text-muted fs-6">Recuring</span>
                                                <!--end::Label-->
                                            </label>
                                            <!--end::Switch-->
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu sub-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-1">
                                <a href="#" class="menu-link px-3">Settings</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu 3-->
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-2">
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-8">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-success"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Create FireStone
                                Logo</a>
                            <span class="text-muted fw-semibold d-block">Due in 2 Days</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-success fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-8">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-primary"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Stakeholder
                                Meeting</a>
                            <span class="text-muted fw-semibold d-block">Due in 3 Days</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-primary fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-8">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-warning"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Scoping &
                                Estimations</a>
                            <span class="text-muted fw-semibold d-block">Due in 5 Days</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-warning fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-8">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-primary"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">KPI App
                                Showcase</a>
                            <span class="text-muted fw-semibold d-block">Due in 2 Days</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-primary fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-8">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-danger"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Project
                                Meeting</a>
                            <span class="text-muted fw-semibold d-block">Due in 12 Days</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-danger fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center">
                        <!--begin::Bullet-->
                        <span class="bullet bullet-vertical h-40px bg-success"></span>
                        <!--end::Bullet-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid mx-5">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Customers
                                Update</a>
                            <span class="text-muted fw-semibold d-block">Due in 1 week</span>
                        </div>
                        <!--end::Description-->
                        <span class="badge badge-light-success fs-8 fw-bold">New</span>
                    </div>
                    <!--end:Item-->
                </div>
                <!--end::Body-->
            </div>
            <!--end:List Widget 3-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 5-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header align-items-center border-0 mt-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="fw-bold mb-2 text-dark">Activities</span>
                        <span class="text-muted fw-semibold fs-7">890,344 Sales</span>
                    </h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_641ac650e9946">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Menu separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Menu separator-->
                            <!--begin::Form-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" data-kt-select2="true"
                                            data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_641ac650e9946" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Member Type:</label>
                                    <!--end::Label-->
                                    <!--begin::Options-->
                                    <div class="d-flex">
                                        <!--begin::Options-->
                                        <label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                            <span class="form-check-label">Author</span>
                                        </label>
                                        <!--end::Options-->
                                        <!--begin::Options-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="2"
                                                checked="checked" />
                                            <span class="form-check-label">Customer</span>
                                        </label>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Notifications:</label>
                                    <!--end::Label-->
                                    <!--begin::Switch-->
                                    <div
                                        class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="notifications" checked="checked" />
                                        <label class="form-check-label">Enabled</label>
                                    </div>
                                    <!--end::Switch-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Reset</button>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-5">
                    <!--begin::Timeline-->
                    <div class="timeline-label">
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">08:42</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-warning fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="fw-mormal timeline-content text-muted ps-3">Outlines keep you honest. And keep
                                structure</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">10:00</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-success fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Content-->
                            <div class="timeline-content d-flex">
                                <span class="fw-bold text-gray-800 ps-3">AEOL meeting</span>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">14:37</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-danger fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Desc-->
                            <div class="timeline-content fw-bold text-gray-800 ps-3">Make deposit
                                <a href="#" class="text-primary">USD 700</a>. to ESL
                            </div>
                            <!--end::Desc-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-primary fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and
                                keep structure keep great</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-danger fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Desc-->
                            <div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
                                <a href="#" class="text-primary">#XF-2356</a>.
                            </div>
                            <!--end::Desc-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-primary fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and
                                keep structure keep great</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-danger fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Desc-->
                            <div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
                                <a href="#" class="text-primary">#XF-2356</a>.
                            </div>
                            <!--end::Desc-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="timeline-item">
                            <!--begin::Label-->
                            <div class="timeline-label fw-bold text-gray-800 fs-6">10:30</div>
                            <!--end::Label-->
                            <!--begin::Badge-->
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-success fs-1"></i>
                            </div>
                            <!--end::Badge-->
                            <!--begin::Text-->
                            <div class="timeline-content fw-mormal text-muted ps-3">Finance KPI Mobile app launch
                                preparion meeting</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Timeline-->
                </div>
                <!--end: Card Body-->
            </div>
            <!--end: List Widget 5-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-4 mb-xl-10">
            <!--begin::List Widget 6-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold text-dark">Notifications</h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--begin::Menu 3-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                            data-kt-menu="true">
                            <!--begin::Heading-->
                            <div class="menu-item px-3">
                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Create Invoice</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link flex-stack px-3">Create Payment
                                    <span class="ms-2" data-bs-toggle="tooltip"
                                        title="Specify a target name for future usage and reference">
                                        <i class="ki-duotone ki-information fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Generate Bill</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                data-kt-menu-placement="right-end">
                                <a href="#" class="menu-link px-3">
                                    <span class="menu-title">Subscription</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <!--begin::Menu sub-->
                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Plans</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Billing</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Statements</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content px-3">
                                            <!--begin::Switch-->
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <!--begin::Input-->
                                                <input class="form-check-input w-30px h-20px" type="checkbox"
                                                    value="1" checked="checked" name="notifications" />
                                                <!--end::Input-->
                                                <!--end::Label-->
                                                <span class="form-check-label text-muted fs-6">Recuring</span>
                                                <!--end::Label-->
                                            </label>
                                            <!--end::Switch-->
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu sub-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-1">
                                <a href="#" class="menu-link px-3">Settings</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu 3-->
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-0">
                    <!--begin::Item-->
                    <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                        <i class="ki-duotone ki-abstract-26 text-warning fs-1 me-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--begin::Title-->
                        <div class="flex-grow-1 me-2">
                            <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6">Group lunch
                                celebration</a>
                            <span class="text-muted fw-semibold d-block">Due in 2 Days</span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Lable-->
                        <span class="fw-bold text-warning py-1">+28%</span>
                        <!--end::Lable-->
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center bg-light-success rounded p-5 mb-7">
                        <i class="ki-duotone ki-abstract-26 text-success fs-1 me-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--begin::Title-->
                        <div class="flex-grow-1 me-2">
                            <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6">Navigation
                                optimization</a>
                            <span class="text-muted fw-semibold d-block">Due in 2 Days</span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Lable-->
                        <span class="fw-bold text-success py-1">+50%</span>
                        <!--end::Lable-->
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-7">
                        <i class="ki-duotone ki-abstract-26 text-danger fs-1 me-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--begin::Title-->
                        <div class="flex-grow-1 me-2">
                            <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6">Rebrand strategy
                                planning</a>
                            <span class="text-muted fw-semibold d-block">Due in 5 Days</span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Lable-->
                        <span class="fw-bold text-danger py-1">-27%</span>
                        <!--end::Lable-->
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center bg-light-info rounded p-5">
                        <i class="ki-duotone ki-abstract-26 text-info fs-1 me-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--begin::Title-->
                        <div class="flex-grow-1 me-2">
                            <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6">Product goals
                                strategy</a>
                            <span class="text-muted fw-semibold d-block">Due in 7 Days</span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Lable-->
                        <span class="fw-bold text-info py-1">+8%</span>
                        <!--end::Lable-->
                    </div>
                    <!--end::Item-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 6-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-lg-10">
        <!--begin::Col-->
        <div class="col-xl-6 mb-5 mb-xl-10">
            <!--begin::Tables Widget 3-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Files</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Over 100 pending files</span>
                    </h3>
                    <div class="card-toolbar">
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-category fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </button>
                        <!--begin::Menu 3-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                            data-kt-menu="true">
                            <!--begin::Heading-->
                            <div class="menu-item px-3">
                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
                            </div>
                            <!--end::Heading-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Create Invoice</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link flex-stack px-3">Create Payment
                                    <span class="ms-2" data-bs-toggle="tooltip"
                                        title="Specify a target name for future usage and reference">
                                        <i class="ki-duotone ki-information fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">Generate Bill</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                data-kt-menu-placement="right-end">
                                <a href="#" class="menu-link px-3">
                                    <span class="menu-title">Subscription</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <!--begin::Menu sub-->
                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Plans</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Billing</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Statements</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator my-2"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content px-3">
                                            <!--begin::Switch-->
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <!--begin::Input-->
                                                <input class="form-check-input w-30px h-20px" type="checkbox"
                                                    value="1" checked="checked" name="notifications" />
                                                <!--end::Input-->
                                                <!--end::Label-->
                                                <span class="form-check-label text-muted fs-6">Recuring</span>
                                                <!--end::Label-->
                                            </label>
                                            <!--end::Switch-->
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu sub-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-1">
                                <a href="#" class="menu-link px-3">Settings</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu 3-->
                        <!--end::Menu-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle gs-0 gy-3">
                            <!--begin::Table head-->
                            <thead>
                                <tr>
                                    <th class="p-0 w-50px"></th>
                                    <th class="p-0 min-w-150px"></th>
                                    <th class="p-0 min-w-140px"></th>
                                    <th class="p-0 min-w-120px"></th>
                                    <th class="p-0 min-w-40px"></th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-light-success">
                                                <i class="ki-duotone ki-basket fs-2x text-success">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Top
                                            Authors</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">ReactJs, HTML</td>
                                    <td class="text-end text-muted fw-bold">4600 Users</td>
                                    <td class="text-end text-dark fw-bold fs-6 pe-0">5.4MB</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-light-danger">
                                                <i class="ki-duotone ki-element-11 fs-2x text-danger">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="text-dark fw-bold text-hover-primary mb-1 fs-6">Popular Authors</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">Python, MySQL</td>
                                    <td class="text-end text-muted fw-bold">7200 Users</td>
                                    <td class="text-end text-dark fw-bold fs-6 pe-0">2.8MB</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-light-info">
                                                <i class="ki-duotone ki-briefcase fs-2x text-info">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">New
                                            Users</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">Laravel, Metronic</td>
                                    <td class="text-end text-muted fw-bold">890 Users</td>
                                    <td class="text-end text-dark fw-bold fs-6 pe-0">1.5MB</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-light-warning">
                                                <i class="ki-duotone ki-abstract-26 fs-2x text-warning">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="text-dark fw-bold text-hover-primary mb-1 fs-6">Active Customers</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">AngularJS, C#</td>
                                    <td class="text-end text-muted fw-bold">4600 Users</td>
                                    <td class="text-end text-dark fw-bold fs-6 pe-0">5.4MB</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="symbol symbol-50px me-2">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="ki-duotone ki-abstract-41 fs-2x text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="text-dark fw-bold text-hover-primary mb-1 fs-6">Active Customers</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">ReactJS, Ruby</td>
                                    <td class="text-end text-muted fw-bold">354 Users</td>
                                    <td class="text-end text-dark fw-bold fs-6 pe-0">500KB</td>
                                </tr>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
                <!--begin::Body-->
            </div>
            <!--end::Tables Widget 3-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-6 mb-5 mb-xl-10">
            <!--begin::Table Widget 6-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Authors Earnings</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">More than 400 new authors</span>
                    </h3>
                    <div class="card-toolbar">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1"
                                    data-bs-toggle="tab" href="#kt_table_widget_6_tab_1">Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1"
                                    data-bs-toggle="tab" href="#kt_table_widget_6_tab_2">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 active"
                                    data-bs-toggle="tab" href="#kt_table_widget_6_tab_3">Day</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="tab-content">
                        <!--begin::Tap pane-->
                        <div class="tab-pane fade" id="kt_table_widget_6_tab_1">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle gs-0 gy-3">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th class="p-0 w-50px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-140px"></th>
                                            <th class="p-0 min-w-120px"></th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/001-boy.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad
                                                    Simmons</a>
                                                <span class="text-muted fw-semibold d-block">Successful Fellas</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$200,500</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-primary fs-7 fw-bold">+28%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/018-girl-9.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Jessie
                                                    Clarcson</a>
                                                <span class="text-muted fw-semibold d-block">HTML, CSS Coding</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$1,200,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-warning fs-7 fw-bold">+52%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/047-girl-25.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Jessie
                                                    Clarcson</a>
                                                <span class="text-muted fw-semibold d-block">PHP, Laravel, VueJS</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$1,200,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-danger fs-7 fw-bold">+52%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/014-girl-7.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Natali
                                                    Trump</a>
                                                <span class="text-muted fw-semibold d-block">UI/UX Designer</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$3,400,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fs-7 fw-bold">-34%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/043-boy-18.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Kevin
                                                    Leonard</a>
                                                <span class="text-muted fw-semibold d-block">Art Director</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$35,600,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-info fs-7 fw-bold">+230%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Tap pane-->
                        <!--begin::Tap pane-->
                        <div class="tab-pane fade" id="kt_table_widget_6_tab_2">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle gs-0 gy-3">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th class="p-0 w-50px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-140px"></th>
                                            <th class="p-0 min-w-120px"></th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/018-girl-9.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Jessie
                                                    Clarcson</a>
                                                <span class="text-muted fw-semibold d-block">HTML, CSS Coding</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$1,200,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-warning fs-7 fw-bold">+52%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/014-girl-7.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Natali
                                                    Trump</a>
                                                <span class="text-muted fw-semibold d-block">UI/UX Designer</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$3,400,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fs-7 fw-bold">-34%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/001-boy.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad
                                                    Simmons</a>
                                                <span class="text-muted fw-semibold d-block">Successful Fellas</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$200,500</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-primary fs-7 fw-bold">+28%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Tap pane-->
                        <!--begin::Tap pane-->
                        <div class="tab-pane fade show active" id="kt_table_widget_6_tab_3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle gs-0 gy-3">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th class="p-0 w-50px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-140px"></th>
                                            <th class="p-0 min-w-120px"></th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/047-girl-25.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Jessie
                                                    Clarcson</a>
                                                <span class="text-muted fw-semibold d-block">HTML, CSS Coding</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$1,200,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-danger fs-7 fw-bold">+52%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/014-girl-7.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Natali
                                                    Trump</a>
                                                <span class="text-muted fw-semibold d-block">UI/UX Designer</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$3,400,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fs-7 fw-bold">-34%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/043-boy-18.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Kevin
                                                    Leonard</a>
                                                <span class="text-muted fw-semibold d-block">Art Director</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$35,600,000</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-info fs-7 fw-bold">+230%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <img src="assets-busdevmarketing/media/svg/avatars/001-boy.svg"
                                                            class="h-75 align-self-end" alt="" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#"
                                                    class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad
                                                    Simmons</a>
                                                <span class="text-muted fw-semibold d-block">Successful Fellas</span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold d-block fs-7">Paid</span>
                                                <span class="text-dark fw-bold d-block fs-5">$200,500</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-primary fs-7 fw-bold">+28%</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#"
                                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                    <i class="ki-duotone ki-arrow-right fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Tap pane-->
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Tables Widget 6-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Post-->

{{-- this for loading javascript data --}}
{{-- @section('script')
@endsection --}}
