@extends('template.document2')
@section('data')

<form method="GET" action="{{ url('sttbyregion') }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div id="filter-modal" class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-sm btn-primary" class="btn btn-primary" data-toggle="modal" data-target="#modal-filter" title="Cari Data"><i class="fa fa-filter"></i> Filter</button>
            <a href="{{ url($filter["urls"]) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fa fa-print"></i> Cetak</a>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
        </div>
    </div>
    
    <table class="table table-responsive table-striped table-sm mt-2" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Kota Pengirim</th>
                <th>Kota Penerima</th>
                <th>Jumlah STT</th>
                <th>Berat</th>
                <th>Volume</th>
                <th>Koli</th>
                <th>Omset</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach($data as $key => $value)
            <tr>
                <td>{{$key+1}}</td>
                <td>@if(isset($value->asal)){{$value->asal}}@endif</td>
                <td>@if(isset($value->tujuan)){{$value->tujuan}}@endif</td>
                <td>@if(isset($value->jumlah_stt)){{$value->jumlah_stt}}@endif</td>
                <td>@if(isset($value->berat)){{$value->berat}}@endif</td>
                <td>@if(isset($value->volume)){{$value->volume}}@endif</td>
                <td>@if(isset($value->jumlah_koli)){{$value->jumlah_koli}}@endif</td>
                <td class="text-right">@if(isset($value->total)){{ toRupiah($value->total) }}@endif</td>
                @php
                    $total += $value->total;
                @endphp
            </tr>           
            @endforeach
            <tr>
                <td class="text-right" colspan="7"><b>Total : </b></td>
                <td class="text-right"><b>{{ toRupiah($total) }} </b></td>
            </tr>
        </tbody>
    </table>
    
    <div class="modal" tabindex="-1" role="dialog" id="modal-filter">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-6 col-6">
                            <label style="font-weight : bold">
                                Tgl. Awal
                            </label>
                            <input type="date" class="form-control" id="f_start" name="f_start" />
                        </div>
                        
                        <div class="col-md-6 col-6">
                            <label style="font-weight : bold">
                                Tgl. Aknir
                            </label>
                            <input type="date" class="form-control" id="f_end" name="f_end" />
                        </div>
                        
                        <div class="col-md-12 text-right mt-1">
                            <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
    @if(isset($filter["f_start"]))$("#f_start").val("{{ $filter["f_start"] }}");@endif
    @if(isset($filter["f_end"]))$("#f_end").val("{{ $filter["f_end"] }}");@endif
</script>
@endsection