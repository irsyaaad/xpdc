@extends('template.document')
@section('data')
    @if (Request::segment(2) == null)
        <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
            @include('kepegawaian::filter.filter-collapse')
            @csrf
            <div class="row ">
                <div class="col-md-12" style="overflow-x:auto;">
                    <table class="table table-responsive table-striped" width="100%">
                        <thead style="background-color: grey; color : #ffff">
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Kelamin</th>
                                <th>Jenis Karyawan</th>
                                <th>Perusahaan</th>
                                <th>Shift</th>
                                <th>Mesin Finger</th>
                                <th>Id Finger</th>
                                <th>Sopir</th>
                                <th>Aktif</th>
                                <th>User</th>
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>{{ strtoupper($value->nm_karyawan) }}</td>
                                    <td>
                                        @if ($value->jenis_kelamin == 'L')
                                            Laki - Laki
                                        @else
                                            Perempuan
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($value->jenis->nm_jenis))
                                            {{ $value->jenis->nm_jenis }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($value->perusahaan->nm_perush))
                                            {{ strtoupper($value->perusahaan->nm_perush) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($value->shift->shift))
                                            {{ strtoupper($value->shift->shift) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($value->mesin->nm_mesin))
                                            {{ strtoupper($value->mesin->nm_mesin) }}
                                        @endif
                                    </td>
                                    <td>{{ $value->id_finger }}</td>
                                    <td>
                                        @if ($value->is_sopir == 1)
                                            <i class="fa fa-check" style="color: green"></i>
                                        @else
                                            <i class="fa fa-times" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->is_aktif == 1)
                                            <i class="fa fa-check" style="color: green"></i>
                                        @else
                                            <i class="fa fa-times" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->is_user == 1)
                                            <i class="fa fa-check" style="color: green"></i>
                                        @else
                                            <i class="fa fa-times" style="color: red"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ url(Request::segment(1) . '/' . $value->id_karyawan . '/edit') }}"><i
                                                        class="fa fa-pencil"></i> Edit</a>
                                                <button class="dropdown-item" type="button" data-toggle="tooltip"
                                                    data-placement="bottom" title="Hapus"
                                                    onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_karyawan) }}')">
                                                    <span><i class="fa fa-times"></i></span> Hapus
                                                </button>
                                                @if ($value->is_user != 1)
                                                    <a class="dropdown-item"
                                                        href="{{ url(Request::segment(1) . '/' . $value->id_karyawan . '/setakses') }}">
                                                        <i class="fa fa-lock"></i> Set User</a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ url(Request::segment(1) . '/' . $value->id_karyawan . '/detail') }}">
                                                    <i class="fa fa-user"></i> Bioadata</a>
                                                @if (in_array(strtolower(Session('role')['nm_role']), ['keuangan']))
                                                    <a class="dropdown-item"
                                                        href="{{ url(Request::segment(1) . '/' . $value->id_karyawan . '/set-gaji') }}">
                                                        <i class="fa fa-money"></i> Set Gaji</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('template.paginate')
            </div>
        </form>
    @elseif(Request::segment(3) == 'setakses')
        <div class="col-md-12">
            <table>
                <tr>
                    <td>Nama Karyawan : <b>{{ strtoupper($data->nm_karyawan) }}</b></td>
                    <td>Perusahaan Asal : <b>
                            @if (isset($data->perusahaan->nm_perush))
                                {{ $data->perusahaan->nm_perush }}
                            @endif
                        </b></td>
                    <td>Jenis Karyawan : <b>
                            @if (isset($data->jenis->nm_jenis))
                                {{ strtoupper($data->jenis->nm_jenis) }}
                            @endif
                        </b></td>
                </tr>
            </table>
        </div>

        <div class="col-md-12" style="margin-top: 20px">
            <span><i class="fa fa-users"></i></span> Data Akses
        </div>

        <form class="m-form m-form--fit m-form--label-align-right" method="POST"
            action="{{ url('karyawan') . '/' . $data->id_karyawan . '/setakses' }}" enctype="multipart/form-data"
            style="margin-top: 10px">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nm_user">
                        <b>Nama User</b> <span class="span-required">*</span>
                    </label>

                    <input type="text" class="form-control m-input m-input--square" readonly="true" name="nm_user"
                        id="nm_user" required
                        value="@if (isset($data->nm_karyawan)) {{ $data->nm_karyawan }}@else{{ old('nm_karyawan') }} @endif"
                        required="required" maxlength="100">

                    @if ($errors->has('nm_user'))
                        <label style="color: red">
                            {{ $errors->first('nm_user') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="username">
                        <b>Username</b> <span class="span-required">*</span>
                    </label>

                    <input type="text" class="form-control m-input m-input--square" name="username" id="username"
                        placeholder="Masukan Username"
                        value="@if (isset($data->username)) {{ $data->username }}@else{{ old('username') }} @endif"
                        maxlength="40">

                    @if ($errors->has('username'))
                        <label style="color: red">
                            {{ $errors->first('username') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="email">
                        <b>Email</b>
                    </label>

                    <input type="email" class="form-control m-input m-input--square" name="email" id="email"
                        placeholder="Masukan Email"
                        value="@if (isset($data->email)) {{ $data->email }}@else{{ old('email') }} @endif"
                        maxlength="40">

                    @if ($errors->has('email'))
                        <label style="color: red">
                            {{ $errors->first('email') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="password">
                        <b>Password</b> <span class="span-required">*</span>
                    </label>

                    <input type="password" class="form-control m-input m-input--square" name="password" id="password"
                        placeholder="Masukan Password" value="{{ old('password') }}" maxlength="40">
                    <label style="margin-top: 5px"><input type="checkbox" value="1" id="showpass"
                            name="showpass"> Tampilkan Password</label>
                    @if ($errors->has('password'))
                        <label style="color: red">
                            {{ $errors->first('password') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="role_id">
                        <b>Role</b> <span class="span-required">*</span>
                    </label>

                    <select class="form-control" id="id_role" name="id_role">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($role as $key => $value)
                            <option value="{{ $value->id_role }}">{{ $value->nm_role }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('id_role'))
                        <label style="color: red">
                            {{ $errors->first('id_role') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="id_perush">
                        <b>Perusahaan</b> <span class="span-required">*</span>
                    </label>

                    <select class="form-control" id="id_perush" name="id_perush">
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach ($perush as $key => $value)
                            <option value="{{ $value->id_perush }}">{{ $value->nm_perush }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('id_perush'))
                        <label style="color: red">
                            {{ $errors->first('id_perush') }}
                        </label>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="is_kacab">
                        <b> <input type="checkbox" value="1" id="is_kacab" name="is_kacab"> Kepala Cabang </b>
                    </label>
                    @if ($errors->has('is_kacab'))
                        <label style="color: red">
                            {{ $errors->first('is_kacab') }}
                        </label>
                    @endif
                </div>

                <div class="col-md-12 text-right">
                    <div class="m-form__actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>

                        <a href="{{ url(Request::segment(1)) }}" class="btn btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @elseif(Request::segment(2) == 'create' or Request::segment(3) == 'edit')
        @include('create-karyawan')
    @endif
    <script>
        $("#f_id_perush").select2();
        $("#id_perush").select2();
        $("#id_role").select2();
        $("#f_karyawan").select2();

        @if (isset($data->id_jenis))
            $("#id_jenis").val('{{ $data->id_jenis }}');
        @endif

        @if (isset($data->jenis_kelamin))
            $("#jenis_kelamin").val('{{ $data->jenis_kelamin }}');
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->is_aktif) and $data->is_aktif == 1)
            $("#is_aktif").prop("checked", true);
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->id_mesin))
            $("#id_mesin").val('{{ $data->id_mesin }}');
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->id_jabatan))
            $("#id_jabatan").val('{{ $data->id_jabatan }}');
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->id_jabatan))
            $("#id_status_karyawan").val('{{ $data->status_karyawan }}');
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->id_jam_kerja))
            $("#id_jam_kerja").val('{{ $data->id_jam_kerja }}');
        @endif

        @if (Request::segment(3) == 'edit' and isset($data->perusahaan->nm_perush))
            $("#id_perush").val('{{ $data->id_perush }}');
        @endif

        $("#shareselect").change(function() {
            $("#form-select").submit();
        });

        @if (isset($filter['page']))
            $("#shareselect").val('{{ $filter['page'] }}');
        @endif

        @if (isset($filter['f_karyawan']))
            $("#f_karyawan").val('{{ $filter['f_karyawan'] }}').trigger("change");
        @endif

        @if (isset($filter['f_is_aktif']))
            $("#f_is_aktif").val('{{ $filter['f_is_aktif'] }}');
        @endif

        $('#showpass').change(function() {
            if ($(this).is(':checked')) {
                $("#password").attr("type", "text");
            } else {
                $("#password").attr("type", "password");
            }
        });

        $('#id_perush').on("change", function(e) {
            getJamKerja();
            getMesin();
        });

        function getMesin() {
            var id_perush = $("#id_perush").val();
            $.ajax({
                type: "get",
                url: "{{ url('getmesinfinger') }}/" + id_perush,
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    $('#id_mesin').empty();
                    $('#id_mesin').append('<option value="">-- Pilih Mesin --</option>');
                    $.each(response, function(index, value) {
                        $('#id_mesin').append('<option value="' + value.id_mesin + '">' + value
                            .nm_mesin + '</option>');
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }

        function getJamKerja() {
            var id_perush = $("#id_perush").val();
            $.ajax({
                type: "get",
                url: "{{ url('getJamKerja') }}/" + id_perush,
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    $('#id_jam_kerja').empty();
                    $('#id_jam_kerja').append('<option value="">-- Pilih Jam Kerja --</option>');
                    $.each(response, function(index, value) {
                        $('#id_jam_kerja').append('<option value="' + value.id_setting + '">' + value
                            .shift + ' ( ' + value.jam_masuk + ' - ' + value.jam_pulang +
                            ') </option>');
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }

        $('#id_jenis').on("change", function(e) {
            $.ajax({
                type: "get",
                url: "{{ url('getGaji') }}/" + this.value,
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    console.log(response);
                    $('#golongan').val(response.golongan);
                    $('#pangkat').val(response.pangkat);
                    $('#n_gaji').val(response.n_gaji);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        });

        $('#f_id_perush').on("change", function(e) {
            $.ajax({
                type: "get",
                url: "{{ url('getKaryawan') }}/" + this.value,
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    $("#f_karyawan").empty();
                    $("#f_karyawan").append('<option value="">-- Pilih Karyawan --</option>');

                    $.each(response, function(index, item) {
                        $("#f_karyawan").append('<option value="' + item.kode + '">' + item
                            .nama + '</option>');
                    });

                    $("#f_karyaan").select2();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        });
    </script>
@endsection
