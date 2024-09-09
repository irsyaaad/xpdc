@extends('template.document2')

@section('data')
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
    <input type="hidden" name="_method" value="GET">
    @csrf
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="#" onclick="goPopUp()" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"> </i> Tambah Penilaian
            </a>
        </div>
        
        <div class="col-md-12 mt-2">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Minimal Nilai</th>
                        <th>Maximal Nilai</th>
                        <th>Konversi</th>
                        <th>Created | Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $value->id_penilaian }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->min_nilai }}</td>
                        <td>{{ $value->max_nilai }}</td>
                        <td>{{ $value->sign }}</td>
                        <td>{{ $value->created_by }} | {{  $value->updated_by }}</td>
                        <td>
                            <a href="#" onclick="goEdit('{{ $value->id_penilaian }}', '{{ $value->name }}', '{{ $value->min_nilai }}', '{{ $value->max_nilai }}', '{{ $value->sign }}')" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url(Request::segment(1)) }}/{{ $value->id_penilaian }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
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
                                Kode Nilai  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="number" value="{{ old('id_penilaian') }}" class="form-control @if($errors->has('id_penilaian')) is-invalid @endif" required id="id_penilaian" name="id_penilaian" placeholder="Masukan kode nilai  ...">
                            
                            @if($errors->has('id_penilaian'))
                            <label class="text-danger">
                                {{ $errors->first('id_penilaian') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Nama  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="text" value="{{ old('name') }}" class="form-control @if($errors->has('name')) is-invalid @endif" required id="name" name="name" minlength="2" maxlength="50" placeholder="Masukan nama  ...">
                            
                            @if($errors->has('name'))
                            <label class="text-danger">
                                {{ $errors->first('name') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Minimal Nilai  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="number" value="{{ old('min_nilai') }}" class="form-control @if($errors->has('min_nilai')) is-invalid @endif" required id="min_nilai" name="min_nilai" placeholder="Masukan minimal nilai  ...">
                            
                            @if($errors->has('min_nilai'))
                            <label class="text-danger">
                                {{ $errors->first('min_nilai') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Maksimal Nilai  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="number" value="{{ old('max_nilai') }}" class="form-control @if($errors->has('max_nilai')) is-invalid @endif" required id="max_nilai" name="max_nilai" placeholder="Masukan maksimal nilai  ...">
                            
                            @if($errors->has('max_nilai'))
                            <label class="text-danger">
                                {{ $errors->first('max_nilai') }}
                            </label>
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <label class="col-form-label">
                                Konversi  <span class="text-danger"> *</span>
                            </label>
                            
                            <input type="text" value="{{ old('sign') }}" class="form-control @if($errors->has('sign')) is-invalid @endif" required id="sign" name="sign" minlength="1" maxlength="1" placeholder="Masukan tanda  ...">
                            
                            @if($errors->has('sign'))
                            <label class="text-danger">
                                {{ $errors->first('sign') }}
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
        $("#id_penilaian").val('');
        $("#name").val('');
        $("#max_nilai").val('');
        $("#min_nilai").val('');
        $("#sign").val('');
    }
    
    function goEdit(id, name, min_nilai, max_nilai, sign){
        $("#modal-Posisi").modal("show");
        $("#id_penilaian").val(id);
        $("#name").val(name);
        $("#min_nilai").val(min_nilai);
        $("#max_nilai").val(max_nilai);
        $("#sign").val(sign);
        $("#form-Posisi").attr("action", "{{ url(Request::segment(1)) }}/"+id);
        $("#method_desc").val("PUT");
    }
</script>
@endsection