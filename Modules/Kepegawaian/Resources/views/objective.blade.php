@extends('template.document2')

@section('data')
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    <input type="hidden" name="_method" value="GET">
    @csrf
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="#" onclick="goPopUp()" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"> </i> Tambah Objective
            </a>
        </div>
        
        <div class="col-md-12 mt-2">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Jenis Karyawan</th>
                        <th>Deskripsi</th>
                        <th>Bobot</th>
                        <th>Created | Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $value->key+1 }}</td>
                        <td>{{ $value->jenis->nm_jenis }}</td>
                        <td>{{ $value->keterangan }}</td>
                        <td>{{ $value->bobot }}</td>
                        <td>{{ $value->created_by }} | {{  $value->updated_by }}</td>
                        <td>
                            <a href="#" onclick="goEdit('{{ $value->id_objective }}', '{{ $value->keterangan }}', '{{ $value->id_jenis }}', '{{ $value->bobot }}')" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url(Request::segment(1)) }}/{{ $value->id_objective }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                <i class="fa fa-times"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="modal fade bd-example-modal-sm" id="modal-Posisi" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Form Input Penilaian</h4>
            </div>
            <form method="POST" action="#" enctype="multipart/form-data" id="form-Posisi">
                @csrf
                <input type="hidden" name="_method" id="method_desc" value="POST">
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Jenis Karyawan  <span class="text-danger"> *</span>
                            </label>
                            
                            <select class="form-control" id="id_jenis" name="id_jenis" required>
                                <option value="">-- Pilih Jenis Karyawan --</option>
                                @foreach($jenis as $key => $value)
                                <option value="{{ $value->id_jenis }}">{{ ucfirst($value->nm_jenis) }}</option>
                                @endforeach
                            </select>
                            
                            @if($errors->has('id_jenis'))
                            <label class="text-danger">
                                {{ $errors->first('id_jenis') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Deskripsi  <span class="text-danger"> *</span>
                            </label>
                            
                            <textarea style="min-height: 100px" class="form-control @if($errors->has('keterangan')) is-invalid @endif" required id="keterangan" name="keterangan" placeholder="Masukan deskripsi  ...">{{ old('keterangan') }}</textarea>
                            
                            @if($errors->has('keterangan'))
                            <label class="text-danger">
                                {{ $errors->first('keterangan') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Bobot  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="number" value="{{ old('bobot') }}" class="form-control @if($errors->has('bobot')) is-invalid @endif" required id="bobot" name="bobot" placeholder="Masukan bobot nilai  ...">
                            
                            @if($errors->has('bobot'))
                            <label class="text-danger">
                                {{ $errors->first('bobot') }}
                            </label>
                            @endif
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                        <span> <i class="fa fa-times"></i></span> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
    function goPopUp() {
        $("#modal-Posisi").modal("show");
        $("#form-Posisi").attr("action", "{{ url(Request::segment(1)) }}");
        $("#method_desc").val("POST");
        $("#keterangan").val('');
        $("#id_jenis").val('');
        $("#bobot").val('');
    }
    
    function goEdit(id, keterangan, id_jenis, bobot){
        $("#modal-Posisi").modal("show");
        $("#form-Posisi").attr("action", "{{ url(Request::segment(1)) }}/"+id);
        $("#method_desc").val("PUT");
        $("#keterangan").text(keterangan);
        $("#id_jenis").val(id_jenis);
        $("#bobot").val(bobot);
    }
</script>
@endsection