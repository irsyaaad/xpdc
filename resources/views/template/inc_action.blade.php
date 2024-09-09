<div class="form-group">
	<div class="m-form__actions">
		@if(Request::segment(2)=="create" or Request::segment(3)=="edit")
		<button type="submit" class="btn btn-sm btn-primary">
			<i class="fa fa-save"></i> Simpan
		</button>
		@endif
		
		<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-danger">
			<i class="fa fa-times"></i>	Batal
		</a>
	</div>
</div>