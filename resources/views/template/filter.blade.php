{{-- <div class="container text-center" style="vertical-align:middle;">
	<h6>Loading Data</h6>
	<div class="m-spinner m-spinner--brand m-spinner--sm"></div>
	<div class="m-spinner m-spinner--primary m-spinner--sm"></div>
	<div class="m-spinner m-spinner--success m-spinner--sm"></div>
	<div class="m-spinner m-spinner--info m-spinner--sm"></div>
	<div class="m-spinner m-spinner--warning m-spinner--sm"></div>
	<div class="m-spinner m-spinner--danger m-spinner--sm"></div>
</div> --}}
<div class="m-form m-form--label-align-right m--margin-bottom-20" style="margin-top: -1%">
	<div class="row align-items-center">
		<form action="{{ url(Request::segment(1)."/filter") }}" class="col-xl-12" name="form-filter" id="form-filter" method="post"> 
			@csrf
			<div class="col-xl-12">

				<div class="form-group row">
					@if(isset($filter))
					@include("filter.filter-".Request::segment(1))
					<div class="col-md-4" style="margin-top:-22px">
						<br>
						<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
						@if(Request::segment(1)=="menus")
						<a href="{{ url(Request::segment(1).'/generateTemp') }}" class="btn btn-md btn-success"><span><i class="fa fa-refresh"></i></span></a>
						@endif
					</div>
					@endif

					<div class="col-md-2">
						@include("template.search")
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<br>