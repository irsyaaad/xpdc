<div class="col-md-3">
    <div class="m-form__control">
        <label style="font-weight: bold;">
            Jenis
        </label>
        <select class="form-control" id="jenis" name="jenis">
            <option value="N">Neraca</option>
            <option value="R">Rugi Laba</option>
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="m-form__control">
        <label style="font-weight: bold;">
            AC 1
        </label>
        <select class="form-control" id="ac1" name="ac1">
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="m-form__control">
        <label style="font-weight: bold;">
            AC 2
        </label>
        <select class="form-control" id="ac2" name="ac2">
            <option value="@if(isset($ac2)){{$ac2}}@endif"></option>
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="m-form__control">
        <label style="font-weight: bold; color: white">
            Action
        </label>
        <div class="form-control" style="border: 0px; padding: 0px">
            <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
        </div>
    </div>
</div>

<script type="text/javascript">

    @if(isset($filter['jenis']) and $filter['jenis']!="0")
    $("#jenis").val('{{ $filter['jenis'] }}');
    @endif


    $.ajax({
        type: "GET",
        url: "{{ url('getAC1') }}",
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#ac1").empty();
            $("#ac1").append('<option value=' + 0 + '>' + "-- Pilih AC1 --" + '</option>');
            $.each(response,function(key, value)
            {
                $("#ac1").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(isset($filter['ac1']) and $filter['ac1']!="0")
            $("#ac1").val('{{ $filter['ac1'] }}');
            @endif

        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ url("ACLev2") }}",
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#ac2").empty();
            $("#ac2").append('<option value=' + 0 + '>' + "-- Pilih AC2 --" + '</option>');
            $.each(response,function(key, value)
            {
                $("#ac2").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(isset($filter['ac2']) and $filter['ac2']!="0")
            $("#ac2").val('{{ $filter['ac2'] }}');
            @endif


        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ url("ACLev3") }}",
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#ac3").empty();
            $("#ac3").append('<option value=' + 0 + '>' + "-- Pilih AC3 --" + '</option>');
            $.each(response,function(key, value)
            {
                $("#ac3").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(isset($filter['ac3']) and $filter['ac3']!="0")
            $("#ac3").val('{{ $filter['ac3'] }}');
            @endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $('#jenis').on('change', function() {
        var id = this.value;
        $.ajax({
            type: "GET",
            url: "{{ url('getAC1') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#ac1").empty();
                $("#ac1").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
                $.each(response,function(key, value)
                {
                    $("#ac1").append('<option value=' + value.kode + '>' + value.value + '</option>');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });

    $('#ac1').on('change', function() {
        var id = this.value;
        $.ajax({
            type: "GET",
            url: "{{ url('getAC2') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#ac2").empty();
                $("#ac2").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
                $.each(response,function(key, value)
                {
                    $("#ac2").append('<option value=' + value.kode + '>' + value.value + '</option>');
                });

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });

    $('#ac2').on('change', function() {
        var id = this.value;
        $.ajax({
            type: "GET",
            url: "{{ url('getAC3') }}/"+id,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
                $("#ac3").empty();
                $("#ac3").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
                $.each(response,function(key, value)
                {
                    $("#ac3").append('<option value=' + value.kode + '>' + value.value + '</option>');
                });

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
    });

</script>
