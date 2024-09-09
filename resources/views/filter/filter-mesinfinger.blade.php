<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="filterperush" name="filterperush">
            <option value="0">-- Pilih Perusahaan --</option>
            @foreach($role_perush as $key => $value)
            <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
            @endforeach
    </select>
</div>

<script>
    $.ajax({
        type: "GET",
        url: "{{ url("getPerusahaan") }}",
        dataType: "json",
        beforeSend: function (e) {
            if (e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function (response) {
            $.each(response, function (key, value) {
                $("#filterperush").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });
            
            @if(Session('id_perush')!=null)
            $("#filterperush").val('{{ Session('id_perush') }}');
            @endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });
    
    function CheckStatus(){
        $("#modal-status").modal('show');
    }
    
    function goSubmitUpdate() {
        $("#form-status").submit();
    }
</script>