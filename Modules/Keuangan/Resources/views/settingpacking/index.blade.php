
@extends('template.document')

@section('data')

@if((Request::segment(1)=="settingpacking" or Request::segment(1)=="settingpackingperush") and Request::segment(2)==null)
@if(Request::segment(1)=="settingpackingperush")
<div class="text-right">
    <a href="{{ Request::segment(1)."/generate" }}" class="btn btn-sm btn-info"><i class="fa fa-retweet"></i> Generate</a>
</div>
@endif
<br>

<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-responsive table-stripped" width="100%" style="margin-top: 10px">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                @if(Request::segment(1)=="settingpackingperush")
                <th>Perusahaan</th>
                @endif
                <th>Akun Pendapatan</th>
                <th>Akun Piutang</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>{{ strtoupper($value->pendapatan) }}</td>
                <td>{{ strtoupper($value->piutang) }}</td>
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
    @include('keuangan::settingpacking.create')
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
    $("#ac4_pend_penerima").val("{{ $data->ac4_pend_penerima }}");
    $("#ac4_piutang_penerima").val("{{ $data->ac4_piutang_penerima }}");
    $("#ac4_hutang").val("{{ $data->ac4_hutang }}");
    $("#ac4_biaya").val("{{ $data->ac4_biaya }}");
    $("#id_perush_pengirim").val("{{ $data->id_perush_pengirim }}");
    $("#nm_setting").val("{{ $data->nama_setting }}");
    @endif
</script>
@endsection