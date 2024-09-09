
@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    @include('kepegawaian::filter.filter-collapse')
    @csrf
    <table class="table table-striped table-responsive">
        <thead style="background-color: grey; color : #ffff">
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                @if(get_admin())
                <th>Perusahaan</th>
                @endif
                <th>Jumlah Piutang > Angsuran</th>
                <th>Pembayaran > Sisa</th>
                <th>Tgl Piutang</th>
                <th>Est. Tgl Lunas</th>
                <th>Status</th>
                <th>Admin</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td >{{ $key+1 }}</td>
                <td >{{ strtoupper($value->nm_karyawan) }}</td>
                @if(get_admin())
                <td >{{ strtoupper($value->nm_perush) }}</td>
                @endif
                <td >{{ toRupiah($value->nominal) }}
                    <br> > 
                    {{ toRupiah($value->n_angsuran) }} 
                </td>
                <td>
                    {{ toRupiah($value->bayar) }} 
                    <br> >
                    {{ toRupiah($value->sisa) }}
                </td>
                <td >{{ $value->tgl_piutang }}</td>
                <td >{{ $value->tgl_selesai }}</td>
                <td>
                    @if($value->is_lunas)
                    <label class="badge badge-md badge-success">Lunas</label>
                    @else
                    <label class="badge badge-md badge-danger">Belum Lunas</label>
                    @endif
                </td>
                <td >{{ strtoupper($value->nm_user) }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>
                        <div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <form method="POST" action="{{ url(Request::segment(1)."/".$value->id_piutang) }}" id="form-delete{{ $value->id_piutang }}" name="form-delete{{ $value->id_perijinan }}">                                
                                @if($value->approve!=true)
                                <a class="dropdown-item" href="#" onclick="goApprove('{{ $value->id_piutang }}')"><i class="fa fa-check"></i> Approve</a>
                                <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_piutang."/edit") }}"><i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_piutang) }}')"><i class="fa fa-times"></i> Delete</a>
                                @csrf
                                {{ method_field("DELETE") }}
                                @endif
                                <a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_piutang."/detail") }}"><i class="fa fa-eye"></i> Detail</a>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
            @if(count($data)<1)
            <tr>
                <td colspan="11" ><center><b>Data Kosong</b></center></td>
            </td>
            @endif
        </tbody>
    </table>
</form>
@endif

<div class="modal fade" id="modal-approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <form id="form-approve" name="form-approve" method="POST" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <h3 class="modal-title" style="font-weight: bold;">Pilih Akun Debit dan Piutang</h3>
                    </center>
                    <div class="row" style="margin-top: 15px">
                        <div class=" col-md-12">
                            <label for="frekuensi">
                                <b>Kas / Bank</b> <span class="span-required"> *</span>
                            </label>
                            
                            <select class="form-control" id="ac_kredit" name="ac_kredit" required>
                                <option value=""> -- Pilih Bank / Kas -- </option>
                                @foreach($ac as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('ac_kredit'))
                            <label style="color: red">
                                {{ $errors->first('ac_kredit') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class=" col-md-12" style="margin-top: 15px">
                            <label for="ac_debit">
                                <b>Akun Piutang</b> <span class="span-required"> *</span>
                            </label>
                            
                            <select class="form-control" id="ac_debit" name="ac_debit" required>
                                <option value=""> -- Pilih Akun Piutang Karyawan -- </option>
                                @foreach($piutang as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('ac_debit'))
                            <label style="color: red">
                                {{ $errors->first('ac_debit') }}
                            </label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-md btn-success"><span aria-hidden="true">Iya</span></button>
                    <button type="button" class="btn btn-md btn-danger"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Tidak</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    function goApprove(id){
        $("#form-approve").attr("action", "{{ url(Request::segment(1)) }}/"+id+"/approve");
        $("#modal-approve").modal("show");
    }
    
    $('#filterperush').on("change", function(e) {
        $('#f_id_karyawan').empty();
        $.ajax({
            type: "GET",
            url: "{{ url('absensi/getkaryawan') }}/"+$("#filterperush").val(),
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                $('#f_id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
                $.each(response, function(index, value) {
                    $('#f_id_karyawan').append('<option value="'+value.id_karyawan+'">'+value.nm_karyawan+'</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });
    
    @if(isset($filter["filterperush"]))
	$("#filterperush").val('{{ $filter["filterperush"] }}');
	@endif

    @if(isset($filter["f_tgl_awal"]))
	$("#f_tgl_awal").val('{{ $filter["f_tgl_awal"] }}');
	@endif
    
    @if(isset($filter["f_tgl_akhir"]))
	$("#f_tgl_akhir").val('{{ $filter["f_tgl_akhir"] }}');
	@endif

	@if(isset($filter["f_id_karyawan"]))
	$("#f_id_karyawan").val('{{ $filter["f_id_karyawan"] }}');
	@endif
</script>
@endsection