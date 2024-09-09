@extends('template.document')

@section('data')

<div class="col-md-6" style="padding-bottom:30px">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data Statistik STT</b>
        </h4>
</div>
@include('operasional::grafik.grafik-stt')

<div class="row" style="padding-top: 50px">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Rincian</b>
        </h4>
    </div>

    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive table-sm table-borderless">
            <thead>
                <tr>
                    <td width="25%">Total Stt Masuk</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data["total"])){{ strtoupper($data["total"]) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total Stt Diterima</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data["sttditerima"])){{ strtoupper($data["sttditerima"]) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total Stt Trucking</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data["trucking"])){{ $data["trucking"] }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total Stt Kontainer</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data["kontainer"])){{ $data["kontainer"] }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total Stt Kapal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data["kapal"])){{ $data["kapal"] }}@endif
                        </b>
                    </td>
                </tr>			
                <tr>
                    <td width="10%">Perkiraan Pendapatan dari STT</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>Rp.
                            @if(isset($data["pendapatan"])){{number_format($data["pendapatan"], 2, ',', '.') }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>

</div>
<div style="padding-top : 50px"></div>
<div class="col-md-6" style="padding-bottom:30px">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data Statistik DM</b>
        </h4>
    </div>
@include('operasional::grafik.grafik-dm')
<div class="row" style="padding-top: 50px">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Rincian</b>
        </h4>
    </div>

    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive table-sm table-borderless">
            <thead>
                <tr>
                    <td width="25%">Total DM Berangkat</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($datadm["total"])){{ strtoupper($datadm["total"]) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total DM Sampai</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($datadm["dmsampai"])){{ strtoupper($datadm["dmsampai"]) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total DM Trucking</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($datadm["trucking"])){{ $datadm["trucking"] }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total DM Kontainer</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($datadm["kontainer"])){{ $datadm["kontainer"] }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Total DM Kapal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($datadm["kapal"])){{ $datadm["kapal"] }}@endif
                        </b>
                    </td>
                </tr>			
                <tr>
                    <td width="10%">Perkiraan Pendapatan dari DM</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>Rp.
                            @if(isset($datadm["pendapatan"])){{number_format($datadm["pendapatan"], 2, ',', '.') }}@endif
                        </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>

</div>
@endsection

{{-- this for loading javascript data --}}
@section('script')
<script>

</script>
@endsection
