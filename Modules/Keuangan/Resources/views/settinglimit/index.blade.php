
@extends('template.document')

@section('data')

@if(Request::segment(1)=="limitpiutang" and Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    @csrf
    <input type="hidden" name="_method" value="GET">
    <table class="table table-responsive table-striped" width="100%">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Limit</th>
                <th>Default</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                
                <td>
                    {{ torupiah($value->nominal) }}
                </td>
                
                <td>
                    @if($value->is_default==1)
                    <i class="fa fa-check" style="color: green"></i>
                    @else
                    <i class="fa fa-times" style="color: red"></i>
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
<form method="POST" action="@if(Request::segment(2)=="create"){{ url(Request::segment(1)) }} @else{{ url(Request::segment(1), $data->id_setting) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    <div> 
        @csrf
        @include('keuangan::settinglimit.create')
    </div>
</form>
@endif

@endsection