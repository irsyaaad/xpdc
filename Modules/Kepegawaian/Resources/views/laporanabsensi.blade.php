@extends('template.document2')

@section('data')

@if(Request::segment(1)=="laporanabsensi" or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-search" style="margin-top: -1%">
    <div class="row">
        <div class="col-md-12">
            <div id="accordion">
                <div class="card border-0">
                    <div id="headingOne" class="text-right">
                        <button class="btn btn-md btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa fa-filter"> </i> Filter
                        </button>
                    </div>
                    
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label style="font-weight : bold">
                                        Perusahaan Asal
                                    </label>
                                    
                                    <select class="form-control" id="f_perush" name="f_perush">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        @foreach($perusahaan as $key => $value)
                                        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                @if(Request::segment(1)=="laporanperijinan")
                                <div class="col-md-3">
                                    <label style="font-weight: bold;">
                                        Jenis Perijinan
                                    </label>
                                    <select class="form-control" id="f_jenis" name="f_jenis">
                                        <option value="">-- Jenis Perijinan --</option>
                                        @foreach($jenis as $key => $value)
                                        <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                
                                <div class="col-md-3">
                                    <label style="font-weight: bold;">
                                        Tanggal Awal
                                    </label>
                                    <input class="form-control" id="f_dr_tgl" name="f_dr_tgl" placeholder="Masukan Tanggal Awal" type="date" value="@if(isset($filter["f_dr_tgl"])){{ $filter["f_dr_tgl"] }}@endif" />
                                </div>
                                
                                <div class="col-md-3">
                                    <label style="font-weight: bold;">
                                        Tanggal Akhir
                                    </label>
                                    <input class="form-control" id="f_sp_tgl" name="f_sp_tgl" placeholder="Masukan Tanggal Awal" type="date" value="@if(isset($filter["f_sp_tgl"])){{ $filter["f_sp_tgl"] }}@endif" />
                                </div>
                                
                                <div class="col-md-3">
                                    <label style="font-weight: bold;">
                                        Status Aktif
                                    </label>
                                    <select class="form-control" id="f_status" name="f_status">
                                        <option value="">-- Semua --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Non Aktif</option>
                                    </select>
                                </div>
                                
                                @if(Request::segment(1)=="laporanperijinan")
                                <div class="col-md-6" style="margin-top: 30px">
                                    @include('kepegawaian::filter.eksport')
                                </div>
                                @else
                                <div class="col-md-12 text-right" style="margin-top: 5px">
                                    @include('kepegawaian::filter.eksport')
                                </div>
                                @endif
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-striped">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Perusahaan / Devisi</th>
                        <th>Tgl Absen</th>
                        <th> Jam Masuk</th>
                        <th> Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>
                            @if(isset($value->nm_karyawan))
                            {{ strtoupper($value->nm_karyawan) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_perush))
                            {{ strtoupper($value->nm_perush) }}
                            @endif
                        </td>
                        <td>
                            {{ daydate($value->tgl_absen).", ".dateindo($value->tgl_absen) }}
                        </td>
                        <td>
                            {{ $value->jam_datang }}
                        </td>
                        <td>
                            {{ $value->jam_pulang }}
                        </td>
                        <td>
                            @if($value->status_datang==1)
                            <label style="color: green">Absen Sebelum Jam Masuk</label>
                            @elseif($value->status_datang==2)
                            <label style="color: red">Absen Terlambat</label>
                            @elseif($value->status_datang==3)
                            <label style="color: red">Tidak Absen Masuk</label>
                            @endif
                            <br>
                            
                            @if($value->status_pulang==4)
                            <label style="color: red">Tidak Absen Pulang</label>
                            @elseif($value->status_pulang==5)
                            <label style="color: red">Absen Pulang Dahulu</label>
                            @endif
                            
                        </td>
                        @php
                        $no++;
                        @endphp
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-2">
            Halaman : <b>{{ $data->currentPage() }}</b>
        </div>
        <div class="col-md-2">
            Jumlah Data : <b>{{ $data->total() }}</b>
        </div>
        <div class="col-md-3">
            <form method="POST" action="{{ url('absensi/page') }}" id="form-share" name="form-share">
                @csrf
                <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="500">500 Data</option>
                    <option value="1000">500 Data</option>
                </select>
            </form>
        </div>
        <div class="col-md-5" style="width: 100%">
            {{ $data->links() }}
        </div>
    </div>
</form>

@endif

@endsection

@section('script')
<script>
    function goDownload(){
        $("#modal-finger").modal("show");
    }
    
    $("#f_perush").val('{{ Session("perusahaan")["id_perush"] }}').trigger("change");
    
    @if(isset($filter["f_perush"]))
    $("#f_perush").val('{{ $filter["f_perush"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_status"]))
    $("#f_status").val('{{ $filter["f_status"] }}').trigger("change");
    @endif

    $("#f_perush").select2();
    $("#f_status").select2();
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif
    
    $("#shareselect").on("change", function(e) {
        $("#form-search").submit();
    });
    
</script>
@endsection