@extends('template.document2')

@section('data')
<form method="GET" action="{{ url("bukubesar") }}" enctype="multipart/form-data" id="form-select">
    @csrf
<div class="row">
    <div class="col-md-3">
        <label style="font-weight: bold;">
            Dari Tanggal
        </label>
        <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter["dr_tgl"])){{ $filter["dr_tgl"] }}@endif">
    </div>
    
    <div class="col-md-3">
        <label style="font-weight: bold;">
            Sampai Tanggal
        </label>
        <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter["sp_tgl"])){{ $filter["sp_tgl"] }}@endif">
    </div>
    
    <div class="col-md-3" style="margin-top: 25px">
        <button type="submit" class="btn btn-md btn-primary" class="btn btn-primary" title="Cari Data">
            <i class="fa fa-search"></i> Cari
        </button>
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh">
            <i class="fa fa-refresh"></i> Reset
        </a>
    </div>
    
    <div class="col-md-12 mt-4">
        <div class="text-center">
            <h3>LAPORAN BUKU BESAR</h3>
            <p>Periode : @if(isset($filter['dr_tgl'])){{dateindo($filter['dr_tgl'])}}@endif s/d. @if(isset($filter['dr_tgl'])){{dateindo($filter['sp_tgl'])}}@endif</p>
        </div>
        <hr>
        <table class="table table-sm" id="buku-table">
            @foreach($ac as $key => $value)
            <tr>
                <td><a href="{{ route('showbukubesar', [
                    'id_ac' => $value->id_ac,
                    'dr_tgl' => $filter['dr_tgl'],
                    'sp_tgl' => $filter['sp_tgl'],
                    ]) }}" style="color:black;">{{$value->id_ac}}
                </a>
            </td>
            <td><a href="{{ route('showbukubesar', [
                'id_ac' => $value->id_ac,
                'dr_tgl' => $filter['dr_tgl'],
                'sp_tgl' => $filter['sp_tgl'],
                ]) }}" style="color:black;">{{$value->nama}}
            </a></td>
            <td>{{$value->parent}}</td>
            <td>@if(isset($data3[$value->parent])){{$data3[$value->parent]->nama}}@endif</td>
            <td>@if(isset($data3[$value->parent])){{$data3[$value->parent]->id_parent}}@endif</td>
            <td>@if(isset($data2[$data3[$value->parent]->id_parent])){{$data2[$data3[$value->parent]->id_parent]->nama}}@endif</td>
            <td>@if(isset($data2[$data3[$value->parent]->id_parent])){{$data2[$data3[$value->parent]->id_parent]->id_parent}}@endif</td>
            <td>@if(isset($data1[$data2[$data3[$value->parent]->id_parent]->id_parent])){{$data1[$data2[$data3[$value->parent]->id_parent]->id_parent]->nama}}@endif</td>
        </tr>
        @endforeach
    </table>
</div>
</div>
</form>
@endsection