<!-- For Layout Body -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- For Head Content -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title m-subheader__title--separator">
					Local Data
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
								Datatables
							</span>
						</a>
					</li>
					<li class="m-nav__separator">
						/
					</li>
					<li class="m-nav__item">
						<a href="" class="m-nav__link">
							<span class="m-nav__link-text">
								Base
							</span>
						</a>
					</li>
					<li class="m-nav__separator">
						/
					</li>
					<li class="m-nav__item">
						<a href="" class="m-nav__link">
							<span class="m-nav__link-text">
								Local Data
							</span>
						</a>
					</li>
				</ul>
			</div>
			
		</div>
	</div>

	<!-- For Body Content -->
	@yield('content')
</div>