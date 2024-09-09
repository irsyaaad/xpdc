@extends('template.document')

@section('data')
<div class="row" style="margin-top: -25px">
    <div class="col-md-12">
        <div class="text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        </div>
        <div class="table-responsive">
            <table class="table " style="margin-top: 5px">
                <tbody>
                    
                    <tr>
                        <td>No. Transaksi</td>
                        <td> : </td>
                        <td><b>{{ $data->kode_memorial }}</b></td>
                    </tr>
                    <tr>
                        <td>Perusahaan</td>
                        <td> : </td>
                        <td><b>{{ strtoupper($data->perusahaan->nm_perush) }}</b></td>
                    </tr>
                    <tr>
                        <td>Tgl. Masuk</td>
                        <td> : </td>
                        <td><b>@if(isset($data->tgl)){{ daydate($data->tgl).", ".dateindo($data->tgl) }}@endif</b></td>
                    </tr>
                    <tr>
                        <td>Nominal</td>
                        <td> : </td>
                        <td>{{ toRupiah($nominal) }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-12">
        <table class="table table-responsive table-striped" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Nominal</th>
                    <th>Info</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($detail as $key => $value)
                
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>@if(isset($value->kredit->nama)){{ strtoupper($value->kredit->nama) }}@endif</td>
                    <td>@if(isset($value->debet->nama)){{ strtoupper($value->debet->nama) }}@endif</td>
                    <td>{{ toRupiah($value->n_debet) }}</td>      
                    <td>
                        {{ $value->info }}
                    </td>
                    <td class="text-center">
                        <form method="POST" action="{{ url(Request::segment(1)."/".$value->id_detail."/deletedetail") }}" id="form-delete{{ $value->id_detail }}" name="form-delete{{ $value->id_detail }}">
                            @csrf
                            {{ method_field("DELETE") }}
                            
                            <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus">
                                <span> <i class="fa fa-times"></i></span>
                            </button>
                        </form>
                    </td>
                </tr>
                
                @endforeach
                <tr>
                    <form method="POST" action="{{ url(Request::segment(1)."/savedetail") }}" id="form-detail" name="form-detail">
                        @csrf
                        <td>
                            <input type="hidden" name="id_memorial" id="id_memorial" value="{{ Request::segment(2) }}" required />
                        </td>
                        <td>
                            <select class="form-control" name="id_ac_debet" id="id_ac_debet" required>
                                <option value="">-- Pilih Akun Debet --</option>
                                @foreach($ac as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_ac_debet'))
                            <label style="color: red">
                                {{ $errors->first('id_ac_debet') }}
                            </label>
                            @endif
                        </td>
                        <td>
                            <select class="form-control" name="id_ac_kredit" id="id_ac_kredit" required>
                                <option value="">-- Pilih Akun Kredit --</option>
                                @foreach($ac as $key => $value)
                                <option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_ac_kredit'))
                            <label style="color: red">
                                {{ $errors->first('id_ac_kredit') }}
                            </label>
                            @endif
                        </td>
                        <td>
                            <input class="form-control" id="nominal" name="nominal" type="number" placeholder="Masukan Nominal" maxlength="24" value="{{ old('nominal') }}" required />
                        </td>
                        <td>
                            <textarea class="form-control" style="min-height: 100px"  id="info" name="info" placeholder="Masukan Info Bayar" required>{{ old('info') }}</textarea>
                            @if ($errors->has('info'))
                            <label style="color: red">
                                {{ $errors->first('info') }}
                            </label>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"><i class="fa fa-save"></i></button>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Batal" onclick="reload()"><i class="fa fa-refresh"></i></button>
                        </td>
                        
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function getEdit(id) {
        $("#form-detail").attr("action", '{{ url(Request::segment(1)."/updatedetail") }}/'+id);
        $.ajax({
            type: "GET", 
            url: "{{ url(Request::segment(1)) }}/"+id+"/editdetail",
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                console.log(response);
                $("#id_ac").val(response.id_ac);
                $("#jumlah").val(response.jumlah);
                $("#harga").val(response.harga);
                $("#info").val(response.info);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
    
    function reload(){
        $("#form-detail").attr("action", '{{ url(Request::segment(1)."/savedetail") }}');
        $("#id_ac").val("");
        $("#jumlah").val("");
        $("#harga").val("");
        $("#info").val("");
    }

    $('#id_ac').select2({
		placeholder: 'Cari Akun ....',
		ajax: {
			url: '{{ url('getACPerush') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_ac').empty();
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
