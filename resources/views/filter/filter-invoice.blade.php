<div class="col-md-3">
    <label>
        Cari Pelanggan
    </label>
    <select class="form-control" id="id_pelanggan" name="id_pelanggan"></select>
</div>
<div class="col-md-3">
    <label>Kode Invoice</label>
    <select class="form-control" id="id_invoice" name="id_invoice"></select>
</div>
<div class="col-md-3">
    <label>
        Kode RESI
    </label>
    <select class="form-control" id="id_stt" name="id_stt"></select>
</div>
<div class="col-md-3">
    <label>
        Dari Tanggal
    </label>
    <input class="form-control" type="date" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>
<div class="col-md-3">
    <label>
        Sampai Tanggal
    </label>
    <input class="form-control" type="date" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-3">
    <label>Status Lunas</label>
    <select class="form-control" id="status" name="status">
        <option value="0"> -- Semua Data --</option>
        <option value="1"> Belum Lunas </option>
        <option value="2"> Lunas </option>
        <option value="3"> Jatuh Tempo </option>
    </select>
</div>

<div class="col-md-3" style="margin-top: 30px">	
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
    
    $('#invoice').select2({
        placeholder: 'Masukkan Invoice yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('Invoice') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                console.log(data);
                $('#stt').empty();
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });
    
    $('#id_stt').select2({
        placeholder: 'Masukkan Kode RESI yang dicari',
        minimumInputLength: 0,
        allowClear: true,
        ajax: {
            url: '{{ url('getSttPerush') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                console.log(data);
                $('#stt').empty();
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });
    
</script>
