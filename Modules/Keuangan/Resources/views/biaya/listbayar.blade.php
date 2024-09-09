
@extends('template.document2')
@section("style")
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
</style>
@endsection

@section('data')
<div class="row">
    <div class="col-md-12">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. DM</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->kode_dm)){{ strtoupper($dm->kode_dm) }}@endif
                        </b>
                    </td>
                    
                    <td width="6%">Total Pendapatan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->c_total) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Perusahaan Asal</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->perush_asal->nm_perush)){{ strtoupper($dm->perush_asal->nm_perush) }}@endif
                        </b>
                    </td>
                    
                    <td width="6%">Biaya HPP</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->c_pro) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Vendor Tujuan</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->vendor->nm_ven)){{ strtoupper($dm->vendor->nm_ven) }}@endif
                        </b>
                    </td>
                    <td width="6%">Terbayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            {{ torupiah($dm->n_bayar) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Layanan </td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            @if(isset($dm->layanan->nm_layanan)){{ strtoupper($dm->layanan->nm_layanan) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Sisa</td>
                    <td width="2%"><b>:</b></td>
                    <td width="10%">
                        <b>
                            {{ torupiah($dm->c_pro-$dm->n_bayar) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="7%">Status</td>
                    <td width="2%"><b>:</b></td>
                    <td width="8%">
                        <b>
                            @if(isset($dm->status->nm_status)){{ strtoupper($dm->status->nm_status) }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" action="" id="form-select">
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/bayar") }}" class="btn btn-sm btn-primary"><span><i class="fa fa-money"> </i></span> Set Bayar</a>
            <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/detail") }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
        </div>
        
        @csrf
        <input type="hidden" name="_method" value="GET">
        <table class="table table-responsive table-bordered mt-1">
            <thead  style="background-color: grey; color : #ffff">
                <tr>
                    <th>No.</th>
                    <th>No. STT</th>
                    <th>No. Handling</th>
                    <th>Biaya > Kelompok</th>
                    <th>Tgl Bayar</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <th>Bayar</th>
                    <th>Last Edit</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bayar as $key => $value)
                <tr>
                    <td>{{ ($key+1) }}</td>
                    <td>{{ $value->kode_stt }}</td>
                    <td>{{ $value->kode_handling }}</td>
                    <td>{{ $value->nm_biaya_grup }}<br><label style="font-size:8pt">{{ " > ".$value->klp }}</label></td>
                    <td>{{ dateindo($value->tgl_bayar) }}</td>
                    <td>{{ $value->debet }}</td>
                    <td>{{ $value->kredit }}</td>
                    <td>{{  toRupiah($value->n_bayar) }}</td>
                    <td>
                        {{ $value->updated_at }}<br><label style="font-size:9pt">{{ " > ".$value->nm_user }}</label>
                    </td>
                    <td class="text-center" width="100">
                        <button class="btn btn-sm btn-warning mb-1" data-toggle="tooltip" data-placement="bottom" title="Edit" type="button" onclick="goEdit('{{ $value->id_biaya }}', '{{ $value->ac4_kredit }}', '{{ $value->tgl_bayar }}','{{ $value->n_bayar }}')">
                            <i class="fa fa-edit"> </i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger mb-1" data-toggle="tooltip" data-placement="bottom" title="Hapus" type="button" onclick="CheckDelete('{{ url('biayahppvendor/'.$value->id_biaya) }}')">
                            <i class="fa fa-times"> </i> Hapus
                        </button>
                        <a href="{{ url(Request::segment(1)."/".$value->id_biaya."/cetakbayar") }}" class="btn btn-sm btn-success mb-1" title="Cetak Bukti Transaksi" target="#cetak-transaksi-{{$value->id_biaya}}" rel="nofollow">
                            <i class="fa fa-print"> </i> Cetak
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>

<div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
            <form method="POST" enctype="multipart/form-data" action="" id="form-bayar">
                @csrf
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
                        <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section("script")
<script>
    function goEdit(id, ac, tgl, nominal){
        $("#ac4_k").val(ac);
        $("#tgl_bayar").val(tgl);
        $("#n_bayar").val(nominal);
        $("#modal-bayar").modal("show");
        $("#form-bayar").attr("action", "{{ url('biayahppvendor') }}/"+id+'/updatebayar');
    }
</script>
@endsection