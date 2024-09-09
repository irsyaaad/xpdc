@extends('template.document')

@section('data')

@if(Request::segment(1)=="sttbelumbayar" && Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row-mt 1">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>No. STT</th>
                        <th>Pelanggan</th>
                        <th>Pengirim / Asal</th>
                        <th>Penerima / Tujuan</th>
                        <th>Total Omzet</th>
                        <th>Bayar</th>
                        <th>Sisa</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $nama = null;
                    @endphp
                    @if(count($data)==null)
                    <tr>
                        <td colspan="11" class="text-center"> Tidak ada data </td>
                    </tr>
                    @endif
                    @foreach($data as $key => $value)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ strtoupper($value->kode_stt) }}
                            <br>{{ dateindo($value->tgl_masuk) }}
                        </td>
                        <td>
                            @if(isset($value->nm_pelanggan))
                            {{ strtoupper($value->nm_pelanggan) }}
                            @php
                            $nama = $value->nm_pelanggan;
                            @endphp
                            @endif
                        </td>
                        <td>
                            @if(isset($value->pengirim_nm))
                            {{ strtoupper($value->nm_pelanggan) }}
                            @endif<br>@if(isset($value->asal)){{ $value->asal }}@endif
                        </td>
                        <td>
                            {{ $value->penerima_nm }}<br>@if(isset($value->tujuan)){{ $value->tujuan }}@endif
                        </td>
                        <td>
                            {{ toRupiah($value->c_total) }}
                        </td>
                        <td>
                            {{ toRupiah($value->n_bayar) }}
                        </td>
                        <td>
                            @php
                                $sisa = $value->c_total - $value->n_bayar;
                                $value->sisa = $sisa;
                            @endphp
                            {{ toRupiah($sisa) }}
                        </td>
                        @php
                            $bags = json_encode($value, true);
                        @endphp
                        <td width="6%" class="text-center">
                            <a href="#" class="btn btn-sm btn-success" onclick="setBayar({{ $bags }})">
                                <i class="fa fa-money"></i> Bayar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-2">
            @include("template.paginate")
        </div>
    </div>
</form>

@include('keuangan::bayar.modal');
<script type="text/javascript">
    $("#f_id_pelanggan").select2();
    $("#f_id_stt").select2();
    $("#f_id_layanan").select2();
    
    function setBayar(data) {
        var nama = data['nm_pelanggan'];
        var id_plgn = data['id_pelanggan'];
        var today = new Date().toISOString().split('T')[0];

        $("#modal-dm").modal('show');
        $("#id_stt").val(data["kode_stt"]);
        $("#n_bayar").val(data["sisa"]);
        $("#tgl_bayar").val(today);
        $("#info").val("Pembayaran STT No. "+data["kode_stt"]+" Atas Nama "+nama);
        $("#nm_pelanggan").val(nama);
        $("#id_plgn").val(id_plgn);
        $("#form-bayar").attr("action", "{{ url('pembayaran') }}/store/"+data["id_stt"]);
    }
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif
    
    @if(isset($filter["f_id_layanan"]))
    $("#f_id_layanan").val('{{ $filter["f_id_layanan"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_id_pelanggan"]))
    $("#f_id_pelanggan").val('{{ $filter["f_id_pelanggan"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_id_stt"]))
    $("#f_id_stt").val('{{ $filter["f_id_stt"] }}').trigger("change");
    @endif
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
</script>
@endif
@endsection
