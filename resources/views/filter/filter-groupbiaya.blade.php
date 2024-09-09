<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
			<select class="form-control" id="filter" name="filter">
				<option value="@if(isset($req)){{$req}}@endif">@if(isset($req)){{$req}}@else-- Pilih Kelompok --@endif</option>
                <option value="HPP">HPP</option>
                <option value="OPERASIONAL">OPERASIONAL</option>
			</select>
		</div>
	</div>
	<div class="d-md-none m--margin-bottom-10"></div>
</div>
<div class="col-md-3">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)."/reset") }}" class="btn btn-sm btn-warning"><span><i class="fa fa-times"> </i></span> Reset </a>
</div>
<script>
	@if(Session('klp')!=null)
		$("#filter").val('{{ Session('klp') }}');
	@endif
</script>
