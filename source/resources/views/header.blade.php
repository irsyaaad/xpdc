<header class="m-grid__item    m-header " >
	<div class="m-container m-container--fluid m-container--full-height">
		<div class="m-stack m-stack--ver m-stack--desktop">
			<div class="m-stack__item m-brand  m-brand--skin-dark ">
					<div class="m-stack m-stack--ver m-stack--general">
						<div class="m-stack__item m-stack__item--middle m-brand__logo">
							<a href="{{ url('/') }}" class="m-brand__logo-wrapper">
								<img alt="" src="{{ url('assets/demo/default/media/img/logo/logo_default_dark.png') }}"/>
							</a>
						</div>

						<div class="m-stack__item m-stack__item--middle m-brand__tools">
							<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block 
							">
							<span></span>
						</a>

						<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
							<span></span>
						</a>

						<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
							<span></span>
						</a>

						<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
							<i class="flaticon-more"></i>
						</a>
					</div>
				</div>
			</div>

			<!-- For Header Menu -->
			<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

				<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
					<i class="la la-close"></i>
				</button>

				<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark "  >
					<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
						<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" data-redirect="true" aria-haspopup="true">
							<a  href="#" class="m-menu__link m-menu__toggle">
								<i class="m-menu__link-icon flaticon-add"></i>
								<span class="m-menu__link-text">
									Actions
								</span>
								<i class="m-menu__hor-arrow la la-angle-down"></i>
								<i class="m-menu__ver-arrow la la-angle-right"></i>
							</a>
							<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
								<span class="m-menu__arrow m-menu__arrow--adjust"></span>
								<ul class="m-menu__subnav">

									<li class="m-menu__item "  data-redirect="true" aria-haspopup="true">
										<a  href="header/actions.html" class="m-menu__link ">
											<i class="m-menu__link-icon flaticon-diagram"></i>
											<span class="m-menu__link-title">
												<span class="m-menu__link-wrap">
													<span class="m-menu__link-text">
														Generate Reports
													</span>
													<span class="m-menu__link-badge">
														<span class="m-badge m-badge--success">
															2
														</span>
													</span>
												</span>
											</span>
										</a>
									</li>

									<li class="m-menu__item  m-menu__item--submenu"  data-menu-submenu-toggle="hover" data-redirect="true" aria-haspopup="true">
										<a  href="#" class="m-menu__link m-menu__toggle">
											<i class="m-menu__link-icon flaticon-business"></i>
											<span class="m-menu__link-text">
												Manage Orders
											</span>
											<i class="m-menu__hor-arrow la la-angle-right"></i>
											<i class="m-menu__ver-arrow la la-angle-right"></i>
										</a>
										<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right">
											<span class="m-menu__arrow "></span>
											<ul class="m-menu__subnav">
												<li class="m-menu__item "  data-redirect="true" aria-haspopup="true">
													<a  href="header/actions.html" class="m-menu__link ">
														<span class="m-menu__link-text">
															Latest Orders
														</span>
													</a>
												</li>
												<li class="m-menu__item "  data-redirect="true" aria-haspopup="true">
													<a  href="header/actions.html" class="m-menu__link ">
														<span class="m-menu__link-text">
															Pending Orders
														</span>
													</a>
												</li>
											</ul>
										</div>
									</li>

									<li class="m-menu__item "  data-redirect="true" aria-haspopup="true">
										<a  href="header/actions.html" class="m-menu__link ">
											<i class="m-menu__link-icon flaticon-users"></i>
											<span class="m-menu__link-text">
												Register Member
											</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>

				<!-- END: Horizontal Menu -->								<!-- BEGIN: Topbar -->
				<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
					<div class="m-stack__item m-topbar__nav-wrapper">
						<ul class="m-topbar__nav m-nav m-nav--inline">

							<li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center 	m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
								<a href="#" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
									<span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
									<span class="m-nav__link-icon">
										<i class="flaticon-music-2"></i>
									</span>
								</a>

								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/notification_bg.jpg); background-size: cover;">
											<span class="m-dropdown__header-title">
												9 New
											</span>
											<span class="m-dropdown__header-subtitle">
												User Notifications
											</span>
										</div>
										<div class="m-dropdown__body">
											<div class="m-dropdown__content">
												<ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
													<li class="nav-item m-tabs__item">
														<a class="nav-link m-tabs__link active" data-toggle="tab" href="#topbar_notifications_notifications" role="tab">
															Alerts
														</a>
													</li>
													<li class="nav-item m-tabs__item">
														<a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events" role="tab">
															Events
														</a>
													</li>

												</ul>

												<div class="tab-content">
													<div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
														<div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
															<div class="m-list-timeline m-list-timeline--skin-light">
																<div class="m-list-timeline__items">
																	<div class="m-list-timeline__item">
																		<span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
																		<span class="m-list-timeline__text">
																			12 new users registered
																		</span>
																		<span class="m-list-timeline__time">
																			Just now
																		</span>
																	</div>

																	<div class="m-list-timeline__item">
																		<span class="m-list-timeline__badge"></span>
																		<span class="m-list-timeline__text">
																			System shutdown
																			<span class="m-badge m-badge--success m-badge--wide">
																				pending
																			</span>
																		</span>
																		<span class="m-list-timeline__time">
																			14 mins
																		</span>
																	</div>

																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>

							<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
								<a href="#" class="m-nav__link m-dropdown__toggle">
									<span class="m-topbar__userpic">
										<img src="{{ asset('assets/app/media/img/users/user4.jpg') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
									</span>
									<span class="m-topbar__username m--hide">
										Nick
									</span>
								</a>
								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
											<div class="m-card-user m-card-user--skin-dark">
												<div class="m-card-user__pic">
													<img src="{{('assets/app/media/img/users/user4.jpg') }}" class="m--img-rounded m--marginless" alt=""/>
												</div>
												<div class="m-card-user__details">
													<span class="m-card-user__name m--font-weight-500">
														Mark Andre
													</span>
													<a href="" class="m-card-user__email m--font-weight-300 m-link">
														mark.andre@gmail.com
													</a>
												</div>
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
													<li class="m-nav__item">
														<a href="header/profile.html" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-profile-1"></i>
															<span class="m-nav__link-title">
																<span class="m-nav__link-wrap">
																	<span class="m-nav__link-text">
																		My Profile
																	</span>
																	<span class="m-nav__link-badge">
																		<span class="m-badge m-badge--success">
																			2
																		</span>
																	</span>
																</span>
															</span>
														</a>
													</li>

													<li class="m-nav__item">
														<a href="header/profile.html" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-share"></i>
															<span class="m-nav__link-text">
																Activity
															</span>
														</a>
													</li>

													<li class="m-nav__separator m-nav__separator--fit"></li>

													<li class="m-nav__item">
														<a href="snippets/pages/user/login-1.html" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
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
		</div>
	</div>
</header>