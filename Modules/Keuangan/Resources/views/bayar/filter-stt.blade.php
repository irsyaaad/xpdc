<div class="col-md-3">
    <label style="font-weight: bold;">
        ID STT
    </label>
    <select class="form-control" id="stt" name="stt">
        
    </select>
</div>
<div class="col-md-3">
    <label style="font-weight: bold;">
        Nama Pelanggan
    </label>
    <select class="form-control" id="pelanggan_id" name="pelanggan_id">
        
    </select>
</div>

@section('script')
<script>
    $('#stt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_ac').empty();
                return {
                    results:  $.map(data, function (item) {
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
    $('#pelanggan_id').select2({
        placeholder: 'Cari Pelanggan ....',
        ajax: {
            url: '{{ url('getPelanggan') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_pendapatan').empty();
                return {
                    results:  $.map(data, function (item) {
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
    @if(isset($temp["pelanggan"]->nm_pelanggan))
    $("#pelanggan_id").empty();
    $("#pelanggan_id").append('<option value="{{ $temp["pelanggan"]->id_pelanggan }}">{{ strtoupper($temp["pelanggan"]->nm_pelanggan) }}</option>');
    @endif
    
    @if(isset($temp["stt"]->kode_stt))
    $("#stt").empty();
    $("#stt").append('<option value="{{ $temp["stt"]->id_stt }}">{{ strtoupper($temp["stt"]->kode_stt) }}</option>');
    @endif
</script>
@endsection