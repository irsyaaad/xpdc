<div class="col-md-12">
    <form action="{{ url(Request::segment(1)) }}" method="POST" id="form-proyeksi">
        @csrf
        <table class="table table-responsive table-striped" id="html_table" width="100%">
        <thead  style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>No. DM</th>
                <th>Biaya</th>
                <th>Kelompok</th>
                <th>Nominal</th>
                <th>Dibayar</th>
                <th>Kurang</th>
                <th width="10%">
                        <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($biaya as $key => $value)
            
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>
                    @if(isset($value->id_dm))
                    {{  strtoupper($value->id_dm)  }}
                    @endif
                </td>
                <td>
                    @if(isset($value->nm_biaya_grup))
                    {{  strtoupper($value->nm_biaya_grup)  }}
                    @endif
                </td>
                <td>
                    @if(isset($value->klp))
                    {{  strtoupper($value->klp)  }}
                    @endif
                </td>
                <td >
                    {{ strtoupper(number_format($value->nominal, 0, ',', '.')) }}
                    <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $value->nominal }}" />
                </td>
                <td>
                    @if(isset($value->n_bayar))
                    {{  strtoupper($value->n_bayar)  }}
                    @else
                    {{0}}
                    @endif
                </td>
                <td>
                    @php
                    $kurang = (Double)$value->nominal-$value->n_bayar;
                    echo $kurang;
                    @endphp
                </td>
                <td>
                    <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_biaya }}" class="form-control c_pro" value="{{  $value->id_biaya }}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

        <div class="modal fade" id="modal-dm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <table>
                            <thead>
                                <tr>
                                    <th width="180px"> No. Handling </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($handling->id_handling)){{ strtoupper($handling->id_handling) }}@endif </b> </th>
                                </tr>
                                <tr>
                                    <th width="180px"> Perusahaan </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($handling->perusahaan->nm_perush)){{ strtoupper($handling->perusahaan->nm_perush) }}@endif </b> </th>
                                </tr>
                                <tr>
                                    <th width="180px"> Perusahaan Pengirim </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($handling->perusahaankirim->nm_perush)){{ strtoupper($handling->perusahaankirim->nm_perush) }}@endif </b> </th>
                                </tr>
                                <tr>
                                    <th width="180px"> Layanan </th>
                                    <th width="10px"> : </th>
                                    <th> <b> @if(isset($handling->layanan->nm_layanan)){{ strtoupper($handling->layanan->nm_layanan) }}@endif </b> </th>
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
                            <input class="form-control" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" readonly="true" />
                            @if ($errors->has('n_bayar'))
                            <label style="color: red">
                                {{ $errors->first('n_bayar') }}
                            </label>
                            @endif  
                        </div>
                        
                        <div class="form-group">
                            <label for="ac4_d" >Akun Debet<span class="span-required"> *</span></label> 
                            <select class="form-control" id="ac4_d" name="ac4_d"> 
                                <option value="1"> -- Pilih Akun Debet--</option>
                                <option value="200-100"> Hutang Cabang</option>
                                {{-- @foreach($bank as $key => $value)
                                <option value="{{ $value->id_bank_perush }}">{{ strtoupper($value->id_bank." - ".$value->atas_nama) }}</option> 
                                @endforeach --}}
                            </select>
                            @if ($errors->has('ac4_d'))
                            <label style="color: red">
                                {{ $errors->first('ac4_d') }}
                            </label>
                            @endif  
                        </div>
                        <div class="form-group">
                            <label for="info" >Keterangan<span class="span-required"> *</span></label> 
                            <textarea class="form-control" id="info" name="info" placeholder="Masukan Keterangan" style="min-height:100px"></textarea>
                            <input class="form-control" id="id_pro_bi" name="id_pro_bi" type="hidden" />
                            <input class="form-control" id="id_handling" name="id_handling" type="hidden" value="{{$handling->id_handling}}"/>
                            @if ($errors->has('info'))
                            <label style="color: red">
                                {{ $errors->first('info') }}
                            </label>
                            @endif  
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-success" id="modal-btn-si" onclick="goSubmitUpdate()">Bayar</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('script')
<script>
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
        $("#form-proyeksi").attr("action", "{{ url(Request::segment(1)) }}");
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
    
    function setSisa(id, total){
        $("#modal-dm").modal('show');
        $("#form-proyeksi").attr("action", "{{ url(Request::segment(1).'/sisa') }}");
        $("#n_bayar").val(total);
        $("#id_pro_bi").val(id);
    }

</script>
@endsection