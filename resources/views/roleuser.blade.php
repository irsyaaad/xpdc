@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12" >
            <table class="table table-striped table-responsive" >
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Perusahaan</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                    
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            @if(isset($value->nm_user))
                            {{ strtoupper($value->nm_karyawan) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_role))
                            {{ strtoupper($value->nm_role) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_perush))
                            {{ strtoupper($value->nm_perush) }}
                            @endif
                        </td>
                        <td>
                            {!! inc_edit($value->id_ru) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('template.paginator')
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });

    $("#f_id_user").select2();
    $("#f_id_perush").select2();
    $("#f_id_role").select2();
    
    @if(isset($filter["page"]))
    $("#shareselect").val('{{ $filter["page"] }}');
    @endif
    
    @if(isset($filter["f_id_user"]))
    $("#f_id_user").val('{{ $filter["f_id_user"] }}').trigger("change");
    @endif

    @if(isset($filter["f_id_role"]))
    $("#f_id_role").val('{{ $filter["f_id_role"] }}').trigger("change");
    @endif
    
    @if(isset($filter["f_id_perush"]))
    $("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
    @endif
</script>
@endsection
