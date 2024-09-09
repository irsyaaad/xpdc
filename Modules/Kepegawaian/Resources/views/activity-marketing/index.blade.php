@extends('template.document')
@section('data')
    <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
        @include('filter.filter-' . Request::segment(1))
        <table class="table table-responsive table-striped" id="html_table" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Marketing</th>
                    <th>Pelanggan</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Activity</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <a
                                href="{{ url(Request::segment(1) . '/' . $value->id . '/show') }}">{{ dateindo($value->tgl) }}</a>
                        </td>
                        <td>
                            @if (isset($value->marketing->nm_marketing))
                                {{ strtoupper($value->marketing->nm_marketing) }}
                            @endif
                        </td>
                        <td>
                            @if (isset($value->pelanggan->nm_pelanggan))
                                {{ strtoupper($value->pelanggan->nm_pelanggan) }}
                            @endif
                        </td>
                        <td>{{ $value->nama }}</td>
                        <td>{{ $value->alamat }}</td>
                        <td>
                            @if (isset($value->activity->nama_activity))
                                {{ strtoupper($value->activity->nama_activity) }}
                            @endif
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
                                    aria-labelledby="dropdownMenuButton"> <a class="dropdown-item"
                                        href="{{ url(Request::segment(1) . '/' . $value->id . '/show') }}"><i
                                            class="fa fa-eye"></i> Detail</a>
                                    <a class="dropdown-item"
                                        href="{{ url(Request::segment(1) . '/' . $value->id . '/edit') }}"><i
                                            class="fa fa-pencil"></i> Edit</a>
                                    @method('DELETE')
                                    @csrf
                                    <a class="dropdown-item" href="#"
                                        onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id) }}')"><i
                                            class="fa fa-times"></i> Delete</a>
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
