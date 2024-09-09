<script>
    $('document').ready(function () {
        //setInterval(function () {}, 10000);
       // Realtime();
    });
    
    function Realtime() {
        @if(isset(Session("perusahaan")["nm_perush"]))
        var ses = "{{Session('perusahaan')['id_perush']}}";
        @else
        var ses = null;
        @endif
        var jml = 0;
        $.ajax({
            type: "GET",
            url: "{{ url("getInvoice") }}/"+ses,
            dataType: "json",
            beforeSend: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function (response) {
                var data = response;
                for (var d in data) {
                    jml+=1;
                }
                document.getElementById('total').innerHTML = jml;
                $("#pilih").empty();
                $.each(response, function (key, value) {
                    $("#pilih").append(
                    '<li class="m-nav__item"><a href="/is_read/'+value.kode+'" style="font-size: 11pt; text-decoration: none;"><i class="fa fa-envelope-open"></i><b style="font-weight: bold;">'+ " Invoice Pelanggan "+ value.kode + " "+value.value +'</b></a></li><li class="m-nav__separator m-nav__separator--fit"></li>'
                    );
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
        
        $.ajax({
            type: "GET",
            url: "{{ url("getInvoiceHandling") }}/"+ses,
            dataType: "json",
            beforeSend: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function (response) {
                var data = response;
                for (var d in data) {
                    jml+=1;
                }
                document.getElementById('total').innerHTML = jml;
                $.each(response, function (key, value) {
                    $("#pilih").append(
                    '<li class="m-nav__item"><a href="/is_readhandling/'+value.kode+'" style="font-size: 11pt; text-decoration: none;"><i class="fa fa-envelope-open"></i><b style="font-weight: bold;">'+ " Invoice Handling"+ value.kode + " "+value.value +'</b></a></li><li class="m-nav__separator m-nav__separator--fit"></li>'
                    );
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    }
</script>