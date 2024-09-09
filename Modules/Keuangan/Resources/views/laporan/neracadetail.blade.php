@extends('template.document2')

@section('data')
<form method="GET" action="{{ url("neracadetail") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Dari Tanggal
            </label>
            <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
        </div>
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Sampai Tanggal
            </label>
            <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
        </div>
        <div class="col-md-3">
            <label style="font-weight: bold;">
                Search :
            </label>
            <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
        </div>
        
        <div class="col-md-3" style="margin-top: 25px">
            <button type="submit" class="btn btn-md btn-primary" class="btn btn-primary" title="Cari Data">
                <i class="fa fa-search"></i> Cari
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh">
                <i class="fa fa-refresh"></i> Reset
            </a>
            <div class="dropdown d-inline-block">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cetak
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="@if(isset($filter["pdf"])){{ $filter["pdf"] }}@endif" class="dropdown-item" target="_blank">
                        <i class="fa fa-file-pdf-o"></i>Pdf
                    </a>
                    <a href="@if(isset($filter["excel"])){{ $filter["excel"] }}@endif" class="dropdown-item" >
                        <i class="fa fa-print"></i>Excel
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row mt-5">
        <div class="col-md-12">
            <table class="table table-hover table-bordered table-sm" id="html_table" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Reff</th>
                    <th>No. Detail</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @foreach($ac as $key => $value)
                    @php $no = 0;@endphp
                    @if($value->id_ac < 4000)
                    @if(isset($data[$value->id_ac]))
                    <tr style="background-color: grey; color : #ffff">
                        <td colspan="8">{{$value->nama}}</td>
                    </tr>
                    @php $total=0; @endphp
                    @foreach($data[$value->id_ac] as $key2 => $value2)
                    <tr>
                        @if($value2->id_debet ==  $value->id_ac)
                        <td>{{$no+=1}}</td>
                        <td>@if(isset($value2->tgl_masuk)){{dateindo($value2->tgl_masuk)}}@endif</td>
                        <td>@if(isset($value2->reff)){{$value2->reff}}@endif</td>
                        <td>@if(isset($value2->id_detail)){{$value2->id_detail}}@endif</td>
                        <td>@if(isset($value2->info_debet)){{$value2->info_debet}}@endif</td>
                        <td class="text-right">@if(isset($value2->total_debet)) {{ number_format($value2->total_debet, 0, ',', '.') }} @endif</td>
                        <td class="text-right">0</td>
                        @php
                        if($value2->pos_d == "D"){
                            $total+=$value2->total_debet;
                        }else{
                            $total-=$value2->total_kredit;
                        }
                        @endphp
                        @else
                        <td>{{$no+=1}}</td>
                        <td>@if(isset($value2->tgl_masuk)){{dateindo($value2->tgl_masuk)}}@endif</td>
                        <td>@if(isset($value2->reff)){{$value2->reff}}@endif</td>
                        <td>@if(isset($value2->id_detail)){{$value2->id_detail}}@endif</td>
                        <td>@if(isset($value2->info_kredit)){{$value2->info_kredit}}@endif</td>
                        <td class="text-right">0</td>
                        <td class="text-right">@if(isset($value2->total_kredit)) {{ number_format($value2->total_kredit, 0, ',', '.') }} @endif</td>
                        @php
                        if($value2->pos_k == "K"){
                            $total+=$value2->total_kredit;
                        }else{
                            $total-=$value2->total_debet;
                        }
                        @endphp
                        @endif
                        
                        <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @endif
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

@endsection


@section('script')
<script>
    function myFunction() {
        var input, filter, table, tr, td, i ;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("html_table");
        tr = table.getElementsByTagName("tr"),
        th = table.getElementsByTagName("th");
        
        // Loop through all table rows, and hide those who don't match the        search query
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            for(var j=0; j<th.length; j++){
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1)                               {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
</script>
@endsection
