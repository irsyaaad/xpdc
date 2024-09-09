@extends('template.document')

@section('data')

@php
$routes = explode(".", Route::currentRouteName());
@endphp

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<div class="row mt-1">
		<div class="col-md-12">
			<table class="table table-responsive table-hover" style="border-collapse:collapse;" cellspacing="-10px">
				<thead  style="background-color: grey; color : #ffff">
					<tr>
						<th>#</th>
						<th>Nama Menu</th>
						<th>Module</th>
						<th>Icon</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data1 as $key => $value)
					<tr class="accordion-toggle collapsed">
						<td id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne{{$value->id_menu}}">
							@if(isset($data2[$value->id_menu]))
							<i class="fa fa-plus"></i>
							@else
							<i class="fa fa-minus"></i>				
							@endif
						</td>
						<td>{{ strtoupper($value->nm_menu) }}
						</td>
						<td>{{ $value->module->nm_module }}
						</td>
						<td><i class="{{ $value->icon }}" style="font-size: 18pt"></i></td>
						<td>
							@if(get_admin())
							<form method="POST" action="{{ url("menus").'/'.$value->id_menu }}" id="form-delete{{ $value->id_menu }}" name="form-delete{{ $value->id_menu }}">
								{{ method_field("DELETE") }}
								<a href="{{ url("menus").'/'.$value->id_menu.'/goup' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Up">
									<i class="fa fa-arrow-up"></i>
								</a>
								<a href="{{ url("menus").'/'.$value->id_menu.'/godown' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Down">
									<i class="fa fa-arrow-down"></i>
								</a>
								<a href="{{ url("menus").'/'.$value->id_menu.'/edit' }}" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
									<i class="fa fa-pencil"></i>
								</a>
								@csrf
								<button class="btn btn-sm btn-danger" id="hapus" type="button" onclick="CheckDelete('{{ $value->id_menu }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
									<i class="fa fa-times"></i>
								</button>
							</form>
							@endif
						</td>
					</tr>
					@if(isset($data2[$value->id_menu]))
					<tr style="padding:0px">
						<td colspan="5" style="padding:0px">
							<div id="collapseOne{{$value->id_menu}}" class="collapse in p-1">
								<table class="table table-condensed table-responsive" style="border-collapse:collapse;">
									@foreach($data2[$value->id_menu] as $key2 => $value2)
									<tr class="accordion-toggle collapsed">
										<td></td>
										<td id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo{{$value2->id_menu}}">
											@if(isset($data3[$value2->id_menu]))
											<i class="fa fa-plus"></i>
											@else	
											<i class="fa fa-minus"></i>				
											@endif
										</td>
										<td>{{ strtoupper($value2->nm_menu) }}
										</td>
										<td>{{ $value2->module->nm_module }}</td>
										<td><i class="{{ $value2->icon }}" style="font-size: 18pt"></i></td>
										<td>
											@if(get_admin())
											<form method="POST" action="{{ url("menus").'/'.$value2->id_menu }}" id="form-delete{{ $value2->id_menu }}" name="form-delete{{ $value->id_menu }}">
												{{ method_field("DELETE") }}
												<a href="{{ url("menus").'/'.$value2->id_menu.'/goup' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Up">
													<i class="fa fa-arrow-up"></i>
												</a>
												<a href="{{ url("menus").'/'.$value2->id_menu.'/godown' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Down">
													<i class="fa fa-arrow-down"></i>
												</a>
												<a href="{{ url("menus").'/'.$value2->id_menu.'/edit' }}" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
													<i class="fa fa-pencil"></i>
												</a>
												@csrf
												<button class="btn btn-sm btn-danger" id="hapus" type="button" onclick="CheckDelete('{{ $value2->id_menu }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
													<i class="fa fa-times"></i>
												</button>
											</form>
											@endif	
										</td>
									</tr>
									@if(isset($data3[$value2->id_menu]))
									<tr style="padding:0px">
										<td colspan="5" style="padding:0px">
											<div id="collapseTwo{{$value2->id_menu}}" class="collapse in p-1">
												<table class="table table-condensed table-responsive" style="border-collapse:collapse;">
													@foreach($data3[$value2->id_menu] as $key => $value3)
													<tr>
														<td></td>
														<td></td>
														<td><i class="fa fa-minus"></i></td>
														<td>{{ strtoupper($value3->nm_menu) }}
														</td>
														<td>{{ $value3->module->nm_module }}</td>
														<td><i class="{{ $value3->icon }}" style="font-size: 18pt"></i></td>
														<td>
															@if(get_admin())
															<form method="POST" action="{{ url("menus").'/'.$value3->id_menu }}" id="form-delete{{ $value3->id_menu }}" name="form-delete{{ $value->id_menu }}">
																{{ method_field("DELETE") }}
																<a href="{{ url("menus").'/'.$value3->id_menu.'/goup' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Up">
																	<i class="fa fa-arrow-up"></i>
																</a>
																<a href="{{ url("menus").'/'.$value3->id_menu.'/godown' }}" class="btn btn-sm btn-accent" data-toggle="tooltip" data-placement="bottom" title="Down">
																	<i class="fa fa-arrow-down"></i>
																</a>
																<a href="{{ url("menus").'/'.$value3->id_menu.'/edit' }}" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
																	<i class="fa fa-pencil"></i>
																</a>
																@csrf
																<button class="btn btn-sm btn-danger" id="hapus" type="button" onclick="CheckDelete('{{ $value3->id_menu }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
																	<i class="fa fa-times"></i>
																</button>
															</form>
															@endif	
														</td>
													</tr>
													@endforeach
												</table>
											</div>
										</td>
									</tr>
									@endif
									@endforeach       
								</table>
							</div>
						</td>
					</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>
		
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")		

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('menus') }}@else{{ route('menus.update', $data->id_menu) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="form-group m-form__group">
		<label for="Module">
			<b>Module Menu</b>
		</label>
		
		<select name="id_module" id="id_module" placeholder="Masukan Color menu" class="form-control m-input m-input--square">
			@foreach($module as $key => $value)
			<option value="{{ $value->id_module }}">{{ $value->nm_module }}</option>
			@endforeach
		</select>
		
		@if ($errors->has('id_module'))
		<label style="color: red">
			{{ $errors->first('id_module') }}
		</label>
		@endif
	</div>
	


	<div class="form-group m-form__group">
		<label for="nm_menu">
			<b>Nama menu</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="nm_menu" id="nm_menu" placeholder="Masukan Nama menu" value="@if(isset($data->nm_menu)){{ $data->nm_menu }}@else{{old('nm_menu')}}@endif" required="required" maxlength="100">
		
		@if ($errors->has('nm_menu'))
		<label style="color: red">
			{{ $errors->first('nm_menu') }}
		</label>
		@endif
	</div>	
	
	<div class="form-group m-form__group">
		<label for="route">
			<b>Route Menu</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="route" id="route" placeholder="Masukan route menu" value="@if(isset($data->route)){{ $data->route }}@else{{old('route')}}@endif" maxlength="100">
		
		@if ($errors->has('route'))
		<label style="color: red">
			{{ $errors->first('route') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="controller">
			<b>Controller Menu</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="controller" id="controller" placeholder="Masukan Controller Menu" value="@if(isset($data->controller)){{ $data->controller }}@else{{old('controller')}}@endif" maxlength="100">
		
		@if ($errors->has('controller'))
		<label style="color: red">
			{{ $errors->first('controller') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="icon">
			<b>Icon menu</b>
		</label>
		
		<div class="row">
			<div class="col-md-11">
				<input type="text" class="form-control m-input m-input--square" name="icon" id="icon" placeholder="Pilih Icon menu" value="@if(isset($data->icon)){{ $data->icon }}@else{{old('icon')}}@endif" required="required">
			</div>
			
			<div class="col-md-1">
				<button class="btn btn-md btn-warning" type="button" data-toggle="modal" data-target="#Modal-font">
					<i class="fa fa-edit"></i>
				</button>
			</div>
		</div>
		
		@if ($errors->has('icon'))
		<label style="color: red">
			{{ $errors->first('icon') }}
		</label>
		@endif
	</div>	
	
	<div class="form-group m-form__group">
		<label for="parent">
			<b>Parent Menu</b>
		</label>
		
		<select name="parent" id="parent" placeholder="Masukan Color Parent" class="form-control m-input m-input--square">
			<option value="0">-- Pilih Parent --</option>
			@foreach($menu as $key => $value)
			<option value="{{ $value->id_menu }}">{{ $value->nm_menu }}</option>
			@endforeach
		</select>
		
		@if ($errors->has('parent'))
		<label style="color: red">
			{{ $errors->first('parent') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="tampil">
			<b>Tampil Menu</b>
		</label>
		
		<div class="row">
			<div class="col-md-2 checkbox">
				<label><input type="checkbox" value="1" id="tampil" name="tampil"> Tampil ?</label>
			</div>
		</div>
	</div>
	
	<div class="text-right">
		@include('template.inc_action')
	</div>
	
</form>
@endif

<div class="modal fade" id="Modal-font" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Pilih Icon</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('inc.font-awesome')
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	@endsection
	
	@section('script')
	<script type="text/javascript">
		@if(isset($filter["f_id_module"]))
		$("#f_id_module").val('{{ $filter["f_id_module"] }}');
		@endif
		
		$("#parent").prop("disabled", true);
		
		@if(Request::segment(3)=="edit")
		$("#parent").prop("disabled", false);
		@endif
		
		@if(Request::segment(3)=="edit" or Request::segment(2)=="create")

		$(".baru").click(function(){
			var icon = $(this).attr("class");
			var icon = icon.replace("baru ", "");
			$("#icon").val(icon);
			$('#Modal-font').modal('toggle');
		});
		
		$("#id_module").change(function(){
			$.ajax({
				type: "GET", 
				url: "{{ url("getmenu") }}/"+$("#id_module").val(), 
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){ 
					$("#parent").prop("disabled", false);
					$("#parent").empty();
					$("#after").empty();
					$("#parent").append('<option value="0">-- Pilih Parent --</option>');
					$.each(response,function(key, value)
					{	
						$("#parent").append('<option value=' + value.id_menu + '>' + value.nm_menu.toUpperCase() + '</option>');
						$("#after").append('<option value=' + value.id_menu + '>' + value.nm_menu.toUpperCase() + '</option>');
					});
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});
		});

		@endif
		
		@if(isset($data->id_module))
		$("#id_module").val({{ $data->id_module }});
		@endif
		
		@if(isset($data->parent))
		$("#parent").val({{ $data->parent }});
		@endif
		
		@if(isset($data->tampil) and $data->tampil==1)
		$("#tampil").prop("checked", true);
		@endif

		$("#parent").select2();
		
	</script>
	@endsection