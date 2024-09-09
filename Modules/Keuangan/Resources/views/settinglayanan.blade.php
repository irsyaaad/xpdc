@extends('template.document')

@section('data')
    @if (
        (Request::segment(1) == 'settinglayanan' or Request::segment(1) == 'settinglayananperush') and
            Request::segment(2) == null)
        @if (Request::segment(1) == 'settinglayananperush')
            <div class="text-right">
                <a href="#" class="btn btn-sm btn-info" id="btn-generate"><i class="fa fa-retweet"></i>
                    Generate</a>
            </div>
            <br><br>
        @endif
        <form method="GET" action="#" enctype="multipart/form-data" id="form-select">
            @csrf
            <input type="hidden" name="_method" value="GET">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Group Biaya</th>
                        <th>Pendapatan</th>
                        <th>Diskon</th>
                        <th>PPN</th>
                        <th>Materai</th>
                        <th>Piutang</th>
                        <th>suransi</th>
                        <th>Packing</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                @if (isset($value->layanan->nm_layanan))
                                    {{ strtoupper($value->layanan->nm_layanan) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->pendapatan->nama))
                                    {{ strtoupper($value->pendapatan->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->diskon->nama))
                                    {{ strtoupper($value->diskon->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->ppn->nama))
                                    {{ strtoupper($value->ppn->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->materai->nama))
                                    {{ strtoupper($value->materai->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->piutang->nama))
                                    {{ strtoupper($value->piutang->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->asuransi->nama))
                                    {{ strtoupper($value->asuransi->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->packing->nama))
                                    {{ strtoupper($value->packing->nama) }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->user->username))
                                    {{ strtoupper($value->user->username) }}
                                @endif
                            </td>
                            <td>
                                {!! inc_edit($value->id_setting) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    @elseif(Request::segment(2) == 'create' or Request::segment(3) == 'edit')
        <form method="POST"
            action="@if (Request::segment(2) == 'create') {{ url(Request::segment(1)) }}@else{{ url(Request::segment(1), $data->id_setting) }} @endif"
            enctype="multipart/form-data">
            @if (Request::segment(3) == 'edit')
                {{ method_field('PUT') }}
            @endif

            @csrf

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="id_layanan">
                            <b>Group Pelanggan</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="id_layanan" id="id_layanan">
                            @foreach ($layanan as $key => $value)
                                <option value="{{ $value->id_layanan }}">{{ strtoupper($value->kode_layanan) }} -
                                    {{ strtoupper($value->nm_layanan) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('id_layanan'))
                            <label style="color: red">
                                {{ $errors->first('id_layanan') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_pendapatan">
                            <b>Akun Pendapatan</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_pendapatan" id="ac_pendapatan">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_pendapatan'))
                            <label style="color: red">
                                {{ $errors->first('ac_pendapatan') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_diskon">
                            <b>Akun Diskon</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_diskon" id="ac_diskon">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_diskon'))
                            <label style="color: red">
                                {{ $errors->first('ac_diskon') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_ppn">
                            <b>Akun PPN</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_ppn" id="ac_ppn">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_ppn'))
                            <label style="color: red">
                                {{ $errors->first('ac_ppn') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_materai">
                            <b>Akun Materai</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_materai" id="ac_materai">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_materai'))
                            <label style="color: red">
                                {{ $errors->first('ac_materai') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_piutang">
                            <b>Akun PIUTANG</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_piutang" id="ac_piutang">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_piutang'))
                            <label style="color: red">
                                {{ $errors->first('ac_piutang') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_piutang_tuju">
                            <b>Akun ASURANSI</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_asuransi" id="ac_asuransi">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_asuransi'))
                            <label style="color: red">
                                {{ $errors->first('ac_asuransi') }}
                            </label>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group m-form__group">
                        <label for="ac_packing">
                            <b>Akun Packing</b><span class="span-required"> *</span>
                        </label>

                        <select class="form-control" name="ac_packing" id="ac_packing">
                            @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('ac_packing'))
                            <label style="color: red">
                                {{ $errors->first('ac_packing') }}
                            </label>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-md-12 text-right">
                @include('template.inc_action')
            </div>
        </form>
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        $("#id_layanan").select2();
        $("#ac_pendapatan").select2();
        $("#ac_piutang").select2();
        $("#ac_diskon").select2();
        $("#ac_ppn").select2();
        $("#ac_materai").select2();
        $("#ac_asuransi").select2();
        $("#ac_packing").select2();

        @if (Request::segment(3) == 'edit')
            $("#id_layanan").val("{{ $data->id_layanan }}").trigger('change');
            $("#ac_pendapatan").val("{{ $data->ac_pendapatan }}").trigger('change');
            $("#ac_piutang").val("{{ $data->ac_piutang }}").trigger('change');
            $("#ac_diskon").val("{{ $data->ac_diskon }}").trigger('change');
            $("#ac_ppn").val("{{ $data->ac_ppn }}").trigger('change');
            $("#ac_materai").val("{{ $data->ac_materai }}").trigger('change');
            $("#ac_asuransi").val("{{ $data->ac_asuransi }}").trigger('change');
            $("#ac_packing").val("{{ $data->ac_packing }}").trigger('change');
        @endif

        $("#btn-generate").click(function() {
            if (confirm('Peringatan !!! \nGenerate Data akan mereplace data yang sudah ada sebelumnya.')) {
                var url = "{{ Request::segment(1) . '/generate' }}";
                window.location.href = url;
            } else {
                console.log('Thing was not saved to the database.');
            }
        });
    </script>
@endsection
