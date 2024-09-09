@extends('template.document')

@section('data')

@if(Request::segment(1)=="pembayaran" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row-mt 1">
        <div class="col-md-12  table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>No. Pembayaran</th>
                        <th>No. RESI</th>
                        <th>Cara Bayar</th>
                        <th>Nama Bayar / Penerima</th>
                        <th>Keterangan</th>
                        <th>Konfirmasi</th>
                        <th>Nominal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            <a href="{{ url(Request::segment(1)."/".$value->id_order_pay."/show") }}">
                                {{ $value->no_kwitansi }}
                                <br>
                                @if(isset($value->tgl)){{ dateindo($value->tgl) }}@endif
                            </a>
                        </td>
                        <td>
                            @isset($value->stt->kode_stt)
                            <span class="label label-inline mr-2">{{ strtoupper($value->stt->kode_stt) }}</span>
                            @endisset
                        </td>
                        <td>
                            @if(isset($value->cara->nm_cr_byr_o))
                            @if ($value->cara->id_cr_byr_o ==  1)
                            <span class="label label-success label-inline mr-2">{{ strtoupper($value->cara->nm_cr_byr_o) }}</span>
                            @elseif($value->cara->id_cr_byr_o ==  2)
                            <span class="label label-primary label-inline mr-2">{{ strtoupper($value->cara->nm_cr_byr_o) }}</span>
                            @else
                            <span class="label label-warning label-inline mr-2">{{ strtoupper($value->cara->nm_cr_byr_o) }}</span>
                            @endif
                            @endif
                        </td>
                        <td>{{ strtoupper($value->nm_bayar) }}
                            <br>
                            @if(isset($value->user->nm_user)){{ strtoupper($value->user->nm_user) }}@endif
                        </td>
                        <td>{{ $value->info }}</td>
                        <td>
                            @if($value->is_konfirmasi==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            {{ toRupiah($value->n_bayar) }}
                        </td>
                        @if(isset($value->pelanggan->nm_pelanggan))
                        @php
                        $nama = $value->pelanggan->nm_pelanggan;
                        @endphp
                        @endif
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    @if(isset($value->is_konfirmasi) && $value->is_konfirmasi!=true)
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_order_pay."/konfirmasi") }}"><i class="fa fa-check"></i> Konfirmasi</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_order_pay."/print") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak</a>
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_order_pay."/show") }}"><i class="fa fa-eye"></i> Detail</a>
                                    
                                    @php
                                    $today = date("Y-m-d");
                                $futureDate = date("Y-m-d", strtotime($value->created_at. ' + 3 days'));
                                    $difference = strtotime($futureDate) - strtotime($today);
                                    $days = abs($difference/(60 * 60)/24);
                                    @endphp
                                    
                                    @if($value->is_konfirmasi!=true and $days<=3  or (isset(Session("role")["nm_role"]) and strtolower(Session("role")["nm_role"])=="keuangan"))
                                    <a href="#" class="dropdown-item" onclick="setBayar({{$value}})">
                                        <i class="fa fa-edit"></i> Edit ({{  $days." tersisa" }})
                                    </a>
                                    <button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_order_pay) }}')">
                                        <span><i class="fa fa-times"></i></span> Hapus ({{  $days." tersisa" }})
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row" style="margin-top: 4%; font-weight: bold;">
        @include('template.paginate')
    </div>
</form>

@include('keuangan::bayar.modal');

@elseif(Request::segment(2)=="create")
@include('keuangan::bayar.create')
@elseif(Request::segment(3)=="bayar" or Request::segment(3)=="edit")
@include('keuangan::bayar.bayar')
@else
@include('keuangan::bayar.show')
@endif
@endsection

@section('script')
<script>
    function setBayar(data) {
        var kode_stt = data['stt']['kode_stt'];
        var nama = data['pelanggan']['nm_pelanggan'];
        var tgl_bayar = data["tgl"];
        var n_bayar = data["n_bayar"];
        var ac4_d = data["ac4_d"];
        var id_cr_byr = data["id_cr_byr"];
        var info = data["info"];
        var no_bayar = data["no_bayar"];
        var keterangan = data["info"];
        console.log(data);
        $("#modal-dm").modal('show');
        $("#id_stt").val(kode_stt);
        $("#tgl_bayar").val(tgl_bayar);
        $("#info").val(info);
        $("#nm_pelanggan").val(nama);
        $("#referensi").val(no_bayar);
        $("#ac4_d").val(ac4_d);
        $("#id_cr_byr").val(id_cr_byr);
        $("#n_bayar").val(n_bayar);
        $("#n_bayar").attr("readonly", true);
        $("#_method").val("PUT");
        $("#info").val(info);
        $("#form-bayar").attr("action", "{{ url(Request::segment(1)) }}/"+data["id_order_pay"]);
    }
</script>
@endsection
