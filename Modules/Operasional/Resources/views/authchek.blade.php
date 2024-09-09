<div class="modal fade" id="auth-check-modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth"> Masukan Code Autentikasi </b></h5>
      </div>
      <div class="modal-body" style="margin-top: -7%">
         <center>
           <input type="text" name="check-code" id="check-code" placeholder="Masukan Code Autentikasi ... " class="form-control" max="8" required="required">

          <div style="margin-top: 1%; font-weight: bold;">
            <label class="span-required" id="check-error-code"></label>
            <label class="span-success" id="check-success-code"></label>
          </div>
         </center>

         <div class="text-right">
          <button type="button" class="btn btn-sm btn-success" onclick="getSubmit()"><i class="fa fa-times"></i> Submit</button>
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
      </div>
    </div>
  </div>
</div>