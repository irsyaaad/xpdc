@extends('template.document')
@section('data')
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('karyawan/save-detail-gaji') }}"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_karyawan" id="id_karyawan" value="{{ $data->id_karyawan }}">
        <table class="table-sm table-borderless" width="100%">
            <tr>
                <td width="200">Nama Karyawan:</td>
                <td>{{ strtoupper($data->nm_karyawan) }}</td>
            </tr>
            <tr>
                <td>Golongan Pangkat:</td>
                <td>{{ isset($data->golongan) ? $data->golongan : '' }} / {{ isset($data->pangkat) ? $data->pangkat : '' }}
                </td>
            </tr>
            <tr>
                <td>Jabatan:</td>
                <td>{{ isset($data->jabatan->nm_jabatan) ? $data->jabatan->nm_jabatan : '' }}</td>
            </tr>
            <tr>
                <td>Bagian:</td>
                <td>{{ isset($data->jenis->nm_jenis) ? $data->jenis->nm_jenis : '' }}</td>
            </tr>
        </table>
        @php
            $total_tunjangan = 0;
            $total_potongan = 0;
            $tunjangan = [
                'n_tunjangan_jabatan' => 'Tunjangan Jabatan',
                'n_tunjangan_kinerja' => 'Tunjangan Kinerja',
                'n_tunjangan_kpi' => 'KPI',
            ];

            $tunj_nonthp = [
                'n_tunjangan_kesehatan' => 'Tunj. BPJS Kesehatan',
                'n_tunjangan_jht' => 'JHT',
                'n_tunjangan_jkk' => 'JKK',
                'n_tunjangan_jkm' => 'JKM',
                'n_tunjangan_jp' => 'JP',
            ];
            $potongan = [
                'n_potongan_pph' => 'PPh 21',
                'n_potongan_kesehatan' => 'Pot. BPJS Kesehatan',
                'n_potongan_jht' => 'Potongan JHT',
                'n_potongan_jp' => 'Potongan JP',
            ];

            foreach ($tunjangan as $key => $value) {
                $total_tunjangan += isset($data->$key) ? $data->$key : 0;
            }

            foreach ($potongan as $key => $value) {
                $total_potongan += isset($data->$key) ? $data->$key : 0;
            }
        @endphp
        <hr>
        <div class="row">

            <div class="col-6">
                <div class="form-group row">
                    <label class="col-3" for="gaji">
                        Gaji Pokok <span class="span-required">*</span>
                    </label>
                    <div class="col-8">
                        <input type="number" class="form-control m-input m-input--square" id="n_gaji" name="n_gaji"
                            value="{{ isset($gaji->n_gaji) ? $gaji->n_gaji : old('n_gaji') }}">
                    </div>

                    @if ($errors->has('n_gaji'))
                        <label style="color: red">
                            {{ $errors->first('n_gaji') }}
                        </label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h5>Tunjangan Non THP</h5>
                <hr>
                @foreach ($tunj_nonthp as $key => $value)
                    <div class="form-group row">
                        <label class="col-3" for="{{ $key }}">
                            {{ $value }} <span class="span-required">*</span>
                        </label>
                        <div class="col-8">
                            <input type="number" class="form-control m-input m-input--square" id="{{ $key }}"
                                name="{{ $key }}" value="{{ isset($gaji->$key) ? $gaji->$key : '' }}">
                        </div>

                        @if ($errors->has($key))
                            <label style="color: red">
                                {{ $errors->first($key) }}
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="col-6">
                <h5>Tunjangan</h5>
                <hr>
                @foreach ($tunjangan as $key => $value)
                    <div class="form-group row">
                        <label class="col-3" for="{{ $key }}">
                            {{ $value }} <span class="span-required">*</span>
                        </label>
                        <div class="col-8">
                            <input type="number" class="form-control m-input m-input--square" id="{{ $key }}"
                                name="{{ $key }}" value="{{ isset($gaji->$key) ? $gaji->$key : '' }}">
                        </div>

                        @if ($errors->has($key))
                            <label style="color: red">
                                {{ $errors->first($key) }}
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="col-6">
                <h5>Potongan</h5>
                <hr>
                @foreach ($potongan as $key => $value)
                    <div class="form-group row">
                        <label class="col-3" for="{{ $key }}">
                            {{ $value }} <span class="span-required">*</span>
                        </label>
                        <div class="col-8">
                            <input type="number" class="form-control m-input m-input--square" id="{{ $key }}"
                                name="{{ $key }}" value="{{ isset($gaji->$key) ? $gaji->$key : '' }}">
                        </div>

                        @if ($errors->has($key))
                            <label style="color: red">
                                {{ $errors->first($key) }}
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-12 text-right">
            <div class="form-group">
                <div class="m-form__actions">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script type="text/javascript">
        console.log('ss');
        // currency format
        $("#n_gaji").inputmask("999.999.999", {
            numericInput: true,
        });
    </script>
@endsection
