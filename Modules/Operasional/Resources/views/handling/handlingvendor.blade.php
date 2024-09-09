@extends('template.document2')

@section('data')
    <form method="GET" action="{{ url(Request::segment(1) . '/filter') }}" enctype="multipart/form-data" id="form-select">
        @include('template.filter-collapse')
        <div class="row" style="font-weight: bold;">
            <div class="col-md-12">
                <table class="table table-responsive table-hover">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No</th>
                            <th>No. RESI</th>
                            <th>Pelanggan > No. AWB</th>
                            <th>Pengirim > Asal</th>
                            <th>Penerima > Tujuan</th>
                            <th>Alamat Penerima</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->kode_stt }}</td>
                                <td>
                                    {{ $value->pelanggan->nm_pelanggan }} <br>
                                    {{ $value->no_awb }}
                                </td>
                                <td>
                                    {{ $value->pengirim_nm }} <br>
                                    {{ $value->asal->nama_wil }}
                                </td>
                                <td>
                                    {{ $value->penerima_nm }} <br>
                                    {{ $value->tujuan->nama_wil }}
                                </td>
                                <td>{{ $value->penerima_alm }}</td>
                                <td>
                                    @if ($value->id_status != 7)
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip"
                                            data-placement="bottom" title="Stt Sampai Tujuan">
                                            <span><i class="fa fa-check"></i></span> Update
                                        </button>
                                    @else
                                        Berhasil Sampai
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if (count($data) < 1)
                            <tr>
                                <td colspan="10" class="text-center">Data Kosong</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            @include('template.paginator')
        </div>
    </form>

    <div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>
                        Apakah Anda Barang Sudah Sampai ?
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="margin-top: -2%">
                    <form method="POST" action="{{ url('dmtiba/sampai') }}" enctype="multipart/form-data" id="form-end">
                        @csrf

                        <label style="font-weight : bold ">
                            Diterima Oleh<span class="text-danger"> *</span>
                        </label>
                        @php
                            $jenis_penerima = ['KELUARGA', 'TETANGGA', 'KERABAT', 'PENERIMA'];
                        @endphp
                        <select class="form-control" id="jenis_penerima" name="jenis_penerima">
                            <option value="">Pilih Penerima</option>
                            @foreach ($jenis_penerima as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        <br>
                        <br>
                        <h6>Tgl Update<span class="text-danger"> *</span> </h6>
                        <input type="date" class="form-control" name="tgl_update" value="{{ date('Y-m-d') }}"
                            id="tgl_update" required>
                        <br>

                        <h6>Foto Dokumentasi 1</h6>
                        <input class="form-control" name="dok1" id="dok1" type="file"
                            accept="image/*;capture=camera">
                        <img id="img1" name="img1" src="">
                        @if ($errors->has('dok1'))
                            <label style="color: red">
                                {{ $errors->first('dok1') }}
                            </label>
                        @endif

                        <br>

                        <h6>Foto Dokumentasi 2</h6>
                        <input class="form-control" name="dok2" id="dok2" type="file"
                            accept="image/*;capture=camera">
                        <img id="img2" name="img2" src="">
                        @if ($errors->has('dok2'))
                            <label style="color: red">
                                {{ $errors->first('dok2') }}
                            </label>
                        @endif

                        <input class="form-control" name="id_stt" id="id_stt_modal" required type="hidden" />
                        @if ($errors->has('id_stt'))
                            <label style="color: red">
                                {{ $errors->first('id_stt') }}
                            </label>
                        @endif

                        <h6>Keterangan<span class="span-required"> * </span></h6>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukan Keterangan ..." required></textarea>
                        @if ($errors->has('keterangan'))
                            <label style="color: red">
                                {{ $errors->first('keterangan') }}
                            </label>
                        @endif

                        <br>
                        <h6>Nama Penerima<span class="span-required"> * </span></h6>
                        <input type="text" class="form-control" name="nm_penerima" id="nm_penerima" maxlength="100"
                            placeholder="Masukan Nama Penerima ..." required />
                        @if ($errors->has('nm_penerima'))
                            <label style="color: red">
                                {{ $errors->first('nm_penerima') }}
                            </label>
                        @endif
                        <br>
                        <label style="font-weight : bold ">
                            Status<span class="text-danger"> *</span>
                        </label>
                        @php
                            $jenis_status = [
                                7 => 'BERHASIL DITERIMA',
                                8 => 'GAGAL'
                            ];
                        @endphp
                        <select class="form-control" id="jenis_status" name="jenis_status">
                            <option value="">Pilih Status</option>
                            @foreach ($jenis_status as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
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
@endsection

@section('script')
    <script type="text/javascript">
        $('#jenis_penerima').select2({
            dropdownParent: $('#modal-end'),
        });

        function CheckSampai(id = "") {
            $("#id_stt_modal").val(id);
            // $("#id_kota_handling").attr('required', true);
            // $("#dok1").attr('required', true);
            // $("#dok2").attr('required', true);
            $("#nm_penerima").attr('required', true);
            $("#modal-end").modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        var myInput = document.getElementById('dok1');

        function sendPic() {
            var file = myInput.files[0];

            // Send file here either by adding it to a `FormData` object 
            // and sending that via XHR, or by simply passing the file into 
            // the `send` method of an XHR instance.
        }

        myInput.addEventListener('change', sendPic, false);

        @if (isset($filter['page']))
            $("#shareselect").val('{{ $filter['page'] }}');
        @endif

        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });
    </script>
@endsection
