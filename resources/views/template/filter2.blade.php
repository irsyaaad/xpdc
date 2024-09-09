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
					<div class="col-md-9 row" style="padding-top:5px">
					@include("filter.filter-".Request::segment(1))
					</div>
					<div class="col-md-3" style="padding-top:15px">
						<br>
                        @if(Request::segment(1)!="rugilabapertahun")
						<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
						@endif
                        @if(Request::segment(1)=="pembayaran"
						or Request::segment(1)=="sttterima"
						or Request::segment(1)=="sttkembali" or Request::segment(1)=="sttkembaliterima"
						or Request::segment(1)=="stt"
						or Request::segment(1)=="dmhandling" or Request::segment(1)=="dmtiba"
						or Request::segment(1)=="biayahppvendor" or Request::segment(1)=="bayartujuan")
						<button type="button" class="btn btn-md btn-accent" onclick="CheckStatus()" data-toggle="tooltip" data-placement="bottom" title="Custome Filter"> <i class="fa fa-calendar"></i> </button>
						@endif
						@if(Request::segment(1)=="handlingtujuan")
						<button type="button" class="btn btn-md btn-accent" onclick="CheckStatus()" data-toggle="tooltip" data-placement="bottom" title="Custome Filter"><i class="fa fa-calendar"></i> </button>
						@endif
						@if(Request::segment(1)=="neracabyperkiraan"
						or Request::segment(1)=="rugilababyperkiraan"
						or Request::segment(1)=="sttbycarabayar" or Request::segment(1)=="sttbyusers"
						or Request::segment(1)=="sttbydm" or Request::segment(1)=="omsetbypelanggan"
						or Request::segment(1)=="bygrouppelanggan" or Request::segment(1)=="lamaharistt"
						or Request::segment(1)=="lamaharisttbygroup"
						or Request::segment(1)=="sttterima"
						or Request::segment(1)=="pembayaran")
						<button type="button" class="btn btn-md btn-accent" onclick="html()" data-toggle="tooltip" data-placement="top" title="Cetak pdf"><i class="fa fa-print"></i> </button>
						@endif
                        @if (Request::segment(1)=="neraca")
                        <a href="{{ route('cetakneraca', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
						<a href="{{ route('excelneraca', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Export Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
                        @endif
                        @if (Request::segment(1)=="rugilaba")
                        <a href="{{ route('cetakrugilaba', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
						<a href="{{ route('excelrugilaba', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Export Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
                        @endif
						@if (Request::segment(1)=="cashflow")
                        <a href="{{ route('cetakcashflow', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
						<a href="{{ route('excelcashflow', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Export Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
                        @endif
						@if (Request::segment(1)=="cashflowdetail")
                        <a href="{{ route('cetakcashflowdetail', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
                        @endif
                        @if (Request::segment(1)=="neracadetail")
                        <a href="{{ route('cetakneracadetail', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
                        @endif
                        @if (Request::segment(1)=="rugilabadetail")
                        <a href="{{ route('cetakrugilabadetail', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
                        @endif
                        @if (Request::segment(1)=="rugilabaproyeksi")
                        <a href="{{ route('cetakrugilabaproyeksi', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
						<a href="{{ route('excelrugilabaproyeksi', [
						'dr_tgl' => $filter['dr_tgl'],
						'sp_tgl' => $filter['sp_tgl'],
						]) }}" style="color:white;" class="btn btn-md btn-success" data-toggle="tooltip" data-placement="top" title="Cetak Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
                        @endif
						@if (Request::segment(1)=="rugilabakonsolidasi")
                        <a href="{{ route('cetakrugilabakonsolidasi', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
							'id_perush' => $filter['perush']
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
						<a href="{{ route('excelrugilabakonsolidasi', [
						'dr_tgl' => $filter['dr_tgl'],
						'sp_tgl' => $filter['sp_tgl'],
						'id_perush' => $filter['perush']
						]) }}" style="color:white;" class="btn btn-md btn-success" data-toggle="tooltip" data-placement="top" title="Cetak Excel" target="_blank" rel="nofollow"><i class="fa fa-file"></i></a>
                        @endif
                        @if (Request::segment(1)=="jurnal")
                        <a href="{{ route('cetakjurnal', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
                        @endif
						@if (Request::segment(1)=="omsetvsbiaya")
                        <a href="{{ route('cetakomsetvsbiaya', [
                            'dr_tgl' => $filter['dr_tgl'],
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
                        @endif

						@if(Request::segment(1)=="gajikaryawan")
						<div class="btn-group">
							<button type="button" class="btn btn-accent dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-print"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="{{ url(Request::segment(1)."/cetak") }}"><i class="fa fa-print"></i> Cetak </a>
								<a class="dropdown-item" href="{{ url(Request::segment(1)."/cetakall") }}"><i class="fa fa-print"></i> Cetak Semua Perusahaan</a>
							</div>
						</div>
						<div class="btn-group">
							<button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-file"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="{{ url(Request::segment(1)."/excel") }}"><i class="fa fa-print"></i> Cetak Excel</a>
								<a class="dropdown-item" href="{{ url(Request::segment(1)."/excelall") }}"><i class="fa fa-print"></i> Cetak Excel Semua Perusahaan</a>
							</div>
						</div>
						@endif

						@if(Request::segment(1)=="neracadetail" or Request::segment(1)=="neracabyperkiraan"
						or Request::segment(1)=="rugilabadetail" or Request::segment(1)=="rugilababyperkiraan"
						or Request::segment(1)=="biayabydm"
						or Request::segment(1)=="sttbycarabayar" or Request::segment(1)=="sttbyusers" or Request::segment(1)=="sttbydm"
						or Request::segment(1)=="omsetbypelanggan" or Request::segment(1)=="bygrouppelanggan"
						or Request::segment(1)=="lamaharistt" or Request::segment(1)=="lamaharisttbygroup")
						<button type="button" class="btn btn-md btn-success" onclick="excel()" data-toggle="tooltip" data-placement="top" title="Cetak excel"><i class="fa fa-file"></i> </button>
						@endif
					</div>
					@endif
				</div>

				<div class="form-group row">
					<div class="col-md-6 text-right">
						{{-- @include("template.search") --}}
					</div>
					@if(Request::segment(1)=="acperush")
					<div class="col-md-6 text-right">
						<a href="{{ url(Request::segment(1).'/generate') }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Generate Akun</a>
					</div>
					@endif
					@if(Request::segment(1)=="mastercashflowperush")
					<div class="col-md-6 text-right">
						<a href="{{ url(Request::segment(1).'/generate') }}" class="btn btn-sm btn-success"><i class="fa fa-refresh"></i> Generate Data</a>
					</div>
					@endif
					@if(Request::segment(1)=="pelanggan")
					<div class="col-md-6 text-right">
						<a href="{{ url(Request::segment(1).'/import') }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Import Data</a>
					</div>
					@endif
					@if(Request::segment(1)=="asuransistt")
					<div class="col-md-6 text-right">
						<a href="{{ url(Request::segment(1).'/import') }}" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Import STT</a>
					</div>
					@endif
				</div>
			</div>
		</form>
	</div>
</div>
<br>
