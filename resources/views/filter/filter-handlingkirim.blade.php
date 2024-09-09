<div class="col-md-3 mt-2">
    <label style="font-weight: bold;">
        No Handling
    </label>
    <select class="form-control" id="id_handling" name="id_handling"></select>
</div>

<div class="col-md-12 mt-2 text-right">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>