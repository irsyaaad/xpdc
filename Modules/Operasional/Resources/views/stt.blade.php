@extends('template.document')

@section('data')
@if (Request::segment(2) == null)
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    {{-- @include('template.filter-collapse') --}}
    <div class="card-body row">
        @include('filter.filter-'.Request::segment(1))
        <input type="hidden" name="_method" value="GET">
      </div>
    @csrf
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="table-responsive" style="overflow-x:auto; min-height:350px">
                <table class="table table-hover">
                    <thead style="background-color: grey; color : #ffff; font-size:11pt;">
                        <tr>
                            <th>No. STT</th>
                            <th>Marketing</th>
                            @if (get_admin())
                            <th>Perusahaan</th>
                            @endif
                            <th>Layanan</th>
                            <th>Pelanggan > No. AWB</th>
                            <th>Pengirim > Asal</th>
                            <th>Penerima > Tujuan</th>
                            <th>Status Barang</th>
                            <th>Status Bayar</th>
                            <th>Omzet</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) == null)
                        <tr>
                            <td colspan="11" class="text-center"> Tidak ada data </td>
                        </tr>
                        @endif
                        @foreach ($data as $key => $value)
                        <tr>
                            <td>
                                <a href="{{ url(Request::segment(1)) . '/' . $value->id_stt . '/show' }}"
                                    class="class-edit">
                                    {{ strtoupper($value->kode_stt) }}
                                </a><br>
                                {{ dateindo($value->tgl_masuk) }}
                            </td>
                            <td>
                                @if (isset($value->nm_marketing))
                                {{ strtoupper($value->nm_marketing) }}
                                @elseif($value->id_marketing=="0")
                                DATANG SENDIRI
                                @endif
                            </td>
                            @if (get_admin())
                            <td>
                                @if (isset($value->perush_asal))
                                {{ $value->nm_perush }}
                                @endif
                            </td>
                            @endif
                            <td>
                                @if (isset($value->nm_layanan))
                                {{ $value->nm_layanan }}
                                @endif
                                <br>
                                @if(isset($value->kode_dm))
                                <a href="#" onclick="goDetaildm('{{ $value->id_dm }}')">
                                    <span class="label label-inline label-light-primary font-weight-bold">
                                        {{ $value->kode_dm }}
                                    </span>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if (isset($value->nm_pelanggan))
                                {{ strtoupper($value->nm_pelanggan) }} > <br>
                                <label style="font-size: 8pt"> > {{ $value->no_awb }}</label>
                                @endif
                            </td>
                            <td>
                                @if (isset($value->pengirim_nm))
                                <a href="#" onclick="goDetail('{{ $value->id_stt }}')" class="class-edit">
                                    {{ strtoupper($value->pengirim_nm) }}
                                </a>
                                @endif
                                <br>
                                @if (isset($value->asal))
                                <label style="font-size: 8pt"> > {{ $value->asal }}</label>
                                @endif
                            </td>
                            <td>
                                {{ strtoupper($value->penerima_nm) }}
                                <br>
                                @if (isset($value->tujuan))
                                <label style="font-size: 8pt"> > {{ $value->tujuan }}</label>
                                @endif
                            </td>
                            <td>
                                @if (isset($value->nm_status))
                                {{ $value->nm_status }}
                                @endif
                                @if(isset($value->tgl_update) and $value->tgl_update != null)
                                <label style="font-size: 8pt">( {{ dateindo($value->tgl_update) }})</label>
                                @endif
                            </td>
                            <td>
                                @if ($value->tot_bayar == 0)
                                    <label class="badge badge-md badge-danger">Belum Bayar</label>
                                @elseif ($value->tot_bayar >= $value->c_total)
                                    <label class="badge badge-md badge-success">Lunas</label>
                                @else                                    
                                    <label class="badge badge-md badge-warning">Belum Lunas</label>
                                @endif
                            </td>
                            <td>
                                {{ toRupiah($value->c_total) }}
                            </td>
                            <td width="6%" class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>
                                    <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        @php
                                        $today = date("Y-m-d");
                                        $futureDate = date("Y-m-d", strtotime($value->created_at. ' + 3 days'));
                                        $difference = strtotime($futureDate) - strtotime($today);
                                        $days = abs($difference/(60 * 60)/24);
                                        @endphp
                                        @if (($value->id_status == 1 && $days <=3) || in_array(strtolower(Session("role")["nm_role"]), ['keuangan', 'admin']))
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/edit') }}">
                                            <i class="fa fa-pencil"></i> Edit 
                                            @if(isset(Session("role")["nm_role"]) and !in_array(strtolower(Session("role")["nm_role"]), ['keuangan', 'admin'])) 
                                            ( {{ $days." Hari tersisa" }} )
                                            @endif
                                        </a>
                                        @endif
                                        @if($value->id_status == 6)
                                        <a href="#" class="dropdown-item" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                            <span><i class="fa fa-check"></i></span> Sampai Tujuan
                                        </a>
                                        @endif
                                        
                                        @if($value->id_status >1)
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/tracking') }}"><i class="fa fa-map-marker"></i> Tracking</a>
                                        @else
                                        
                                        @endif
                                        
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/cetak_pdf') }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak</a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/cetak_tnp_tarif') }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i>
                                            Cetak Tanpa Tarif
                                        </a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/cetak_kosong') }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i>
                                            Cetak Kosong
                                        </a>
                                        <a class="dropdown-item" href="{{ url(Request::segment(1) . '/' . $value->id_stt . '/new-label') }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak Label</a>
                                        @if(isset($value->kode_dm) and $value->id_status < 6)
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus('{{ $value->id_stt }}')"><i class="fa fa-edit"></i> Update Status</a>
                                        @endif
                                        @if (Session("role")["id_role"] == 3)
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_stt) }}')"><i class="fa fa-times"></i> Delete</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('template.paginator')
    </div>
</form>

<div class="modal fade" id="modal-detail" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Detail Stt</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="hasil">
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modal-update" role="dialog" aria-labelledby="exampleModalCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Update Status</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="#" method="POST" enctype="multipart/form-data" id="form-update">
            <div class="modal-body">
                
                @csrf
                <label>
                    Pilih Tanggal Update
                </label>
                <input type="date" name="tgl_update" id="tgl_update" class="form-control">
                
                <input type="hidden" name="nostt" id="nostt">
                
                <label class="mt-3">
                    Pilih Status Stt
                </label>
                <select name="id_status" id="id_status" class="form-control">
                    <option value="">-- Pilih Status --</option>
                    @foreach($status as $key => $value)
                    <option value="{{$value->kode_status}}">{{ strtoupper($value->nm_ord_stt_stat) }}</option>
                    @endforeach
                </select>
                
                <label class="mt-3">
                    Pilih Wilayah
                </label>
                <select class="form-control" id="id_kota" name="id_kota"></select>
                
                <label class="mt-3">
                    Keterangan Tambahan
                </label>
                
                <textarea class="form-control" id="info" name="info"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-success" type="submit">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="modal-dm" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style=" font-weight: bold;"><i class="fa fa-filter"></i> Detail DM</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="hasilnya">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>  
                    Apakah Anda Barang Sudah Sampai ? 
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body" style="margin-top: -2%">
                <form method="POST" action="{{ url("dmtiba/sampai") }}" enctype="multipart/form-data" id="form-end">
                    @csrf
                    
                    <label style="font-weight : bold ">
                        Kota Posisi Barang <span class="text-danger"> *</span>
                    </label>
                    <select class="form-control" id="id_kota_handling" name="id_kota_handling"></select>
                    <br>
                    <br>
                    <h6>Tgl Update<span class="text-danger"> *</span> </h6>
                    <input type="date" class="form-control" name="tgl_update" value="{{ date("Y-m-d") }}" id="tgl_update" required>
                    <br>
                    
                    <h6>Foto Dokumentasi  1</h6>
                    <input class="form-control" name="dok1" id="dok1" type="file" />
                    <img id="img1" name="img1" src="" >  
                    @if ($errors->has('dok1'))
                    <label style="color: red">
                        {{ $errors->first('dok1') }}
                    </label>
                    @endif  
                    
                    <br>
                    
                    <h6>Foto Dokumentasi  2</h6>
                    <input class="form-control" name="dok2" id="dok2" type="file" />
                    <img id="img2" name="img2" src="" >
                    @if ($errors->has('dok1'))
                    <label style="color: red">
                        {{ $errors->first('dok1') }}
                    </label>
                    @endif
                    
                    <input class="form-control" name="id_stt" id="id_stt" required type="hidden" />
                    @if ($errors->has('id_stt'))
                    <label style="color: red">
                        {{ $errors->first('id_stt') }}
                    </label>
                    @endif
                    
                    <h6>Keterangan<span class="span-required"> * </span></h6>
                    <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukan Keterangan ..."></textarea>
                    @if ($errors->has('keterangan'))
                    <label style="color: red">
                        {{ $errors->first('keterangan') }}
                    </label>
                    @endif
                    
                    <br>
                    <h6>Nama Penerima<span class="span-required"> * </span></h6>
                    <input type="text" class="form-control" name="nm_penerima" id="nm_penerima" maxlength="100" placeholder="Masukan Nama Penerima ..." />
                    @if ($errors->has('nm_penerima'))
                    <label style="color: red">
                        {{ $errors->first('nm_penerima') }}
                    </label>
                    @endif
                    <br>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Sampai</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@elseif(Request::segment(2) == 'create' or Request::segment(3) == 'edit')
<style type="text/css">
    textarea {
        min-height: 80px;
    }
</style>
@include('operasional::create-stt')
@endif
@endsection

@section('script')
<script type="text/javascript">
    function goDetail(id) {
        $("#modal-detail").modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('detailstt') }}/" + id,
            dataType: "json",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response) {
                $("#hasil").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    function updateStatus(data){
        $("#nostt").val(data);
        $("#tgl_update").val('{{ date("Y-m-d") }}');
        $("#modal-update").modal('show');
        $("#form-update").attr("action", "{{ url('dmtrucking/saveupdatestatusajax') }}");
    }
    
    $('#id_kota').select2({
        minimumInputLength: 0,
        placeholder: 'Cari Kota ....',
        allowClear: true,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_kota').empty();
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
    
    function goDetaildm(id) {
        console.log(id);
        $("#modal-dm").modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('dmvendor/detaildm') }}/" + id,
            dataType: "html",
            beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response) {
                $("#hasilnya").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    var idstatus = "";
    
    function CheckStatus() {
        $("#modal-status").modal('show');
    }
    
    $('#filterasal').select2({
        placeholder: 'Cari Kota Asal ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filterasal').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#filtertujuan').select2({
        placeholder: 'Cari Kota Tujuan ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filtertujuan').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#id_kota_handling').select2({
        placeholder: 'Cari Kota Tujuan ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_kota_handling').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#filterstt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filterstt').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#f_awb').select2({
        placeholder: 'Cari No Awb ....',
        ajax: {
            url: '{{ url('getAwb') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#f_awb').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#f_pelanggan').select2({
        placeholder: 'Cari Pelanggan ....',
        ajax: {
            url: '{{ url('getPelanggan') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#f_pelanggan').empty();
                return {
                    results: $.map(data, function(item) {
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

    $('#f_penerima').select2({
        placeholder: 'Cari Penerima ....',
        ajax: {
            url: '{{ url('getPenerima') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#f_penerima').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    $('#filterperush').select2({
        placeholder: 'Cari Perusahaan ....',
        ajax: {
            url: '{{ url('getPerusahaan') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filterperush').empty();
                return {
                    results: $.map(data, function(item) {
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
    
    @if (isset($filter['page']))
    $("#shareselect").val('{{ $filter['page'] }}');
    @endif
    
    @if (isset($filter['id_layanan']))
    $("#filterlayanan").val('{{ $filter['id_layanan'] }}');
    @endif
    
    @if (isset($filter['status']))
    $("#filterstatusstt").val('{{ $filter['status'] }}');
    @endif
    
    @if (isset($filter['cara']))
    $("#filtercarabayar").val('{{ $filter['cara'] }}');
    @endif
    
    @if (isset($filter['tujuan']->nama_wil))
    $("#filtertujuan").empty();
    $("#filtertujuan").append('<option value="{{ $filter['tujuan']->id_wil }}">{{ strtoupper($filter['tujuan']->nama_wil) }}</option>');
    @endif
    
    @if (isset($filter['f_awb']))
    $("#f_awb").empty();
    $("#f_awb").append('<option value="{{ $filter['f_awb'] }}">{{ strtoupper($filter['f_awb']) }}</option>');
    @endif
    
    @if (isset($filter['f_pelanggan']->id_pelanggan))
    $("#f_pelanggan").empty();
    $("#f_pelanggan").append('<option value="{{ $filter['f_pelanggan']->id_pelanggan }}">{{ strtoupper($filter['f_pelanggan']->nm_pelanggan) }}</option>');
    @endif
    
    @if (isset($filter['asal']->nama_wil))
    $("#filterasal").empty();
    $("#filterasal").append(
    '<option value="{{ $filter['asal']->id_wil }}">{{ strtoupper($filter['asal']->nama_wil) }}</option>');
    @endif
    
    @if (isset($filter['id_stt']->kode_stt))
    $("#filterstt").empty();
    $("#filterstt").append('<option value="{{ $filter['id_stt']->id_stt }}">{{ strtoupper($filter['id_stt']->kode_stt) }}</option>');
    @endif
    
    @if (get_admin() and isset($filter['id_perush']))
    $("#filterperush").val('{{ $filter['id_perush'] }}');
    @endif
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
    
    function CheckSampai(id = ""){
        $("#id_stt").val(id);
        $("#id_kota_handling").attr('required', true);
        $("#keterangan").attr('required', true);
        $("#nm_penerima").attr('required', true);
        $("#modal-end").modal('show');
    }
</script>
@endsection
