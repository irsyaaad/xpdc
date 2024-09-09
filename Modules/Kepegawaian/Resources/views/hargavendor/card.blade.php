@extends('template.document3')
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

<form method="GET" action="{{ url('hargavendor') }}" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filterhargavendor')
    @csrf
    <input type="hidden" name="_method" value="GET">
    <div class="row">
        
        @foreach($data as $key => $value)
        <div class="col-md-12 mt-2">
            @if($value->type==1)
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 @if($value->rekomendasi=="1") data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan"  @endif style="cursor: pointer">
                                <b><i class="fa fa-industry"></i> {{ $value->nm_ven }}</b>
                                @if($value->rekomendasi=="1")
                                <label><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></label>
                                @endif
                            </h5>
                            @if($value->type == 1)
                            <span class="badge badge-pill badge-info"><i class="fa fa-plane"></i> Direct</span>
                            @else
                            <span class="badge badge-pill badge-success"><i class="fa fa-truck"></i>  Transit</span>
                            @endif
                            <br>
                            <label style="font-size: 8pt; margin-top: 5px">
                                Last Updated :
                                @if($value->update_user != null)
                                {{ $value->updated_at }}<br>{{ $value->update_user }}
                                @else{{ $value->created_at }}<br>{{ $value->insert_user }}
                                @endif
                            </label>
                        </div>
                        <div class="col-md-5">
                            <label><i class="fa fa-star"></i> Asal : <b>{{ strtoupper($value->wil_asal) }}</b> </label>
                            <label><i class="fa fa-chevron-right"></i>  Tujuan : <b>{{ strtoupper($value->wil_tujuan) }}</b> </label><br>
                            <label><i class="fa fa-clock-o"></i> Lead Time : <b>{{ $value->time.' Hari ' }}</b> </label>
                        </div>
                        <div class="col-md-4">
                            <label><i class="fa fa-money"></i> Hpp Kg : <b>{{ toRupiah($value->harga).' / Kg | Min : '.$value->min_kg }}</b> </label><br>
                            <label><i class="fa fa-dollar"></i> Hpp M3 : <b>{{ toRupiah($value->hrg_kubik).' / M3 | Min : '.$value->min_kubik }}</b> </label><br>
                            <label><i class="fa fa-cog"></i> Keterangan : <b>{{ $value->keterangan }}</b> </label>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($value->type==2)
            <div style="cursor: pointer" class="accordion accordion-toggle-arrow" id="accordionExample{{ $value->id_harga }}" onclick="goDetail('{{ $value->id_harga }}')">
                <div class="card">
                    <div class="card-body" id="headingOne{{ $value->id_harga }}">
                        <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne{{ $value->id_harga }}" aria-expanded="false" aria-controls="collapseOne2">
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>
                                        <b>
                                            @if($value->nm_ven !=null)
                                            {{ $value->nm_ven }}
                                            @else
                                            <h4 @if($value->rekomendasi=="1") data-toggle="tooltip" data-placement="bottom" title="Harga ini direkomendasikan" @endif>
                                                <span class="badge badge-pill badge-primary"> Multivendor</span>
                                                @if($value->rekomendasi=="1")
                                                <label><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i><i class="fa fa-star text-warning"></i></label>
                                                @endif</h4>
                                            @endif
                                        </b>
                                    </h5>
                                    @if($value->type == 1)
                                    <span class="badge badge-pill badge-info"><i class="fa fa-plane"></i> Direct</span>
                                    @else
                                    <span class="badge badge-pill badge-success"><i class="fa fa-truck"></i>  Transit</span>
                                    ({{ $value->tven." vendor" }})
                                    @endif
                                    <br>
                                    <label style="font-size: 8pt; margin-top: 5px">
                                        Last Updated :
                                        @if($value->update_user != null)
                                        {{ $value->updated_at }}<br>{{ $value->update_user }}
                                        @else{{ $value->created_at }}<br>{{ $value->insert_user }}
                                        @endif
                                    </label>
                                </div>
                                <div class="col-md-5">
                                    <label><i class="fa fa-star"></i> Asal : <b>{{ strtoupper($value->wil_asal) }}</b> </label>
                                    <label><i class="fa fa-chevron-right"></i>  Tujuan : <b>{{ strtoupper($value->wil_tujuan ) }}</b> </label><br>
                                    <label><i class="fa fa-clock-o"></i> Lead Time : <b>{{ $value->time.' Hari ' }}</b> </label>
                                </div>
                                <div class="col-md-4">
                                    <label><i class="fa fa-money"></i> Hpp Kg : <b>{{ toRupiah($value->harga).' / Kg | Min : '.$value->min_kg }}</b> </label><br>
                                    <label><i class="fa fa-dollar"></i> Hpp M3 : <b>{{ toRupiah($value->hrg_kubik).' / M3 | Min : '.$value->min_kubik }}</b> </label><br>
                                    <label><i class="fa fa-cog"></i> Keterangan : <b>{{ $value->keterangan }}</b> </label>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div id="collapseOne{{ $value->id_harga }}" class="collapse" aria-labelledby="headingOne{{ $value->id_harga }}" data-parent="#accordionExample2" style="">
                        <div class="card-body table-responsive" style="margin-top: -20px">
                            <table class="table table-hover">
                                <thead class="table-info text-dark">
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Asal</th>
                                        <th>Tujuan</th>
                                        <th width="150">Hpp Kg</th>
                                        <th width="150">Hpp M3</th>
                                        <th>Est. Lead Time</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="table-{{ $value->id_harga }}"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        @endforeach
        <br>
        
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body row">
                    @include('template.paginator')
                </div>
            </div>
        </div>
    </div>
</form>
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
    
    function goDetail(id){
        $.ajax({
            type: 'GET',
            url: '{{ url("hargavendor") }}/'+id+'/getdetail',
            success: function(response){
                $("#table-"+id).empty();
                $("#table-"+id).append(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            },
        });
    }

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