@extends('template.document')

@section('data')
@php
$routes = explode(".", Route::currentRouteName());
@endphp

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @csrf
    @include("template.filter-collapse")
    <div class="row mt-1">
        <div class="col-md-12">
            <table class="table table-responsive table-hover" style="width=100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>Kode Reff</th>
                        <th>Kode Perusahaan</th>
                        <th>Nama Perusahaan</th>
                        <th>Perusahaan Induk</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ strtoupper($value->kode_ref) }}</td>
                        <td>{{ strtoupper($value->kode_perush) }}</td>
                        <td>{{ strtoupper($value->nm_perush) }}</td>
                        <td>
                            @if(isset($value->induk->nm_perush))
                            {{ strtoupper($value->induk->nm_perush) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->wilayah->nama_wil)){{ strtoupper($value->wilayah->nama_wil) }}@endif
                            <br>
                            {{ $value->alamat }}
                        </td>
                        <td>{{ $value->telp }}</td>
                        <td>
                            {!! inc_edit($value->id_perush) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )
@include('perusahaan.create-edit')
@endif

@endsection
@section('script')
<script type="text/javascript">

    $("#id_region").select2();
    $("#f_id_perush").select2();
    $("#f_id_wil").select2();

    @if(isset($filter["f_id_perush"]))
    $("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
    @endif

    @if(isset($filter["f_id_wil"]))
    $("#f_id_wil").val('{{ $filter["f_id_wil"] }}').trigger("change");
    @endif

    @if(isset($data->id_region) and $data->id_region!=null)$("#id_region").val('{{ $data->id_region }}').trigger("change");@endif
    
    @if(isset($data->cabang) and $data->cabang)
    $("#cabang").val('{{ $data->cabang }}');
    @endif

    @if(isset($data->id_cab_group))
    $("#id_cab_group").val('{{ $data->id_cab_group }}');
    @endif

    @if(isset($data->is_aktif) and $data->is_aktif==1)
    $("#is_aktif").attr("checked", true);
    @else
    $("#is_aktif").attr("checked", false);
    @endif

    $( document ).ready(function() {
        $("#cabang").select2();
    })
</script>
@endsection
