<header class="m-grid__item    m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
	<div class="m-container m-container--fluid m-container--full-height">
		<div class="m-stack m-stack--ver m-stack--desktop">
			<div class="m-stack__item m-brand  m-brand--skin-light">
				
				<div class="m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-stack__item--middle m-brand__logo">
						<a href="{{ url('/') }}" class="m-brand__logo-wrapper">
							<img alt="Bpee" src="{{ url('img/logo-bpee.png') }}" style="width: 100%;" id="img-logo" />
						</a>
					</div>

					<div class="m-stack__item m-stack__item--middle m-brand__tools">
						@if(Request::segment(1)!="dashboard")
						<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block m-brand__toggler--active">
							<span></span>
						</a>
						<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
							<span></span>
						</a>
						@endif

						<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
							<i class="flaticon-more"></i>
						</a>
					</div>
				</div>
			</div>
		
		<!-- For Header Menu -->
		@include("template.content-header")
	</div>
</div>

</header>