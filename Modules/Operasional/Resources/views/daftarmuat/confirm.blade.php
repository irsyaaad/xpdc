<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-status" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <form method="POST" action="{{ url('dmtiba/updatestatus') }}" enctype="multipart/form-data" id="form-status">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Apakah Anda Ingin Update data ini ?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota")
                    <label style="font-weight : bold ">
                        Kota Posisi Barang <span class="text-danger"> *</span>
                    </label>
                    <select class="form-control" id="id_kota" name="id_kota"></select>
                    <br>
                    @endif
                    <br>
                    <h6>Tgl Update<span class="text-danger"> *</span> </h6>
                    <input type="date" class="form-control" name="tgl_update" value="{{ date("Y-m-d") }}" id="tgl_update" required>
                    <br>
                    
                    <h6>Masukan Keterangan</h6>
                    <textarea class="form-control" placeholder="tuliskan keterangan status disini (maks 100 karakter) ..." id="keterangan" name="keterangan" maxlength="100"></textarea>
                    <input type="hidden" name="id_dmtb" id="id_dmtb" required>
                    <input type="hidden" name="id_status" id="id_status" required>
                    @csrf

                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Update</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

@if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota")
<div class="modal fade" id="modal-stt-stat" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <form method="POST" action="#" enctype="multipart/form-data" id="form-stt-stat">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Apakah Anda Ingin Update data ini ?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <label style="font-weight : bold ">
                        Kota Posisi Barang <span class="text-danger"> *</span>
                    </label>
                    <select class="form-control" id="id_kota_stt" name="id_kota_stt"></select>
                    <br>
                    <br>
                    <h6>Tgl Update<span class="text-danger"> *</span> </h6>
                    <input type="date" class="form-control" name="tgl_update" value="{{ date("Y-m-d") }}" id="tgl_update" required>
                    <br>

                    <label style="font-weight : bold ">Masukan Keterangan</label>
                    <textarea class="form-control" placeholder="tuliskan keterangan status disini (maks 100 karakter) ..." id="keterangan" name="keterangan" maxlength="100"></textarea>
                    <input type="hidden" name="id_status2" id="id_status2" required>
                    @csrf
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Update</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif