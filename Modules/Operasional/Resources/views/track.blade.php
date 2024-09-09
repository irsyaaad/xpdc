@extends('template.document')

@section('data')
    <div class="col-md-12 text-right" style="margin-top: -1%">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>
    <br>
    <table class="table table-responsive">
        <thead>
            <tr>
                <td width="15%">
                    <h6>No. RESI</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->kode_stt }}</td>

                <td width="15%">
                    <h6>Layanan</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->layanan->nm_layanan))
                        {{ $data->layanan->nm_layanan }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <h6>Tgl. Masuk</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->tgl_masuk))
                        {{ daydate($data->tgl_masuk) . ', ' . dateindo($data->tgl_masuk) }}
                    @endif
                </td>

                <td width="15%">
                    <h6>Tipe Kirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->tipekirim->nm_tipe_kirim))
                        {{ $data->tipekirim->nm_tipe_kirim }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <h6>Tgl. Keluar</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->tgl_keluar))
                        {{ daydate($data->tgl_keluar) . ', ' . dateindo($data->tgl_keluar) }}
                    @endif
                </td>

                <td width="15%">
                    <h6>Packing</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->packing->nm_packing))
                        {{ $data->packing->nm_packing }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <h6>Tgl. Jatuh Tempo</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->tgl_tempo))
                        {{ daydate($data->tgl_tempo) . ', ' . dateindo($data->tgl_tempo) }}
                    @endif
                </td>

                <td width="15%">
                    <h6>Status</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->status->nm_ord_stt_stat))
                        {{ $data->status->nm_ord_stt_stat }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <h6>No. AWB</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->no_awb }}</td>

                <td width="15%">
                    <h6>Info Kirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->info_kirim))
                        {{ $data->info_kirim }}
                    @endif
                </td>
            </tr>

            <tr>
                <td>
                    <h5> > Pengirim</h5>
                </td>
                <td width="1%"></td>
                <td>
                <td>
                    <h5> > Penerima</h5>
                </td>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <h6>Nama Pengirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->pengirim_nm }}</td>

                <td width="20%">
                    <h6>Nama Penerima</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->penerima_nm }}</td>
            </tr>
            <tr>
                <td width="20%">
                    <h6>Perusahaan Pengirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->pengirim_perush }}</td>

                <td width="20%">
                    <h6>Perusahaan Penerima</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->penerima_perush }}</td>
            </tr>
            <tr>
                <td width="20%">
                    <h6>Telp Pengirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->pengirim_telp }}</td>

                <td width="20%">
                    <h6>Telp Penerima</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>{{ $data->penerima_telp }}</td>
            </tr>
            <tr>
                <td width="20%">
                    <h6>Alamat Pengirim</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->asal->nama_wil))
                        {{ $data->asal->nama_wil }}
                    @endif, {{ $data->pengirim_alm }} - {{ $data->pengirim_kodepos }}
                </td>

                <td width="20%">
                    <h6>Alamat Penerima</h6>
                </td>
                <td width="2%"><b>:</b></td>
                <td>
                    @if (isset($data->tujuan->nama_wil))
                        {{ $data->tujuan->nama_wil }}
                    @endif, {{ $data->penerima_alm }} - {{ $data->penerima_kodepos }}
                </td>
            </tr>
        </thead>
    </table>

    <div class="row">
        <h4> > Detail Status RESI </h4>
        <div class="col-md-12">
            <form action="#" method="POST" id="form-select" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="_method" name="_method" />
                <table class="table table-responsive table-striped">
                    <thead style="background: grey; color:#fff ">
                        <tr>
                            <th>No. </th>
                            <th>Status</th>
                            <th>Detail </th>
                            <th>Waktu Update </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $key => $value)
                            @if (isset($value->foto_dooring))
                                @php
                                    $foto = json_decode($value->foto_dooring);
                                @endphp
                                <tr>
                                    <td colspan="5">
                                        <div class="row">
                                            @foreach ($foto as $item)
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <img class="card-img-top" src="{{ $item }}"
                                                            alt="Card image cap">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if (isset($value->nm_status))
                                        {{ $value->nm_status . ' ( ' . $value->place . ' )' }}
                                    @endif
                                </td>
                                <td>
                                    <label style="font-size: 12px">
                                        @if (isset($value->tgl_update) and $value->tgl_update != null)
                                            {{ $value->tgl_update }}
                                        @endif
                                    </label>
                                    <br>
                                    <label>
                                        @if (isset($value->nm_status) and $value->nm_status != null)
                                            {{ $value->nm_status . ' ( ' . $value->place . ' )' }}
                                        @endif
                                    </label>
                                    <br>
                                    @if ($value->id_status == 1)
                                        {{ $value->keterangan }}
                                    @elseif($value->id_status == 6)
                                        Kurir : {{ $value->nm_sopir }}
                                    @elseif($value->id_status == 4)
                                        Diterima Oleh : {{ $value->nm_user }}
                                    @elseif($value->id_status == 7)
                                        Penerima : {{ $value->nm_penerima }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ daydate($value->created_at) . ', ' . $value->created_at }}
                                </td>
                                <td>
                                    @if (isset($mapping[$value->nm_status]))
                                        @if ($value->id_status > 1)
                                            <button class="btn btn-sm btn-warning" type="button"
                                                onclick="updateStatus({{ $value->id_history }},{{ $value->id_status }},'{{ $value->tgl_update }}',{{ $value->id_wil }},'{{ $value->place }}', '{{ $value->keterangan }}')">
                                                <span><i class="fa fa-edit"></i></span>
                                            </button>
                                            <button class="btn btn-sm btn-danger" type="button"
                                                onclick="CheckDelete('{{ url('dmtrucking/' . $value->id_history . '/deletehistory') }}')">
                                                <span><i class="fa fa-times"></i></span>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($data->id_status == 1)
                            <tr>
                                <form method="POST" action="{{ url('stt/savedetail') }}" id="form-detail"
                                    name="form-detail">
                                    @csrf
                                    <td>
                                        <input type="hidden" name="id_stt" id="id_stt" value="{{ $data->id_stt }}">
                                    </td>
                                    <td>
                                        <input type="text" name="ket_koli" id="ket_koli"
                                            class="form-control m-input m-input--square" placeholder="Masukan Koli"
                                            required="required">

                                        @if ($errors->has('ket_koli'))
                                            <label style="color: red">
                                                {{ $errors->first('ket_koli') }}
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="keterangan" id="keterangan"
                                            class="form-control m-input m-input--square"
                                            placeholder="Masukan Keterangan Koli" required="required">

                                        @if ($errors->has('keterangan'))
                                            <label style="color: red">
                                                {{ $errors->first('keterangan') }}
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success" type="submit">
                                            <span><i class="fa fa-save"></i></span>
                                        </button>
                                        <button class="btn btn-sm btn-danger" type="button" onclick="getBatal()">
                                            <span><i class="fa fa-times"></i></span>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    @if (isset($gambar->gambar1) or isset($gambar->gambar2))
        <style>
            .img-ktp {
                width: 300px;
                /* height: 200px; */
                margin-left: 20px;
            }
        </style>

        @php
            if (Storage::exists('public/uploads/handling/' . $gambar->gambar1)) {
                $path = 'public/uploads/handling/' . $gambar->gambar1;

                $full_path = Storage::path($path);
                $base64 = base64_encode(Storage::get($path));
                $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                $gambar->gambar1 = $image;
            }

            if (Storage::exists('public/uploads/handling/' . $gambar->gambar2)) {
                $path = 'public/uploads/handling/' . $gambar->gambar2;

                $full_path = Storage::path($path);
                $base64 = base64_encode(Storage::get($path));
                $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                $gambar->gambar2 = $image;
            }
        @endphp
        <br>
        <div class="row">
            <h4> > Dokumen Bukti Dooring </h4>
            <br>
            <div class="col-md-4">
                <label><b>Gambar 1 :</b></label>

                <img src="{{ $gambar->gambar1 }}" class="img-ktp" />
            </div>

            <div class="col-md-4">
                <label><b>Gambar 2 : </b></label>

                <img src="{{ $gambar->gambar2 }}" class="img-ktp" />
            </div>
        </div>
    @endif

    <div class="modal fade" id="modal-update" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Update Status</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <label>
                            Pilih Tanggal Update
                        </label>
                        <input type="date" name="tgl_update" id="tgl_update" class="form-control">

                        <input type="hidden" name="id_history" id="id_history">
                        <input type="hidden" name="id_stt" id="id_stt" value="{{ $data->id_stt }}">

                        <label class="mt-3">
                            Pilih Status Stt
                        </label>
                        <select name="id_status" id="id_status" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            @foreach ($status as $key => $value)
                                <option value="{{ $value->id_ord_stt_stat }}">{{ strtoupper($value->nm_ord_stt_stat) }}
                                </option>
                            @endforeach
                        </select>

                        <label class="mt-3">
                            Pilih Wilayah
                        </label>
                        <select class="form-control" id="id_kota" name="id_kota"></select>

                        <label class="mt-3">
                            Keterangan Tambahan
                        </label>

                        <textarea class="form-control" id="info" name="info"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" id="simpanstatus" name="simpanstatus">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#id_kota').select2({
            minimumInputLength: 0,
            placeholder: 'Cari Kota ....',
            allowClear: true,
            ajax: {
                url: '{{ url('getKota') }}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#id_kota').empty();
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                cache: true
            }
        });

        function getEdit(id, ket_koli, keterangan) {
            $("#ket_koli").val(ket_koli);
            $("#keterangan").val(keterangan);

            $("#form-detail").attr("action", "{{ url('stt/updatestt') }}/" + id);
        }

        function updateStatus(id_history, id_status, tgl, wil, place, info) {
            var today = new Date().toISOString().split('T')[0];
            $('#id_history').val(id_history);
            $("#id_kota").append('<option value=' + wil + '>' + place + '</option>');
            $("#tgl_update").val(tgl);
            $("#id_status").val(id_status);
            $("#info").val(info);
            $("#modal-update").modal('show');
        }

        $("#simpanstatus").click(function(e) {
            e.preventDefault();
            let id_history = $('#id_history').val();
            let id_stt = $('#id_stt').val();
            let id_status = $('#id_status').val();
            let id_kota = $('#id_kota').val();
            let tgl_update = $('#tgl_update').val();
            let info = $('#info').val();
            $.ajax({
                type: 'POST',
                data: {
                    "id_history": id_history,
                    "id_stt": id_stt,
                    "id_status": id_status,
                    "id_kota": id_kota,
                    "tgl_update": tgl_update,
                    "info": info,
                    "_token": "{{ csrf_token() }}",
                },
                url: '{{ route('editupdatestatusajax') }}',
                success: function(data) {
                    // console.log(data);
                    location.reload();
                    $("#modal-update").modal('hide');
                }
            });
        });

        function getBatal() {
            $("#ket_koli").val('');
            $("#keterangan").val('');

            $("#form-detail").attr("action", "{{ url('stt/savedetail') }}/");
        }
    </script>
@endsection
