<form method="POST" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-search">
    @csrf
    <div class="row">
        
        <div class="col-md-3">
            <label style="font-weight : bold">
                Perusahaan Asal
            </label>
            
            <select class="form-control" id="f_perush" name="f_perush">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label style="font-weight : bold">
                Pelanggan
            </label>
            
            <select class="form-control" id="f_pelanggan" name="f_pelanggan">
                <option value="">-- Pilih Pelanggan --</option>
            </select>
        </div>
        
        <div class="col-md-3" style="margin-top: 30px">
            <button type="submit" class="btn btn-md btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"><i class="fa fa-search"></i></button>
            <a href="{{ url(Request::segment(1)."/refresh") }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i></a>
            <a href="{{ url(Request::segment(1)."/showimport") }}" class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="top" title="Import data"><i class="fa fa-download"></i> Import</a>
            <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Import data"><i class="fa fa-plus"></i> Tambah</a>
            {{-- <button type="button" class="btn btn-md btn-primary" onclick="html()" data-toggle="tooltip" data-placement="top" title="Cetak pdf"><i class="fa fa-print"></i> </button>
            <button type="button" class="btn btn-md btn-success" onclick="excel()" target="_blank" data-toggle="tooltip" data-placement="top" title="Cetak excel"><i class="fa fa-file"></i> </button> --}}
        </div>
    </div>
</form>