@extends('template.document')

@section('data')
    <div class="col-md-12 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <td width="30%">Nama Marketing</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->marketing->nm_marketing))
                                    {{ $data->marketing->nm_marketing }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Jenis Activity</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->activity->nama_activity))
                                    {{ $data->activity->nama_activity }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Tanggal</td>
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
                        <td width="30%">Nama</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->nama))
                                    {{ strtoupper($data->nama) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Pelanggan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->pelanggan->nm_pelanggan))
                                    {{ strtoupper($data->pelanggan->nm_pelanggan) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Alamat</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->alamat))
                                    {{ strtoupper($data->alamat) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">Keterangan</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if (isset($data->keterangan))
                                    {{ strtoupper($data->keterangan) }}
                                @endif
                            </b>
                        </td>
                    </tr>
                </thead>
            </table>
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
