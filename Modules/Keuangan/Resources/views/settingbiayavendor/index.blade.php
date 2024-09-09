
@extends('template.document')

@section('data')

@if(Request::segment(1)=="settingbiayavendor" and Request::segment(2)==null)
<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
            <th>Biaya Group</th>
			<th>Akun Hutang</th>
            <th>Akun Biaya</th>
            <th>User</th>
            <th>Action</th>
		</tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>
                    @if(isset($value->group->nm_biaya_grup))
                        {{ strtoupper($value->group->nm_biaya_grup) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->hutang->nama))
                        {{ strtoupper($value->hutang->nama) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->biaya->nama))
                        {{ strtoupper($value->biaya->nama) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->user->nm_user))
                        {{ strtoupper($value->user->nm_user) }}
                    @endif
                </td>
                <td>
                {!! inc_edit($value->id_setting) !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

@if(Request::segment(2)=="create")
<form method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
@else
<form method="POST" action="{{ url(Request::segment(1), $data->id_setting) }}" enctype="multipart/form-data">
	{{ method_field("PUT") }} 
@endif
    @csrf
    @include('keuangan::settingbiayavendor.create')
    <div class="col-md-12 text-right" style="margin-top: 1.5%">
        @include('template.inc_action')
    </div>
</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	@if(Request::segment(3)=="edit")
        
    @endif
</script>
@endsection