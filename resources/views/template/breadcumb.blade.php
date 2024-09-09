<div class="m-grid__item m-grid__item--fluid m-wrapper" style="margin-top: -20px">
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title m-subheader__title--separator">
					@if(isset(Session("module")["nm_module"]))
					<i class="{{ Session("module")["icon"] }}"></i> 
					{{ strtoupper(Session("module")["nm_module"]) }}
					@endif
				</h3>
				
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					@for($i = 1; $i<6; $i++)
						@if(Request::segment($i)!==null)
						<li class="m-nav__item">
							<a href="{{ url(Request::segment(1)) }}" class="m-nav__link">
								<span class="m-nav__link-text">
									@if($i==1)
									{{ strtoupper(str_replace("_", " ", get_menu(Request::segment($i)))) }}
									@else
									{{ strtoupper(str_replace("_", " ", Request::segment($i))) }}
									@endif
								</span>
							</a>
						</li>
						
						<li class="m-nav__separator">
							>
						</li>
						@endif
					@endfor
				</ul>
			</div>
			
		</div>
	</div>
	
	@yield('content')
</div>