<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
	
	<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
		<i class="la la-close"></i>
	</button> 
	
	<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
		<div class="m-stack__item m-topbar__nav-wrapper">
			<ul class="m-topbar__nav m-nav m-nav--inline">
				
				<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
					<a href="#" class="m-nav__link m-dropdown__toggle">
						<span class="m-topbar__userpic">
							<b style="font-weight: bold;">
								@if(isset(Session("perusahaan")["nm_perush"]))
								{{ strtoupper(Session("perusahaan")["nm_perush"]) }}
								@endif
							</b>
						</span>
					</a>
					<style>
						.m-dropdown__content {
							max-height: 500px;
							max-width: 100%;
							overflow-y: auto;
						}
					</style>
					
					<div class="m-dropdown__wrapper">
						<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
						<div class="m-dropdown__inner">
							<div class="m-dropdown__body">
								<div class="m-dropdown__content">
									<ul class="m-nav m-nav--skin-light">
										@if(Auth::check())
										@php
										$auth_perush = \App\Models\RoleUser::ChekPerush(Auth::user()->id_user);
										@endphp
										
										@foreach($auth_perush as $key => $value)
										@if(Session("perusahaan")["id_perush"]!=$value->id_perush)
										<li class="m-nav__item" onclick="onSubmit()">
											<a href="{{ url('changeperush')."/".$value->id_perush }}" style="font-size: 10pt; text-decoration: none;">
												<b style="font-weight: bold;">{{ strtoupper($value->nm_perush) }}</b>
											</a>
										</li>
										<hr>
										@endif
										@endforeach
										@endif
									</ul>
								</div>
							</div>
						</div>
					</div>
				</li>
				
				<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
					<a href="#" class="m-nav__link m-dropdown__toggle row">
						<span class="m-topbar__userpic">
							<b style="font-weight: bold;"><i class="fa fa-envelope" style="font-size: 18pt"></i> </b>
						</span>
						<p id="total" style="color : red"></p> 
					</a>
					
					<div class="m-dropdown__wrapper">
						<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
						<div class="m-dropdown__inner">
							<div class="m-dropdown__body">
								<div class="m-dropdown__content">
									<ul class="m-nav m-nav--skin-light" id="pilih"></ul>
								</div>
							</div>
						</div>
					</div>
				</li>
				
				<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
					<a href="#" class="m-nav__link m-dropdown__toggle">
						<span class="m-topbar__userpic">
							<img src="{{ asset('img/logo-bpee.png') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
						</span>
						<span class="m-topbar__username m--hide">
							Nick Pop
						</span>
					</a>
					<div class="m-dropdown__wrapper">
						<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
						<div class="m-dropdown__inner">
							
							<div class="m-dropdown__header m--align-center">
								<div class="m-card-user">
									<div class="m-card-user__pic">
										<img src="{{ asset('img/logo-bpee.png') }}" class="m--img-rounded m--marginless"/>
									</div>
									@if(Auth::check())
									<div class="m-card-user__details">
										<a href="{{ url('profile') }}" class="m-card-user__email" style="color: white; text-decoration: none;">
											<span class="m-card-user__name" style="color: black">
												@php
													$ceks = \App\Models\Karyawan::find(Auth::user()->id_karyawan);
													if(isset($ceks->nm_karyawan)){
														 echo $ceks->nm_karyawan;
													}else{
														echo Auth::user()->username;
													}
												@endphp
											</span>
											<b class="mt-4">{{ strtoupper(Session("role")["nm_role"]) }}</b>
										</a>
									</div>
									@endif
								</div>
							</div>
							
							<div class="m-dropdown__body">
								<div class="m-dropdown__content">
									<ul class="m-nav m-nav--skin-light">
										<li class="m-nav__section m--hide">
											<span class="m-nav__section-text">
												Section
											</span>
										</li>
										@if(Auth::check())
										@php
										$auth_role = \App\Models\RoleUser::ChekRole(Auth::user()->id_user);
										@endphp
										
										@foreach($auth_role as $key => $value)
										@if(Session("role")["id_role"]!=$value->id_role)
										<li class="m-nav__item text-right" onclick="onSubmit()">
											<a href="{{ url('changerole')."/".$value->id_role }}" class="m-nav__link">
												<h6>{{ strtoupper($value->nm_role ) }}</h6>
											</a>
										</li>
										@endif
										@endforeach
										@endif
										<li class="m-nav__separator m-nav__separator--fit"></li>
										
										<li class="m-nav__item text-right">
											<a href="{{ url('auth/logout') }}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
												Logout
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>

@include("template.js-header")