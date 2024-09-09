<div class="modal fade" id="modal-asuransi" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth"> Masukkan Harga Pertanggungan Barang </b></h5>
      </div>
      <div class="modal-body" style="margin-top: -7%">
        <center>
          <input type="number" name="n_pertanggungan" id="n_pertanggungan" placeholder="Masukan Harga Pertanggungan ... " class="form-control">
          
          <div style="margin-top: 1%; font-weight: bold;">
            <label class="span-required" id="error-code"></label>
            <label class="span-success" id="success-code"></label>
          </div>
        </center>
        
        <div class="text-right">
          <button type="button" class="btn btn-sm btn-success" onclick="hitungasuransi()"><i class="fa fa-save"></i> Submit</button>
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-ppn" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth"> Nilai PPN Belum Disetting </b></h5>
      </div>
      <hr>
      <div class="modal-body" style="margin-top: -7%">
        
        <center>
          <h6>Untuk menSetting nilai PPn Silahkan Login dan masuk ke Module Administrator, Kemudian masuk menu <b> Perusahaan </b> </h6>
        </center>
        
        <br>
        <div class="text-right">
          
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="asuransi-alert" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth"> Tarif Asuransi Belum Dibuat </b></h5>
      </div>
      <hr>
      <div class="modal-body" style="margin-top: -7%">
        
        <center>
          <h6>Untuk membuat Tarif Asuransi Silahkan masuk menu Master -> Tarif -> Tarif Asuransi </h6>
        </center>
        
        <br>
        <div class="text-right">
          
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function hitungasuransi() {
    @if(isset($tarif_asuransi))
    var tarif               = "{{$tarif_asuransi->harga_jual}}";
    var minim_harga         = "{{$tarif_asuransi->min_harga_pertanggungan}}";
    var harga_pertanggungan = "{{$tarif_asuransi->harga_pertanggungan}}";
    var harga               = parseFloat($("#n_pertanggungan").val());
    var total               = harga*tarif;
    
    //console.log(tarif,minim_harga,harga_pertanggungan,harga,total);
    
    if (harga < minim_harga) {
      $("#n_asuransi").val(harga_pertanggungan);
    } else {
      $("#n_asuransi").val(total);
    }
    $("#modal-asuransi").modal('hide');
    setNetto();
    @else
    alert("Tarif Asuransi Belum Dibuat");
    @endif
  }
  
  function addPelanggan() {
    $("#modal-pelanggan").modal('show');
  }
</script>

