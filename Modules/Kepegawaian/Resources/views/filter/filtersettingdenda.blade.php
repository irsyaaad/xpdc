<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($role_perush as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4" style="padding-top: 30px">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
    <button class="btn btn-sm btn-success" onclick="goSetting()" type="button"  data-toggle="tooltip" data-placement="bottom" title="Copy Setting"><span><i class="fa fa-copy"></i></span> Copy Data </button>
</div>