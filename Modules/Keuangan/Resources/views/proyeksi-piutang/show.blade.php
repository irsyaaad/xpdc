@extends('template.document')
@section('style')
<style>
    #modal-stt {
        padding: 0 !important;
    }
    #modal-stt .modal-dialog {
        width: 95%;
        max-width: none;
        margin: auto;
        top: 5%;
    }
    #modal-stt .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }
    #modal-stt .modal-body {
        overflow-y: auto;
    }
    .achievements-wrapper { min-height:400px; max-height: 400px; overflow-y: auto; }
</style>
@endsection

@section('data')
<div class="row">
    
    <div class="form-group col-md-3">
        <label for="level">
            <b>Bulan</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="bulan" id="bulan" disabled>
            <option value="">-- Pilih Bulan --</option>
            @foreach($bulan as $key => $value)
            <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-md-3">
        <label for="level">
            <b>Tahun</b>
        </label>
        
        <select class="form-control m-input m-input--square" name="tahun" id="tahun" disabled>
            <option value="">-- Pilih Tahun --</option>
            @foreach($tahun as $key => $value)
            <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    
    @php
    $tgl_awal = date("Y-m-d", strtotime($data->tahun."-".$data->bulan."-"."01"));
    $dates  = date("Y-m-d", strtotime($data->tahun."-".$data->bulan));
    $tgl_akhir = date("Y-m-t", strtotime($dates));
    @endphp
    <div class="form-group col-md-3">
        <label for="admin">
            <b>Tgl. Awal</b>
        </label>
        
        <input class="form-control m-input m-input--square" name="tgl_awal" id="tgl_awal" disabled value="{{ $tgl_awal }}"  />
    </div>
    
    <div class="form-group col-md-3">
        <label for="admin">
            <b>Tgl. Akhir</b>
        </label>
        
        <input class="form-control m-input m-input--square" name="tgl_akhir" id="tgl_akhir" disabled value="{{ $tgl_akhir }}"  />
    </div>
    
    <div class="form-group col-md-3">
        <label for="admin">
            <b>Admin Piutang</b>
        </label>
        
        <input class="form-control m-input m-input--square" name="user" id="user" disabled value="{{ $data->user->username }}"  />
    </div>
    
    <div class="form-group col-md-3">
        <label for="nominal">
            <b>Total Proyeksi</b>
        </label>
        
        <input class="form-control m-input m-input--square" name="nominal" id="nominal" disabled value="{{ toRupiah($sum) }}"  />
    </div>

    <div class="form-group col-md-3">
        <label for="nominal">
            <b>Perusahaan</b>
        </label>
        
        <input class="form-control m-input m-input--square" name="nominal" id="nominal" disabled value="{{ $data->perush->nm_perush }}"  />
    </div>
    
    <div class="col-md-12">
        <hr>
        <div class="row">
            <div class="col-md-3">
                <input class="form-control" type="text" name="tstt" id="tstt" placeholder="Cari Data ..." />
            </div>
            <div class="col-md-9 text-right">
                <button type="button" data-toggle="modal" data-target="#modal-pendapatan" onclick="goPlus()" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Stt</button>
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 mt-2">
        <form method="POST" action="#" enctype="multipart/form-data" id="form-select">
            @csrf
            <table class="table table-responsive table-striped" id="table_proyeksi">
                <thead style="background-color: grey; color : #ffff">
                    <th class="text-center">No. STT</th>
                    <th class="text-center">Tgl. Masuk</th>
                    <th class="text-center">Pelanggan</th>
                    <th class="text-center">Marketing</th>
                    <th class="text-center">Proyeksi Piutang (Rp.)</th>
                    <th >Updated At</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    @foreach($proyeksi as $key => $value)
                    <tr>
                        <td>{{ $value->kode_stt }}</td>
                        <td>{{ date("d-m-Y", strtotime($value->tgl_masuk)) }}</td>
                        <td>{{ $value->nm_pelanggan }}</td>
                        <td>{{ $value->nm_marketing }}</td>
                        <td>
                            {{ toRupiah($value->piutang) }}
                            <input type="hidden" id="piutang{{ $value->piutang }}" name="piutang[]" value="{{ $value->piutang }}"/>
                        </td>
                        <td class="text-center">{{ $value->updated_at }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id.'/deletedetail') }}')">
                                <span><i class="fa fa-times"></i></span> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-stt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-weight: bold;">
                    <span><i class="fa fa-money"></i></span> 
                    Data STT Belum Lunas
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url("proyeksipiutang/".$data->id."/savedetail") }}" id="form-stt">
                    <input type="hidden" name="_method" id="_method" value="POST" />
                    @csrf
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <input class="form-control" type="text" name="id_stt" id="id_stt" placeholder="Cari No. STT" />
                        </div>
                    </div>
                    <div class="achievements-wrapper">
                        <table class="table table-responsive" id="table_stt">
                            <thead style="background-color: grey; color : #ffff;">
                                <tr>
                                    <th class="text-center">No. STT</th>
                                    <th class="text-center">Tgl. Masuk</th>
                                    <th>Pelanggan</th>
                                    <th >Marketing</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Dibayar</th>
                                    <th class="text-center">Piutang</th>
                                    <th class="text-center">Updated At</th>
                                    <th class="text-right"><input type="checkbox" id="checkall" name="checkall" />  Check All </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stt as $key => $value)
                                @if($value->n_bayar<$value->c_total)
                                <tr>
                                    <td>{{ $value->kode_stt }}</td>
                                    <td>{{ date("d-m-Y", strtotime($value->tgl_masuk)) }}</td>
                                    <td>{{ $value->nm_pelanggan }}</td>
                                    <td>{{ $value->nm_marketing }}</td>
                                    <td class="text-right">{{ toRupiah($value->c_total) }}</td>
                                    <td class="text-right">{{ toRupiah($value->n_bayar) }}</td>
                                    <td class="text-right">
                                        @php
                                            $piutang = $value->c_total-$value->n_bayar;
                                        @endphp
                                        {{ toRupiah($piutang) }}
                                        <input type="hidden" id="piutang{{ $piutang}}" name="piutang[]" value="{{ $piutang }}"/>
                                    </td>
                                    <td>{{ $value->updated_at }}</td>
                                    <td>
                                        <input type="checkbox" id="check{{ $value->id_stt }}" name="check[]" value="{{ $value->id_stt }}" class="checkin" />
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" onclick="goSave()" class="btn btn-sm btn-success" >Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    @if(isset($data->bulan))
    $("#bulan").val("{{ $data->bulan }}");
    @endif
    
    @if(isset($data->tahun))
    $("#tahun").val("{{ $data->tahun }}");
    @endif
    
    function goPlus(){
        $("#modal-stt").modal("show");
    }

    function goSave(){
        $("#form-stt").submit();
    }
    
    $("#id_stt").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_stt tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $("#tstt").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_proyeksi tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $("#checkall").on("change", function() {
        if ($("#checkall").prop('checked')) {
            $(".checkin").attr("checked", true);
        }else{
            $(".checkin").attr("checked", false);
        }
    });
</script>
@endsection