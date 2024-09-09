
@extends('template.document')

@section('data')

@if(Request::segment(1)=="groupbiaya" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    @csrf
    <div class="row mt-1">
        <div class="col-md-12" >

            <table class="table table-responsive table-hover">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Nama Group</th>
                        <th>Kelompok</th>
                        <th>User</th>
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
                            {{ strtoupper($value->nm_biaya_grup) }}
                        </td>
                        <td>
                            {{ strtoupper($value->klp) }}
                        </td>
                        <td>
                            @if(isset($value->user->nm_user)){{ strtoupper($value->user->nm_user) }}@endif
                        </td>
                        <td>
                            {!! inc_edit($value->id_biaya_grup) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>


<div class="row" style="margin-top: 4%; font-weight: bold;">
    @include('template.paginate')
</div>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
    @else
    <form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1), $data->id_biaya_grup) }}" enctype="multipart/form-data">
        {{ method_field("PUT") }}
        @endif

        @csrf

        <div class="form-group m-form__group">
            <label for="nm_biaya_grup">
                <b>Nama Group</b><span class="span-required"> *</span>
            </label>

            <input type="text" class="form-control m-input m-input--square" name="nm_biaya_grup" id="nm_biaya_grup" value="@if(isset($data->nm_biaya_grup)){{$data->nm_biaya_grup}}@else{{ old('nm_biaya_grup') }}@endif" required="required" maxlength="128">

            @if ($errors->has('nm_biaya_grup'))
            <label style="color: red">
                {{ $errors->first('nm_biaya_grup') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group">
            <label for="klp">
                <b>Kelompok</b><span class="span-required"> *</span>
            </label>

            <select class="form-control m-input m-input--square" name="klp" id="klp" required="required">
                <option> -- Pilih Kelompok --</option>
                <option value="HPP"> HPP </option>
                <option value="OPERASIONAL"> Operasional </option>
            </select>

            @if ($errors->has('klp'))
            <label style="color: red">
                {{ $errors->first('klp') }}
            </label>
            @endif
        </div>

        @include('template.inc_action')
    </form>
    @endif

    @endsection

    @section('script')
    <script type="text/javascript">
        $("#shareselect").on("change", function(e) {
            $("#form-share").submit();
        });
        @if(isset($page))
        $("#shareselect").val({{ $page }});
        @endif
        @if(Request::segment(3)=="edit" and isset($data->klp))
        $("#klp").val('{{ $data->klp }}');
        @endif

        @if(isset($filter) and $filter!=null)
        $("#filter").val('{{ $req }}');
        @endif
    </script>
    @endsection
