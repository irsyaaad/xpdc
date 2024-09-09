@extends('template.document')

@section('data')

@if(Request::segment(1)=="acperush" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf

    <div class="row mt-1">
        <div class="col-md-12" >
            <table class="table table-responsive table-hover">
                <thead style="background-color: grey; color : #fff">
                    <tr>
                        <th>Kode Account</th>
                        <th>AC 1</th>
                        <th>AC 2</th>
                        <th>AC 3</th>
                        <th>Nama Account</th>
                        <th>Def Post</th>
                        <th>Is Aktif</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akun as $key => $value)
                    <tr>
                        <td>{{ $value->id_ac }}</td>
                        <td>@if(isset($data1[$data2[$data3[$value->parent]->id_parent]->id_parent])) {{ $data1[$data2[$data3[$value->parent]->id_parent]->id_parent]->nama }} @endif</td>
                        <td>@if(isset($data2[$data3[$value->parent]->id_parent])) {{ $data2[$data3[$value->parent]->id_parent]->nama }} @endif</td>
                        <td>@if(isset($data3[$value->parent])) {{ $data3[$value->parent]->nama }} @endif</td>
                        <td>{{ $value->nama }}</td>
                        <td>
                            {{ $value->def_pos }}
                        </td>
                        <td>
                            @if($value->is_aktif==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            @if (in_array(strtolower(Session("role")["nm_role"]), ['admin']))
                                {!! inc_delete($value->id) !!}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@endif

@endsection

@section('script')

@endsection
