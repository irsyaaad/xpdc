
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

<div class="col-md-3 col-6">
    <label style="font-weight : bold">
        Wilayah Tujuan
    </label>
    <select class="form-control" id="id_tujuan" name="id_tujuan"></select>
</div>

<div class="col-md-3 col-6">
    <label style="font-weight : bold">
        Last Updated
    </label>
    <input type="date" class="form-control" id="updated" name="updated" />
</div>

<div class="col-md-12 col-6 text-right mt-1">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
</div>