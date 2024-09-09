@extends('template.document2')

@section('data')
    <form method="GET" action="{{ url(Request::segment(1) . '/filter') }}" enctype="multipart/form-data" id="form-select">
        @php
            $rm = get_role_menu(Request::segment(1));
        @endphp
        @include('kepegawaian::filter.filter-collapse')
        <div class="row">
            <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-responsive table-hover">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Tgl Absen</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status Kehadiran</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr style="color: {{ $value->status_datang == 2 ? 'red' : 'green' }}">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if (isset($value->nm_karyawan))
                                        {{ strtoupper($value->nm_karyawan) }}
                                    @endif
                                </td>
                                <td>
                                    {{ daydate($value->tgl_absen) . ', ' . dateindo($value->tgl_absen) }}
                                </td>
                                <td>
                                    {{ $value->jam_datang }}
                                </td>
                                <td>
                                    {{ $value->jam_pulang }}
                                </td>
                                <td>
                                    <ul>
                                        @if ($value->status_datang == '2')
                                            <li class="text-danger">Terlambat</li>
                                        @endif
                                        @if ($value->jam_masuk == '00:00:00')
                                            <li class="text-danger">Tidak Absen Masuk</li>
                                        @endif
                                        {{-- @if ($value->jam_istirahat == '00:00:00')
                                <li class="text-danger">
                                    Tidak Absen Istirahat
                                </li>
                                @endif --}}
                                        {{-- @if ($value->jam_istirahat_masuk == '00:00:00')
                                <li class="text-danger">
                                    Tidak Absen Masuk Istirahat
                                </li>
                                @endif --}}
                                        @if ($value->jam_pulang == '00:00:00')
                                            <li class="text-danger">
                                                Tidak Absen Pulang
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    @if ($value->status_datang && $value->status_pulang != '0')
                                    <a href="{{ route('show-log', [
                                        'id_karyawan' => $value->id_karyawan,
                                        'tgl_absen' => $value->tgl_absen,
                                    ]) }}" style="color:black;" target="_blank"> <i class="fa fa-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('template.paginator')
        </div>
    </form>

    <div class="modal fade" id="modal-finger" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <center>
                    <h3 style="font-weight: bold; margin-top:10pt">Tarik Data Finger</h3>
                </center>
                <div class="modal-header">
                </div>
                <form method="POST" action="{{ url(Request::segment(1) . '') }}" enctype="multipart/form-data"
                    id="form-status">
                    <div class="modal-body">

                        @csrf
                        @php
                            $tgl = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                        @endphp

                        <div class="form-group m-form__group col-md-12" style="margin-top: -15px">
                            <label for="id_perush">
                                <b>Perusahaan / Devisi</b><span class="span-required"></span>
                            </label>

                            <select class="form-control m-input m-input--square" name="id_perush" id="id_perush" required>
                                <option value="">-- Pilih Perusahaan / Devisi --</option>
                                @foreach ($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('id_perush'))
                                <label style="color: red">
                                    {{ $errors->first('id_perush') }}
                                </label>
                            @endif
                        </div>

                        <div class="form-group m-form__group col-md-12">
                            <label for="start_date">
                                <b>Tanggal Awal</b><span class="span-required"></span>
                            </label>

                            <input type="date" class="form-control m-input m-input--square" name="start_date"
                                id="start_date" required value="{{ $tgl }}">

                            @if ($errors->has('start_date'))
                                <label style="color: red">
                                    {{ $errors->first('start_date') }}
                                </label>
                            @endif
                        </div>

                        <div class="form-group m-form__group col-md-12">
                            <label for="end_date">
                                <b>Tanggal Akhir</b><span class="span-required"></span>
                            </label>

                            <input type="date" class="form-control m-input m-input--square" name="end_date"
                                id="end_date" required value="{{ $tgl }}">

                            @if ($errors->has('end_date'))
                                <label style="color: red">
                                    {{ $errors->first('end_date') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">
                            <span> <i class="fa fa-download"></i></span> Tarik
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                            <span> <i class="fa fa-times"></i></span> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-by-mesin-finger" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <center>
                    <h3 style="font-weight: bold; margin-top:10pt">Tarik Data Finger</h3>
                </center>
                <div class="modal-header">
                </div>
                <form method="POST" action="{{ url(Request::segment(1) . '/download-by-mesin') }}"
                    enctype="multipart/form-data" id="form-status">
                    <div class="modal-body">

                        @csrf
                        @php
                            $tgl = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                        @endphp

                        <div class="form-group m-form__group col-md-12" style="margin-top: -15px">
                            <label for="id_perush">
                                <b>Perusahaan / Devisi</b><span class="span-required"></span>
                            </label>

                            <select class="form-control m-input m-input--square" name="id_mesin" id="id_mesin" required>
                                <option value="">-- Pilih Mesin Finger --</option>
                                @foreach ($list_mesin as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('id_perush'))
                                <label style="color: red">
                                    {{ $errors->first('id_perush') }}
                                </label>
                            @endif
                        </div>

                        <div class="form-group m-form__group col-md-12">
                            <label for="start_date">
                                <b>Tanggal Awal</b><span class="span-required"></span>
                            </label>

                            <input type="date" class="form-control m-input m-input--square" name="start_date"
                                id="start_date" required value="{{ $tgl }}">

                            @if ($errors->has('start_date'))
                                <label style="color: red">
                                    {{ $errors->first('start_date') }}
                                </label>
                            @endif
                        </div>

                        <div class="form-group m-form__group col-md-12">
                            <label for="end_date">
                                <b>Tanggal Akhir</b><span class="span-required"></span>
                            </label>

                            <input type="date" class="form-control m-input m-input--square" name="end_date"
                                id="end_date" required value="{{ $tgl }}">

                            @if ($errors->has('end_date'))
                                <label style="color: red">
                                    {{ $errors->first('end_date') }}
                                </label>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">
                            <span> <i class="fa fa-download"></i></span> Tarik
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                            <span> <i class="fa fa-times"></i></span> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pindah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <center>
                    <h3 style="font-weight: bold; margin-top:10pt">Pindah Data Absen</h3>
                </center>
                <div class="modal-header">
                </div>
                <form method="POST" action="{{ url(Request::segment(1) . '/' . Auth::user()->id_user . '/pindah') }}"
                    enctype="multipart/form-data" id="form-pindah">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group m-form__group col-md-12" style="margin-top: -15px">
                            <label for="p_id_karyawan">
                                <b>Karyawan</b><span class="span-required"></span>
                            </label>

                            <select class="form-control m-input m-input--square" name="p_id_karyawan" id="p_id_karyawan"
                                required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($pkaryawan as $key => $value)
                                    <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('p_id_karyawan'))
                                <label style="color: red">
                                    {{ $errors->first('p_id_karyawan') }}
                                </label>
                            @endif

                        </div>

                        <div class="form-group m-form__group col-md-12">
                            <label for="p_id_perush">
                                <b>Perusahaan Tujuan</b><span class="span-required"></span>
                            </label>

                            <select class="form-control m-input m-input--square" name="p_id_perush" id="p_id_perush"
                                required>
                                <option value="">-- Pilih Perusahaan / Devisi --</option>
                                @foreach ($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('p_id_perush'))
                                <label style="color: red">
                                    {{ $errors->first('p_id_perush') }}
                                </label>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="btn-pindah">
                            <span> <i class="fa fa-download"></i></span> Pindah
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                            <span> <i class="fa fa-times"></i></span> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function goDownload() {
            $("#modal-finger").modal("show");
        }

        function goDownloadMesin() {
            $("#modal-by-mesin-finger").modal("show");
        }

        function goPindah() {
            $("#modal-pindah").modal("show");
        }

        $("#id_perush").select2({
            dropdownParent: $("#modal-finger")
        });

        $("#p_id_karyawan").select2({
            dropdownParent: $("#modal-pindah")
        });

        $("#p_id_perush").select2({
            dropdownParent: $("#modal-pindah")
        });

        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });

        @if (isset($filter['page']))
            $("#shareselect").val('{{ $filter['page'] }}');
        @endif

        @if (isset($filter['id_perush']))
            $("#f_id_perush").val('{{ $filter['id_perush'] }}');
        @endif

        $("#f_id_karyawan").select2();
        $("#f_id_perush").select2();

        @if (isset($filter['f_id_karyawan']))
            $("#f_id_karyawan").val('{{ $filter['f_id_karyawan'] }}').trigger("change");
        @endif

        $('#f_id_perush').on("change", function(e) {
            $('#f_id_karyawan').empty();
            $.ajax({
                type: "GET",
                url: "{{ url('absensi/getkaryawan') }}/" + $("#f_id_perush").val(),
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    $('#f_id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
                    $.each(response, function(index, value) {
                        $('#f_id_karyawan').append('<option value="' + value.id_karyawan +
                            '">' + value.nm_karyawan + '</option>');
                    });
                    $("#f_id_karyawan").select2();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        });
    </script>
@endsection
