<div class="row">
	@if(Request::segment(3)=="show")	
	<div class="col-md-12 text-right">
		@if($data->id_status ==1 and $data->is_approve!=true and $data->id_perush_dr == Session("perusahaan")["id_perush"])
		<div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-plus"> </i> Tambah Data
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/detail' }}">
					<i class="fa fa-plus"></i> Tambah Muatan
				</a>
				<a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-create" onclick="refresh()">
					<i class="fa fa-plus"></i> Tambah Biaya
				</a>
			</div>
		</div>
		@endif

		@if(Request::segment(1)=="dmtrucking" or Request::segment(1)=="dmcontainer")
		<a href="{{ url("dmtrucking/".$data->id_dm."/generateproyeksi") }}" class="btn btn-sm btn-success"><span><i class="fa fa-retweet"> </i></span> Generate Proyeksi</a>
		@endif

		@if($data->is_approve!=true and Request::segment(1)!="dmtiba")
		<a href="{{ url('dmtrucking/'.Request::segment(2).'/counting') }}" class="btn btn-sm btn-danger">
			<i class="fa fa-refresh"></i> Hitung Ulang
		</a>
		@endif

		
		@if($data->id_status<3 and Request::segment(1)!="dmtiba")
		<div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-bell"> </i> Update Status
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				@php
					$stts = $data->id_status+1;
					$stats = $status[$stts];
				@endphp
				<a class="dropdown-item" href="#"  onclick="CheckStatus('{{ Request::segment(2) }}', '{{ $stats->id_status }}')">
					<i class="fa fa-truck"></i> {{ $stats->nm_status }}
				</a>
			</div>
		</div>
		@elseif(Request::segment(1)=="dmtiba" and $data->id_status==3 and $data->id_perush_tj==Session("perusahaan")["id_perush"])
		<div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-bell"> </i> Update Status
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				@php
					$stts = $data->id_status+1;
					$stats = $status[$stts];
				@endphp
				<a class="dropdown-item" href="#"  onclick="CheckStatus('{{ Request::segment(2) }}', '{{ $stats->id_status }}')">
					<i class="fa fa-truck"></i> {{ $stats->nm_status }}
				</a>
			</div>
		</div>
		@endif
	</div>
	@endif
</div>