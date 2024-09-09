@extends('layout')

@section('content')

<div class="m-content">

	<!-- For Sub Body Content -->
		<!-- <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
			<div class="m-alert__icon">
				<i class="flaticon-exclamation m--font-brand"></i>
			</div>
			<div class="m-alert__text">
				The Metronic Datatable component supports local or remote data binding. For the local data binding you can pass javascript array as data source. In this example the grid fetches its data from a javascript array data source. It also defines the schema m-btn--icon-onlydel of the data source. In addition to the visualization, the Datatable provides built-in support for operations over data such as sorting, filtering and paging performed in user browser(frontend).
			</div>
		</div> -->
		
		<!-- For Header -->
		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Local Datatable
							<small>
								initialized from javascript array
							</small>
						</h3>
					</div>
				</div>

				<!-- For Optional Data -->
				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
								<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
									<i class="la la-ellipsis-h m--font-brand"></i>
								</a>
								<div class="m-dropdown__wrapper">
									<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
									<div class="m-dropdown__inner">
										<div class="m-dropdown__body">
											<div class="m-dropdown__content">
												<ul class="m-nav">
													<li class="m-nav__section m-nav__section--first">
														<span class="m-nav__section-text">
															Quick Actions
														</span>
													</li>
													<li class="m-nav__item">
														<a href="" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-share"></i>
															<span class="m-nav__link-text">
																Create Post
															</span>
														</a>
													</li>
													<li class="m-nav__item">
														<a href="" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-chat-1"></i>
															<span class="m-nav__link-text">
																Send Messages
															</span>
														</a>
													</li>
													<li class="m-nav__item">
														<a href="" class="m-nav__link">
															<i class="m-nav__link-icon flaticon-multimedia-2"></i>
															<span class="m-nav__link-text">
																Upload File
															</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>

			<!-- For Body Data Content -->
			<div class="m-portlet__body">
				<table class="m-datatable" id="html_table" width="100%">
					<thead>
						<tr>
							<th title="Field #1">
								No
							</th>
							<th title="Field #2">
								Nama
							</th>
							<th title="Field #3">
								Jenis Kelamin
							</th>
							<th title="Field #4">
								Alamat
							</th>
							<th title="Field #9">
								Action
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								16590-107
							</td>
							<td>
								Zandra Fisbburne
							</td>
							<td>
								(916) 6137523
							</td>
							<td>
								Pontiac
							</td>
							<td class="text-centr">
								<button class="btn btn-sm btn-warning" style="color: white">
									<i class="fa fa-pencil"></i>
								</button>

								<button class="btn btn-sm btn-danger">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>

@endsection