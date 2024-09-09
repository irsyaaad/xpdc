
@extends('template.document')

@section('data')
@if((Request::segment(1)=="settingbiaya" or Request::segment(1)=="settingbiayaperush") and Request::segment(2)==null)
@if(Request::segment(1)=="settingbiayaperush")
@if (count($data) <= 0) 
    <div class="text-right">
        <a href="{{ Request::segment(1)."/generate" }}" class="btn btn-sm btn-info"><i class="fa fa-retweet"></i> Generate</a>
    </div>
@endif
<br><br>
@endif
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-responsive table-striped" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Group Biaya</th>
                <th>Perkiraan Akun Biaya</th>
                <th>Perkiraan Akun Hutang</th>
                <th>User</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>
                    @if(isset($value->nm_biaya_grup))
                    {{ strtoupper($value->nm_biaya_grup) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->nama_biaya))
                    {{ strtoupper($value->nama_biaya) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->nama_hutang))
                    {{ strtoupper($value->nama_hutang) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->nm_user))
                    {{ strtoupper($value->nm_user) }}
                    @endif
                </td>
                <td>
                    {!! inc_edit($value->id_setting) !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }}@else{{ url(Request::segment(1), $data->id_setting) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    
    @csrf
    @include('keuangan::settingbiaya.create')
    <div class="col-md-3" style="margin-top: 1.5%">
        @include('template.inc_action')
    </div>
</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
    @if(Request::segment(3)=="edit")
    $("#ac4_hutang").val("{{ $data->id_ac_hutang }}");
    $("#ac4_biaya").val("{{ $data->id_ac_biaya }}");
    @endif
</script>
@endsection