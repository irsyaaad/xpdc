@extends('template.document')

@section('data')
    <div class="col-md-12 text-right">
        <button class="btn btn-sm btn-info" onclick="process({{ $data->id }})"><i class="fa fa-thumbs-o-up"></i> Process</button>
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <td width="30%">No. Tiket</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->no_ticket))
                                    {{ $data->no_ticket }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Jenis Komplain</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->jenis_komplain->nama_jenis))
                                    {{ $data->jenis_komplain->nama_jenis }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Tanggal Komplain</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->tgl_complain))
                                    {{ daydate($data->tgl_complain) . ', ' . dateindo($data->tgl_complain) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Pelapor</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->pelapor))
                                    {{ strtoupper($data->pelapor) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">HP Pelapor</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->hp_pelapor))
                                    {{ strtoupper($data->hp_pelapor) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Perusahaan Tujuan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->perush_tujuan->nm_perush))
                                    {{ strtoupper($data->perush_tujuan->nm_perush) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Keterangan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                {{ $data->keterangan }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Status</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->status))
                                    {{ $data->status }}
                                @endif
                            </b>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="container-fluid row" style="margin-top: 10px">
        <div class="col-md-6">
            <h4 style="margin-left: 2%"><i class="fa fa-thumb-tack"></i>
                <b>History</b>
            </h4>
        </div>
        <div class="col-md-12" style="margin-top: 10px">
            <table class="table table-responsive table-hover" id="tableasal">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Tgl Update</th>
                        <th>Petugas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }} </td>
                            <td>{{ dateindo($value->tgl_update) }}</td>
                            <td>{{ strtoupper($value->petugas) }}</td>
                            <td>{{ $value->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-process" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">
                        Proses Complain?
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="margin-top: -2%">
                    <form method="POST" action="{{ url(Request::segment(1) . '/save-process') }}"
                        enctype="multipart/form-data" id="form-end">
                        @csrf
                        <h6>Tgl Process<span class="text-danger"> *</span> </h6>
                        <input type="date" class="form-control" name="tgl_update" value="{{ date('Y-m-d') }}"
                            id="tgl_update" required>
                        <br>

                        <input type="hidden" name="id_complain" id="id_complain">

                        <h6>Nama Penerima<span class="span-required"> * </span></h6>
                        <input type="text" class="form-control" name="petugas" id="petugas" maxlength="100"
                            placeholder="Masukan Nama Penerima ..." value="{{ Auth::user()->nm_user }}" />
                        @if ($errors->has('nm_penerima'))
                            <label style="color: red">
                                {{ $errors->first('nm_penerima') }}
                            </label>
                        @endif
                        <br>

                        <h6>Keterangan<span class="span-required"> * </span></h6>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukan Keterangan ..."></textarea>
                        @if ($errors->has('keterangan'))
                            <label style="color: red">
                                {{ $errors->first('keterangan') }}
                            </label>
                        @endif

                        <br>
                        <h6>Tandai Sebagai Selesai?<span class="span-required"> * </span></h6>
                        <input type="checkbox" class="" name="is_selesai" id="is_selesai" value="1"
                            style="width: 15px;
                        height: 15px;">

                        <br>
                        <div class="text-right">
                            <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Sampai</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true">Batal</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function process(id) {
            console.log(id);
            $("#id_complain").val(id);
            $("#modal-process").modal('show');
        }
    </script>
@endsection
