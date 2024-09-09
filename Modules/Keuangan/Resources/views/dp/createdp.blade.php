@extends('template.document')

@section('data')
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data STT</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div> 
    
    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. STT</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->id_stt)){{ strtoupper($data->id_stt) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Penerima</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->penerima_nm)){{ strtoupper($data->penerima_nm) }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Berat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->n_berat)){{ strtoupper($data->n_berat) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Perusahaan Asal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->perush_asal->nm_perush)){{ strtoupper($data->perush_asal->nm_perush) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%"> Alamat Penerima</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->tujuan->nama_wil)){{ strtoupper($data->tujuan->nama_wil)." - ".$data->penerima_alm }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Volume</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->n_volume)){{ strtoupper($data->n_volume) }}@endif
                        </b>
                    </td>
                </tr>			
                <tr>
                    <td width="10%">Pengirim</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->pengirim_nm)){{ strtoupper($data->pengirim_nm) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%"> Tanggal Masuk</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->tgl_masuk)){{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Koli</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->n_koli)){{ strtoupper($data->n_koli) }}@endif
                        </b>
                    </td>
                </tr>
                
                <tr>
                    <td width="10%"> Alamat Pengirim</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->asal->nama_wil)){{ strtoupper($data->asal->nama_wil)." - ".$data->pengirim_alm }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Status</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->status)){{ strtoupper($data->status->nm_ord_stt_stat) }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Total</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            
                            @if(isset($data->c_total))Rp. {{ number_format($data->c_total, 2, ',', '.') }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>  
    
    <div class="col-md-12">
        <h4><i class="fa fa-chevron-right"></i>
            <b>Form DP</b>
        </h4> 
    </div>
</div>
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)."/save") }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label for="id_plgn"> Pelanggan <span class="span-required"> * </span></label>

            <select class="form-control" id="id_pelanggan" name="id_pelanggan"></select>

            <input type="hidden" id="nm_pelanggan" name="nm_pelanggan" />
            <input type="hidden" name="id_plgn" value="{{$data->id_plgn}}">
            <input type="hidden" name="id_stt" value="{{$data->id_stt}}">
            <input type="hidden" name="c_total" value="{{$data->c_total}}">

            @if($errors->has('id_plgn'))
                <label style="color: red">
                    {{ $errors->first('id_plgn') }}
                </label>
            @endif
        </div>

        <div class="col-md-3">
            <label for="tgl_dp"> Tanggal DP <span class="span-required"> * </span></label>

            <input class="form-control" type="date" id="tgl_dp" name="tgl_dp" placeholder="Tanggal Dp" maxlength="16" />

            @if($errors->has('tgl_dp'))
                <label style="color: red">
                    {{ $errors->first('tgl_dp') }}
                </label>
            @endif
        </div>

        <div class="col-md-3">
            <label for="n_dp"> Jumlah DP <span class="span-required"> * </span></label>

            <input class="form-control" type="number" id="n_dp" name="n_dp" placeholder="Jumlah Dp" maxlength="24" />

            @if($errors->has('n_dp'))
                <label style="color: red">
                    {{ $errors->first('n_dp') }}
                </label>
            @endif
        </div>

        <div class="col-md-3">
            <label for="info_dp"> Info DP <span class="span-required"> * </span></label>

            <textarea class="form-control" id="info_dp" name="info_dp" placeholder="Info Dp" maxlength="124"
                style="min-height: 100px"> {{ "Pembayaran DP untuk STT No. ".strtoupper($data->id_stt)." Atas Nama ".strtoupper($data->pengirim_nm) }} </textarea>

            @if($errors->has('info_dp'))
                <label style="color: red">
                    {{ $errors->first('info_dp') }}
                </label>
            @endif
        </div>

    </div>
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-md btn-success">
            <span></span> <i class="fa fa-send"> </i> Bayar
        </button>
    </div>
</form>
<script>
var today = new Date().toISOString().split('T')[0];		
$("#tgl_dp").val(today);

@if (isset($data->pengirim_nm))
    $("#id_pelanggan").append('<option value=' + '{{$data->id_plgn}}' + '>' + '{{ strtoupper($data->pengirim_nm)}}' + '</option>');
@endif

</script>
@endsection
