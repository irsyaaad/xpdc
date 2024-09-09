<div class="row">
    <div class="col-md-12 text-right">
        @if($handling->is_approve==true and $handling->is_confirm==true  and Request::segment(1)=="handlingtujuan" and $handling->is_lunas!=true)
        <button class="btn btn-sm btn-success" type="button" onclick="setBayar()">
            <i class="fa fa-plus"></i> Set Bayar
        </button>
        @elseif($handling->is_approve==true and $handling->is_confirm==true  and Request::segment(1)=="biayahandling" and $handling->is_lunas!=true)
        <button class="btn btn-sm btn-success" type="button" onclick="setKonfirmasi()">
            <i class="fa fa-check"></i> Konfirmasi
        </button>
        @elseif($handling->is_approve!=true)
        <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/approve") }}" class="btn btn-sm btn-success">
            <i class="fa fa-check"></i>	Approve
        </a>
        @elseif($handling->id_perush_tj==Session("perusahaan")["id_perush"] && $handling->is_confirm!=true)
        <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/konfirmasi") }}" class="btn btn-sm btn-success">
            <i class="fa fa-check"></i>	Konfirmasi
        </a>
        @endif
    </div>
    
    <div class="col-md-12">
        <h4><b>Total Biaya : @if(isset($total)){{ "Rp. ".number_format($total, 0, ',', '.') }}@endif</b></h4>
        <table class="table table-responsive table-striped" id="html_table">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Kelompok Biaya</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>
                        @if($handling->is_lunas!=true)
                        <label>
                            <input type="checkbox" id="cek_all" name="cek_all" value="1">
                            Pilih Semua
                        </label>
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @if($handling->is_approve==true and $handling->is_confirm==true)
                <form method="POST" action="#" id="form-proyeksi" name="form-proyeksi">
                    @csrf
                    @endif
                    
                    @foreach($proyeksi as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ strtoupper($value->nm_biaya_grup) }}</td>
                        <td>{{ strtoupper($value->keterangan) }}</td>
                        <td>{{ strtoupper($value->nominal) }}
                            <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $value->nominal }}" />
                        </td>
                        <td>
                            @if($handling->is_approve!=true)
                            <center>
                                <button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_biaya }}')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a href="{{ url(Request::segment(1)."/deletebiaya/".$value->id_biaya) }}" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                            </center>
                            @elseif($handling->is_confirm==true)
                            @if($value->n_bayar==$value->nominal)
                            
                            @if($value->is_lunas==true)
                            Lunas
                            @else
                            @if(Request::segment(1)=="biayahandling")
                            <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_biaya }}" class="form-control c_pro" value="{{  $value->id_biaya }}">
                            @else
                            Menunggu Konfrimasi
                            @endif
                            @endif
                            
                            @else
                            <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_biaya }}" class="form-control c_pro" value="{{  $value->id_biaya }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($handling->is_approve!=true)
                    <form method="POST" action="{{ url(Request::segment(1).'/'.Request::segment(2))."/savebiaya" }}" id="form-proyeksi" name="form-proyeksi">
                        @csrf
                        <tr>
                            <td></td>
                            <td>
                                <select class="form-control m-input m-input--square" id="id_biaya_group" name="id_biaya_group" required>
                                    <option>-- Pilih Group Biaya --</option>
                                    @foreach($biaya as $key => $value)
                                    <option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('id_biaya_group'))
                                <label style="color: red">
                                    {{ $errors->first('id_biaya_group') }}
                                </label>
                                @endif 
                            </td>
                            
                            <td>
                                <textarea class="form-control m-input m-input--square" id="keterangan" name="keterangan"
                                placeholder="Masukan keterangan" style="min-height: 100px"></textarea>
                                
                                @if ($errors->has('keterangan'))
                                <label style="color: red">
                                    {{ $errors->first('keterangan') }}
                                </label>
                                @endif 
                            </td>
                            <td>
                                <input type="number" class="form-control m-input m-input--square" id="nominal" name="nominal"
                                placeholder="Masukan Nominal Biaya" required />
                                @if ($errors->has('nominal'))
                                <label style="color: red">
                                    {{ $errors->first('nominal') }}
                                </label>
                                @endif 
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button type="button" onclick="goCancel()" class="btn btn-sm btn-warning">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </td>
                        </tr>
                    </form>
                    @endif
                </tbody>
            </table>
            
            @if($handling->is_approve==true and $handling->is_confirm==true  and Request::segment(1)=="handlingtujuan")
            <div class="modal fade" id="modal-handling" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
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
                                        <th width="180px"> No. Invoice </th>
                                        <th width="10px"> : </th>
                                        <th> <b> @if(isset($handling->id_invoice)){{ strtoupper($handling->id_invoice) }}@endif </b> </th>
                                    </tr>
                                    <tr>
                                        <th width="180px"> Perusahaan </th>
                                        <th width="10px"> : </th>
                                        <th> <b> @if(isset($handling->perush_asal->nm_perush)){{ strtoupper($handling->perush_asal->nm_perush) }}@endif </b> </th>
                                    </tr>
                                    <tr>
                                        <th width="180px"> Perusahaan  Penerima</th>
                                        <th width="10px"> : </th>
                                        <th> <b> @if(isset($handling->perush_tj->nm_perush)){{ strtoupper($handling->perush_tj->nm_perush) }}@endif </b> </th>
                                    </tr>
                                </thead>
                            </table>
                            
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
                                <label for="ac4_k" >Perkiraan Akun Asal<span class="span-required"> *</span></label> 
                                <select class="form-control" id="ac4_k" name="ac4_k"> 
                                    <option value="1"> -- Pilih Akun --</option>
                                    @foreach ($ac as $key => $value)
                                    <option value="{{ $value->id_ac }}"> {{ "(".$value->id_ac.") ".strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ac4_k'))
                                <label style="color: red">
                                    {{ $errors->first('ac4_k') }}
                                </label>
                                @endif  
                            </div>
                            
                            <div class="form-group">
                                <label for="ac_tujuan" >Perkiraan Akun Tujuan<span class="span-required"> *</span></label> 
                                <select class="form-control" id="ac_tujuan" name="ac_tujuan"> 
                                    <option value="1"> -- Pilih Akun --</option>
                                    @foreach ($ac_tujuan as $key => $value)
                                    <option value="{{ $value->id_ac }}"> {{ "(".$value->id_ac.") ".strtoupper($value->nama) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ac_tujuan'))
                                <label style="color: red">
                                    {{ $errors->first('ac_tujuan') }}
                                </label>
                                @endif  
                            </div>
                            
                            <div class="form-group">
                                <label for="n_bayar" >Nominal Bayar<span class="span-required"> *</span></label> 
                                <input class="form-control" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" readonly />
                                @if ($errors->has('n_bayar'))
                                <label style="color: red">
                                    {{ $errors->first('n_bayar') }}
                                </label>
                                @endif  
                            </div>
                            
                            <div class="form-group">
                                <label for="info" >Keterangan<span class="span-required"> *</span></label> 
                                <textarea class="form-control" id="info" name="info" placeholder="Masukan Keterangan" style="min-height:100px"></textarea>
                                <input class="form-control" id="id_pro_bi" name="id_pro_bi" type="hidden" />
                                @if ($errors->has('info'))
                                <label style="color: red">
                                    {{ $errors->first('info') }}
                                </label>
                                @endif  
                            </div>
                            
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Bayar</button>
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="modal fade" id="modal-konfirm" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4 style="margin-left: 10%; font-weight: bold;"> Konfirmasi Pembayaran Biaya Handling ?</h4>
                        <hr>
                        <div class="text-right">
                            <button type="submit" class="btn btn-md btn-success">Iya</button>
                            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @endif
    </div>
</div>

@section('script')
<script>
    function goEdit(id){
        $.ajax({
            type: "GET", 
            url: "{{ url(Request::segment(1)) }}/getBiaya/"+id, 
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $("#id_biaya_group").val(response.id_biaya_grup);
                $("#nominal").val(response.nominal);
                $("#keterangan").val(response.keterangan);
                $("#form-proyeksi").attr("action", "{{ url(Request::segment(1).'/updatebiaya/') }}/"+id);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    $("#tgl_bayar").val('{{ date("Y-m-d") }}');
    
    function goCancel(){
        $("#id_biaya_group").val("");
        $("#nominal").val("");
        $("#keterangan").val("");
        $("#form-proyeksi").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2))."/savebiaya" }}");
    }
    
    $('#cek_all').click(function() {
        if ($('#cek_all').is(':checked')) {
            $('input:checkbox').attr('checked', true);
        } else{
            $('input:checkbox').attr('checked', false);
        }
    });
    
    function setBayar() {
        $("#form-proyeksi").attr("action", "{{ url(Request::segment(1)) }}/store/{{ $handling->id_invoice }}");
        $("#modal-handling").modal('show');
        var total = 0;
        $('#html_table [name="c_pro[]"]').each(function(i, chk) {
            if (chk.checked) {
                var baru = parseFloat($(this).closest('td').parent().find('.sum-total').val());
                total += baru++;
            }
        });
        $("#n_bayar").val(total);
    }
    
    function setKonfirmasi() {
        $("#form-proyeksi").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2).'/confirmbayar') }}");
        $("#modal-konfirm").modal('show');
    }
    
    function goEdit(id, id_group, nominal){
        $("#_method").val("PUT");
        $("#nominal").val(nominal);
        $("#id_biaya_grup").val(id_group);
        $("#form-data").attr("action", "{{ url(Request::segment(1)) }}/"+id+"/updatebiaya");
        $("#modal-create").modal("show");
    }
    
    function refresh(){
        $("#_method").val("POST");
        $("#form-data").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2)) }}/savebiaya");
        $("#nominal").val("");
        $("#id_biaya_grup").val("");
    }
    
</script>
@endsection