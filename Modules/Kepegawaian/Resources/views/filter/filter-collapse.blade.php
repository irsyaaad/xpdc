<div id="accordion" style="margin-top: -25px">
    <div class="card border-0">
        <div id="headingOne" class="text-right">
            <button class="btn btn-md btn-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fa fa-filter"> </i> Filter
            </button>
        </div>
        
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    @include('kepegawaian::filter.filter'.Request::segment(1))
                    <input type="hidden" name="_method" value="GET">
                </div>
            </div>
        </div>
    </div>
</div>