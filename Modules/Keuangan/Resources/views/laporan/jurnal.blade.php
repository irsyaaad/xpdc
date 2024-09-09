@extends('template.document')

@section('data')
@if(Request::segment(1)=="jurnal" && (Request::segment(2)==null or Request::segment(2)=="filter"))
@include("template.filter2")
<div class="table-responsive" style="display: block;
overflow-x: auto;
white-space: nowrap;">
<table class="table table-bordered table-striped" id="jurnal-table">
    <thead style="background-color: grey; color : #ffff">
        <th>No</th>
        <th>No Bukti</th>
        <th>Reff</th>
        <th>Tanggal</th>
        <th>ID AC</th>
        <th>Nama AC</th>
        <th width="500px">Keterangan</th>
        <th>Debet</th>
        <th>Kredit</th>
    </thead>
    <tbody>
        @php
        $total_debit = 0;
        $total_kredit = 0;
        $no = 0;
        @endphp
        
        @foreach($data as $key => $value)
        <tr>
            <td>{{$no+=1}}</td>
            <td>{{$value->id_detail}}</td>
            <td>{{$value->reff}}</td>
            <td>@if(isset($value->tgl_masuk)){{date('d-M-Y', strtotime($value->tgl_masuk))}} @endif</td>
            <td>
                <p>@if(isset($value->id_debet)){{$value->id_debet}}@endif</p>
                <p>@if(isset($value->id_kredit)){{$value->id_kredit}}@endif</p>
            </td>
            <td>
                <p>@if(isset($value->nama_debet)){{$value->nama_debet}}@endif</p>
                <p>@if(isset($value->nama_kredit)){{$value->nama_kredit}}@endif</p>
            </td>
            <td>
                <p>@if(isset($value->info_debet)){{$value->info_debet}}@endif</p>
                <p>@if(isset($value->info_kredit)){{$value->info_kredit}}@endif</p>
            </td>
            <td>
                <p>@if(isset($value->total_debet))Rp. {{number_format($value->total_debet, 0, ',', '.')}}@endif</p>
                @php
                $total_debit+=$value->total_debet;
                @endphp
                <p>0</p>
            </td>
            <td>
                <p>0</p>
                @php
                $total_kredit+=$value->total_kredit;
                @endphp
                <p>@if(isset($value->total_kredit))Rp. {{number_format($value->total_kredit, 0, ',', '.')}}@endif</p>
            </td>
        </tr>
        @endforeach
        <tr style="background-color: grey; color : #ffff">
            <td colspan="5" class="text-center">TOTAL</td>
            <td>Rp. {{number_format($total_debit, 0, ',', '.')}}</td>
            <td>Rp. {{number_format($total_kredit, 0, ',', '.')}}</td>
        </tr>
    </tbody>
</table>
</div>
@endif
@endsection
