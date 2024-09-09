@extends('template.layout2')

@section('content')

<style type="text/css">
	.box-role{
		background-color: #fff;
		border-bottom: solid 1px grey;
	}

	.box-role h4{
		margin-top: 2%;
	}

	.box-role p{
		font-weight: bold;
	}

	.box-role:hover{
		cursor: pointer;
		background-color: #337ab7;
		color: #fff;
	}

	.card:hover{
		background-color: #e6ecec;
		font-weight: bold;
		cursor: pointer;
	}

	.card-body a{
		text-decoration: none; 
		color: #45ccb1;
	}

	.card-body a:hover{

	}
</style>

<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

	<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
		<i class="la la-close"></i>
	</button>

	<!-- For Layout Body -->
	<div class="m-grid__item m-grid__item--fluid m-wrapper">
		<!-- For Head Content -->
		<div class="m-subheader ">
			<div class="d-flex align-items-center">
				<div class="mr-auto">
					<h3 class="m-subheader__title m-subheader__title--separator">
						{{ strtoupper(Request::segment(1)) }}
					</h3>

					<!-- For Bradcumb -->
					<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
						<li class="m-nav__item m-nav__item--home">
							<a href="#" class="m-nav__link m-nav__link--icon">
								<i class="m-nav__link-icon la la-home"></i>
							</a>
						</li>
						<li class="m-nav__separator">
							/
						</li>

						<li class="m-nav__item">
							<a href="" class="m-nav__link">
								<span class="m-nav__link-text">
									{{ strtoupper(Request::segment(1)) }}
								</span>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
		
		<!-- For Body Content -->
		<div class="m-content">
			<!-- For Header -->
			<div class="m-portlet m-portlet--mobile">
				<div class="row m-row--no-padding m-row--col-separator-xl">	
					
					<div class="col-xl-12" style="padding: 2%">
						
						@include("template.notif")
						
						<div class="row">
							@foreach($module as $key => $value)
							<div class="col-xl-4" style="margin-top: 10px">
								<div class="card">
									
									<div class="card-body">
										<center>
											<a href="{{ url('choose').'/'.$value->id_module }}">
												<i class="{{ $value->icon }}" style="font-size: 100pt"></i>
												<h5 class="card-title" style="margin-top: 5%">
													{{ strtoupper($value->nm_module) }}
												</h5>
											</a>
										</center>
										
										<p class="card-text text-center">Modul Digunakan Untuk {{ strtoupper($value->nm_module) }} Aplikasi</p>
										
									</div>
								</div>
							</div>
							@endforeach
							
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="m-datatable" id="m-datatable" >
			
		</div>
	</div>
@endsection

@section("script")
<script type="text/javascript">
	$(document).ready(function() {
		$("#m-datatable").hide();
	});
</script>
@endsection