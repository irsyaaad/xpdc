@extends('template.document2')

@section('data')

<style>
    #divbayar{
        margin-top: 15px;
        border-radius: 10px;
        padding: 5px;
        padding-bottom: 10px;
    }
</style>
<div class="col-md-12">
    <ul class="nav nav-tabs nav-bold nav-tabs-line">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabstt">
                <span class="nav-icon">
                    <i class="fa fa-calendar"></i>
                </span>
                <span class="nav-text">Biaya STT</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabumum">
                <span class="nav-icon">
                    <i class="fa fa-eye"></i>
                </span>
                <span class="nav-text">Biaya Umum</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabvendor">
                <span class="nav-icon">
                    <i class="fa fa-truck"></i>
                </span>
                <span class="nav-text">Biaya Vendor</span>
            </a>
        </li>
    </ul>
    
    <form action="@if(Request::segment(1)=="biayahppvendor"){{ url("biayahpp") }}@else {{ url(Request::segment(1)) }} @endif " method="POST" id="form-proyeksi">
        @csrf
        <div class="tab-content">
            <div class="tab-pane active show" id="tabstt" role="tabpanel" aria-labelledby="tabstt">
                <table class="table table-responsive table-striped" id="html_table" width="100%">
                    <thead  style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No.</th>
                            <th>Nomor STT</th>
                            <th>Nomor Handling</th>
                            <th>Biaya</th>
                            <th>Kelompok</th>
                            <th>Nominal</th>
                            <th>Dibayar</th>
                            <th>Kurang</th>
                            <th>Status</th>
                            <th width="10%">
                                <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bstt as $key => $value)
                        @php
                        $sisa = $value->nominal-$value->n_bayar;
                        @endphp
                        <tr>
                            <td>{{ strtoupper($key+1) }}</td>
                            <td>@if(isset($value->kode_stt)){{ strtoupper($value->kode_stt) }}@endif</td>
                            <td>{{ $value->kode_handling }}</td>
                            <td>
                                @if(isset($value->group->nm_biaya_grup))
                                {{  strtoupper($value->group->nm_biaya_grup)  }}
                                @endif
                            </td>
                            <td>
                                @if(isset($value->group->klp))
                                @if($value->group->klp==1)
                                HPP
                                @else
                                Operasional
                                @endif
                                @endif
                            </td>
                            <td >
                                {{ torupiah($value->nominal) }}
                            </td>
                            <td>
                                {{ torupiah($value->n_bayar) }}
                            </td>
                            <td>
                                {{ torupiah($sisa) }}
                                <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                            </td>
                            <td>
                                @if($value->is_lunas==true)
                                <i class="fa fa-check" style="color: green"></i>
                                @else
                                <i class="fa fa-times" style="color: red"></i>
                                @endif
                            </td>
                            <td>
                                @if($value->is_lunas != true)
                                <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_pro_bi }}" class="form-control c_pro" value="{{  $value->id_pro_bi }}">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tabumum" role="tabpanel" aria-labelledby="tabumum">
                <table class="table table-responsive table-striped" id="html_table" width="100%">
                    <thead  style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No.</th>
                            <th>Nomor STT</th>
                            <th>Nomor Handling</th>
                            <th>Biaya</th>
                            <th>Kelompok</th>
                            <th>Nominal</th>
                            <th>Dibayar</th>
                            <th>Kurang</th>
                            <th>Status</th>
                            <th width="10%">
                                <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bumum as $key => $value)
                        @php
                        $sisa = $value->nominal-$value->n_bayar;
                        @endphp
                        <tr>
                            <td>{{ strtoupper($key+1) }}</td>
                            <td>@if(isset($value->kode_stt)){{ strtoupper($value->kode_stt) }}@endif</td>
                            <td>{{ $value->kode_handling }}</td>
                            <td>
                                @if(isset($value->group->nm_biaya_grup))
                                {{  strtoupper($value->group->nm_biaya_grup)  }}
                                @endif
                            </td>
                            <td>
                                @if(isset($value->group->klp))
                                @if($value->group->klp==1)
                                HPP
                                @else
                                Operasional
                                @endif
                                @endif
                            </td>
                            <td >
                                {{ torupiah($value->nominal) }}
                            </td>
                            <td>
                                {{ torupiah($value->n_bayar) }}
                            </td>
                            <td>
                                {{ torupiah($sisa) }}
                                <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                            </td>
                            <td>
                                @if($value->is_lunas==true)
                                <i class="fa fa-check" style="color: green"></i>
                                @else
                                <i class="fa fa-times" style="color: red"></i>
                                @endif
                            </td>
                            <td>
                                @if($value->is_lunas != true)
                                <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_pro_bi }}" class="form-control c_pro" value="{{  $value->id_pro_bi }}">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tabvendor" role="tabpanel" aria-labelledby="tabvendor">
                <table class="table table-responsive table-striped" id="html_table" width="100%">
                    <thead  style="background-color: grey; color : #ffff">
                        <tr>
                            <th>No.</th>
                            <th>Nomor STT</th>
                            <th>Nomor Handling</th>
                            <th>Biaya</th>
                            <th>Kelompok</th>
                            <th>Nominal</th>
                            <th>Dibayar</th>
                            <th>Kurang</th>
                            <th>Status</th>
                            <th width="10%">
                                <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bvendor as $key => $value)
                        @php
                        $sisa = $value->nominal-$value->n_bayar;
                        @endphp
                        <tr>
                            <td>{{ strtoupper($key+1) }}</td>
                            <td>@if(isset($value->kode_stt)){{ strtoupper($value->kode_stt) }}@endif</td>
                            <td>{{ $value->kode_handling }}</td>
                            <td>
                                @if(isset($value->group->nm_biaya_grup))
                                {{  strtoupper($value->group->nm_biaya_grup)  }}
                                @endif
                            </td>
                            <td>
                                @if(isset($value->group->klp))
                                @if($value->group->klp==1)
                                HPP
                                @else
                                Operasional
                                @endif
                                @endif
                            </td>
                            <td >
                                {{ torupiah($value->nominal) }}
                            </td>
                            <td>
                                {{ torupiah($value->n_bayar) }}
                            </td>
                            <td>
                                {{ torupiah($sisa) }}
                                <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                            </td>
                            <td>
                                @if($value->is_lunas==true)
                                <i class="fa fa-check" style="color: green"></i>
                                @else
                                <i class="fa fa-times" style="color: red"></i>
                                @endif
                            </td>
                            <td>
                                @if($value->is_lunas != true)
                                <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_pro_bi }}" class="form-control c_pro" value="{{  $value->id_pro_bi }}">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal fade" id="modal-dm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran Biaya HPP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @php
                    $ldate = date('Y-m-d H:i:s')
                    @endphp
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th width="180px"> No. DM </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($dm->kode_dm)){{ strtoupper($dm->kode_dm) }}@endif </b> </th>
                                </tr>
                                <tr>
                                    <th width="180px"> Perusahaan Asal </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($dm->perush_asal->nm_perush)){{ strtoupper($dm->perush_asal->nm_perush) }}@endif </b> </th>
                                </tr>
                                @if(isset($dm->id_ven) and $dm->id_ven!=null)
                                <tr>
                                    <th width="180px"> Vendor Tujuan </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($dm->vendor->nm_ven)){{ strtoupper($dm->vendor->nm_ven) }}@endif </b> </th>
                                </tr>
                                @else
                                <tr>
                                    <th width="180px"> Perusahaan Tujuan </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($dm->perush_tujuan->nm_perush)){{ strtoupper($dm->perush_tujuan->nm_perush) }}@endif </b> </th>
                                </tr>
                                @endif
                                <tr>
                                    <th width="180px"> Layanan </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($dm->layanan->nm_layanan)){{ strtoupper($dm->layanan->nm_layanan) }}@endif </b> </th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <div class="form-group">
                            <label for="tgl_bayar" >Tanggal Bayar<span class="span-required"> *</span></label> 
                            <input class="form-control" id="tgl_bayar" name="tgl_bayar" type="date" placeholder="Masukan Tanggal Bayar" />
                            @if ($errors->has('tgl_bayar'))
                            <label style="color: red">
                                {{ $errors->first('tgl_bayar') }}
                            </label>
                            @endif  
                        </div>
                        
                        <div class="form-group">
                            <label for="n_bayar" >Nominal Bayar<span class="span-required"> *</span></label> 
                            <input class="form-control" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" />
                            @if ($errors->has('n_bayar'))
                            <label style="color: red">
                                {{ $errors->first('n_bayar') }}
                            </label>
                            @endif  
                        </div>
                        
                        <div class="form-group">
                            <label for="ac4_k" >Perkiraan Akun<span class="span-required"> *</span></label> 
                            <select class="form-control" id="ac4_k" name="ac4_k"> 
                                <option value="1"> -- Pilih Akun --</option>
                                @foreach ($akun as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('ac4_k'))
                            <label style="color: red">
                                {{ $errors->first('ac4_k') }}
                            </label>
                            @endif  
                        </div>
                        
                        <div class="col-md-12 text-right">
                            <input type="hidden" id="id_dm" name="id_dm" value="{{ $dm->id_dm }}">
                            <button type="button" class="btn btn-sm btn-success" id="modal-btn-si" onclick="goSubmitUpdate()">Bayar</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $("#divbayar").hide();
    
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
    
    function setBayar() {
        $("#modal-dm").modal('show');
        var total = 0;
        $('#html_table [name="c_pro[]"]').each(function(i, chk) {
            if (chk.checked) {
                var baru = parseFloat($(this).closest('td').parent().find('.sum-total').val());
                total += baru++;
            }
        });
        $("#n_bayar").val(total);
    }
    
    var today = new Date().toISOString().split('T')[0];
    $("#tgl_bayar").val(today);
    
    function goSubmitUpdate() {
        $("#form-proyeksi").submit();
    }
    
    $('#ac4_k').change(function(){	
        ChekCara();
    });
    
    // function check value cara bayar
    function ChekCara() {
        var cara = $("#ac4_k").val();
        
        if(cara!="" && cara =="100-101"){
            $("#divbayar").show();
        }else{
            $("#divbayar").hide();
        }   
    }
</script>
@endsection