<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class='fa fa-hourglass-end fa-spin fa-3x'></i>  Apakah Handling Sudah Selesai ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <h6> KM Awal : <span class="span-required"> * </span></h6>
                    <input class="form-control" type="number" name="km_awal" id="km_awal" readonly="true" value="@if(isset($data->km_awal)){{ $data->km_awal }}@endif" placeholder="Kilometer Awal ..." required/>
                </div>
                <br>
                <div class="col-md-12">
                    <form method="POST" action="{{ url(Request::segment(1).'/'.Request::segment(2).'/setselesai') }}" enctype="multipart/form-data" id="form-status">
                        @csrf
                        <h6>Masukan KM Akhir : <span class="span-required"> * </span></h6>
                        <input class="form-control" type="number" name="km_akhir" id="km_akhir" placeholder="Masukan Kilometer Akhir ..." required/>
                        <br>
                    <div class="text-right">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" data-toggle="tooltip" data-placement="bottom" title="Handling Selesai" >Selesai</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Batal">Batal</span></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var idstatus = "";
    function CheckEnd(id = ""){
        $("#modal-status").modal('show');
    }
</script>