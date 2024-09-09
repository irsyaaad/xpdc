<div id="accordion" >
    <div class="card border-0">
        <div id="headingOne" class="text-right">
            <button class="btn btn-md btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fa fa-filter"> </i> Filter
            </button>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form method="GET" action="" enctype="multipart/form-data" id="form-search">
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
                            <div class="m-form__label">
                                <label style="font-weight: bold;">
                                    Karyawan
                                </label>
                            </div>
                            <div class="m-form__control">
                                <select class="form-control" id="f_id_karyawan" name="f_id_karyawan">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($karyawan as $key => $value)
                                    <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                        
                        <div class="col-md-3">
                            <label style="font-weight: bold;">
                                Tanggal Awal
                            </label>
                            <input class="form-control" id="f_dr_tgl" name="f_dr_tgl" placeholder="Masukan Tanggal Awal" type="date" value="@if(isset($filter["f_dr_tgl"])){{ $filter["f_dr_tgl"] }}@endif" />
                        </div>
                        
                        <div class="col-md-3">
                            <label style="font-weight: bold;">
                                Tanggal Akhir
                            </label>
                            <input class="form-control" id="f_sp_tgl" name="f_sp_tgl" placeholder="Masukan Tanggal Awal" type="date" value="@if(isset($filter["f_sp_tgl"])){{ $filter["f_sp_tgl"] }}@endif" />
                        </div>
                        
                        <div class="col-md-12 text-right mt-1" >
                            <button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"><i class="fa fa-search"></i> Cari</button>
                            <a href="{{ url(Request::segment(1)."/".Request::segment(2)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>