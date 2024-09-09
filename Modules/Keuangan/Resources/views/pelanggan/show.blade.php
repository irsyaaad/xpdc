@extends('template.document')

@section('data')
    <div class="row">
        <div class="col-8"></div>
        <div class="col-4 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
            <a href="cetak_pdf" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Cetak</a>
        </div>
    </div>

    <table class="table table-sm table-borderless">
        <tr>
            <td>Id Pelanggan</td>
            <td>:</td>
            <td>{{ $pelanggan->id_pelanggan }}</td>
        </tr>
        <tr>
            <td>Nama Pelanggan</td>
            <td>:</td>
            <td>{{ $pelanggan->nm_pelanggan }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pelanggan->alamat }}</td>
        </tr>
        <tr>
            <td>No Telp</td>
            <td>:</td>
            <td>{{ $pelanggan->telp }}</td>
        </tr>
    </table>
    <br>
    <form action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/show') }}" name="form-filter" id="form-filter"
        method="GET">
        @csrf
        <div class="col-xl-12">
            <div class="form-group row">
                <input type="hidden" value="{{ Request::segment(2) }}" name="id_pelanggan">
                <div class="col-md-3">
                    <div class="m-form__control">
                        <label style="font-weight : bold ">
                            Dari Tanggal
                        </label>
                        <input type="date" class="form-control" name="dr_tgl" id="dr_tgl"
                            value="@if(isset($filter['dr_tgl'])){{ $filter['dr_tgl'] }}@endif">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="m-form__control">
                        <label style="font-weight : bold ">
                            Sampai Tanggal
                        </label>
                        <input type="date" class="form-control" name="sp_tgl" id="sp_tgl"
                            value="@if(isset($filter['sp_tgl'])){{ $filter['sp_tgl'] }}@endif">
                    </div>
                </div>

                <div class="col-md-3" style="padding-top:4px">
                    <br>
                    <button class="btn btn-md btn-primary"><span><i class="fa fa-search"></i></span></button>
                    <a href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/show') }}"
                        class="btn btn-md btn-warning"><span><i class="fa fa-refresh"></i></span></a>
                </div>
            </div>
        </div>
    </form>
    <br>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Semua Data</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">Sudah Lunas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                aria-selected="false">Belum Lunas</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <table class="table table-responsive table-hover" id="html_table" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No Stt</th>
                        <th>Jumlah Koli</th>
                        <th>Info</th>
                        <th>Piutang</th>
                        <th>Bayar</th>
                        <th>Kurang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if (isset($value->kode_stt))
                                    <a href="#" onclick="goDetail('{{ $value->id_stt }}')" class="class-edit">
                                        {{ $value->kode_stt }}
                                    </a>
                                @endif
                                <br>
                                @if (isset($value->tgl_masuk))
                                    {{ dateindo($value->tgl_masuk) }}
                                @endif
                            </td>

                            <td>
                                @if (isset($value->n_koli))
                                    {{ $value->n_koli }}
                                @endif
                                <br>
                            </td>
                            <td>
                                @if (isset($value->n_berat))
                                    {{ $value->n_berat }} Kg
                                @endif
                                <br>
                                @if (isset($value->n_volume))
                                    {{ $value->n_volume }} KgV
                                @endif
                                <br>
                                @if (isset($value->n_kubik))
                                    {{ $value->n_kubik }} M3
                                @endif
                            </td>

                            <td>
                                @if (isset($value->piutang))
                                    Rp. {{ number_format($value->piutang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->bayar))
                                    Rp. {{ number_format($value->bayar, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->kurang))
                                    Rp. {{ number_format($value->kurang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($value->kurang == 0)
                                    <label class="badge badge-md badge-success">Lunas</label>
                                @else
                                    <label class="badge badge-md badge-danger">Belum Lunas</label>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row" style="margin-top: 4%; font-weight: bold;">
                <div class="col-md-2">
                    Halaman : <b>{{ $data->currentPage() }}</b>
                </div>
                <div class="col-md-2">
                    Jumlah Data : <b>{{ $data->total() }}</b>
                </div>
                <div class="col-md-3">
                    {{-- rubah setia view disini --}}
                    @if (Request::segment(2) == 'filter')
                        <form method="POST" action="{{ url('piutangpelanggan/filter') }}" id="form-share"
                            name="form-share">
                        @else
                            <form method="POST" action="{{ url('piutangpelanggan/page') }}" id="form-share"
                                name="form-share">
                    @endif
                    @csrf
                    <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                        <option value="10">-- Tampil 10 Data --</option>
                        <option value="50">50 Data</option>
                        <option value="100">100 Data</option>
                        <option value="500">500 Data</option>
                    </select>
                    </form>
                </div>
                <div class="col-md-5" style="width: 100%">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <table class="table table-responsive table-hover" id="html_table" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No Stt</th>
                        <th>Jumlah Koli</th>
                        <th>Info</th>
                        <th>Piutang</th>
                        <th>Bayar</th>
                        <th>Kurang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                    @endphp
                    @foreach ($lunas as $key => $value)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>
                                @if (isset($value->kode_stt))
                                    <a href="#" onclick="goDetail('{{ $value->id_stt }}')" class="class-edit">
                                        {{ $value->kode_stt }}
                                    </a>
                                @endif
                                <br>
                                @if (isset($value->tgl_masuk))
                                    {{ dateindo($value->tgl_masuk) }}
                                @endif
                            </td>

                            <td>
                                @if (isset($value->n_koli))
                                    {{ $value->n_koli }}
                                @endif
                                <br>
                            </td>
                            <td>
                                @if (isset($value->n_berat))
                                    {{ $value->n_berat }} Kg
                                @endif
                                <br>
                                @if (isset($value->n_volume))
                                    {{ $value->n_volume }} KgV
                                @endif
                                <br>
                                @if (isset($value->n_kubik))
                                    {{ $value->n_kubik }} M3
                                @endif
                            </td>

                            <td>
                                @if (isset($value->piutang))
                                    Rp. {{ number_format($value->piutang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->bayar))
                                    Rp. {{ number_format($value->bayar, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->kurang))
                                    Rp. {{ number_format($value->kurang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($value->kurang == 0)
                                    <label class="badge badge-md badge-success">Lunas</label>
                                @else
                                    <label class="badge badge-md badge-danger">Belum Lunas</label>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row" style="margin-top: 4%; font-weight: bold;">
                <div class="col-md-2">
                    Halaman : <b>{{ $lunas->currentPage() }}</b>
                </div>
                <div class="col-md-2">
                    Jumlah Data : <b>{{ $lunas->total() }}</b>
                </div>
                <div class="col-md-3">
                    {{-- rubah setia view disini --}}
                    @if (Request::segment(2) == 'filter')
                        <form method="POST" action="{{ url('piutangpelanggan/filter') }}" id="form-share"
                            name="form-share">
                        @else
                            <form method="POST" action="{{ url('piutangpelanggan/page') }}" id="form-share"
                                name="form-share">
                    @endif
                    @csrf
                    <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                        <option value="10">-- Tampil 10 Data --</option>
                        <option value="50">50 Data</option>
                        <option value="100">100 Data</option>
                        <option value="500">500 Data</option>
                    </select>
                    </form>
                </div>
                <div class="col-md-5" style="width: 100%">
                    {{ $lunas->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

            <table class="table table-responsive table-hover" id="html_table" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No Stt</th>
                        <th>Jumlah Koli</th>
                        <th>Info</th>
                        <th>Piutang</th>
                        <th>Bayar</th>
                        <th>Kurang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                    @endphp
                    @foreach ($belum as $key => $value)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>
                                @if (isset($value->kode_stt))
                                    <a href="#" onclick="goDetail('{{ $value->id_stt }}')" class="class-edit">
                                        {{ $value->kode_stt }}
                                    </a>
                                @endif
                                <br>
                                @if (isset($value->tgl_masuk))
                                    {{ dateindo($value->tgl_masuk) }}
                                @endif
                            </td>

                            <td>
                                @if (isset($value->n_koli))
                                    {{ $value->n_koli }}
                                @endif
                                <br>
                            </td>
                            <td>
                                @if (isset($value->n_berat))
                                    {{ $value->n_berat }} Kg
                                @endif
                                <br>
                                @if (isset($value->n_volume))
                                    {{ $value->n_volume }} KgV
                                @endif
                                <br>
                                @if (isset($value->n_kubik))
                                    {{ $value->n_kubik }} M3
                                @endif
                            </td>

                            <td>
                                @if (isset($value->piutang))
                                    Rp. {{ number_format($value->piutang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->bayar))
                                    Rp. {{ number_format($value->bayar, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if (isset($value->kurang))
                                    Rp. {{ number_format($value->kurang, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($value->kurang == 0)
                                    <label class="badge badge-md badge-success">Lunas</label>
                                @else
                                    <label class="badge badge-md badge-danger">Belum Lunas</label>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row" style="margin-top: 4%; font-weight: bold;">
                <div class="col-md-2">
                    Halaman : <b>{{ $belum->currentPage() }}</b>
                </div>
                <div class="col-md-2">
                    Jumlah Data : <b>{{ $belum->total() }}</b>
                </div>
                <div class="col-md-3">
                    {{-- rubah setia view disini --}}
                    @if (Request::segment(2) == 'filter')
                        <form method="POST" action="{{ url('piutangpelanggan/filter') }}" id="form-share"
                            name="form-share">
                        @else
                            <form method="POST" action="{{ url('piutangpelanggan/page') }}" id="form-share"
                                name="form-share">
                    @endif
                    @csrf
                    <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                        <option value="10">-- Tampil 10 Data --</option>
                        <option value="50">50 Data</option>
                        <option value="100">100 Data</option>
                        <option value="500">500 Data</option>
                    </select>
                    </form>
                </div>
                <div class="col-md-5" style="width: 100%">
                    {{ $belum->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-detail" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Detail Stt</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="hasil">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function goDetail(id) {
            $("#modal-detail").modal('show');
            $.ajax({
                type: "GET",
                url: "{{ url('detailstt') }}/" + id,
                dataType: "json",
                beforeSend: function(e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response) {
                    $("#hasil").html(response);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }
    </script>
@endsection
