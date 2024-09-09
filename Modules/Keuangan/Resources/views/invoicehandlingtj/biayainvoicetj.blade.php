
<div class="row">
    <div class="col-md-4">
        <h4 style="margin-left: 3%"><i class="fa fa-money"></i>
            <b>Data Biaya Invoice Handling</b>
        </h4>
    </div>
    @if($data->id_status < 3)
    <div class="col-md-8 text-right">
        <button class="btn btn-sm btn-success" type="button" onclick="goTerima()" style="margin-left: 10px;">
            <i class="fa fa-check"></i> Terima
        </button>
    </div>
    @endif
    <div class="col-md-12" style="margin-top:5px">
        <table class="table table-responsive table-bordered" id="html_table">
            <thead style="background-color : #ececec">
                <tr>
                    <th rowspan="2">Group Biaya</th>
                    <th rowspan="2">Kelompok</th>
                    <th rowspan="2">Nomor DM</th>
                    <th rowspan="2">Nomor STT</th>
                    <th colspan="2" class="text-center">Nama Akun (AC4)</th>
                    <th colspan="3" class="text-center">Nominal</th>
                    <th rowspan="2">Is Lunas ?</th>
                </tr>
                <tr>
                    <td>Hutang</td>
                    <td>Biaya</td>
                    
                    <td>Total</td>
                    <td>Bayar</td>
                    <td>Sisa</td>
                </tr>
            </thead>
            <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-bayar" name="form-bayar" enctype="multipart/form-data">
                <input type="hidden" id="id_invoice" name="id_invoice" value="{{ Request::segment(2) }}" required readonly />
                @csrf
                <tbody>
                    @foreach($biaya as $key => $value)
                    <tr>
                        @php
                        $sisa =$value->nominal - $value->dibayar;
                        @endphp
                        <td onclick="goPopUp('{{ $value->id_biaya_pend }}')"><a href="#" style="text-decoration: none">{{ $value->nm_biaya_grup }}</a></td>
                        <td>{{ $value->klp }}</td>
                        <td>{{ $value->kode_dm }}</td>
                        <td>{{ $value->kode_stt }}</td>
                        <td>@if($data->id_status > 2){{ $akun[$value->hutang]->nama }}@endif</td>
                        <td>@if($data->id_status > 2){{ $akun[$value->biaya]->nama }}@endif</td>
                        <td>{{ torupiah($value->nominal) }}</td>
                        <td>{{ torupiah($value->dibayar) }}</td>
                        <td>
                            {{ torupiah($sisa) }}
                            <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                        </td>
                        <td>
                            @if($value->is_lunas==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="modal fade" id="modal-show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Detail Pembayaran Biaya Invoice Handling</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-bordered" id="table_show">
                    <thead style="background-color : #ececec">
                        <th>Group Biaya</th>
                        <th>Tgl. Bayar</th>
                        <th>AC4 Debit</th>
                        <th>AC4 Kredit</th>
                        <th>Nominal Bayar</th>
                    </thead>
                    <tbody id="body_show">
                        <tr>
                            <td colspan="4" class="text-center">Data Kosong</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="form-group text-right">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> <i class=" fa fa-times"> </i> Tutup</span></button>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-terima" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="{{ url(Request::segment(1).'/'.$data->id_invoice."/terima") }}" id="form-send">
                    @csrf
                    <center>
                        <h4 style="margin-left: 5%; font-weight: bold;"> Terima Biaya Invoice Handling {{ $data->kode_invoice }} ?</h4>
                    </center>
                    <hr>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Terima</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" style="margin-left:10px"><span aria-hidden="true"><i class="fa fa-times"></i> Batal</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section("script")
<script type="text/javascript">
    function goPopUp(id){
        $("#table_show").closest('tr').remove();
        $("#modal-show").modal("show");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url(Request::segment(1)) }}/"+id+"/showbayar",
            success: function(data) {
                $("#table_show > tbody").html(data);
            },
        });
    }
    
    function goTerima(){
        $("#modal-terima").modal("show");
    }

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
    
</script>
@endsection