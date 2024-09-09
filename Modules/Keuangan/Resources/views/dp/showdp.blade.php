@extends('template.document')

@section('data')
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data DP</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div> 
    
    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. STT</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->id_stt)){{ strtoupper($data->id_stt) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%">Nama Pelanggan</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->id_pelanggan)){{ strtoupper($data->pelanggan->nm_pelanggan) }}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Total</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->n_total)){{ strtoupper($data->n_total) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">No DP</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->id_dp)){{ strtoupper($data->id_dp) }}@endif
                        </b>
                    </td>
                    
                    <td width="10%"> Info</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->info_dp)){{ strtoupper($data->info_dp)}}@endif
                        </b>
                    </td>
                    
                    <td width="5%">Jumlah DP</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->n_dp)){{ strtoupper($data->n_dp) }}@endif
                        </b>
                    </td>
                </tr>			
                <tr>
                    <td width="10%"></td>
                    <td width="2%"></td>
                    <td >
                        <b>
                            
                        </b>
                    </td>
                    
                    <td width="10%"></td>
                    <td width="2%"></td>
                    <td>
                        <b>
                            
                        </b>
                    </td>
                    
                    <td width="5%">Kurang</td>
                    <td width="2%"><b>:</b></td>
                    <td width="28%">
                        <b>
                            @if(isset($data->n_kurang)){{ strtoupper($data->n_kurang) }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>  
    
</div>
<script>
var nama = '{{$data}}';
console.log(nama);



</script>
@endsection
