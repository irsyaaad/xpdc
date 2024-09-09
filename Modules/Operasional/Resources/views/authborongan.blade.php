<div class="modal fade" id="auth-modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5><b id="text-auth"> Masukan Code Autentikasi </b></h5>
      </div>
      <div class="modal-body" style="margin-top: -7%">
         <center>
           <input type="text" name="code" id="code" placeholder="Masukan Code Autentikasi ... " class="form-control" max="8" required="required">
           <label class="text-danger" id="response"></label>
         </center>
         <br>
         <div class="text-right">
          <button type="button" class="btn btn-sm btn-success" onclick="goAuthBorongan()"><i class="fa fa-save"></i> Submit</button>
          <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
         </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function goAuthBorongan() {
      var code = $("#code").val();
      var token = "{{ csrf_token() }}";

      if(code==0){

        $("#response").addClass("text-danger");
        $("#response").text("Masukan Code Autentikasi");

      }else{

        $.ajax({
            type: "POST",
            url: "{{ url('stt/goAuthBorongan') }}",
            dataType: "json",
            data: {_token: token, code:code},
            beforeSend: function(e) {
              if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
              }
            },
            success: function(response){ 
              console.log(response);
              if(response.code==0){
                $("#response").addClass("text-danger");
                $("#response").text(response.message);
                $("#secret_code").val("");
                $('#n_tarif_borongan').prop('readonly', true);
              }else{
                setTarif();
                $("#form-data").submit();
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
            }
        });
      }
  }
</script>