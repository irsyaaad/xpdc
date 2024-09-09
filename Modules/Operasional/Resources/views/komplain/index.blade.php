@extends('template.document')
@section('data')
    <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
        @include('filter.filter-' . Request::segment(1))
        <table class="table table-responsive table-hover" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>No Tiket</th>
                    <th>Jenis Komplain</th>
                    <th>Nama Pelapor</th>
                    <th>Kode STT</th>
                    <th>Info Complain</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <a href="{{ url(Request::segment(1) . '/' . $value->id . '/show') }}">{{ $value->no_ticket }}</a>
                            <br>
                            {{ dateindo($value->tgl_complain) }}
                        </td>
                        <td>{{ $value->jenis_komplain->nama_jenis }}</td>
                        <td>{{ $value->pelapor }} <br> {{ $value->hp_pelapor }}</td>
                        <td><a href="{{ url('stt/' . $value->id_stt . '/show') }}" target="_blank">
                                {{ $value->stt->kode_stt }}
                            </a> <br>
                            {{ isset($value->perush_tujuan->nm_perush) ? strtoupper($value->perush_tujuan->nm_perush) : '-' }}
                        </td>
                        <td>{{ $value->keterangan }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right"
                                    aria-labelledby="dropdownMenuButton">
                                    <form method="POST" action="{{ url(Request::segment(1) . '/' . $value->id) }}"
                                        id="form-delete{{ $value->id }}" name="form-delete{{ $value->id }}">@csrf
                                        <a class="dropdown-item"
                                            href="{{ url(Request::segment(1) . '/' . $value->id . '/edit') }}"><i
                                                class="fa fa-edit"></i> Edit</a>
                                        @if (Session('role')['id_role'] == 3)
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item" href="#"
                                                onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id) }}')"><i
                                                    class="fa fa-times"></i> Delete</a>
                                        @endif
                                        <a class="dropdown-item"
                                            href="{{ url(Request::segment(1) . '/' . $value->id . '/show') }}"><i
                                                class="fa fa-eye"></i> Detail</a>
                                        <a class="dropdown-item"
                                            href="{{ url(Request::segment(1) . '/' . $value->id . '/cetak') }}"
                                            target="_blank"><i class="fa fa-print"></i> Print</a>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    <div class="row" style="margin-top: 4%; font-weight: bold;">
        <div class="col-md-2">
            Halaman : <b>{{ $data->currentPage() }}</b>
        </div>
        <div class="col-md-2">
            Jumlah Data : <b>{{ $data->total() }}</b>
        </div>
        <div class="col-md-5" style="width: 100%">
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
