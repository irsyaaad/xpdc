@extends('template.document')

@section('data')

@if(Request::segment(1)=="masterac" && Request::segment(2)==null)

<table class="table table-responsive" style="border-collapse:collapse; margin-top:-2%" cellspacing="-10px">
	<thead>
		<tr>
			<th>#</th>
			<th>ID</th>
			<th>Nama Menu</th>
			<th>Tipe</th>
			<th>Level</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data1 as $key => $value)
		<tr class="accordion-toggle collapsed">
			<td id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne{{$value->id_ac}}">
				@if(isset($data2[$value->id_ac]))
				<i class="fa fa-plus"></i>				
				@endif
			</td>
			<td>{{ strtoupper($value->id_ac) }}</td>
			<td>{{ strtoupper($value->nama) }}</td>
			<td>{{ $value->tipe }}</td>
			<td>{{ strtoupper($value->level) }}</td>
			<td> @if(get_admin()){!! inc_edit($value->id_ac) !!} {{ dd() }} @endif </td>
		</tr>
		@if(isset($data2[$value->id_ac]))
		<tr style="padding:0px">
			<td colspan="8" style="padding:0px">
				<div id="collapseOne{{$value->id_ac}}" class="collapse in p-1">
					<table class="table table-condensed table-responsive" style="border-collapse:collapse;">
						@foreach($data2[$value->id_ac] as $key2 => $value2)
						<tr class="accordion-toggle collapsed">
							<td></td>
							<td id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo{{$value2->id_ac}}">
								@if(isset($data3[$value2->id_ac]))
								<i class="fa fa-plus"></i>		
								@endif
							</td>
							<td>{{strtoupper ($value2->id_ac) }}</td>
							<td>{{ strtoupper($value2->nama) }}</td>
							<td>{{ strtoupper($value2->tipe) }}</td>
							<td>{{ strtoupper($value2->level) }}</td>
							<td>-</td>
							<td> @if(get_admin()){!! inc_edit($value2->id_ac) !!}@endif </td>
						</tr>
						@if(isset($data3[$value2->id_ac]))
						<tr style="padding:0px">
							<td colspan="8" style="padding:0px">
								<div id="collapseTwo{{$value2->id_ac}}" class="collapse in p-1">
									<table class="table table-condensed table-responsive" style="border-collapse:collapse;">
										@foreach($data3[$value2->id_ac] as $key => $value3)
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td>{{strtoupper($value3->id_ac)}}</td>
											<td>{{ strtoupper($value3->nama) }}
											</td>
											<td>{{ strtoupper($value3->tipe) }}</td>
											<td>{{ strtoupper($value3->level) }}</td>
											<td> @if(get_admin()){!! inc_edit($value2->id_ac) !!}@endif </td>
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


@endif

{{-- for insert data or edit --}}
<style type="text/css">
	textarea{
		min-height: 100px;
	}
</style>

@endsection

{{-- this for loading javascript data --}}
@section('script')
<script type="text/javascript">
	$("#parent1").prop("disabled", true);
	$('#level').on("change", function(e) {
	if ($('#level').val() > 0) {
		$("#parent1").prop("disabled", false);
	}
	});
</script>
@endsection