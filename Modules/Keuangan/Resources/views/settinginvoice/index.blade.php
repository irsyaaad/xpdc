
@extends('template.document')

@section('data')

@if((Request::segment(1)=="settinginvoicecabang" or Request::segment(1)=="settinginvoicecabangperush") and Request::segment(2)==null)
@if(Request::segment(1)=="settinginvoicecabangperush")
<div class="text-right">
    <a href="{{ Request::segment(1)."/generate" }}" class="btn btn-sm btn-info"><i class="fa fa-retweet"></i> Generate</a>
</div>
<br><br>
@endif
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-responsive table-sm" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                @if(Request::segment(1)=="settinginvoicecabangperush")
                <th>Perusahaan</th>
                @endif
                {{-- <th>Perusahaan Pengirim</th> --}}
                <th>Akun Pendapatan</th>
                <th>Akun Piutang</th>
                <th>Akun Biaya</th>
                <th>Akun Hutang</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td colspan="5">{{strtoupper($value->nama_setting)}}</td>
            </tr>
            <tr>
                <td></td>
                @if(Request::segment(1)=="settinginvoicecabangperush")
                <td>
                    @if(isset($value->perush->nm_perush))
                    {{ strtoupper($value->perush->nm_perush) }}
                    @endif
                </td>
                @endif
                {{-- <td>
                    @if(isset($value->pengirim->nm_perush))
                    {{ strtoupper($value->pengirim->nm_perush) }}
                    @endif
                </td> --}}
                <td>
                    @if(isset($value->pendapatan->nama))
                    {{ strtoupper($value->pendapatan->nama) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->piutang->nama))
                    {{ strtoupper($value->piutang->nama) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->biaya->nama))
                    {{ strtoupper($value->biaya->nama) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->hutang->nama))
                    {{ strtoupper($value->hutang->nama) }}
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
    @include('keuangan::settinginvoice.create')
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