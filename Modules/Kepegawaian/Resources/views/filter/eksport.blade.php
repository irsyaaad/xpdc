<button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"><i class="fa fa-search"></i> Cari</button>
<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
<div class="dropdown d-inline-block">
    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-upload"> </i> Export 
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @php
        $cetak = url(Request::segment(1)."/cetak");
        $excel =  url(Request::segment(1)."/excel");
        if(preg_match("/filter/i", url()->full())){
            $cetak = str_replace("filter","cetak", url()->full());
            $excel = str_replace("filter","excel", url()->full());
        }
        @endphp
        <a class="dropdown-item" href="{{ $cetak }}" target="_blank"> <i class="fa fa-print"></i> Pdf</a>
        <a class="dropdown-item" href="{{ $excel }}" target="_blank"> <i class="fa fa-file" aria-hidden="true"></i> Excel</a>
    </div>
</div>