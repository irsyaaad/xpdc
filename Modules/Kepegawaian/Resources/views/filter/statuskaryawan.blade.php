<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-search">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Perusahaan
            </label>
            <select class="form-control" id="f_perush" name="f_perush">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Bagian
            </label>
            <select class="form-control" id="f_id_jenis" name="f_id_jenis">
                <option value="">-- Bagian --</option>
                @foreach($jenis as $key => $value)
                <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Status Karyawan
            </label>
            <select class="form-control" id="f_id_status" name="f_id_status">
                <option value="">-- Pilih Status Karyawan --</option>
                @foreach($status_karyawan as $key => $value)
                <option value="{{ $value->id_status_karyawan }}">{{ strtoupper($value->nm_status_karyawan) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-3" style="margin-top: 30px">
            <button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
        </div>
    </div>
</form>