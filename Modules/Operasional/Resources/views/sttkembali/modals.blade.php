<style>
    textarea {
        min-height: 100px;
    } 
</style>

<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Pilih STT Yang Ingin Dimasukan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)."/addstt") }}" enctype="multipart/form-data" id="form-status">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="id_stt">
                            <b>Nomor STT</b> <span class="span-required"> * </span>
                        </label>
                        
                        <select class="form-control" name="id_stt" id="id_stt" required="required">
                            <option value="">-- Pilih STT --</option>
                            @foreach($stt as $key => $value)
                            <option value="{{ $value->id_stt }}">{{ strtoupper($value->id_stt) }}</option>
                            @endforeach
                        </select>
                        
                        @if ($errors->has('id_stt'))
                        <label style="color: red">
                            {{ $errors->first('id_stt') }}
                        </label>
                        @endif
                    </div>

                    <div class="form-group text-right" style="margin-top: 10%">
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si">Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function goUpStt(){
        $("#modal-status").modal('show');
    }
</script>