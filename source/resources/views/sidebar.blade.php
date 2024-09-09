<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

	<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
		<i class="la la-close"></i>
	</button>

	<!-- for aside menu -->
	<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
		<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " data-menu-vertical="true"data-menu-scrollable="false" data-menu-dropdown-timeout="500" >
			<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
				<li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
					<a  href="{{ url('/') }}" class="m-menu__link ">
						<i class="m-menu__link-icon flaticon-line-graph"></i>
						<span class="m-menu__link-title">
							<span class="m-menu__link-wrap">
								<span class="m-menu__link-text">
									Dashboard
								</span>
								<span class="m-menu__link-badge">
									<span class="m-badge m-badge--danger">
										2
									</span>
								</span>
							</span>
						</span>
					</a>
				</li>

				<li class="m-menu__section">
					<h4 class="m-menu__section-text">
						Components
					</h4>
					<i class="m-menu__section-icon flaticon-more-v3"></i>
				</li>
				
				<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
					<a  href="#" class="m-menu__link m-menu__toggle">
						<i class="m-menu__link-icon flaticon-interface-7"></i>
						<span class="m-menu__link-text">
							Forms
						</span>
						<i class="m-menu__ver-arrow la la-angle-right"></i>
					</a>
					<div class="m-menu__submenu">
						<span class="m-menu__arrow"></span>
						<ul class="m-menu__subnav">
							<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
								<a  href="$" class="m-menu__link ">
									<span class="m-menu__link-text">
										Forms
									</span>
								</a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="{{ url('/') }}" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Form Controls
									</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/controls/base.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Base Inputs
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/controls/checkbox-radio.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Checkbox & Radio
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/controls/switch.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Switch
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/controls/input-group.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Input Groups
												</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Form Widgets
									</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-datepicker.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Datepicker
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-datetimepicker.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Datetimepicker
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-timepicker.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Timepicker
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-daterangepicker.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Daterangepicker
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-touchspin.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Touchspin
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-maxlength.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Maxlength
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-switch.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Switch
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-multipleselectsplitter.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Multiple Select Splitter
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-select.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Bootstrap Select
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/select2.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Select2
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/typeahead.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Typeahead
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/nouislider.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													noUiSlider
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/form-repeater.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Form Repeater
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/ion-range-slider.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Ion Range Slider
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/input-mask.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Input Masks
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/summernote.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Summernote WYSIWYG
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/bootstrap-markdown.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Markdown Editor
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/autosize.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Autosize
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/clipboard.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Clipboard
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/dropzone.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Dropzone
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/widgets/recaptcha.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Google reCaptcha
												</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Form Layouts
									</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/layouts/default-forms.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Default Forms
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/layouts/multi-column-forms.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Multi Column Forms
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/layouts/action-bars.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Action Bars
												</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Form Validation
									</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/validation/states.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Validation States
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/validation/form-controls.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Form Controls
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/forms/validation/form-widgets.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Form Widgets
												</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>