<div id="accordion" style="margin-top: -25px">
    <div class="card border-0">
        <div id="headingOne" class="text-right">
            <button class="btn btn-md btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fa fa-filter"> </i> Filter
            </button>
        </div>
        
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-search">
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
                        
                        @if(Request::segment(1)=="laporanperijinan")
                        <div class="col-md-3">
                            <label style="font-weight: bold;">
                                Jenis Perijinan
                            </label>
                            <select class="form-control" id="f_jenis" name="f_jenis">
                                <option value="">-- Jenis Perijinan --</option>
                                @foreach($jenis as $key => $value)
                                <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
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
                        
                        <div class="col-md-3" style="margin-top: 30px">
                            <button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"><i class="fa fa-search"></i> Cari</button>
                            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-upload"> </i> Cetak 
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @php
                                    $cetak = url(Request::segment(1)."/cetak");
                                    $excel =  url(Request::segment(1)."/excel");
                                    $cabang = url(Request::segment(1)."/allcabang");
                                    if(preg_match("/filter/i", url()->full())){
                                        $cetak = str_replace("filter","cetak", url()->full());
                                        $excel = str_replace("filter","excel", url()->full());
                                        $cabang = str_replace("filter","allcabang", url()->full());
                                    }
                                    @endphp
                                    <a class="dropdown-item" href="{{ $cabang }}" target="_blank"> <i class="fa fa-print"></i> Semua Cabang</a>
                                    <a class="dropdown-item" href="{{ $cetak }}" target="_blank"> <i class="fa fa-print"></i> Cetak Pdf</a>
                                    <a class="dropdown-item" href="{{ $excel }}" target="_blank"> <i class="fa fa-file" aria-hidden="true"></i> Cetak Excel</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>