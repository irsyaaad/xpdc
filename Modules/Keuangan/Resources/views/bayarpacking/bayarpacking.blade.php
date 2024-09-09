@extends('template.document2')

@section('data')
<style>
    .class-edit{
        text-decoration: none;
    }
</style>
@if(Request::segment(1)=="bayarpacking" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<div class="col-md-12" style="margin-top: 25px">
    <div class="text-right">
        <button class="btn btn-md btn-primary" type="button" onclick="goBayar()"><i class="fa fa-money"> </i> Set Bayar</button>
    </div>
    <br>
    <table class="table table-responsive table-striped" id="html_table" width="100%" >
        <thead  style="background-color: grey; color : #ffff;">>
            <tr>
                <th>No</th>
                <th>Kode STT</th>
                <th>No AWB</th>
                <th>Perusahaan Asal</th>
                <th>Pelanggan</th>
                <th>Pengirim</th>
                <th>Nominal</th>
                <th>Dibayar</th>
                <th>Sisa</th>
                <th>Status Bayar</th>
                <th width="10%">
                    <input type="checkbox" value="1" id="c_all" name="c_all"> Pilih Semua
                </th>
            </tr>
        </thead>
        <form method="POST" action="{{ url(Request::segment(1)) }}" id="form-bayar" enctype="multipart/form-data">
            @csrf
            <tbody>
                @foreach($data as $key => $value)
                <tr> 
                    <td>{{ ($key+1) }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ $value->no_awb }}</td>
                    <td>{{ $value->nm_perush }}</td>
                    <td>{{ $value->nm_pelanggan }}</td>
                    <td>{{ $value->nm_pengirim }}</td>
                    <td>{{ $value->n_total }}</td>
                    <td>{{ torupiah($value->n_bayar) }}</td>
                    @php
                    $sisa = $value->n_total - $value->n_bayar;
                    @endphp
                    <td>
                        {{ torupiah($sisa) }}
                        <input class="sum-total" type="hidden" id="n_total" name="n_total[]" value="{{ $sisa }}" />
                    </td>
                    <td>
                        @if($value->is_lunas == true)
                        <i class="fa fa-check text-success"></i>
                        @else
                        <i class="fa fa-times text-danger"></i>
                        @endif
                    </td>
                    <td>
                        @if($value->n_bayar < $value->n_total)
                        <input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_packing }}" class="form-control c_pro" value="{{  $value->id_packing }}">
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
    
    <div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <h4 style="margin-left: 5%; font-weight: bold;" id="txtjudul"><span><i class="fa fa-check"></i></span> Bayar Packing Barang </h4>
                            </center>
                            <hr>
                        </div>
                        
                        <div class="col-md-12" style="margin-top : 15px">
                            <label for="id_stt">
                                <b>Akun Bank / Kas</b> <span class="text-danger"> * </span>
                            </label>
                            <select id="ac4_d" name="ac4_d" type="text" class="form-control" required > 
                                @foreach($ac as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('ac4_d'))
                            <label style="color: red">
                                {{ $errors->first('ac4_d') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12" style="margin-top : 15px">
                            <label for="n_bayar">
                                <b>Nominal</b> <span class="text-danger"> * </span>
                            </label>
                            <input id="n_bayar" name="n_bayar" type="number" class="form-control" required placeholder="masukan nominal bayar" />

                            @if ($errors->has('n_bayar'))
                            <label style="color: red">
                                {{ $errors->first('n_bayar') }}
                            </label>
                            @endif
                            
                        </div>
                        
                        <div class="col-md-12" style="margin-top : 15px">
                            <label for="keterangan">
                                <b>Keterangan</b>
                            </label>
                            <textarea id="keterangan" name="keterangan"class="form-control" placeholder="masukan nominal bayar"></textarea>
                            @if ($errors->has('keterangan'))
                            <label style="color: red">
                                {{ $errors->first('keterangan') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 text-right" style="margin-top : 15px">
                            <button type="button" class="btn btn-md btn-success" onclick="goSubmit()">Iya</button>
                            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close" style="margin-left:10px"><span aria-hidden="true">tidak</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row" style="margin-top: 4%; font-weight: bold;">
    <div class="col-md-3">
        Halaman : <b>{{ $data->currentPage() }}</b>
    </div>
    <div class="col-md-3">
        @if(Request::segment(2)=="filter")
        <form method="POST" action="{{ url('biayahpp/filter') }}" id="form-share" name="form-share">
            @else
            <form method="POST" action="{{ url('biayahpp/page') }}" id="form-share" name="form-share">
                @endif
                @csrf
                <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                    <option value="10">-- Tampil 10 Data --</option>
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="500">500 Data</option>
                </select>
            </form>
        </div>
        <div class="col-md-6" style="width: 100%">
            {{ $data->links() }}
        </div>
    </div>
</div>

@endif

@endsection

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
    
    function goSubmit(){
        $("#form-bayar").submit();
    }
</script>
@endsection