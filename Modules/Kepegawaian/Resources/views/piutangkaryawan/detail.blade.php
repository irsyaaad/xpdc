@extends('template.document2')

@section('data')
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
                <i class="fa fa-reply"></i> Kembali
            </a>
        </div>

        <div class="col-md-12" style="margin-top: 10px">
            <table class="table tabl-responsive table-stripped">
                <thead>
                    <tr>
                        <td width="13%">No. Piutang</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->id_piutang))
                                    {{ strtoupper($data->id_piutang) }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Nama Karyawan</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->karyawan->nm_karyawan))
                                    {{ strtoupper($data->karyawan->nm_karyawan) }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Perusahaan</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->perusahaan->nm_perush))
                                    {{ strtoupper($data->perusahaan->nm_perush) }}
                                @endif
                            </b>
                        </td>
                    </tr>

                    <tr>
                        <td width="13%">Keperluan</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->keterangan))
                                    {{ strtoupper($data->keterangan) }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Tanggal Piutang</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->tgl_piutang))
                                    {{ dateindo($data->tgl_piutang) }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Perkiraan Tgl Selesai</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->tgl_selesai))
                                    {{ dateindo($data->tgl_selesai) }}
                                @endif
                            </b>
                        </td>
                    </tr>

                    <tr>
                        <td width="13%">Nominal</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->nominal))
                                    {{ 'Rp. ' . number_format($data->nominal, 0, ',', '.') }}
                                @else
                                    {{ 'Rp. 0,00' }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Nominal Tagihan Pembarayan</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->n_angsuran))
                                    {{ 'Rp. ' . number_format($data->n_angsuran, 0, ',', '.') }}
                                @else
                                    {{ 'Rp. 0,00' }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Jumlah terbayar</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->bayar))
                                    {{ 'Rp. ' . number_format($data->bayar, 0, ',', '.') }}
                                @else
                                    {{ 'Rp. 0,00' }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="13%">Sisa Piutang</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->sisa))
                                    {{ 'Rp. ' . number_format($data->sisa, 0, ',', '.') }}
                                @else
                                    {{ 'Rp. 0,00' }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Tenor Pembayaran</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->frekuensi))
                                    {{ $data->frekuensi }}
                                @endif
                            </b>
                        </td>

                        <td width="13%">Pembayaran Ke</td>
                        <td width="2%"><b>:</b></td>
                        <td width="15%">
                            <b>
                                @if (isset($data->angsuran_ke))
                                    {{ $data->angsuran_ke }}
                                @endif
                            </b>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-12" style="margin-top: 10px">
            <h4><i class="fa fa-thumb-tack"></i>
                <b>Detail Pembayaran Piutang</b>
            </h4>
            <div class="text-right">
                @if (isset($data->is_lunas) and $data->is_lunas == false)
                    <button class="btn btn-primary" type="button" onclick="goBayar()">
                        <i class="fa fa-money"> </i> Bayar Piutang
                    </button>
                @endif
            </div>

            <table class="table table-responsive table-hover mt-2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Nominal Pembayaran</th>
                        <th>Admin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($detail as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ dateindo($value->tgl_bayar) }}</td>
                            <td>{{ number_format($value->n_bayar, 0, ',', '.') }}</td>
                            <td>
                                @if (isset($value->user->nm_user))
                                    {{ ucfirst($value->user->nm_user) }}
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit" title="Edit Bayar Piutang"
                                    data-id="{{ $value->id_detail }}" 
                                    data-tgl-bayar="{{ $value->tgl_bayar }}"
                                    data-n-bayar="{{ $value->n_bayar }}"
                                    data-ac4-debit="{{$value->ac4_debit}}"
                                    data-ac4-kredit="{{$value->ac4_kredit}}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td>
                            @php
                                $total += $value->n_bayar;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" align="right"><b>Total Pembayaran : </b></td>
                        <td><b>{{ 'Rp. ' . number_format($total, 0, ',', '.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


    <div class="modal fade" id="modal-bayar" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/bayar') }}"
                    enctype="multipart/form-data" id="form-bayar">
                    @csrf
                    <div class="modal-body">
                        <center>
                            <h5 id="text-modal"><i class="fa fa-money"> </i> Form Pembayaran Piutang</h5>
                        </center>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-md-12" id="div-nominal">
                                <label>Nominal Bayar
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" step="any" class="form-control" id="n_bayar" name="n_bayar"
                                    placeholder="masukan jumlah bayar(Rp. 100.000) ..." required />

                                @if ($errors->has('ac4_debet'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_debet') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-tgl">
                                <label>Tanggal Pembayaran
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" required />

                                @if ($errors->has('tgl_bayar'))
                                    <label style="color: red">
                                        {{ $errors->first('tgl_bayar') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-debet">
                                <label>Akun Debet
                                    <span class="text-danger">*</span>
                                </label>

                                <select class="form-control" id="ac4_debet" name="ac4_debet" required>
                                    <option value="">-- Pilih Akun Debet --</option>
                                    @foreach ($debet as $key => $value)
                                        <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('ac4_debet'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_debet') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-kredit">
                                <label>Akun Kredit
                                    <span class="text-danger">*</span>
                                </label>

                                <select class="form-control" id="ac4_kredit" name="ac4_kredit" required>
                                    <option value="">-- Pilih Akun Kredit --</option>
                                    @foreach ($kredit as $key => $value)
                                        <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('ac4_kredit'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_kredit') }}
                                    </label>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top"
                            title="Simpan Data">
                            <i class="fa fa-check"></i> Bayar
                        </button>

                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tidak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-bayar" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/edit-bayar') }}"
                    enctype="multipart/form-data" id="form-bayar">
                    @csrf
                    <div class="modal-body">
                        <center>
                            <h5 id="text-modal"><i class="fa fa-money"> </i> Form Pembayaran Piutang</h5>
                        </center>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-md-12" id="div-nominal">
                                <label>Nominal Bayar
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" step="any" class="form-control" id="e_n_bayar" name="n_bayar"
                                    placeholder="masukan jumlah bayar(Rp. 100.000) ..." required />\
                                    
                                <input type="hidden" name="id_detail" id="id_detail">

                                @if ($errors->has('ac4_debet'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_debet') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-tgl">
                                <label>Tanggal Pembayaran
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" class="form-control" id="e_tgl_bayar" name="tgl_bayar" required />

                                @if ($errors->has('tgl_bayar'))
                                    <label style="color: red">
                                        {{ $errors->first('tgl_bayar') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-debet">
                                <label>Akun Debet
                                    <span class="text-danger">*</span>
                                </label>

                                <select class="form-control" id="e_ac4_debet" name="ac4_debet" required>
                                    <option value="">-- Pilih Akun Debet --</option>
                                    @foreach ($debet as $key => $value)
                                        <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('ac4_debet'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_debet') }}
                                    </label>
                                @endif
                            </div>

                            <div class="col-md-12 mt-3" id="div-kredit">
                                <label>Akun Kredit
                                    <span class="text-danger">*</span>
                                </label>

                                <select class="form-control" id="e_ac4_kredit" name="ac4_kredit" required>
                                    <option value="">-- Pilih Akun Kredit --</option>
                                    @foreach ($kredit as $key => $value)
                                        <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('ac4_kredit'))
                                    <label style="color: red">
                                        {{ $errors->first('ac4_kredit') }}
                                    </label>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top"
                            title="Simpan Data">
                            <i class="fa fa-check"></i> Bayar
                        </button>

                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tidak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function goBayar() {
            $("#modal-bayar").modal("show");
        }

        $(".btn-edit").click(function() {
            var id = $(this).data('id');
            var tgl_bayar = $(this).data('tgl-bayar');
            var ac4_debit = $(this).data('ac4-debit');
            var ac4_kredit = $(this).data('ac4-kredit');
            var n_bayar = $(this).data('n-bayar');

            $("#e_tgl_bayar").val(tgl_bayar);
            $("#e_ac4_debet").val(ac4_debit);
            $("#e_ac4_kredit").val(ac4_kredit);
            $("#e_n_bayar").val(n_bayar);
            $("#id_detail").val(id);

            $("#modal-edit-bayar").modal("show");
        });
    </script>
@endsection
