@if(Request::segment(1)!="" and Auth::check())
	@php
	$menu = \App\Models\Menu::where("route", Request::segment(1))->get()->first();
	$cekplus = \App\Models\RoleMenu::where("id_menu", $menu->id_menu)->where("id_role", Session("role")["id_role"])->get()->first();
	@endphp

	@if(isset($cekplus->c_insert) and $cekplus->c_insert==1)
	<div class="col-xl-4 order-1 order-xl-2 m--align-right" style="padding: 3%">
	<a href="{{ url(Request::segment(1).'/create') }}" class="btn btn-accent m-btn--air m-btn--pill">
		<span>
			<i class="fa fa-plus"></i>
			<span>
				Tambah {{ strtoupper(get_menu(Request::segment(1))) }}
			</span>
		</span>
	</a>
	</div>
	@endif
@endif
