@extends('template.document')

@section('data')
@if(Request::segment(1)=="proyeksihandling" && Request::segment(3)=="show")
    <div class="row">
        <div class="col-md-11 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-reply"></i>	Kembali
            </a>
        </div>
        
        <table class="table table-responsive" style="margin-top: 10px">
            <tr>
               <td>
                   Perusahaan Asal :
                <b>@if(isset($data->perusahaan->nm_perush)){{ strtoupper($data->perusahaan->nm_perush) }}@endif</b>
               </td> 
               <td>
                Perusahaan Pengirim :
                <b>@if(isset($data->perush_kirim->nm_perush)){{ strtoupper($data->perush_kirim->nm_perush) }}@endif</b>
               </td> 
               <td>
                Layanan :
                <b>@if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif</b>
               </td> 
            </tr>
        </table>
    </div>
    
    @if(Request::segment(3)=="show")
    @include("operasional::detail-proyeksi")
    @endif
@endif

@endsection

@section('script')
<script type="text/javascript">
    
</script>
@endsection