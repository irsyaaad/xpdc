@extends('template.document')

@section('data')
    <div class="col-md-12 text-right">
        <a href="{{ url(Request::segment(1) . '/' . $data->id_stt_kembali . '/cetak') }}" class="btn btn-sm btn-success"
            target="_blank"><i class="fa fa-print"></i> Cetak Surat Pengantar</a>
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <td width="30%">No. Dokumen</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->kode_stt_kembali))
                                    {{ $data->kode_stt_kembali }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Tanggal Terima</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->tgl))
                                    {{ daydate($data->tgl) . ', ' . dateindo($data->tgl) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Admin Penerima</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->user->nm_user))
                                    {{ strtoupper($data->user->nm_user) }}
                                @endif
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
                    <tr>
                        <td width="30%">Keterangan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                {{ $data->keterangan }}
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
                <b>DATA STT</b>
            </h4>
        </div>
        <form method="POST" action="{{ url('sttkembali/update-dokumen') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12" style="margin-top: 10px">
                <table class="table table-responsive table-bordered" id="tableasal">
                    <thead style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No. </th>
                            <th>Kode STT (AWB)</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Tipe Kiriman</th>
                            <th>Koli</th>
                            <th>Terima Foto</th>
                            <th>Terima Fisik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($detail as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }} </td>
                                <td>
                                    {{ strtoupper($value->kode_stt) }} ({{ strtoupper($value->no_awb) }})
                                    <br>
                                    {{ dateindo($value->tgl_masuk) }}
                                </td>
                                <td>{{ strtoupper($value->pengirim_nm) }}
                                    <br>
                                    <span class="label label-inline label-light-primary">
                                        {{ $value->pengirim_telp }}</span>
                                    <br>
                                    <span>{{ $value->asal->nama_wil }}</span>
                                </td>
                                <td>
                                    @isset($value->penerima_nm)
                                        {{ strtoupper($value->penerima_nm) }}
                                    @endisset
                                    <br>
                                    <span class="label label-inline label-light-primary">
                                        @isset($value->penerima_telp)
                                            {{ $value->penerima_telp }}
                                        @endisset
                                    </span>
                                    <br>
                                    <span>
                                        @isset($value->penerima_alm)
                                            {{ $value->tujuan->nama_wil }}
                                        @endisset
                                    </span>
                                </td>
                                <td>{{ isset($value->tipekirim->nm_tipe_kirim) ? $value->tipekirim->nm_tipe_kirim : '-' }}
                                </td>
                                <td>{{ $value->n_koli }}</td>
                                <td>
                                    <input class="form-control" type="checkbox"
                                        name="is_foto[{{ $value->id_stt }}]" id="is_foto"
                                        value="{{ $value->id_stt }}" {{ $value->is_foto == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input class="form-control" type="checkbox"
                                        name="is_fisik[{{ $value->id_stt }}]" id="is_fisik"
                                        value="{{ $value->id_stt }}" {{ $value->is_fisik == 1 ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 text-right">
                <div class="col-md-12 text-right">
                    <div class="form-group">
                        <div class="m-form__actions">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-save"></i> Update
                            </button>
                            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-danger">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if (Request::segment(1) == 'sttkembali')
        {{-- @include('operasional::sttkembali.modals') --}}
    @endif
    @include('operasional::sttkembali.modal-back')
@endsection
