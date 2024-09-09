@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1) . '/import') }}" enctype="multipart/form-data" id="form-select">
    <div class="row">
        @csrf
        {{-- <div class="col-md-3">
            <label class="">
                Kode Booking
            </label>
            <input type="text" placeholder="cari booking ..." class="form-control" name="kode_booking" id="kode_booking" value="@if(isset($filter['kode_booking'])){{$filter['kode_booking']}}@endif">
        </div> --}}
        
        <div class="col-md-3">
            <label class="">
                Tanggal Awal Masuk
            </label>
            <input type="date" class="form-control" name="tgl_mulai" id="tgl_mulai" value="@if(isset($filter['tgl_mulai'])){{$filter['tgl_mulai']}}@endif">
        </div>
        
        <div class="col-md-3">
            <label class="">
                Tanggal Akhir Masuk
            </label>
            <input type="date" class="form-control" name="tgl_selesai" id="tgl_selesai" value="@if(isset($filter['tgl_selesai'])){{$filter['tgl_selesai']}}@endif">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-sm" style="margin-top:30px">
                <i class="fa fa-search"></i> Cari Data
            </button>    
        </div>
        <div class="col-md-12 mt-3">
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-hover">
                    <thead style="background-color: grey; color : #ffff; font-size:11pt;">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Tanggal Booking</th>
                            <th>Layanan</th>
                            <th>Nama Pengirim</th>
                            <th>Alamat Pengirim</th>
                            <th>Status</th>
                            <th>Wilayah</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $value)
                        <tr>
                            <td>{{ $value->kode_booking }}</td>
                            <td>{{ dateindo($value->created_at) }}</td>
                            <td>
                                @if(isset($layanan[$value->id_layanan]->nm_layanan))
                                {{ $layanan[$value->id_layanan]->nm_layanan." ( ".$layanan[$value->id_layanan]->kode_layanan." )" }}
                                @endif
                            </td>
                            <td>{{ $value->nama_pengirim }}</td>
                            <td>{{ $value->alamat_pengirim }}</td>
                            <td>{{ $value->nama_status }}</td>
                            <td>{{ $value->wilayah_pengirim }}</td>
                            <td>
                                <a class="btn btn-sm btn-info" target="_blank" href="{{ url("stt/".$value->kode_booking."/showimport") }}">
                                    <i class="fa fa-download"></i> Import
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
    $('#tgl_mulai').val('{{ date("Y-m-d", strtotime("-2 day")) }}');
    $('#tgl_selesai').val('{{ date("Y-m-d", strtotime("+2 day")) }}');
    
</script>
@endsection
