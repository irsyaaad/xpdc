<div class="collapse mb-5" id="filter-data">
    <div class="card card-body">
        <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-5">
                        <label class="fs-6 form-label fw-bold text-dark">Search :</label>
                        <input type="text" class="form-control form-control-solid" name="search" id="search"
                            value="{{ $filter['search'] ?? '' }}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-5">
                        <label class="fs-6 form-label fw-bold text-dark">Pelanggan :</label>
                        <select name="f_id_pelanggan" id="f_id_pelanggan"
                            class="form-select form-select-solid fw-bold" data-kt-select2="true"
                            data-placeholder="Pilih Pelanggan" data-allow-clear="true">

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mt-10">
                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                            data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
