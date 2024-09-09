@extends('template.document')

@section('data')

@if(Request::segment(1)=="masterac" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12" >
            <table class="table table-responsive table-hover">
                <thead style="background-color: grey; color : #fff">
                    <tr>
                        <th>Kode Account</th>
                        <th>Parent</th>
                        <th>Nama Account</th>
                        <th>Tipe</th>
                        <th>Level</th>
                        <th>Is Aktif</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data1 as $key => $value)
                    <tr>
                        <td>{{ strtoupper($value->id_ac) }}</td>
                        <td>{{ strtoupper($value->id_parent) }}</td>
                        <td>{{ strtoupper($value->nama) }}</td>
                        <td>{{ $value->tipe }}</td>
                        <td>{{ strtoupper($value->level) }}</td>
                        <td>
                            @if($value->is_aktif==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            {!! inc_delete($value->id_ac) !!}
                        </td>
                    </tr>
                    @if(isset($data2[$value->id_ac]))
                    @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                        <td style="padding-left: 40px">{{ strtoupper($value2->id_ac) }}</td>
                        <td style="padding-left: 40px">{{ strtoupper($value2->id_parent) }}</td>
                        <td style="padding-left: 40px">{{ strtoupper($value2->nama) }}</td>
                        <td>{{ $value2->tipe }}</td>
                        <td>{{ strtoupper($value2->level) }}</td>
                        <td>
                            @if($value2->is_aktif==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            {!! inc_delete($value2->id_ac) !!}
                        </td>
                    </tr>
                    @if(isset($data3[$value2->id_ac]))
                    @foreach($data3[$value2->id_ac] as $key => $value3)
                    <tr>
                        <td style="padding-left: 80px">{{ strtoupper($value3->id_ac) }}</td>
                        <td style="padding-left: 80px">{{ strtoupper($value3->id_parent) }}</td>
                        <td style="padding-left: 80px">{{ strtoupper($value3->nama) }}</td>
                        <td>{{ $value3->tipe }}</td>
                        <td>{{ strtoupper($value3->level) }}</td>
                        <td>
                            @if($value3->is_aktif==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td> {!! inc_delete($value3->id_ac) !!} </td>
                    </tr>
                    @endforeach
                    @endif
                    @endforeach
                    @endif
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
