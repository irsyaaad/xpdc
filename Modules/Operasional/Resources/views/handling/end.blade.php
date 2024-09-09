<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>  Apakah Anda Barang Sudah Sampai ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="margin-top: -2%">
                <form method="POST" action="{{ url(Request::segment(1)."/sampai"."/".$data->id_handling) }}" enctype="multipart/form-data">
                    @csrf
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
                    
                    <h6>Keterangan</h6>
                    <textarea class="form-control" name="keterangan" id="keterangan" maxlength="100" placeholder="Masukan Keterangan ..."></textarea>
                    @if ($errors->has('keterangan'))
                    <label style="color: red">
                        {{ $errors->first('keterangan') }}
                    </label>
                    @endif
                    <br>
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

<script type="text/javascript">

    function CheckSampai(id = ""){
        $("#id_stt").val(id);
        $("#modal-end").modal('show');
    }
    
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