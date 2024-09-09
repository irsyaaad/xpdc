<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Harap Isi Form Dengan Benar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                $ldate = date('Y-m-d H:i:s');
            @endphp
            <form action="{{ url(Request::segment(1)) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-weight : bold">
                                Perusahaan Asal <span class="text-danger"> *</span>
                            </label>
                            <select class="form-control" id="id_perush" name="id_perush" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach ($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label style="font-weight : bold">
                                Model Penggajian <span class="text-danger"> *</span>
                            </label>
                            <br>
                            <label>
                                <input type="radio" id="type" name="type" value="1" /> Persentase
                            </label>
                            <label style="margin-left: 10px">
                                <input type="radio" id="type" name="type" value="2" /> Toleransi
                            </label>
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tanggal Awal Kerja<span class="text-danger"> *</span>
                            </label>
                            <input class="form-control" id="dr_tgl" name="dr_tgl" required
                                placeholder="Masukan Tanggal Awal" type="date"
                                value="@if (isset($dr_tgl)) {{ $dr_tgl }} @endif" />
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tanggal Akhir Kerja<span class="text-danger"> *</span>
                            </label>
                            <input class="form-control" id="sp_tgl" name="sp_tgl" required
                                placeholder="Masukan Tanggal Awal" type="date"
                                value="@if (isset($sp_tgl)) {{ $sp_tgl }} @endif" />
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Bulan <span class="text-danger"> *</span>
                            </label>

                            <select class="form-control" id="bulan" name="bulan" required>
                                <option value="0">-- Pilih Bulan --</option>
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

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tahun <span class="text-danger"> *</span>
                            </label>

                            <select name="tahun" class="form-control" id="tahun" name="tahun" required>
                                <option selected="selected" value="0">-- Pilih Tahun --</option>
                                @php
                                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                                        echo "<option value='$i'> $i </option>";
                                    }
                                @endphp
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-refresh"></i>
                        Generate</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i class="fa fa-times"></i>
                            Batal</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-create-denda" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Harap Isi Form Dengan Benar
                    (Generate Denda)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                $ldate = date('Y-m-d H:i:s');
            @endphp
            <form action="{{ url(Request::segment(1)) }}/generate-denda" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label style="font-weight : bold">
                                Perusahaan Asal <span class="text-danger"> *</span>
                            </label>
                            <select class="form-control" id="id_perush" name="id_perush" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach ($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label style="font-weight : bold">
                                Model Penggajian <span class="text-danger"> *</span>
                            </label>
                            <br>
                            <label>
                                <input type="radio" id="type" name="type" value="1" /> Persentase
                            </label>
                            <label style="margin-left: 10px">
                                <input type="radio" id="type" name="type" value="2" /> Toleransi
                            </label>
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tanggal Awal Kerja<span class="text-danger"> *</span>
                            </label>
                            <input class="form-control" id="dr_tgl" name="dr_tgl" required
                                placeholder="Masukan Tanggal Awal" type="date"
                                value="@if (isset($dr_tgl)) {{ $dr_tgl }} @endif" />
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tanggal Akhir Kerja<span class="text-danger"> *</span>
                            </label>
                            <input class="form-control" id="sp_tgl" name="sp_tgl" required
                                placeholder="Masukan Tanggal Awal" type="date"
                                value="@if (isset($sp_tgl)) {{ $sp_tgl }} @endif" />
                        </div>

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Bulan <span class="text-danger"> *</span>
                            </label>

                            <select class="form-control" id="bulan" name="bulan" required>
                                <option value="0">-- Pilih Bulan --</option>
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

                        <div class="col-md-6" style="margin-top: 15px;">
                            <label style="font-weight: bold;">
                                Tahun <span class="text-danger"> *</span>
                            </label>

                            <select name="tahun" class="form-control" id="tahun" name="tahun" required>
                                <option selected="selected" value="0">-- Pilih Tahun --</option>
                                @php
                                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                                        echo "<option value='$i'> $i </option>";
                                    }
                                @endphp
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-refresh"></i>
                        Generate</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i>
                            Batal</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <form method="POST" action="{{ url(Request::segment(1) . '/approve') }}" id="form-generate"
            name="form-generate">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <h4 class="modal-title" style="font-weight: bold;">Apakah Anda Ingin Approve Gaji Karyawan
                            <br> Periode {{ dateindo(date('d-m-Y')) }} ?
                        </h4>
                    </center>

                    <div class="row" style="margin-top: 15px">
                        <div class="col-md-6">
                            <label style="font-weight: bold;">
                                Bulan <span class="text-danger">* </span>
                            </label>

                            <select class="form-control" id="a_bulan" name="a_bulan" required>
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

                        <div class="col-md-6">
                            <label style="font-weight: bold;">
                                Tahun <span class="text-danger">* </span>
                            </label>

                            <select class="form-control" id="a_tahun" name="a_tahun" required>
                                <option selected="selected" value="">-- Pilih Tahun --</option>
                                @php
                                    for ($i = date('Y'); $i >= date('Y') - 10; $i -= 1) {
                                        echo "<option value='$i'> $i </option>";
                                    }
                                @endphp
                            </select>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label for="frekuensi">
                                <b>Kas / Bank Keluar</b> <span class="span-required"> *</span>
                            </label>

                            <select class="form-control" id="ac_kredit" name="ac_kredit" required>
                                <option value=""> -- Pilih Bank / Kas -- </option>
                                @foreach ($ac as $key => $value)
                                    <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('ac_kredit'))
                                <label style="color: red">
                                    {{ $errors->first('ac_kredit') }}
                                </label>
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <label for="ac_debit_gaji">
                                <b>Akun Gaji Karyawan</b> <span class="span-required"> *</span>
                            </label>

                            <select class="form-control" id="ac_debit_gaji" name="ac_debit_gaji" required>
                                <option value=""> -- Pilih Akun Gaji -- </option>
                                @foreach ($ac_gaji as $key => $value)
                                    <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('ac_debit_gaji'))
                                <label style="color: red">
                                    {{ $errors->first('ac_debit_gaji') }}
                                </label>
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <label for="ac_kredit_piutang">
                                <b>Akun Piutang Karyawan</b> <span class="span-required"> *</span>
                            </label>

                            <select class="form-control" id="ac_kredit_piutang" name="ac_kredit_piutang" required>
                                <option value=""> -- Pilih Akun Piutang -- </option>
                                @foreach ($piutang as $key => $value)
                                    <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('ac_kredit_piutang'))
                                <label style="color: red">
                                    {{ $errors->first('ac_kredit_piutang') }}
                                </label>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-success"><span
                            aria-hidden="true">Iya</span></button>
                    <button type="button" class="btn btn-md btn-danger" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">Tidak</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modal-cetak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form
                action="{{ route('cetakgaji', [
                    'bulan' => $filter['f_bulan'],
                    'tahun' => $filter['f_tahun'],
                    'id_perush' => $filter['f_perush'],
                ]) }}"
                method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Silahkan Pilih Kolom yang akan dicetak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="bulan" id="bulan" value="{{ $filter['f_bulan'] }}">
                    <input type="hidden" name="tahun" id="tahun" value="{{ $filter['f_tahun'] }}">
                    <input type="hidden" name="id_perush" id="id_perush" value="{{ $filter['f_perush'] }}">
                    <div class="row">
                        @foreach ($tunjangan as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="tunjangan[{{ $key }}]" id="tunjangan"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @foreach ($tunj_nonthp as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="tunj_nonthp[{{ $key }}]" id="tunj_nonthp"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @foreach ($potongan as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="potongan[{{ $key }}]" id="potongan"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-print"></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-excel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form
                action="{{ route('excelgaji', [
                    'bulan' => $filter['f_bulan'],
                    'tahun' => $filter['f_tahun'],
                    'id_perush' => $filter['f_perush'],
                ]) }}"
                method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Silahkan Pilih Kolom yang akan dicetak Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="bulan" id="bulan" value="{{ $filter['f_bulan'] }}">
                    <input type="hidden" name="tahun" id="tahun" value="{{ $filter['f_tahun'] }}">
                    <input type="hidden" name="id_perush" id="id_perush" value="{{ $filter['f_perush'] }}">
                    <div class="row">
                        @foreach ($tunjangan as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="tunjangan[{{ $key }}]" id="tunjangan"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @foreach ($tunj_nonthp as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="tunj_nonthp[{{ $key }}]" id="tunj_nonthp"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @foreach ($potongan as $key => $item)
                            <div class="col-md-4 mt-3">
                                <input type="checkbox" name="potongan[{{ $key }}]" id="potongan"
                                    value="{{ $item }}" checked>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-print"></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>
