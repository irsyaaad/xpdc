<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>  Apakah Anda Ingin Berangkat ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url(Request::segment(1).'/'.Request::segment(2).'/setberangkat') }}" enctype="multipart/form-data" id="form-status">
                    @csrf
                    <h6>Masukan KM Awal : <span class="span-required"> * </span></h6>
                    <input class="form-control" type="number" name="km_awal" id="km_awal" readonly="true" placeholder="Masukan Kilometer Awal ..." required/>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" data-toggle="tooltip" data-placement="bottom" title="Handling Berangkat">Berangkat</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Batal">Batal</span></button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    @if(isset($km->km_akhir) and  $km!=null){{ $km->km_akhir }} 
    $("#km_awal").val('{{ $km->km_akhir }}');
    @else
    $("#km_awal").val('0');
    @endif

    var idstatus = "";
    function CheckStatus(id = ""){
        $("#modal-status").modal('show');
        $("#km_awal").val(km);
    }
    
</script>