<div class="col-md-2 mt-2">
    Page : {{ $data->currentPage() }}
</div>

<div class="col-md-2 mt-2">
    Total : {{ $data->total() }}
</div>

<div class="col-md-2 mt-2">
    <select class="form-control" id="shareselect" name="shareselect">
        <option value="10">10 Data</option>
        <option value="50">50 Data</option>
        <option value="100">100 Data</option>
        <option value="500">500 Data</option>
        <option value="1000">1000 Data</option>
    </select>
</div>

<div class="col-md-6 mt-2" style="width: 100%">
    @php
    $params = request()->query();
    $a_params = null;
    foreach($params as $key => $value){
        if($key != "page"){
            $a_params .= "&".$key."=".$value;
        }
    }
    @endphp

   @if ($data->hasPages())
   <nav aria-label="Page navigation example">
    <ul class="pagination">

        @if ($data->onFirstPage())
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        @else
        @php
        $number = $data->currentPage()-1;
        $prev = url()->current()."?page=".$number.$a_params;
        if(Request::segment(2)==null){
            $prev =  url()->current()."?page=".$number.$a_params;
        }
        @endphp
        <li class="page-item"><a class="page-link" href="{{ $prev }}">Previous</a></li>
        @endif

        @foreach ($data->links()->elements as $element)
        @if (is_string($element))
        <li class="page-item disabled">{{ $element }}</li>
        @endif
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $data->currentPage())
        <li class="page-item active">
            <a class="page-link">{{ $page }}</a>
        </li>
        @else
        @php
        $url =  url()->current()."?page=".$page.$a_params;
        @endphp
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        @if ($data->hasMorePages())
        <li class="page-item">
            @php
            $number = $data->currentPage()+1;
            $next = url()->current()."?page=".$number.$a_params;
            @endphp
            <a class="page-link" href="{{ $next }}" rel="next">Next</a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link" href="#">Next</a>
        </li>
        @endif

    </ul>
    @endif
</nav>
</div>