@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12">
            <table class="table table-responsive table-hover" style="width=100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>Module</th>
                        <th>Role</th>
                        <th width="5%">
                            View
                        </th>
                        <th width="5%">
                            Create
                        </th>
                        <th width="5%">
                            Update
                        </th>
                        <th width="5%">
                            Delete
                        </th>
                        <th width="5%">
                            Other
                        </th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ strtoupper($value->nm_menu) }}</td>
                        <td>{{ strtoupper($value->nm_module) }}</td>
                        <td>{{ strtoupper($value->nm_role) }}</td>
                        <td>
                            @if($value->c_read==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            @if($value->c_insert==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            @if($value->c_update==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            @if($value->c_delete==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            @if($value->c_other==1)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            {!! inc_edit($value->id_rm) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginate')
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif
    
    @if(isset($filter["f_id_role"]))
    $("#f_id_role").val('{{ $filter["f_id_role"] }}');
    @endif
    
    @if(isset($filter["f_id_module"]))
    $("#f_id_module").val('{{ $filter["f_id_module"] }}');
    @endif
</script>
@endsection
