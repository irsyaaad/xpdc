@extends('template.document2')

@section('data')
<style>
    .col-md-4{
        margin-top: 15px;
    }
    
    .col-md-12{
        margin-top: 15px;
    }
    
    #divbayar{
        margin-top: 15px;
        border-radius: 10px;
        padding: 5px;
        padding-bottom: 10px;
    }
    
    .modal-dialog {
        position:absolute;
        top:60% !important;
        left: 35% !important;
        transform: translate(0, -50%) !important;
        -ms-transform: translate(0, -50%) !important;
        -webkit-transform: translate(0, -50%) !important;
        width:90%;
        height:80%;
    }
</style>

<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data Handling</b>
        </h4>
    </div>
    
    <div class="col-md-6 text-right">
        @if(Request::segment(3)=="show")
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
        @else
        <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/show") }}" class="btn btn-sm btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
        @endif
    </div> 
    
    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="13%">No. Handling</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->kode_handling)){{ strtoupper($handling->kode_handling) }}@endif
                        </b>
                    </td>
                    
                    <td width="7%">Armada</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->armada->nm_armada)){{ strtoupper($handling->armada->nm_armada) }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Total Biaya</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->c_biaya)){{ "Rp. ".number_format($handling->c_biaya, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="13%">Perusahaan</td>
                    <td width="2%"><b>:</b></td>
                    <td width="15%">
                        <b>
                            @if(isset($handling->perusahaan->nm_perush)){{ strtoupper($handling->perusahaan->nm_perush) }}@endif
                        </b>
                    </td>
                    
                    <td width="7%">Sopir</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->sopir->nm_sopir)){{ strtoupper($handling->sopir->nm_sopir) }}@endif
                        </b>
                    </td>
                    
                    <td width="3%">Bayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->n_bayar)){{ "Rp. ".number_format($handling->n_bayar, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
                        </b>
                    </td>
                    
                </tr>
                <tr>
                    <td width="10%">Wilayah Asal </td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->asal->nama_wil)){{ strtoupper($handling->asal->nama_wil) }}@endif
                        </b>
                    </td>
                    
                    <td width="9%">Tgl Berangkat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->tgl_berangkat) and $handling->tgl_berangkat!=null)
                            <b>
                                {{ dateindo($handling->tgl_berangkat) }}
                            </b>
                            @else
                            -
                            @endif
                        </b>
                    </td>
                    
                    <td width="3%">Kurang</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        @php
                        $sisa = $handling->c_total - $handling->n_bayar;
                        @endphp
                        <b>
                            @if(isset($sisa)){{ "Rp. ".number_format($sisa, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Wilayah Tujuan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->nm_tuju)){{ strtoupper($handling->nm_tuju) }}@endif
                        </b>
                    </td>
                    
                    <td width="7%">Tgl Selesai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->tgl_selesai) and $handling->tgl_selesai!=null)
                            <b>
                                {{ dateindo($handling->tgl_selesai) }}
                            </b>
                            @else
                            -
                            @endif
                        </b>
                    </td>
                    
                    <td width="3%">Keterangan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($handling->info)){{ strtoupper($handling->info) }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-chevron-right"></i>
            <b>Data Biaya</b>
        </h4>
    </div>
    
    <div class="col-md-6 text-right">
        @if(Request::segment(3)=="show")
        <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/bayar") }}" class="btn btn-sm btn-success">
            <i class="fa fa-money"></i> Set	Bayar 
        </a>
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-biaya"><span> <i class="fa fa-plus"> </i> </span> Tambah Biaya</button>
        @else
        <button type="button"  class="btn btn-sm btn-success" onclick="goBayar()">
            <i class="fa fa-money"></i> Bayar 
        </button>
        @endif
    </div>
    
    <div class="col-md-12" style="margin-top: 5px">
        <table class="table table-responsive table-striped" id="html_table" width="100%">
            <thead  style="background-color: grey; color : #ffff">
                <tr>
                    <th rowspan="2">No. </th>
                    <th rowspan="2">Nomor DM</th>
                    <th rowspan="2">Nomor STT</th>
                    <th rowspan="2">Group biaya</th>
                    <th rowspan="2">Kelompok</th>
                    <th colspan="3" class="text-center">Nominal</th>
                    <th> </th>
                    <th colspan="2" class="text-center">Nama Akun (AC4)</th>
                    <th rowspan="2" width="10%">
                        @if(Request::segment(3)=="show")
                        Action
                        @else
                        <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                        @endif
                    </th>
                </tr>
                <tr>
                    <td>Biaya</td>
                    <td>Bayar</td>
                    <td>Sisa</td>
                    <th> </th>
                    <td>AC4 Debit</td>
                    <td>AC4 Kredit</td>                    
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                <tr>
                    @php
                    $sisa =$value->nominal - $value->n_bayar;
                    @endphp
                    <td>{{ $key+1 }}</td>
                    <td>
                        @if(isset($dm[$value->id_stt]->kode_dm))
                        {{ strtoupper($dm[$value->id_stt]->kode_dm) }}
                        @endif
                    </td>
                    <td>{{ strtoupper($value->kode_stt) }}</td>
                    <td>{{ strtoupper($value->nm_biaya_grup) }}</td>
                    <td>{{ strtoupper($value->klp) }}</td>
                    <td>{{ torupiah($value->nominal) }}
                        <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                    </td>
                    <td>{{ torupiah($value->n_bayar) }}</td>
                    <td>{{ torupiah($sisa) }}</td>
                    <td> </td>
                    <td>{{ $value->ac4_debit }}</td>
                    <td>{{ $value->ac4_kredit }}</td>
                    <td>
                        @if(Request::segment(3)=="show")
                        <form action="{{ url(Request::segment(1)).'/'.$value->id_biaya.'/deletebiaya' }}" method="post" id="form-delete{{ $value->id_biaya }}" name="form-delete{{ $value->id_biaya }}">
                            {{ method_field("DELETE") }}
                            @csrf
                            <button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_stt }}', '{{ $value->id_biaya }}', '{{ $value->id_biaya_grup }}', '{{ $value->nominal }}')" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                <span><i class="fa fa-edit"></i></span>
                            </button>
                            
                            <button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ $value->id_biaya }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                <span><i class="fa fa-times"></i></span>
                            </button>
                        </form>
                        @else
                        @if($value->is_lunas != true)
                        <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-bayar" name="form-bayar" enctype="multipart/form-data">
                            <input type="hidden" id="id_handling" name="id_handling" value="{{ Request::segment(2) }}" required readonly />
                            @csrf
                        <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_biaya }}" class="form-control c_pro" value="{{  $value->id_biaya }}">
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(Request::segment(3)=="bayar")
<div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran Biaya Handling</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
            $ldate = date('Y-m-d H:i:s')
            @endphp
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_ac" >AC4 Kredit Bayar<span class="span-required"> *</span></label> 
                    
                    <select class="form-control" id="id_ac" name="id_ac" required>
                        <option value="">-- Pilih Akun Bayar --</option>
                        @foreach($kasbank as $key => $value)
                        <option value="{{ $value->id_ac }}">{{ strtoupper($value->nm_ac) }}</option>
                        @endforeach
                    </select>
                    
                    @if ($errors->has('ac4_kredit'))
                    <label style="color: red">
                        {{ $errors->first('ac4_kredit') }}
                    </label>
                    @endif  
                </div>
                
                <div class="form-group">
                    <label for="n_bayar" >Nominal Bayar<span class="span-required"> *</span></label> 
                    <input class="form-control" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" maxlength="20" required />
                    @if ($errors->has('n_bayar'))
                    <label style="color: red">
                        {{ $errors->first('n_bayar') }}
                    </label>
                    @endif  
                </div>
                
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-money"> </i> Bayar</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> <i class=" fa fa-times"> </i> Batal</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
</div>

<div class="modal fade" id="modal-biaya" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            
            <form method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)."/savebiaya") }}" id="form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12" style="padding-top: 10px">
                            <label for="filter_stt">
                                <b>Nomor STT : </b> <span class="span-required"></span>
                            </label>
                            <select class="form-control m-input m-input--square" id="id_stt" name="id_stt">
                                <option value="">-- Pilih STT --</option>
                                @foreach($stt as $key => $value)
                                <option value="{{ $value->id_stt }}">{{ strtoupper($value->kode_stt) }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_handling" id="id_handling" value="{{ Request::segment(2) }}"/> 
                        </div>
                        
                        <div class="col-md-12" style="padding-top: 10px">
                            <label for="id_biaya_grup">
                                <b>Group Biaya</b> <span class="span-required"> *</span>
                            </label>
                            
                            <select class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" required>
                                <option value="">-- Pilih Group Biaya --</option>
                                @foreach($group as $key => $value)
                                <option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_biaya_grup'))
                            <label style="color: red">
                                {{ $errors->first('id_biaya_grup') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12" style="padding-top: 10px">
                            <label for="nominal">
                                <b>Nominal Biaya</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required maxlength="16" />
                            
                            @if ($errors->has('nominal'))
                            <label style="color: red">
                                {{ $errors->first('nominal') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 text-right" style="padding-top: 10px">
                            <button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

@endsection

@section("script")
<script type="text/javascript">
    
    function goBayar(){
        $("#modal-bayar").modal("show");
        var total = 0;
        $('#html_table [name="c_pro[]"]').each(function(i, chk) {
            if (chk.checked) {
                var baru = parseFloat($(this).closest('td').parent().find('.sum-total').val());
                total += baru++;
            }
        });
        $("#n_bayar").val(total);
    }
    
    $(function(){
        $('#c_all').change(function()
        {
            if($(this).is(':checked')) {
                $(".c_pro").prop("checked", true);
            }else{   
                $(".c_pro").prop("checked", false);
            }
        });
    });
    
    @if(Request::segment(3)=="show")
    function goEdit(id_stt, id, id_group, nominal){
        $("#_method").val("PUT");
        $("#nominal").val(nominal);
        $("#id_stt").val(id_stt);
        $("#id_biaya_grup").val(id_group);
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}/"+id+"/updatebiaya");
        $("#modal-biaya").modal("show");
    }
    
    function refresh(){
        $("#_method").val("POST");
        $("#form-data").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2)) }}/savebiaya");
        $("#nominal").val("");
        $("#id_biaya_grup").val("");
    }
    
    @endif
</script>
@endsection