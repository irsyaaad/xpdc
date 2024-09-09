@extends('template.document')
@section('style')
<style>
    #modal-filter {
        padding: 0 !important;
    }
    #modal-filter .modal-dialog {
        width: 90%;
        max-width: none;
        margin: auto;
        top: 10%;
    }
    #modal-filter .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }
    #modal-filter .modal-body {
        overflow-y: auto;
    }
</style>
@endsection
@section('data')

@php
    $params = request()->query();
    $a_params= getParamsUrl($params);
    $ulrs = url('hargavendor').$a_params;
@endphp
<form method="GET" action="{{ url('hargavendor') }}" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filterhargavendor')
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        <div class="col-md-12 mt-2">
            <table class="table table-hover table-responsive">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Vendor </th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th width="150">Hpp Kg </th>
                        <th width="150">HPP M3</th>
                        <th>Lead Time</th>
                        <th width="80">Type</th>
                        <th>Last Updated</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                    <tr> 
                        <td>{{ $key+1 }}</td>
                        <td>
                            @if($value->rekomendasi=="1")
                            <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan">
                                @if($value->nm_ven !=null)
                                {{ $value->nm_ven }} <br>
                                @else
                                <h4><span class="badge badge-pill badge-success">Multivendor</span></h4>
                                @endif
                                @if($value->rekomendasi=="1")
                                <label><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></label>
                                @endif
                                @else
                                @if($value->nm_ven !=null)
                                {{ $value->nm_ven }}
                                @else
                                <h4><span class="badge badge-pill badge-success">Multivendor</span></h4>
                                @endif
                                @endif
                            </td>
                            <td>{{  strtoupper($value->wil_asal) }}</td>
                            <td>{{  strtoupper($value->wil_tujuan) }}</td>
                            <td>{{  toRupiah($value->harga)." / Kg " }}<br> Min : {{  $value->min_kg }}<br></td>
                            <td>{{  toRupiah($value->hrg_kubik)." / M3 " }}<br> Min : {{  $value->min_kubik }}<br></td>
                            <td>{{  $value->time." Hari " }}</td>
                            <td>
                                @if($value->type == 1)
                                <span class="badge badge-pill badge-info">Direct</span>
                                @else
                                <span class="badge badge-pill badge-success">Multivendor </span>
                                <br><label style="font-size: 8pt">({{ $value->tven.' Vendor'}})</label>
                                @endif
                            </td>
                            <td>
                                @if($value->update_user != null)
                                <label style="font-size: 8pt">{{ $value->updated_at }}<br>{{ $value->update_user }}</label>
                                @else
                                <label style="font-size: 8pt">{{ $value->created_at }}<br>{{ $value->insert_user }}</label>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_harga."/detail".$a_params) }}"><i class="fa fa-eye"></i> Detail</a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_harga."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
                                        <button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_harga) }}')">
                                            <span><i class="fa fa-times"></i></span> Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            @include('template.paginator')
        </div>
    </form>
    
    <div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-labelledby="modal-upload" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-money"> </i> Import Data Harga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url("hargavendor/".Auth::user()->id_user."/import") }}"  enctype="multipart/form-data">
                        @csrf
                        <div class=" text-center">
                            <label>Pilih File Excel / CSV</label><br>
                            <input type="file" id="files" name="files" class="form-control"  />
                        </div>
                        <div class="text-right mt-2">
                            <button class="btn btn-sm btn-success" type="submit">
                                <i class="fa fa-save"></i> import
                            </button>
                            <button class="btn btn-sm btn-danger" type="button" data-dismiss="modal">
                                <i class="fa fa-times"></i> batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @endsection
    @section('script')
    <script>
        
        $("#shareselect").on("change", function(e) {
            $("#form-select").submit();
        });
        
        $('#id_tujuan').select2({
            placeholder: 'Cari Wilayah Asal ....',
            dropdownParent: $("#modal-filter"),
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_tujuan').empty();
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#id_asal').select2({
            placeholder: 'Cari Wilayah Tujuan ....',
            dropdownParent: $("#modal-filter"),
            ajax: {
                url: '{{ url('getwilayah') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    $('#id_asal').empty();
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.value,
                                id: item.kode
                            }
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#id_ven').select2({
            dropdownParent: $("#modal-filter")
        });
        
        var total = 0;
        
        @if(isset($filter["page"]))
        $("#shareselect").val('{{ $filter["page"] }}');
        @endif	
        
        @if(isset($filter["id_ven"]))$("#id_ven").select2().val("{{ $filter["id_ven"] }}").trigger("change");@endif
        
        @if(isset($filter["asal"]->id_wil))
        $('#id_asal').append('<option value="{{ $filter["asal"]->id_wil }}">{{ $filter["asal"]->nama_wil }}</option>');
        @endif
        
        @if(isset($filter["tujuan"]->id_wil))
        $('#id_tujuan').append('<option value="{{ $filter["tujuan"]->id_wil }}">{{ $filter["tujuan"]->nama_wil }}</option>');
        @endif
        
        @if(isset($filter["updated"]))$("#updated").val("{{ $filter["updated"] }}");@endif
        @if(isset($filter["type"]))$("#type").val("{{ $filter["type"] }}");@endif
        
        @if(isset($filter["range"]) and $filter["range"]==1)
        $("#range").val("1");
        $("#btn-area").removeClass("btn-info");
        $("#btn-area").addClass("btn-danger");
        @else
        $("#range").val("");
        $("#btn-area").removeClass("btn-danger");
        $("#btn-area").addClass("btn-info");
        @endif
        
        $("#btn-area").on("click", function(e) {
            var range = $("#range").val();
            if(range=="1"){
                $("#range").val("");
                $("#btn-area").removeClass("btn-danger");
                $("#btn-area").addClass("btn-info");
            }else{
                $("#range").val("1");
                $("#btn-area").removeClass("btn-info");
                $("#btn-area").addClass("btn-danger");
            }
        });

    </script>
    @endsection