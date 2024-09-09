
<div id="filter-modal" class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-md btn-primary" class="btn btn-primary" data-toggle="modal" data-target="#modal-filter" title="Cari Data"><i class="fa fa-filter"></i> Filter</button>
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
        @if(strtolower(Session("role")["nm_role"])=="busdev")
        <button type="button" class="btn btn-md btn-info" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload" title="Import Data"><i class="fa fa-upload"></i> Import</button>
        @endif
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-filter">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <label style="font-weight : bold">
                            Vendor
                        </label>
                        <select class="form-control" id="id_ven" name="id_ven">
                            <option value="">-- Vendor --</option>
                            @foreach($vendor as $key => $value)
                            <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 col-6">
                        <label style="font-weight : bold">
                            Wilayah Asal
                        </label>
                        <select class="form-control" id="id_asal" name="id_asal"></select>
                    </div>
                    
                    <div class="col-md-4 col-6">
                        <label style="font-weight : bold">
                            Wilayah Tujuan
                        </label>
                        <select class="form-control" id="id_tujuan" name="id_tujuan"></select>
                    </div>
                    
                    <input type="hidden" name="range" id="range" value="" />
                    <div class="col-md-2 col-6">
                        <label style="font-weight : bold">
                            Type Harga 
                        </label>
                        <select class="form-control" id="type" name="type">
                            <option value="">-- Semua Type --</option>
                            <option value="1">Direct</option>
                            <option value="2">Multivendor</option>
                        </select>
                        <div class="text-right mt-1">
                            <button type="button" class="btn btn-sm btn-info" id="btn-area" name="btn-are" data-toggle="tooltip" data-placement="top" title="Dibawah Area Wilayah" ><i class="fa fa-level-down"></i> Area</button>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>