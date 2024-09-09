<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-back" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if($data->status=="1")
                <h4 class="modal-title" style="font-weight: bold;">Apakah anda yakin akan mengembalikan data ini ?</h4>
                @else
                <h4 class="modal-title" style="font-weight: bold;">Apakah dokumen stt kembali sudah selesai ?</h4>
                @endif
            </div>
            <div class="modal-body text-right">
                @if($data->status=="1")
                <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/sendstt") }}" class="btn btn-md btn-success">Iya</a>
                @else
                <a href="{{ url(Request::segment(1)."/".Request::segment(2)."/terima") }}" class="btn btn-md btn-success">Iya</a>
                @endif
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Tidak</span></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function goUpKirim(){
        $("#modal-back").modal('show');
    }

    function goUpTerima(){
        $("#modal-back").modal('show');
    }
</script>