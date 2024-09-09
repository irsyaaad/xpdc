<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-search">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight : bold">
                Perusahaan Asal
            </label>
            <select class="form-control" id="f_perush" name="f_perush">
                <option value="">-- Pilih Perusahaan --</option>
                @foreach ($perusahaan as $key => $value)
                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label style="font-weight: bold;">
                Bulan
            </label>

            <select class="form-control" id="f_bulan" name="f_bulan">
                <option value="">-- Pilih Bulan --</option>
                <option value="01"> Januari </option>
                <option value="02"> Februari </option>
                <option value="03"> Maret </option>
                <option value="04"> April </option>
                <option value="05"> Mei </option>
                <option value="06"> Juni </option>
                <option value="07"> Juli </option>
                <option value="08"> Agustus </option>
                <option value="09"> September </option>
                <option value="10"> Oktober </option>
                <option value="11"> November </option>
                <option value="12"> Desember </option>
            </select>
        </div>

        <div class="col-md-3">
            <label style="font-weight: bold;">
                Tahun
            </label>

            <select class="form-control" id="f_tahun" name="f_tahun">
                <option selected="selected" value="0">-- Pilih Tahun --</option>
                @php
                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                        echo "<option value='$i'> $i </option>";
                    }
                @endphp
            </select>
        </div>

        <div class="col-md-3" style="margin-top: 27px">
            <button type="submit" class="btn btn-md btn-info" data-toggle="tooltip" data-placement="top"
                title="Cari Data">
                <i class="fa fa-search"></i> Cari
            </button>

            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip"
                data-placement="top" title="Refresh">
                <i class="fa fa-refresh"></i> Refresh
            </a>
        </div>

        <div class="col-md-12 text-right mt-2">
            <button type="button" class="btn btn-md btn-primary" onclick="CheckStatus()" data-toggle="tooltip"
                data-placement="bottom" title="Generate Gaji"><i class="fa fa-refresh"></i> Generate</button>
            <button type="button" class="btn btn-md btn-danger" onclick="CheckDenda()" data-toggle="tooltip"
                data-placement="bottom" title="Generate Denda"><i class="fa fa-refresh"></i> Generate Denda</button>
            <button type="button" class="btn btn-md btn-success" onclick="CheckApprove()" data-toggle="tooltip"
                data-placement="bottom" title="Approve Gaji"><i class="fa fa-check"></i> Approve</button>
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-md btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-print"> </i> Cetak Pdf
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal-cetak">
                            <i class="fa fa-print"></i> Cabang
                          </button>
                        {{-- <a href="{{ route('cetakgajiall', [
                            'bulan' => $filter['f_bulan'],
                            'tahun' => $filter['f_tahun'],
                        ]) }}"
                            class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Cetak Semua Cabang"
                            target="_blank" rel="nofollow"> Semua Cabang</a> --}}
                    </div>
                </div>
            </div>
            <div class="btn-group">
                <div class="dropdown">
                    <button class="btn btn-md btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-file"> </i> Export Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal-excel">
                            <i class="fa fa-print"></i> Cabang
                          </button>
                        {{-- <a class="dropdown-item"
                            href="{{ route('excelgaji', [
                                'bulan' => $filter['f_bulan'],
                                'tahun' => $filter['f_tahun'],
                                'id_perush' => $filter['f_perush'],
                            ]) }}"
                            data-toggle="tooltip" data-placement="top" title="Export Cabang Sendiri"
                            target="_blank"> Cabang</a> --}}
                        {{-- <a class="dropdown-item"
                            href="{{ route('excelallgaji', [
                                'bulan' => $filter['f_bulan'],
                                'tahun' => $filter['f_tahun'],
                            ]) }}"
                            data-toggle="tooltip" data-placement="top" title="Export Semua Cabang" target="_blank">
                            Semua Cabang</a> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
