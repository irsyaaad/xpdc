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

{{--                         
                        <div class="col-md-3">
                            <label style="font-weight: bold;">
                                Status Aktif
                            </label>
                            <select class="form-control" id="f_status" name="f_status">
                                <option value="">-- Semua --</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>
                            </select>
                        </div> --}}
                        
                        @if(Request::segment(1)=="laporanperijinan")
                        <div class="col-md-12 text-right" style="margin-top: 5px">
                            @include('kepegawaian::filter.eksport')
                        </div>
                        @else
                        <div class="col-md-3" style="margin-top: 30px">
                            @include('kepegawaian::filter.eksport')
                        </div>
                        @endif
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>