<div class="row">
    <div class="col-md-6">
        <h4>
            <i class="fa fa-thumb-tack"></i>
            <b>Detail Dm</b>
        </h4>
    </div>
    <div class="col-md-12">
        <form method="GET" action="#" enctype="multipart/form-data" id="form-select">
            @csrf
            <input type="hidden" name="_method" value="GET">
            <table class="table table-responsive table-bordered" id="tableasal" style="margin-top: 5px">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No. </th>
                        <th>Kode STT</th>
                        <th>Tgl Masuk</th>
                        <th>Pengirim</th>
                        <th>Total Koli</th>
                        <th>Koli Termuat</th>
                        <th>Status</th>
                        @if($data->id_perush_dr == Session("perusahaan")["id_perush"])
                        <th>Pendapatan</th>
                        @endif
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    @endphp
                    @foreach($detail as $key => $value)
                    <tr>
                        <td>{{ $key+1 }} </td>
                        <td>
                            <a href="#" onclick="myFunction('{{ $value->id_stt }}')" class="class-edit">
                                {{ strtoupper($value->kode_stt) }}
                            </a>
                            <br>{{ dateindo($value->tgl_masuk) }}
                        </td>
                        <td>
                            {{ strtoupper($value->pengirim_nm)}}<br><span class="label label-inline label-light-primary font-weight-bold">{{$value->pengirim_telp}}</span><br><span >{{$value->pengirim_alm}}</span>
                        </td>					
                        <td>
                            @isset($value->penerima_nm)
                            {{ strtoupper($value->penerima_nm)}}
                            @endisset
                            <br>
                            <span class="label label-inline label-light-primary font-weight-bold">
                                @isset($value->penerima_telp)
                                {{$value->penerima_telp}}
                                @endisset
                            </span>
                            <br>
                            <span>
                                @isset($value->penerima_alm)
                                {{$value->penerima_alm}}
                                @endisset
                            </span>
                        </td>
                        <td class="text-right">{{ $value->n_koli }}</td>
                        <td class="text-right">{{ $value->muat }}</td>
                        <td class="text-center">
                            @if(isset($sttstat[$value->id_status]->nm_ord_stt_stat))
                            {{ strtoupper($sttstat[$value->id_status]->nm_ord_stt_stat) }}
                            @endif
                        </td>
                        @if(Request::segment(1)!="dmtiba")
                        @php
                        $c_total = $value->n_tarif_koli * $value->muat;
                        $total += $c_total;
                        @endphp
                        <td class="text-right">{{ torupiah($c_total) }}</td> 
                        @endif
                        <td class="text-center">
                            @if(Request::segment(1)=="dmtiba")
                            @if($data->id_status>3 and $value->id_status < 6 and $value->is_import != true and $value->is_penerusan != 1)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    @if($data->is_vendor == null)
                                    <a href="#" class="dropdown-item" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                        <span><i class="fa fa-check"></i></span> Ambil Gudang
                                    </a>
                                    @else
                                    <a href="#" class="dropdown-item" type="button" onclick="ShowModal('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                        <span><i class="fa fa-download"></i></span> Import
                                    </a>
                                    @endif
                                    <a href="#" class="dropdown-item" type="button" onclick="CheckTerusan('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                        <span><i class="fa fa-truck"></i></span> Ambil Gudang Vendor Penerusan
                                    </a>
                                </div>
                            </div>
                            @endif
                            @elseif($data->id_ven != null and $value->id_status < 7)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    @php
                                    $id = $sttstat[$value->id_status]->id_ord_stt_stat+1;
                                    $status_stt = $sttstat[$id]->nm_ord_stt_stat;
                                    @endphp
                                    @if($value->id_status > 2 and $value->id_status < 6)
                                    <a href="#" class="dropdown-item" type="button" onclick="UpdateStt('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                        <span><i class="fa fa-truck"></i></span> {{ $status_stt }}
                                    </a>
                                    @endif
                                    @if($value->id_status == 6)
                                    <a href="#" class="dropdown-item" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
                                        <span><i class="fa fa-check"></i></span> Diterima
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @elseif($data->id_ven != null and $value->id_status >= 7)
                            
                            @else
                            @if(Request::segment(3)=="show" and isset($data->id_status) and $data->id_status==1 and $data->id_perush_dr==Session("perusahaan")["id_perush"])
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/'.$value->id_stt.'/detailstt' }}">
                                        <span><i class="fa fa-eye"></i></span> Detail
                                    </a>
                                    @if($data->ata==null and $data->atd==null)
                                    <input type="hidden" name="kode_dm" id="kode_dm" value="{{ Request::segment(2) }}">
                                    <a href="#" class="dropdown-item" type="button" onclick="CheckDelete('{{ url('dmtrucking/'.$value->id_stt.'/deletestt') }}')">
                                        <span><i class="fa fa-times"></i></span> Hapus
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @else
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if(Request::segment(1)!="dmtiba")
                    <tr>
                        <td colspan="7" class="text-right"><h6>TOTAL : </h6></td>
                        <td class="text-right"><h6>{{ torupiah($total) }}</h6></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-detail"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Detail Stt</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="hasil">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-stt" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-weight: bold;">Apakah Anda Ingin Mengimport Data STT ?</h4>
            </div>
            <div class="modal-footer">
                <form action="#" method="post" id="form-stt" name="form-stt">
                    @csrf
                    <input type="hidden" value="{{ Request::segment(2) }}" id="id_dm_tiba" name="id_dm_tiba"/>
                    <button type="button" class="btn btn-md btn-success" id="modal-btn-si" onclick="goSubmitUpdate()">Iya </button>
                    <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Tidak</span></button>
                </form>
            </div>
        </div>
    </div>
</div>

@if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota" or Request::segment(1)=="dmtiba")
<div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>  
                    @if(Request::segment(1)=="dmtiba") 
                    Barang diambil di Gudang ? 
                    @else Apakah Anda Barang Sudah Sampai ? 
                    @endif
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body" style="margin-top: -2%">
                <form method="POST" action="{{ url(Request::segment(1)."/sampai") }}" enctype="multipart/form-data" id="form-end">
                    @csrf
                    
                    @if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota" and $data->id_status >= 3)
                    <label style="font-weight : bold ">
                        Kota Posisi Barang <span class="text-danger"> *</span>
                    </label>
                    <select class="form-control" id="id_kota_handling" name="id_kota_handling"></select>
                    <br>
                    <br>
                    @endif
                    
                    <h6>Foto Dokumentasi  1<span class="span-required"> * </span></h6>
                    <input class="form-control" name="dok1" id="dok1" required type="file" />
                    <img id="img1" name="img1" src="" >  
                    @if ($errors->has('dok1'))
                    <label style="color: red">
                        {{ $errors->first('dok1') }}
                    </label>
                    @endif  
                    
                    <br>
                    
                    <h6>Foto Dokumentasi  2<span class="span-required"> * </span></h6>
                    <input class="form-control" name="dok2" id="dok2" required type="file" />
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
                    <textarea class="form-control" name="keterangan" id="keterangan" maxlength="100" placeholder="Masukan Keterangan ..."></textarea>
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

<div class="modal fade" id="modal-terusan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin-left: 7%; font-weight: bold;"> 
                    Apakah Barang Diteruskan Ke Vendor ?
                </h3>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url(Request::segment(1)."/penerusan") }}" enctype="multipart/form-data" id="form-penrusan">
                    @csrf
                    <div class="text-right">
                        <input class="form-control" name="t_id_stt" id="t_id_stt" required type="hidden" />
                        <button type="submit" class="btn btn-md btn-success" id="modal-btn-si" >Iya</button>
                        <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Tidak</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endif

@section('script')
<script type="text/javascript">
    function ShowModal(id){
        var url = "{{ url(Request::segment(1)) }}/"+id+"/import";
        $("#form-stt").attr("action", url);
        $("#modal-stt").modal('show');
    }
    
    function UpdateStt(id){
        var url = "{{ url('dmtiba') }}/"+id+"/updatestt";
        $("#id_dmtb").val(id);
        $("#form-stt-stat").attr("action", url);
        $("#modal-stt-stat").modal('show');
    }
    
    function CheckSampai(id = ""){
        $("#id_stt").val(id);
        $("#modal-end").modal('show');
    }
    
    function CheckTerusan(id = ""){
        $("#t_id_stt").val(id);
        $("#modal-terusan").modal('show');
    }
    
    function myFunction(id) {
        $("#modal-detail").modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('getDetailStt') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $("#hasil").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    function goSubmitUpdate(){
        $("#form-stt").submit();
    }
    
    var idstatus = "";
    function CheckStatus(id = ""){
        idstatus = id;
        $("#id_dmtb").val(idstatus);
        $('#form-status').attr('action', '{{ url('dmtiba/updatestatus') }}/'+idstatus);
        $("#modal-status").modal('show');
    }
    
    $('#id_kota').select2({
        minimumInputLength: 3,
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
    
    $('#id_kota_stt').select2({
        minimumInputLength: 3,
        placeholder: 'Cari Kota ....',
        allowClear: true,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_kota_stt').empty();
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
    
    $('#id_kota_handling').select2({
        minimumInputLength: 3,
        placeholder: 'Cari Kota ....',
        allowClear: true,
        ajax: {
            url: '{{ url('getKota') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_kota_handling').empty();
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
    
</script>
@endsection